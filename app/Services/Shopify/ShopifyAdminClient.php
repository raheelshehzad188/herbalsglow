<?php

namespace App\Services\Shopify;

use App\Models\ShopifyConnection;
use RuntimeException;

class ShopifyAdminClient
{
    protected string $shop;
    protected string $token;
    protected string $version;

    public function __construct(string $shopDomain, string $accessToken)
    {
        $this->shop = self::normalizeShop($shopDomain);
        $this->token = $accessToken;
        $this->version = (string) config('shopify.api_version', '2026-07');
    }

    public static function forConnection(ShopifyConnection $connection): self
    {
        $token = app(ShopifyAuthService::class)->getAccessToken($connection);
        return new self($connection->shop_domain, $token);
    }

    public static function normalizeShop(string $input): string
    {
        $input = strtolower(trim($input));
        if ($input === '' || preg_match('#^(javascript|data|file):#i', $input)) {
            throw new RuntimeException('Enter a valid Shopify store URL, like your-store.myshopify.com');
        }
        $input = preg_replace('#^https?://#', '', $input);
        $input = preg_replace('#^www\.#', '', $input);
        $input = rtrim($input, '/');
        $host = preg_replace('#/.*$#', '', $input);
        $host = explode(':', (string) $host)[0];
        if (filter_var($host, FILTER_VALIDATE_IP) || in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            throw new RuntimeException('Enter a valid Shopify store URL, like your-store.myshopify.com');
        }
        if (!str_contains($host, '.')) {
            $host .= '.myshopify.com';
        }
        if (!preg_match('/^[a-z0-9][a-z0-9\-]*\.myshopify\.com$/', $host)) {
            throw new RuntimeException('Enter a valid Shopify store URL, like your-store.myshopify.com');
        }
        return $host;
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, $query);
    }

    /**
     * Revoke this app's access token on Shopify. Safe to call if already revoked.
     */
    public function revoke(): void
    {
        $url = 'https://' . $this->shop . '/admin/api_permissions/current.json';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                'X-Shopify-Access-Token: ' . $this->token,
                'Accept: application/json',
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    public function getShop(): array
    {
        $res = $this->get('shop.json');
        return $res['shop'] ?? [];
    }

    public function count(string $resource, array $query = []): int
    {
        $res = $this->get($resource . '/count.json', $query);
        return (int) ($res['count'] ?? 0);
    }

    /**
     * Fetch one REST page. Returns [items, next_page_info].
     */
    public function page(string $path, string $rootKey, array $query = [], ?string $pageInfo = null): array
    {
        if ($pageInfo) {
            $query = ['limit' => $query['limit'] ?? 50, 'page_info' => $pageInfo];
        } else {
            $query['limit'] = $query['limit'] ?? 50;
        }
        $res = $this->request('GET', $path, $query, true);
        $items = $res['json'][$rootKey] ?? [];
        return [$items, $res['next_page']];
    }

    protected function request(string $method, string $path, array $query = [], bool $withPaging = false, int $attempt = 0): array
    {
        $path = ltrim($path, '/');
        $url = 'https://' . $this->shop . '/admin/api/' . $this->version . '/' . $path;
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-Shopify-Access-Token: ' . $this->token,
                'Accept: application/json',
            ],
            CURLOPT_HEADER => true,
        ]);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        if ($errno) {
            throw new RuntimeException('Could not reach Shopify. Please try again.');
        }

        $headerBlob = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);
        $headers = $this->parseHeaders($headerBlob);

        if ($status === 429 && $attempt < 5) {
            $wait = (int) ($headers['retry-after'] ?? 2);
            sleep(max(1, min($wait, 10)));
            return $this->request($method, $path, $query, $withPaging, $attempt + 1);
        }

        if ($status === 401 || $status === 403) {
            throw new RuntimeException('Shopify connection is no longer valid. Reconnect your store.');
        }

        if ($status >= 500 && $attempt < 4) {
            sleep((int) pow(2, $attempt));
            return $this->request($method, $path, $query, $withPaging, $attempt + 1);
        }

        if ($status < 200 || $status >= 300) {
            $decoded = json_decode($body, true);
            $msg = is_array($decoded) ? ($decoded['errors'] ?? $decoded['error'] ?? null) : null;
            if (is_array($msg)) {
                $msg = json_encode($msg);
            }
            throw new RuntimeException($this->safeError($msg ?: ('Shopify API error (' . $status . ')')));
        }

        $this->respectRateLimit($headers);

        $json = json_decode($body, true);
        if (!is_array($json)) {
            $json = [];
        }

        if ($withPaging) {
            return ['json' => $json, 'next_page' => $this->nextPageInfo($headers)];
        }
        return $json;
    }

    protected function respectRateLimit(array $headers): void
    {
        $limit = $headers['x-shopify-shop-api-call-limit'] ?? '';
        if (preg_match('/(\d+)\/(\d+)/', $limit, $m)) {
            $used = (int) $m[1];
            $max = (int) $m[2];
            if ($max > 0 && ($max - $used) <= 4) {
                usleep(500000);
            }
        }
    }

    protected function nextPageInfo(array $headers): ?string
    {
        $link = $headers['link'] ?? '';
        if (preg_match('/<([^>]+)>;\s*rel="next"/', $link, $m)) {
            $query = parse_url($m[1], PHP_URL_QUERY);
            parse_str((string) $query, $params);
            return $params['page_info'] ?? null;
        }
        return null;
    }

    protected function parseHeaders(string $blob): array
    {
        $out = [];
        foreach (preg_split("/\r\n|\n|\r/", $blob) as $line) {
            if (strpos($line, ':') === false) {
                continue;
            }
            [$k, $v] = explode(':', $line, 2);
            $out[strtolower(trim($k))] = trim($v);
        }
        return $out;
    }

    protected function safeError($message): string
    {
        $text = (string) $message;
        $text = preg_replace('/shpat_[a-zA-Z0-9]+/', '[redacted]', $text);
        $text = preg_replace('/shpss_[a-zA-Z0-9]+/', '[redacted]', $text);
        return $text;
    }
}
