<?php

namespace App\Services\Shopify;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use RuntimeException;

class ShopifyOAuth
{
    public function isConfigured(): bool
    {
        return (bool) (config('shopify.api_key') && config('shopify.api_secret'));
    }

    public function redirectUri(): string
    {
        $base = rtrim((string) (config('shopify.app_url') ?: config('app.url')), '/');
        return $base . config('shopify.redirect_path', '/admin/import-data/shopify/callback');
    }

    public function authorizeUrl(string $shop, int $storeId, int $adminId): string
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Shopify connection is not available yet. Please try again later.');
        }
        $shop = ShopifyAdminClient::normalizeShop($shop);
        $nonce = bin2hex(random_bytes(16));
        Session::put('shopify_oauth', [
            'nonce' => $nonce,
            'store_id' => $storeId,
            'shop' => $shop,
            'admin_id' => $adminId,
        ]);
        $state = Crypt::encryptString(json_encode([
            'store_id' => $storeId,
            'admin_id' => $adminId,
            'shop' => $shop,
            'nonce' => $nonce,
            'exp' => time() + 900,
        ]));
        $params = http_build_query([
            'client_id' => config('shopify.api_key'),
            'scope' => config('shopify.scopes'),
            'redirect_uri' => $this->redirectUri(),
            'state' => $state,
        ]);
        return 'https://' . $shop . '/admin/oauth/authorize?' . $params;
    }

    public function decodeState(string $state): array
    {
        try {
            $payload = json_decode(Crypt::decryptString($state), true);
        } catch (\Throwable $e) {
            throw new RuntimeException('Shopify authorization expired. Please try again.');
        }
        if (!is_array($payload) || empty($payload['store_id']) || empty($payload['shop']) || empty($payload['nonce'])) {
            throw new RuntimeException('Shopify authorization could not be verified.');
        }
        if (!empty($payload['exp']) && (int) $payload['exp'] < time()) {
            throw new RuntimeException('Shopify authorization expired. Please try again.');
        }
        return $payload;
    }

    public function verifyHmac(array $query): bool
    {
        $secret = (string) config('shopify.api_secret');
        if ($secret === '') {
            return false;
        }
        $hmac = (string) ($query['hmac'] ?? '');
        unset($query['hmac'], $query['signature']);
        ksort($query);
        $parts = [];
        foreach ($query as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $parts[] = $key . '=' . $value;
        }
        $digest = hash_hmac('sha256', implode('&', $parts), $secret);
        return $hmac !== '' && hash_equals($digest, $hmac);
    }

    /**
     * Validate Shopify callback. Shop domain is taken from HMAC-signed query + encrypted state,
     * never from a raw form field.
     */
    public function assertValidCallback(array $query, int $currentStoreId): array
    {
        if (!empty($query['error'])) {
            if (($query['error'] ?? '') === 'access_denied') {
                throw new RuntimeException('Shopify authorization was cancelled.');
            }
            throw new RuntimeException('Shopify connection failed. Please try again.');
        }
        if (!$this->verifyHmac($query)) {
            throw new RuntimeException('Shopify authorization could not be verified.');
        }
        $state = $this->decodeState((string) ($query['state'] ?? ''));
        $session = Session::get('shopify_oauth');
        if (!is_array($session) || ($state['nonce'] ?? '') !== ($session['nonce'] ?? '')) {
            throw new RuntimeException('Shopify authorization expired. Please try again.');
        }
        if ((int) $state['store_id'] !== $currentStoreId || (int) ($session['store_id'] ?? 0) !== $currentStoreId) {
            throw new RuntimeException('This Shopify connection belongs to another store.');
        }
        $shop = ShopifyAdminClient::normalizeShop((string) ($query['shop'] ?? ''));
        if ($shop !== $state['shop'] || $shop !== ($session['shop'] ?? '')) {
            throw new RuntimeException('Shopify shop mismatch.');
        }
        if (empty($query['code'])) {
            throw new RuntimeException('Shopify connection failed. Please try again.');
        }
        Session::forget('shopify_oauth');
        return [
            'shop' => $shop,
            'code' => (string) $query['code'],
            'store_id' => $currentStoreId,
        ];
    }

    /**
     * @return array{access_token:string,scope:?string,refresh_token:?string}
     */
    public function exchangeCode(string $shop, string $code): array
    {
        $shop = ShopifyAdminClient::normalizeShop($shop);
        $ch = curl_init('https://' . $shop . '/admin/oauth/access_token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => http_build_query([
                'client_id' => config('shopify.api_key'),
                'client_secret' => config('shopify.api_secret'),
                'code' => $code,
            ]),
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode((string) $body, true);
        $token = is_array($json) ? (string) ($json['access_token'] ?? '') : '';
        if ($status !== 200 || $token === '') {
            throw new RuntimeException('Shopify connection failed. Please try again.');
        }
        return [
            'access_token' => $token,
            'scope' => is_array($json) ? ($json['scope'] ?? null) : null,
            'refresh_token' => is_array($json) ? ($json['refresh_token'] ?? null) : null,
        ];
    }
}
