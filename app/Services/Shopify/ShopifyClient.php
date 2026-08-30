<?php

namespace App\Services\Shopify;

use App\Models\ShopifyConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ShopifyClient
{
    public function shop(ShopifyConnection $connection): array
    {
        $data = $this->graphql($connection, 'query { shop { id name myshopifyDomain } }');
        $shop = $data['shop'] ?? [];
        return [
            'id' => self::numericId($shop['id'] ?? null),
            'name' => $shop['name'] ?? null,
            'myshopifyDomain' => $shop['myshopifyDomain'] ?? $connection->shop_domain,
        ];
    }

    public function productsCount(ShopifyConnection $connection): int
    {
        try {
            $data = $this->graphql($connection, 'query { productsCount { count } }');
            return (int) ($data['productsCount']['count'] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * @return array{0: array<int,array>, 1: ?string}
     */
    public function productsPage(ShopifyConnection $connection, ?string $cursor = null, int $first = 50): array
    {
        $first = max(1, min(50, $first));
        $query = <<<'GQL'
query ImportProducts($cursor: String, $first: Int!) {
  products(first: $first, after: $cursor) {
    pageInfo { hasNextPage endCursor }
    nodes {
      id
      title
      handle
      descriptionHtml
      vendor
      productType
      tags
      status
      createdAt
      updatedAt
      publishedAt
      seo { title description }
      options { id name values }
      variants(first: 100) {
        nodes {
          id
          title
          sku
          barcode
          price
          compareAtPrice
          inventoryQuantity
          selectedOptions { name value }
        }
      }
      media(first: 50) {
        nodes {
          ... on MediaImage {
            id
            image { url altText width height }
          }
        }
      }
      collections(first: 50) {
        nodes { id title handle }
      }
    }
  }
}
GQL;
        $data = $this->graphql($connection, $query, ['cursor' => $cursor, 'first' => $first]);
        $conn = $data['products'] ?? [];
        $items = [];
        foreach ($conn['nodes'] ?? [] as $node) {
            $items[] = $this->toRestProduct($node);
        }
        $next = !empty($conn['pageInfo']['hasNextPage']) ? ($conn['pageInfo']['endCursor'] ?? null) : null;
        return [$items, $next];
    }

    /**
     * @return array{0: array<int,array>, 1: ?string}
     */
    public function collectionsPage(ShopifyConnection $connection, ?string $cursor = null): array
    {
        $query = <<<'GQL'
query ImportCollections($cursor: String) {
  collections(first: 50, after: $cursor) {
    pageInfo { hasNextPage endCursor }
    nodes { id title handle descriptionHtml image { url } }
  }
}
GQL;
        $data = $this->graphql($connection, $query, ['cursor' => $cursor]);
        $conn = $data['collections'] ?? [];
        $items = [];
        foreach ($conn['nodes'] ?? [] as $node) {
            $items[] = [
                'id' => self::numericId($node['id'] ?? null),
                'title' => $node['title'] ?? 'Collection',
                'handle' => $node['handle'] ?? null,
                'body_html' => $node['descriptionHtml'] ?? '',
                'image' => !empty($node['image']['url']) ? ['src' => $node['image']['url']] : null,
            ];
        }
        $next = !empty($conn['pageInfo']['hasNextPage']) ? ($conn['pageInfo']['endCursor'] ?? null) : null;
        return [$items, $next];
    }

    public function graphql(ShopifyConnection $connection, string $query, array $variables = [], int $attempt = 0): array
    {
        $token = app(ShopifyAuthService::class)->getAccessToken($connection);
        $version = (string) config('shopify.api_version', '2026-07');
        $url = 'https://' . $connection->shop_domain . '/admin/api/' . $version . '/graphql.json';
        $payload = ['query' => $query];
        if ($variables) {
            $payload['variables'] = $variables;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $token,
                'Accept' => 'application/json',
            ])->timeout(45)->post($url, $payload);
        } catch (\Throwable $e) {
            throw new RuntimeException('Could not reach Shopify. Please try again.');
        }

        $status = $response->status();
        if ($status === 401 || $status === 403) {
            throw new RuntimeException('Shopify connection is no longer valid. Reconnect your store.');
        }
        if ($status === 429 && $attempt < 5) {
            sleep(min(8, 1 + $attempt * 2));
            return $this->graphql($connection, $query, $variables, $attempt + 1);
        }
        if ($status >= 500 && $attempt < 4) {
            sleep((int) pow(2, $attempt));
            return $this->graphql($connection, $query, $variables, $attempt + 1);
        }
        if (!$response->successful()) {
            throw new RuntimeException('Shopify API error (' . $status . ')');
        }

        $json = $response->json();
        if (!is_array($json)) {
            $json = [];
        }
        if (!empty($json['errors'])) {
            $first = $json['errors'][0] ?? [];
            $msg = is_array($first) ? (string) ($first['message'] ?? 'Shopify GraphQL error') : 'Shopify GraphQL error';
            $code = $first['extensions']['code'] ?? '';
            if (stripos((string) $code, 'THROTTLED') !== false && $attempt < 5) {
                sleep(min(8, 2 + $attempt));
                return $this->graphql($connection, $query, $variables, $attempt + 1);
            }
            Log::warning('Shopify GraphQL error', ['shop' => $connection->shop_domain, 'code' => $code]);
            throw new RuntimeException($this->safe($msg));
        }

        $throttle = $json['extensions']['cost']['throttleStatus'] ?? [];
        if (isset($throttle['currentlyAvailable'], $throttle['restoreRate']) && (int) $throttle['currentlyAvailable'] < 50) {
            usleep(400000);
        }

        return $json['data'] ?? [];
    }

    public static function numericId($gid): string
    {
        $gid = (string) $gid;
        if ($gid === '') {
            return '';
        }
        if (preg_match('#/(\d+)$#', $gid, $m)) {
            return $m[1];
        }
        return $gid;
    }

    protected function toRestProduct(array $node): array
    {
        $variants = [];
        foreach ($node['variants']['nodes'] ?? [] as $v) {
            $variants[] = [
                'id' => self::numericId($v['id'] ?? null),
                'title' => $v['title'] ?? 'Default',
                'sku' => $v['sku'] ?? null,
                'barcode' => $v['barcode'] ?? null,
                'price' => $v['price'] ?? 0,
                'compare_at_price' => $v['compareAtPrice'] ?? null,
                'inventory_quantity' => (int) ($v['inventoryQuantity'] ?? 0),
            ];
        }
        $images = [];
        foreach ($node['media']['nodes'] ?? [] as $media) {
            if (empty($media['image']['url'])) {
                continue;
            }
            $images[] = [
                'id' => self::numericId($media['id'] ?? null),
                'src' => $media['image']['url'],
                'alt' => $media['image']['altText'] ?? null,
            ];
        }
        $options = [];
        foreach ($node['options'] ?? [] as $opt) {
            $options[] = [
                'id' => self::numericId($opt['id'] ?? null),
                'name' => $opt['name'] ?? 'Option',
                'values' => $opt['values'] ?? [],
            ];
        }
        $collectionIds = [];
        foreach ($node['collections']['nodes'] ?? [] as $col) {
            $collectionIds[] = self::numericId($col['id'] ?? null);
        }
        $status = strtolower((string) ($node['status'] ?? 'active'));
        return [
            'id' => self::numericId($node['id'] ?? null),
            'title' => $node['title'] ?? 'Untitled',
            'handle' => $node['handle'] ?? null,
            'body_html' => $node['descriptionHtml'] ?? '',
            'vendor' => $node['vendor'] ?? '',
            'product_type' => $node['productType'] ?? '',
            'tags' => is_array($node['tags'] ?? null) ? implode(', ', $node['tags']) : (string) ($node['tags'] ?? ''),
            'status' => $status,
            'variants' => $variants,
            'images' => $images,
            'options' => $options,
            'collection_ids' => $collectionIds,
        ];
    }

    protected function safe(string $message): string
    {
        return preg_replace('/shpat_[a-zA-Z0-9]+|shpss_[a-zA-Z0-9]+|shpca_[a-zA-Z0-9]+/', '[redacted]', $message);
    }
}
