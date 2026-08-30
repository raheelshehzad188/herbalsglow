<?php

namespace App\Services\WooCommerce;

use App\Models\ShopifyImportJob;
use App\Models\WooCommerceConnection;
use App\Services\Shopify\ShopifyImporter;
use Illuminate\Support\Facades\Schema;

class WooCommerceImporter extends ShopifyImporter
{
    protected string $resourcePrefix = 'woo_';

    public function buildPreview($connection, array $config): array
    {
        $client = app(WooCommerceClient::class);
        $types = $config['types'] ?? [];
        $status = $this->wooStatusQuery($config['options'] ?? []);
        $preview = ['samples' => [], 'totals' => []];

        $productCount = in_array('products', $types, true) ? $client->productsCount($connection, $status) : 0;
        [$products] = (in_array('products', $types, true) || in_array('brands', $types, true))
            ? $client->productsPage($connection, null, 10, $status)
            : [[], null];
        [$collections] = in_array('collections', $types, true) ? $client->collectionsPage($connection) : [[], null];

        $vendors = [];
        $images = 0;
        $variants = 0;
        foreach ($products as $p) {
            if (!empty($p['vendor'])) {
                $vendors[$p['vendor']] = true;
            }
            $images += count($p['images'] ?? []);
            $variants += max(1, count($p['variants'] ?? []));
        }
        if (!$productCount) {
            $productCount = count($products);
        }

        $preview['totals'] = [
            'products' => $productCount,
            'categories' => count($collections),
            'brands' => count($vendors),
            'images' => $productCount ? (int) round($images * max($productCount / max(count($products), 1), 1)) : 0,
            'variants' => $productCount ? (int) round($variants * max($productCount / max(count($products), 1), 1)) : 0,
        ];

        foreach (array_slice($products, 0, 5) as $p) {
            $v = $p['variants'][0] ?? [];
            $preview['samples'][] = [
                'title' => $p['title'] ?? '',
                'vendor' => $p['vendor'] ?? '',
                'sku' => $v['sku'] ?? '',
                'price' => $v['price'] ?? '',
                'status' => $p['status'] ?? '',
            ];
        }
        return $preview;
    }

    public function tick(ShopifyImportJob $job, int $seconds = 8): void
    {
        $this->lastBatch = [];
        $job->refresh();
        if (in_array($job->status, ['completed', 'failed', 'cancelled'], true)) {
            return;
        }
        if ($job->cancel_requested) {
            $job->status = 'cancelled';
            $job->finished_at = now();
            $job->save();
            return;
        }

        $connection = WooCommerceConnection::withoutStore()
            ->where('id', $job->connection_id)
            ->where('store_id', $job->store_id)
            ->first();
        if (!$connection || !$connection->isConnected()) {
            $job->status = 'failed';
            $job->finished_at = now();
            $job->save();
            $this->fail($job, 'connection', null, 'WooCommerce', 'WooCommerce is not connected.');
            return;
        }

        $client = app(WooCommerceClient::class);
        $cursor = $job->cursor();
        $stage = $cursor['stage'] ?? 'start';
        $deadline = microtime(true) + $seconds;

        try {
            if ($stage === 'start') {
                $cursor = ['stage' => 'collections', 'page_info' => null];
                $job->status = 'running';
                if (!$job->started_at) {
                    $job->started_at = now();
                }
                $job->setCursor($cursor);
                $job->save();
                $stage = 'collections';
            }

            while (microtime(true) < $deadline) {
                $job->refresh();
                if ($job->cancel_requested) {
                    $job->status = 'cancelled';
                    $job->finished_at = now();
                    $job->save();
                    return;
                }
                $cursor = $job->cursor();
                $stage = $cursor['stage'] ?? 'done';
                if ($stage === 'collections') {
                    $this->importCollectionsPage($job, $connection, $client, $cursor);
                } elseif ($stage === 'products') {
                    $this->importProductsPage($job, $connection, $client, $cursor);
                } elseif ($stage === 'orders') {
                    $this->importOrdersPage($job, $connection, $client, $cursor);
                } else {
                    $this->finish($job, $connection);
                    return;
                }
                $job->refresh();
            }
        } catch (\Throwable $e) {
            $this->fail($job, 'job', null, 'Import', $this->safe($e->getMessage()));
            if ($this->isAuthError($e)) {
                $connection->status = 'invalid';
                $connection->save();
                $job->status = 'failed';
                $job->finished_at = now();
                $job->save();
            }
        }
    }

