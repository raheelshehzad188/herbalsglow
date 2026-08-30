<?php

namespace App\Services\WooCommerce;

use App\Models\WooCommerceConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WooCommerceClient
{
    public static function normalizeShop(string $input): string
    {
        $input = trim($input);
        if ($input === '' || preg_match('#^(javascript|data|file|ftp):#i', $input)) {
            throw new RuntimeException('Enter a valid WooCommerce store URL, like https://yourstore.com');
        }
        if (!preg_match('#^https?://#i', $input)) {
            $input = 'https://' . $input;
        }
        $parts = parse_url($input);
        $scheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new RuntimeException('Enter a valid WooCommerce store URL, like https://yourstore.com');
        }
        $host = strtolower((string) ($parts['host'] ?? ''));
        $host = preg_replace('#^www\.#', '', $host);
        if ($host === '' || filter_var($host, FILTER_VALIDATE_IP) || in_array($host, ['localhost'], true)) {
            throw new RuntimeException('Enter a valid WooCommerce store URL, like https://yourstore.com');
        }
        if (!preg_match('/^[a-z0-9]([a-z0-9\-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9\-]*[a-z0-9])?)+$/i', $host)) {
            throw new RuntimeException('Enter a valid WooCommerce store URL, like https://yourstore.com');
        }
        $path = rtrim((string) ($parts['path'] ?? ''), '/');
        $path = (string) preg_replace('#/wp-json(/.*)?$#', '', $path);
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        return $scheme . '://' . $host . $port . $path;
    }

    public static function hostFromUrl(string $url): string
    {
        return strtolower((string) parse_url($url, PHP_URL_HOST));
    }

    public static function productSlug(array $p): ?string
    {
        $slug = strtolower(trim((string) ($p['slug'] ?? ''), '/'));
        if ($slug !== '') {
            return $slug;
        }
        $path = trim((string) parse_url((string) ($p['permalink'] ?? ''), PHP_URL_PATH), '/');
        if ($path === '') {
            return null;
        }
        $parts = explode('/', $path);
        $last = strtolower(trim((string) end($parts)));
        return $last !== '' ? $last : null;
    }

    public function shop(WooCommerceConnection $connection): array
    {
        try {
            [$data] = $this->get($connection, 'system_status');
            $name = $data['environment']['site_title'] ?? $data['settings']['title'] ?? null;
            if ($name) {
                return ['name' => $name];
            }
        } catch (\Throwable $e) {
            // system_status needs extra capability on some stores
        }
        $this->get($connection, 'products', ['per_page' => 1, 'page' => 1]);
        return ['name' => $connection->shop_host ?: self::hostFromUrl($connection->shop_url)];
    }

    public function productsCount(WooCommerceConnection $connection, ?string $status = null): int
    {
        $query = ['per_page' => 1, 'page' => 1];
        if ($status) {
            $query['status'] = $status;
        }
        [, $meta] = $this->get($connection, 'products', $query);
        return (int) ($meta['total'] ?? 0);
    }

    /**
     * @return array{0: array<int,array>, 1: ?string}
     */
    public function productsPage(WooCommerceConnection $connection, ?string $cursor = null, int $perPage = 10, ?string $status = null): array
    {
        $page = max(1, (int) ($cursor ?: 1));
        $query = ['page' => $page, 'per_page' => max(1, min(50, $perPage))];
        if ($status) {
            $query['status'] = $status;
        }
        [$items, $meta] = $this->get($connection, 'products', $query);
        $mapped = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $mapped[] = $this->toShopifyProduct($item);
        }
        $next = $page < (int) ($meta['pages'] ?? 1) ? (string) ($page + 1) : null;
        return [$mapped, $next];
    }

    /**
     * @return array{0: array<int,array>, 1: ?string}
     */
    public function collectionsPage(WooCommerceConnection $connection, ?string $cursor = null): array
    {
        $page = max(1, (int) ($cursor ?: 1));
        [$items, $meta] = $this->get($connection, 'products/categories', [
            'page' => $page,
            'per_page' => 50,
        ]);
        $mapped = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $mapped[] = [
                'id' => (string) ($item['id'] ?? ''),
                'title' => $item['name'] ?? 'Category',
                'handle' => self::productSlug($item),
                'body_html' => $item['description'] ?? '',
                'image' => !empty($item['image']['src']) ? ['src' => $item['image']['src']] : null,
            ];
        }
        $next = $page < (int) ($meta['pages'] ?? 1) ? (string) ($page + 1) : null;
        return [$mapped, $next];
    }

    /**
     * @return array{0: array<int,array>, 1: ?string}
     */
    public function ordersPage(WooCommerceConnection $connection, ?string $cursor = null): array
    {
        $page = max(1, (int) ($cursor ?: 1));
        [$items, $meta] = $this->get($connection, 'orders', [
            'page' => $page,
            'per_page' => 20,
            'status' => 'any',
        ]);
        $mapped = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $mapped[] = $this->toShopifyOrder($item);
        }
        $next = $page < (int) ($meta['pages'] ?? 1) ? (string) ($page + 1) : null;
        return [$mapped, $next];
    }

    /**
     * @return array<int,array>
     */
    public function variations(WooCommerceConnection $connection, $productId): array
    {
        try {
            [$items] = $this->get($connection, 'products/' . $productId . '/variations', [
                'per_page' => 50,
                'page' => 1,
            ]);
        } catch (\Throwable $e) {
            return [];
        }
        $out = [];
        foreach ($items as $v) {
            if (!is_array($v)) {
                continue;
            }
            $out[] = $this->toShopifyVariant($v);
        }
        return $out;
    }

    /**
     * @return array{0: array, 1: array{total:int,pages:int}}
     */
    public function get(WooCommerceConnection $connection, string $path, array $query = [], int $attempt = 0): array
    {
        $base = rtrim((string) $connection->shop_url, '/');
        $url = $base . '/wp-json/wc/v3/' . ltrim($path, '/');
        $key = (string) $connection->consumer_key;
        $secret = $connection->getConsumerSecret();
        if ($key === '' || $secret === '') {
            throw new RuntimeException('WooCommerce connection is no longer valid. Reconnect your store.');
        }

        try {
            $response = Http::withBasicAuth($key, $secret)
                ->acceptJson()
                ->timeout(45)
                ->get($url, $query);
        } catch (\Throwable $e) {
            throw new RuntimeException('Could not reach WooCommerce. Please try again.');
        }

        $status = $response->status();
        if (($status === 401 || $status === 403) && $attempt === 0) {
            $query['consumer_key'] = $key;
            $query['consumer_secret'] = $secret;
            return $this->get($connection, $path, $query, 1);
        }
        if ($status === 401 || $status === 403) {
            throw new RuntimeException('WooCommerce connection is no longer valid. Reconnect your store.');
        }
        if ($status === 429 && $attempt < 5) {
            sleep(min(8, 1 + $attempt * 2));
            return $this->get($connection, $path, $query, $attempt + 1);
        }
        if ($status >= 500 && $attempt < 4) {
            sleep((int) pow(2, $attempt));
            return $this->get($connection, $path, $query, $attempt + 1);
        }
        if (!$response->successful()) {
            Log::warning('WooCommerce API error', [
                'host' => $connection->shop_host,
                'http_status' => $status,
                'path' => $path,
            ]);
            throw new RuntimeException('WooCommerce API error (' . $status . ')');
        }

        $json = $response->json();
        if (!is_array($json)) {
            $json = [];
        }
        $total = (int) ($response->header('X-WP-Total') ?: $response->header('x-wp-total') ?: 0);
        $pages = (int) ($response->header('X-WP-TotalPages') ?: $response->header('x-wp-totalpages') ?: 0);
        if ($pages < 1) {
            $pages = $total > 0 ? 1 : (empty($json) ? 0 : 1);
        }
        return [$json, ['total' => $total, 'pages' => $pages]];
    }

    protected function toShopifyProduct(array $p): array
    {
        $id = (string) ($p['id'] ?? '');
        $regular = $p['regular_price'] ?? null;
        $sale = $p['sale_price'] ?? null;
        $price = $p['price'] ?? ($sale ?: $regular ?: 0);
        $compare = ($sale !== null && $sale !== '' && $regular && (string) $regular !== (string) $sale) ? $regular : null;
        $variants = [];
        if (!empty($p['variations']) && is_array($p['variations'])) {
            $variants[] = [
                'id' => 'p' . $id,
                'title' => 'Default',
                'sku' => $p['sku'] ?? null,
                'barcode' => null,
                'price' => $price,
                'compare_at_price' => $compare,
                'inventory_quantity' => (int) ($p['stock_quantity'] ?? 0),
                '_woo_variation_ids' => $p['variations'],
            ];
        } else {
            $variants[] = $this->defaultVariant($p, $id, $price, $compare);
        }

        $images = [];
        foreach ($p['images'] ?? [] as $img) {
            if (empty($img['src'])) {
                continue;
            }
            $images[] = [
                'id' => $img['id'] ?? null,
                'src' => $img['src'],
                'alt' => $img['alt'] ?? null,
                'position' => $img['position'] ?? 0,
            ];
        }

        $options = [];
        foreach ($p['attributes'] ?? [] as $attr) {
            if (empty($attr['variation']) && empty($attr['visible'])) {
                continue;
            }
            $options[] = [
                'id' => $attr['id'] ?? null,
                'name' => $attr['name'] ?? 'Option',
                'values' => $attr['options'] ?? [],
            ];
        }

        $collectionIds = [];
        foreach ($p['categories'] ?? [] as $cat) {
            if (isset($cat['id'])) {
                $collectionIds[] = (string) $cat['id'];
            }
        }

        $tags = [];
        foreach ($p['tags'] ?? [] as $tag) {
            if (!empty($tag['name'])) {
                $tags[] = $tag['name'];
            }
        }

        return [
            'id' => $id,
            'title' => $p['name'] ?? 'Untitled',
            'handle' => self::productSlug($p),
            'body_html' => $p['description'] ?? '',
            'vendor' => $this->extractVendor($p),
            'product_type' => $p['type'] ?? '',
            'tags' => implode(', ', $tags),
            'status' => $this->mapStatus((string) ($p['status'] ?? 'publish')),
            'variants' => $variants,
            'images' => $images,
            'options' => $options,
            'collection_ids' => $collectionIds,
        ];
    }

    protected function defaultVariant(array $p, string $id, $price, $compare): array
    {
        return [
            'id' => 'p' . $id,
            'title' => 'Default',
            'sku' => $p['sku'] ?? null,
            'barcode' => null,
            'price' => $price,
            'compare_at_price' => $compare,
            'inventory_quantity' => (int) ($p['stock_quantity'] ?? 0),
        ];
    }

    protected function toShopifyVariant(array $v): array
    {
        $regular = $v['regular_price'] ?? null;
        $sale = $v['sale_price'] ?? null;
        $price = $v['price'] ?? ($sale ?: $regular ?: 0);
        $compare = ($sale !== null && $sale !== '' && $regular && (string) $regular !== (string) $sale) ? $regular : null;
        $title = 'Variant';
        $bits = [];
        foreach ($v['attributes'] ?? [] as $attr) {
            if (!empty($attr['option'])) {
                $bits[] = $attr['option'];
            }
        }
        if ($bits) {
            $title = implode(' / ', $bits);
        }
        return [
            'id' => (string) ($v['id'] ?? ''),
            'title' => $title,
            'sku' => $v['sku'] ?? null,
            'barcode' => null,
            'price' => $price,
            'compare_at_price' => $compare,
            'inventory_quantity' => (int) ($v['stock_quantity'] ?? 0),
        ];
    }

    protected function toShopifyOrder(array $o): array
    {
        $billing = $o['billing'] ?? [];
        $shipping = $o['shipping'] ?? [];
        $items = [];
        foreach ($o['line_items'] ?? [] as $item) {
            $items[] = [
                'title' => $item['name'] ?? 'Item',
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];
        }
        return [
            'id' => $o['id'] ?? '',
            'name' => $o['number'] ?? ($o['id'] ?? 'Order'),
            'total_price' => $o['total'] ?? 0,
            'financial_status' => $o['status'] ?? 'pending',
            'phone' => $billing['phone'] ?? '',
            'customer' => [
                'first_name' => $billing['first_name'] ?? '',
                'last_name' => $billing['last_name'] ?? '',
            ],
            'billing_address' => [
                'name' => trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '')),
                'phone' => $billing['phone'] ?? '',
                'address1' => $billing['address_1'] ?? '',
                'city' => $billing['city'] ?? '',
            ],
            'shipping_address' => [
                'name' => trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')),
                'phone' => $shipping['phone'] ?? ($billing['phone'] ?? ''),
                'address1' => $shipping['address_1'] ?? '',
                'city' => $shipping['city'] ?? '',
            ],
            'line_items' => $items,
        ];
    }

    protected function extractVendor(array $p): string
    {
        if (!empty($p['brands'][0]['name'])) {
            return (string) $p['brands'][0]['name'];
        }
        foreach ($p['attributes'] ?? [] as $attr) {
            $name = strtolower((string) ($attr['name'] ?? ''));
            if (in_array($name, ['brand', 'brands', 'vendor', 'manufacturer', 'pa_brand'], true)) {
                $opts = $attr['options'] ?? [];
                if ($opts) {
                    return (string) $opts[0];
                }
            }
        }
        return '';
    }

    protected function mapStatus(string $status): string
    {
        $status = strtolower($status);
        if ($status === 'publish') {
            return 'active';
        }
        if (in_array($status, ['draft', 'pending'], true)) {
            return 'draft';
        }
        return 'archived';
    }
}
