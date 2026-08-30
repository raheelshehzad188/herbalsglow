<?php

namespace App\Services\Shopify;

use App\Models\ShopifyConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ShopifyAuthService
{
    public function connect(int $storeId, string $shop, string $clientId, string $clientSecret): ShopifyConnection
    {
        $shop = ShopifyAdminClient::normalizeShop($shop);
        $clientId = trim($clientId);
        $clientSecret = trim($clientSecret);
        $token = $this->requestToken($shop, $clientId, $clientSecret);

        $row = ShopifyConnection::withoutStore()->firstOrNew(['store_id' => $storeId]);
        $row->store_id = $storeId;
        $row->shop_domain = $shop;
        $row->client_id = $clientId;
        $row->setClientSecret($clientSecret);
        $this->applyToken($row, $token);
        $row->connection_method = 'client_credentials';
        $row->status = 'connected';
        $row->last_connected_at = now();
        if (!$row->installed_at) {
            $row->installed_at = now();
        }
        $row->save();

        $shopInfo = app(ShopifyClient::class)->shop($row);
        $row->shop_name = $shopInfo['name'] ?? $shop;
        $row->shopify_shop_id = $shopInfo['id'] ?? null;
        if (!empty($shopInfo['myshopifyDomain'])) {
            $row->shop_domain = ShopifyAdminClient::normalizeShop($shopInfo['myshopifyDomain']);
        }
        $row->save();

        return $row;
    }

    public function getAccessToken(ShopifyConnection $connection): string
    {
        if ($this->isTokenValid($connection)) {
            return $connection->getAccessToken();
        }
        return $this->refreshToken($connection);
    }

    public function isTokenValid(ShopifyConnection $connection): bool
    {
        if (!$connection->access_token_encrypted) {
            return false;
        }
        if (!$connection->token_expires_at) {
            return (bool) $connection->getAccessToken();
        }
        return $connection->token_expires_at->gt(now()->addMinutes(5));
    }

    public function refreshToken(ShopifyConnection $connection): string
    {
        $clientId = (string) $connection->client_id;
        $secret = $connection->getClientSecret();
        if ($clientId === '' || $secret === '') {
            throw new RuntimeException('Shopify connection has expired. Please reconnect.');
        }
        $token = $this->requestToken($connection->shop_domain, $clientId, $secret);
        $this->applyToken($connection, $token);
        $connection->save();
        return $connection->getAccessToken();
    }

    public function test(ShopifyConnection $connection): array
    {
        $this->getAccessToken($connection);
        return app(ShopifyClient::class)->shop($connection);
    }

    /**
     * @return array{access_token:string,scope:?string,expires_in:int}
     */
    public function requestToken(string $shop, string $clientId, string $clientSecret): array
    {
        $shop = ShopifyAdminClient::normalizeShop($shop);
        try {
            $response = Http::asForm()
                ->timeout(25)
                ->acceptJson()
                ->post('https://' . $shop . '/admin/oauth/access_token', [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ]);
        } catch (\Throwable $e) {
            Log::warning('Shopify client credentials token failed', [
                'shop' => $shop,
                'http_status' => 0,
                'error_code' => 'connection_failed',
                'error_message' => 'Could not reach Shopify token endpoint.',
                'organization_restriction' => false,
            ]);
            throw new RuntimeException('Shopify OAuth error (HTTP 0): connection_failed — Could not reach the token endpoint.');
        }

        $json = $response->json();
        if (!is_array($json)) {
            $json = [];
        }
        $status = $response->status();
        $access = (string) ($json['access_token'] ?? '');

        if ($status === 429) {
            $this->failToken($shop, $status, $json, 'temporarily_unavailable');
        }
        if (!$response->successful() || $access === '') {
            $this->failToken($shop, $status, $json);
        }

        return [
            'access_token' => $access,
            'scope' => $json['scope'] ?? null,
            'expires_in' => (int) ($json['expires_in'] ?? 86399),
        ];
    }

    protected function failToken(string $shop, int $status, array $json, ?string $forcedCode = null): void
    {
        $code = $forcedCode ?: $this->oauthErrorCode($json);
        $message = $this->sanitizeOAuthText((string) (
            $json['error_description']
            ?? $json['error_description_translated']
            ?? (is_string($json['errors'] ?? null) ? $json['errors'] : '')
            ?? $json['message']
            ?? $json['error']
            ?? ($status ? 'HTTP ' . $status : 'unknown error')
        ));
        $org = $code === 'shop_not_permitted'
            || str_contains(strtolower($code . ' ' . $message), 'shop_not_permitted')
            || str_contains(strtolower($message), 'same organization')
            || str_contains(strtolower($message), 'not in the same');

        Log::warning('Shopify client credentials token failed', [
            'shop' => $shop,
            'http_status' => $status,
            'error_code' => $code,
            'error_message' => $message,
            'organization_restriction' => $org,
        ]);

        $out = 'Shopify OAuth error (HTTP ' . $status . '): ' . $code;
        if ($message !== '' && strcasecmp($message, $code) !== 0) {
            $out .= ' — ' . $message;
        }
        if ($org) {
            $out .= ' App/store organization restriction: yes.';
        }
        throw new RuntimeException($out);
    }

    protected function oauthErrorCode(array $json): string
    {
        $raw = $json['error'] ?? $json['error_code'] ?? '';
        if (is_array($raw)) {
            $raw = implode(',', $raw);
        }
        $raw = strtolower(trim((string) $raw));
        $known = [
            'shop_not_permitted',
            'application_cannot_be_found',
            'invalid_client',
            'unauthorized_client',
            'invalid_request',
            'invalid_grant',
            'unsupported_grant_type',
            'access_denied',
            'invalid_scope',
            'server_error',
            'temporarily_unavailable',
        ];
        foreach ($known as $code) {
            if ($raw === $code || str_contains($raw, $code)) {
                return $code;
            }
        }
        if (preg_match('/^[a-z0-9_]{3,64}$/', $raw)) {
            return $raw;
        }
        $blob = strtolower((string) json_encode($json));
        foreach ($known as $code) {
            if (str_contains($blob, $code)) {
                return $code;
            }
        }
        return 'unknown';
    }

    protected function sanitizeOAuthText(string $text): string
    {
        $text = preg_replace('/shpat_[A-Za-z0-9]+|shpss_[A-Za-z0-9]+|shpca_[A-Za-z0-9]+|shpua_[A-Za-z0-9]+/', '[redacted]', $text);
        $text = preg_replace('/(client_secret|access_token|refresh_token)\s*[:=]\s*\S+/i', '$1=[redacted]', $text);
        $text = preg_replace('/[A-Za-z0-9]{32,}/', '[redacted]', $text);
        return trim($text);
    }

    protected function applyToken(ShopifyConnection $row, array $token): void
    {
        $row->setAccessToken($token['access_token']);
        $row->scopes = $token['scope'] ?: ($row->scopes ?: 'read_products');
        $row->token_expires_at = now()->addSeconds(max(60, (int) $token['expires_in']));
        $row->status = 'connected';
    }
}
