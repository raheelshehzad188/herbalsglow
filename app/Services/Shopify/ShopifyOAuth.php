<?php

namespace App\Services\Shopify;

use Illuminate\Support\Facades\Crypt;
use RuntimeException;

class ShopifyOAuth
{
    public function isConfigured(): bool
    {
        return (bool) (config('shopify.api_key') && config('shopify.api_secret'));
    }

    public function authorizeUrl(string $shop, int $storeId, int $adminId): string
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Shopify app credentials are not configured yet. Use API credentials instead.');
        }
        $shop = ShopifyAdminClient::normalizeShop($shop);
        $state = Crypt::encryptString(json_encode([
            'store_id' => $storeId,
            'admin_id' => $adminId,
            'shop' => $shop,
            'nonce' => bin2hex(random_bytes(16)),
            'exp' => time() + 900,
        ]));
        $params = http_build_query([
            'client_id' => config('shopify.api_key'),
            'scope' => config('shopify.scopes'),
            'redirect_uri' => url('/admin/import-data/shopify/callback'),
            'state' => $state,
        ]);
        return 'https://' . $shop . '/admin/oauth/authorize?' . $params;
    }

    public function decodeState(string $state): array
    {
        $payload = json_decode(Crypt::decryptString($state), true);
        if (!is_array($payload) || empty($payload['store_id']) || empty($payload['shop'])) {
            throw new RuntimeException('Invalid Shopify authorization state.');
        }
        if (!empty($payload['exp']) && (int) $payload['exp'] < time()) {
            throw new RuntimeException('Shopify authorization expired. Try connecting again.');
        }
        return $payload;
    }

    public function verifyHmac(array $query): bool
    {
        $secret = (string) config('shopify.api_secret');
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
        return hash_equals($digest, $hmac);
    }

    public function exchangeCode(string $shop, string $code): string
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
        $token = is_array($json) ? ($json['access_token'] ?? '') : '';
        if ($status !== 200 || !$token) {
            throw new RuntimeException('Could not complete Shopify authorization.');
        }
        return $token;
    }
}