    protected function importCollectionsPage($job, $connection, $client, array $cursor): void
    {
        $config = $job->config();
        if (!in_array('collections', $config['types'] ?? [], true)) {
            $cursor['stage'] = 'products';
            $cursor['page_info'] = null;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        [$items, $next] = $client->collectionsPage($connection, $cursor['page_info'] ?? null);
        foreach ($items as $col) {
            $this->upsertCategory($job, $connection, $col);
            $this->bump($job, 'categories', 'done');
        }
        if ($next) {
            $cursor['page_info'] = $next;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        $cursor['stage'] = 'products';
        $cursor['page_info'] = null;
        $job->setCursor($cursor);
        $job->save();
    }

    protected function importProductsPage($job, $connection, $client, array $cursor): void
    {
        $config = $job->config();
        $types = $config['types'] ?? [];
        if (!in_array('products', $types, true) && !in_array('brands', $types, true)) {
            $cursor['stage'] = in_array('orders', $types, true) ? 'orders' : 'done';
            $cursor['page_info'] = null;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        $status = $this->wooStatusQuery($config['options'] ?? []);
        [$items, $next] = $client->productsPage($connection, $cursor['page_info'] ?? null, 10, $status);
        $allowed = $this->productStatuses($config['options'] ?? []);
        foreach ($items as $product) {
            if (!in_array($product['status'] ?? 'active', $allowed, true)) {
                $this->bump($job, 'products', 'skipped');
                continue;
            }
            if (in_array('variants', $types, true) && !empty($config['options']['import_variants'])) {
                $product = $this->expandVariations($client, $connection, $product);
            }
            $this->importOneProduct($job, $connection, $product);
        }
        if ($next) {
            $cursor['page_info'] = $next;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        $cursor['stage'] = in_array('orders', $types, true) ? 'orders' : 'done';
        $cursor['page_info'] = null;
        $job->setCursor($cursor);
        $job->save();
    }

    protected function importOrdersPage($job, $connection, $client, array $cursor): void
    {
        $config = $job->config();
        if (!in_array('orders', $config['types'] ?? [], true) || !Schema::hasTable('custom_order')) {
            $cursor['stage'] = 'done';
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        [$items, $next] = $client->ordersPage($connection, $cursor['page_info'] ?? null);
        foreach ($items as $order) {
            $this->upsertOrder($job, $connection, $order);
        }
        if ($next) {
            $cursor['page_info'] = $next;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        $cursor['stage'] = 'done';
        $job->setCursor($cursor);
        $job->save();
    }

    protected function expandVariations(WooCommerceClient $client, WooCommerceConnection $connection, array $product): array
    {
        $first = $product['variants'][0] ?? [];
        $ids = $first['_woo_variation_ids'] ?? [];
        if (!$ids) {
            return $product;
        }
        $extras = $client->variations($connection, $product['id'] ?? 0);
        if (!$extras) {
            return $product;
        }
        $product['variants'] = array_merge([
            [
                'id' => $first['id'] ?? ('p' . ($product['id'] ?? '')),
                'title' => $first['title'] ?? 'Default',
                'sku' => $first['sku'] ?? null,
                'barcode' => null,
                'price' => $first['price'] ?? 0,
                'compare_at_price' => $first['compare_at_price'] ?? null,
                'inventory_quantity' => (int) ($first['inventory_quantity'] ?? 0),
            ],
        ], $extras);
        return $product;
    }

    protected function wooStatusQuery(array $opt): ?string
    {
        $want = $this->productStatuses($opt);
        $map = ['active' => 'publish', 'draft' => 'draft', 'archived' => 'private'];
        $woo = [];
        foreach ($want as $status) {
            if (isset($map[$status])) {
                $woo[] = $map[$status];
            }
        }
        return count($woo) === 1 ? $woo[0] : null;
    }

    protected function isAuthError(\Throwable $e): bool
    {
        $msg = strtolower($e->getMessage());
        return str_contains($msg, 'no longer valid') || str_contains($msg, 'reconnect');
    }
}
