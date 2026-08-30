<?php

namespace App\Services\Shopify;

use App\Models\Admins\Brand;
use App\Models\Admins\Category;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Order;
use App\Models\Admins\Product;
use App\Models\ShopifyConnection;
use App\Models\ShopifyImportError;
use App\Models\ShopifyImportJob;
use App\Models\ShopifyResourceMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ShopifyImporter
{
    /** @var array<int, array{title:string,sku:string,price:string,status:string}> */
    public array $lastBatch = [];

    protected string $resourcePrefix = '';

    protected function mapType(string $type): string
    {
        return $this->resourcePrefix === '' ? $type : $this->resourcePrefix . $type;
    }

    protected function mappedLocal(int $storeId, string $type, $sid): ?int
    {
        return ShopifyResourceMap::findLocal($storeId, $this->mapType($type), $sid);
    }

    protected function mappedRemember(int $storeId, int $connectionId, string $type, $sid, int $localId): void
    {
        ShopifyResourceMap::remember($storeId, $connectionId, $this->mapType($type), $sid, $localId);
    }

    protected function writesShopifyIds(): bool
    {
        return $this->resourcePrefix === '';
    }

    public static function defaultMapping(): array
    {
        return [
            'title' => 'product_name',
            'body_html' => 'product_details',
            'vendor' => 'brand',
            'collection' => 'category',
            'sku' => 'sku',
            'price' => 'selling_price',
            'compare_at_price' => 'discount_price',
            'images' => 'image_one',
        ];
    }

    public function buildPreview(ShopifyConnection $connection, array $config): array
    {
        $gql = app(ShopifyClient::class);
        $types = $config['types'] ?? [];
        $preview = ['samples' => [], 'totals' => []];

        $productCount = in_array('products', $types, true) ? $gql->productsCount($connection) : 0;
        [$products] = (in_array('products', $types, true) || in_array('brands', $types, true))
            ? $gql->productsPage($connection)
            : [[], null];
        [$collections] = in_array('collections', $types, true) ? $gql->collectionsPage($connection) : [[], null];

        $vendors = [];
        $images = 0;
        $variants = 0;
        foreach ($products as $p) {
            if (!empty($p['vendor'])) {
                $vendors[$p['vendor']] = true;
            }
            $images += count($p['images'] ?? []);
            $variants += count($p['variants'] ?? []);
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

        $connection = ShopifyConnection::withoutStore()
            ->where('id', $job->connection_id)
            ->where('store_id', $job->store_id)
            ->first();
        if (!$connection || !$connection->isConnected()) {
            $job->status = 'failed';
            $job->finished_at = now();
            $job->save();
            $this->fail($job, 'connection', null, 'Shopify', 'Shopify is not connected.');
            return;
        }

        $client = ShopifyAdminClient::forConnection($connection);
        $gql = app(ShopifyClient::class);
        $cursor = $job->cursor();
        $stage = $cursor['stage'] ?? 'start';
        $deadline = microtime(true) + $seconds;

        try {
            if ($stage === 'start') {
                $cursor = ['stage' => 'collections', 'page_info' => null, 'kind' => 'custom'];
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
                    $this->importCollectionsPage($job, $connection, $gql, $cursor);
                } elseif ($stage === 'products') {
                    $this->importProductsPage($job, $connection, $gql, $cursor);
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

    protected function importCollectionsPage(ShopifyImportJob $job, $connection, $gql, array $cursor): void
    {
        $config = $job->config();
        if (!in_array('collections', $config['types'] ?? [], true)) {
            $cursor['stage'] = 'products';
            $cursor['page_info'] = null;
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        [$items, $next] = $gql->collectionsPage($connection, $cursor['page_info'] ?? null);
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
        unset($cursor['kind']);
        $job->setCursor($cursor);
        $job->save();
    }

    protected function importProductsPage(ShopifyImportJob $job, $connection, $gql, array $cursor): void
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
        [$items, $next] = $gql->productsPage($connection, $cursor['page_info'] ?? null, 10);
        foreach ($items as $product) {
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

    protected function importOrdersPage(ShopifyImportJob $job, $connection, $client, array $cursor): void
    {
        $config = $job->config();
        if (!in_array('orders', $config['types'] ?? [], true) || !Schema::hasTable('custom_order')) {
            $cursor['stage'] = 'done';
            $job->setCursor($cursor);
            $job->save();
            return;
        }
        $query = ['limit' => 50, 'status' => 'any'];
        [$items, $next] = $client->page('orders.json', 'orders', $query, $cursor['page_info'] ?? null);
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

    protected function importOneProduct(ShopifyImportJob $job, $connection, array $sp): void
    {
        $config = $job->config();
        $types = $config['types'] ?? [];
        $opt = $config['options'] ?? [];
        $mode = $job->duplicate_mode ?: 'update';
        $storeId = (int) $job->store_id;
        $name = $sp['title'] ?? 'Untitled';
        $sid = (string) ($sp['id'] ?? '');

        try {
            if (in_array('brands', $types, true) && !empty($sp['vendor'])) {
                $this->upsertBrand($job, $connection, $sp['vendor']);
            }
            if (!in_array('products', $types, true)) {
                return;
            }

            $variants = $sp['variants'] ?? [];
            $first = $variants[0] ?? [];

            $existingId = $this->mappedLocal($storeId, 'product', $sid);
            if (!$existingId && $this->writesShopifyIds() && Schema::hasColumn('products', 'shopify_product_id')) {
                $existingId = Product::withoutStore()
                    ->where('store_id', $storeId)
                    ->where('shopify_product_id', $sid)
                    ->value('id');
            }
            if ($existingId && $mode === 'skip') {
                $this->bump($job, 'products', 'skipped');
                $this->noteBatch($name, $first['sku'] ?? '', (string) ($first['price'] ?? ''), 'skipped');
                return;
            }

            $product = null;
            if ($existingId && $mode !== 'duplicate') {
                $product = Product::withoutStore()->where('store_id', $storeId)->where('id', $existingId)->first();
            }
            if (!$product) {
                $product = new Product();
                $product->store_id = $storeId;
                $product->status = 1;
                $product->New_Arrival = 1;
            } elseif ($mode === 'update') {
                // keep
            }

            $price = $first['price'] ?? 0;
            $compare = $first['compare_at_price'] ?? null;
            $selling = $compare ?: $price;
            $discount = $compare ? $price : 0;
            if (empty($opt['import_pricing'])) {
                $selling = $product->selling_price ?: $selling;
                $discount = $product->discount_price ?: $discount;
            }

            $brandId = null;
            if (!empty($sp['vendor'])) {
                $brandId = $this->mappedLocal($storeId, 'vendor', md5(strtolower($sp['vendor'])));
            }
            $catId = $this->firstMappedCollection($storeId, $sp);

            $product->product_name = $name;
            $handle = self::normalizeHandle($sp['handle'] ?? null, $name);
            $product->slug = $this->uniqueStoreSlug('products', $storeId, $handle, $product->id ?? null);
            if ($this->writesShopifyIds() && Schema::hasColumn('products', 'shopify_product_id')) {
                $product->shopify_product_id = $sid;
            }
            if ($this->writesShopifyIds() && Schema::hasColumn('products', 'shopify_handle')) {
                $product->shopify_handle = $handle;
            }
            if (Schema::hasColumn('products', 'ptype') && isset($sp['product_type'])) {
                $product->ptype = $sp['product_type'] ?: null;
            }
            if ($catId) {
                $product->category_id = $catId;
            }
            if ($brandId) {
                $product->brand = $brandId;
            }
            if (!empty($opt['import_descriptions'])) {
                $product->product_details = $sp['body_html'] ?? '';
                $product->short_discriiption = Str::limit(strip_tags($sp['body_html'] ?? $name), 180);
            }
            if (in_array('tags', $types, true)) {
                $product->tags = $sp['tags'] ?? '';
            }
            if (!empty($opt['import_sku'])) {
                $product->sku = $first['sku'] ?? null;
                $product->product_code = $first['barcode'] ?? ($first['sku'] ?? ('SH' . $sid));
            }
            if (!empty($opt['import_pricing'])) {
                $product->selling_price = $selling;
                $product->discount_price = $discount;
            }
            if (in_array('inventory', $types, true) && !empty($opt['import_inventory'])) {
                $qty = 0;
                foreach ($variants as $v) {
                    $qty += (int) ($v['inventory_quantity'] ?? 0);
                }
                $product->product_quantity = $qty;
            }
            if (in_array('options', $types, true) && !empty($sp['options'])) {
                $bits = [];
                foreach ($sp['options'] as $option) {
                    $bits[] = ($option['name'] ?? 'Option') . ': ' . implode(', ', $option['values'] ?? []);
                }
                $product->size = implode(' | ', $bits);
            }
            $product->status = (($sp['status'] ?? '') === 'active') ? 1 : 0;
            $product->save();

            $this->mappedRemember($storeId, (int) $connection->id, 'product', $sid, (int) $product->id);
            if ($existingId && $mode === 'update') {
                $this->bump($job, 'products', 'updated');
                $this->noteBatch($name, $first['sku'] ?? '', (string) ($price ?? ''), 'updated');
            } else {
                $this->bump($job, 'products', 'imported');
                $this->noteBatch($name, $first['sku'] ?? '', (string) ($price ?? ''), 'imported');
            }

            if (in_array('images', $types, true) && !empty($opt['import_images'])) {
                $this->importImages($job, $connection, $product, $sp['images'] ?? []);
            }

            if (in_array('variants', $types, true) && !empty($opt['import_variants'])) {
                foreach (array_slice($variants, 1) as $variant) {
                    $this->importExtraVariant($job, $connection, $product, $sp, $variant, $mode);
                }
                $this->bump($job, 'variants', 'done', count($variants));
                if ($first) {
                    $this->mappedRemember($storeId, (int) $connection->id, 'variant', $first['id'] ?? '', (int) $product->id);
                }
            }
        } catch (\Throwable $e) {
            $this->fail($job, 'product', $sid, $name, $this->safe($e->getMessage()));
            $this->bump($job, 'products', 'failed');
            $this->noteBatch($name, '', '', 'failed');
        }
    }

    protected function importExtraVariant(ShopifyImportJob $job, $connection, Product $parent, array $sp, array $variant, string $mode): void
    {
        $storeId = (int) $job->store_id;
        $vid = (string) ($variant['id'] ?? '');
        $existing = $this->mappedLocal($storeId, 'variant', $vid);
        if ($existing && $mode === 'skip') {
            $this->bump($job, 'products', 'skipped');
            return;
        }
        $title = ($sp['title'] ?? 'Product') . ' - ' . ($variant['title'] ?? 'Variant');
        $p = null;
        if ($existing && $mode !== 'duplicate') {
            $p = Product::withoutStore()->where('store_id', $storeId)->where('id', $existing)->first();
        }
        if (!$p) {
            $p = new Product();
            $p->store_id = $storeId;
        }
        $p->product_name = $title;
        $parentHandle = $parent->shopify_handle ?: $parent->slug ?: self::normalizeHandle($sp['handle'] ?? null, $sp['title'] ?? 'product');
        $variantBit = Str::slug($variant['title'] ?? $vid);
        $p->slug = $this->uniqueStoreSlug('products', $storeId, $parentHandle . '-' . $variantBit, $p->id ?? null);
        if ($this->writesShopifyIds() && Schema::hasColumn('products', 'shopify_product_id')) {
            $p->shopify_product_id = $vid;
        }
        $p->category_id = $parent->category_id;
        $p->brand = $parent->brand;
        $p->sku = $variant['sku'] ?? null;
        $p->product_code = $variant['barcode'] ?? ($variant['sku'] ?? ('SHV' . $vid));
        $p->selling_price = $variant['compare_at_price'] ?: $variant['price'] ?? 0;
        $p->discount_price = !empty($variant['compare_at_price']) ? $variant['price'] : 0;
        $p->product_quantity = (int) ($variant['inventory_quantity'] ?? 0);
        $p->status = $parent->status;
        $p->image_one = $parent->image_one;
        $p->save();
        $this->mappedRemember($storeId, (int) $connection->id, 'variant', $vid, (int) $p->id);
    }

    protected function importImages(ShopifyImportJob $job, $connection, Product $product, array $images): void
    {
        usort($images, function ($a, $b) {
            return ((int) ($a['position'] ?? 0)) <=> ((int) ($b['position'] ?? 0));
        });
        $dir = public_path('images/products/imports/' . $job->store_id);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $gallery = [];
        foreach ($images as $i => $img) {
            $src = $img['src'] ?? '';
            if (!$src) {
                continue;
            }
            $imgId = (string) ($img['id'] ?? '');
            if ($imgId && $this->mappedLocal((int) $job->store_id, 'image', $imgId)) {
                $this->bump($job, 'images', 'skipped');
                continue;
            }
            try {
                $imageSid = (string) ($img['id'] ?? '');
                if ($imageSid && $this->mappedLocal((int) $job->store_id, 'image', $imageSid)) {
                    $this->bump($job, 'images', 'skipped');
                    continue;
                }
                $local = $this->downloadImage($src, $dir, $product->id . '-' . $i);
                if (!$local) {
                    continue;
                }
                $rel = 'public/images/products/imports/' . $job->store_id . '/' . $local;
                if ($i === 0) {
                    $product->image_one = $rel;
                    $product->save();
                } else {
                    $gallery[] = $rel;
                    if (Schema::hasTable('galleries')) {
                        $g = new Gallerie();
                        $g->product_id = $product->id;
                        $g->photo = $rel;
                        if (Schema::hasColumn('galleries', 'store_id')) {
                            $g->store_id = $job->store_id;
                        }
                        $g->save();
                    }
                }
                $this->bump($job, 'images', 'imported');
                $this->mappedRemember((int) $job->store_id, (int) $connection->id, 'image', $img['id'] ?? $i, (int) $product->id);
            } catch (\Throwable $e) {
                $this->fail($job, 'image', $img['id'] ?? null, $product->product_name, $this->safe($e->getMessage()));
                $this->bump($job, 'images', 'failed');
            }
        }
        if ($gallery && Schema::hasColumn('products', 'gallary_images')) {
            $product->gallary_images = implode(',', $gallery);
            $product->save();
        }
    }

    protected function downloadImage(string $url, string $dir, string $basename): ?string
    {
        $ctx = stream_context_create(['http' => ['timeout' => 25, 'follow_location' => 1], 'ssl' => ['verify_peer' => true]]);
        $bin = @file_get_contents($url, false, $ctx);
        if (!$bin) {
            return null;
        }
        $ext = 'jpg';
        $path = parse_url($url, PHP_URL_PATH);
        if (preg_match('/\.(jpe?g|png|webp|gif)/i', (string) $path, $m)) {
            $ext = strtolower($m[1]);
            if ($ext === 'jpeg') {
                $ext = 'jpg';
            }
        }
        $file = $basename . '.' . $ext;
        file_put_contents($dir . '/' . $file, $bin);
        return $file;
    }

    protected function upsertCategory(ShopifyImportJob $job, $connection, array $col): void
    {
        $storeId = (int) $job->store_id;
        $sid = (string) ($col['id'] ?? '');
        $name = $col['title'] ?? 'Collection';
        $mode = $job->duplicate_mode ?: 'update';
        try {
            $existing = $this->mappedLocal($storeId, 'collection', $sid);
            if ($existing && $mode === 'skip') {
                $this->bump($job, 'categories', 'skipped');
                return;
            }
            $cat = null;
            if ($existing && $mode !== 'duplicate') {
                $cat = Category::withoutStore()->where('store_id', $storeId)->where('id', $existing)->first();
            }
            if (!$cat) {
                $cat = new Category();
                $cat->store_id = $storeId;
                $cat->status = 1;
                $this->bump($job, 'categories', 'imported');
            } else {
                $this->bump($job, 'categories', 'updated');
            }
            $cat->name = $name;
            $handle = self::normalizeHandle($col['handle'] ?? null, $name);
            $cat->slug = $this->uniqueStoreSlug('categories', $storeId, $handle, $cat->id ?? null);
            if ($this->writesShopifyIds() && Schema::hasColumn('categories', 'shopify_collection_id')) {
                $cat->shopify_collection_id = $sid;
            }
            if ($this->writesShopifyIds() && Schema::hasColumn('categories', 'shopify_handle')) {
                $cat->shopify_handle = $handle;
            }
            $cat->short_description = Str::limit(strip_tags($col['body_html'] ?? ''), 250);
            if (!empty($col['image']['src'])) {
                $dir = public_path('images/categories/imports/' . $storeId);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $file = $this->downloadImage($col['image']['src'], $dir, $sid);
                if ($file) {
                    $cat->image = 'public/images/categories/imports/' . $storeId . '/' . $file;
                }
            }
            $cat->save();
            $this->mappedRemember($storeId, (int) $connection->id, 'collection', $sid, (int) $cat->id);
        } catch (\Throwable $e) {
            $this->fail($job, 'collection', $sid, $name, $this->safe($e->getMessage()));
            $this->bump($job, 'categories', 'failed');
        }
    }

    protected function upsertBrand(ShopifyImportJob $job, $connection, string $vendor): void
    {
        $storeId = (int) $job->store_id;
        $key = md5(strtolower($vendor));
        $mode = $job->duplicate_mode ?: 'update';
        try {
            $existing = $this->mappedLocal($storeId, 'vendor', $key);
            if ($existing && $mode === 'skip') {
                return;
            }
            $brand = null;
            if ($existing && $mode !== 'duplicate') {
                $brand = Brand::withoutStore()->where('store_id', $storeId)->where('id', $existing)->first();
            }
            if (!$brand) {
                $brand = Brand::withoutStore()
                    ->where('store_id', $storeId)
                    ->whereRaw('LOWER(name) = ?', [strtolower($vendor)])
                    ->first();
            }
            if (!$brand) {
                $brand = new Brand();
                $brand->store_id = $storeId;
                $brand->status = 1;
                $this->bump($job, 'brands', 'imported');
            } else {
                $this->bump($job, 'brands', 'updated');
            }
            $brand->name = $vendor;
            $brand->slug = $this->uniqueStoreSlug('brands', $storeId, $vendor, $brand->id ?? null);
            $brand->save();
            $this->mappedRemember($storeId, (int) $connection->id, 'vendor', $key, (int) $brand->id);
        } catch (\Throwable $e) {
            $this->fail($job, 'vendor', $key, $vendor, $this->safe($e->getMessage()));
            $this->bump($job, 'brands', 'failed');
        }
    }

    protected function upsertOrder(ShopifyImportJob $job, $connection, array $order): void
    {
        $storeId = (int) $job->store_id;
        $sid = (string) ($order['id'] ?? '');
        $mode = $job->duplicate_mode ?: 'update';
        try {
            $existing = $this->mappedLocal($storeId, 'order', $sid);
            if ($existing && $mode === 'skip') {
                $this->bump($job, 'orders', 'skipped');
                return;
            }
            $row = null;
            if ($existing && $mode !== 'duplicate') {
                $row = Order::withoutStore()->where('store_id', $storeId)->where('id', $existing)->first();
            }
            if (!$row) {
                $row = new Order();
                $row->store_id = $storeId;
                $this->bump($job, 'orders', 'imported');
            } else {
                $this->bump($job, 'orders', 'updated');
            }
            $item = $order['line_items'][0] ?? [];
            $addr = $order['shipping_address'] ?? $order['billing_address'] ?? [];
            $row->product_name = $item['title'] ?? ('Order ' . ($order['name'] ?? $sid));
            $row->customer_name = trim(($order['customer']['first_name'] ?? '') . ' ' . ($order['customer']['last_name'] ?? '')) ?: ($addr['name'] ?? 'Customer');
            $row->price = $order['total_price'] ?? 0;
            $row->mobile_number = $addr['phone'] ?? ($order['phone'] ?? '');
            $row->quantity = (int) ($item['quantity'] ?? 1);
            $row->address = trim(($addr['address1'] ?? '') . ' ' . ($addr['city'] ?? ''));
            $row->status = $order['financial_status'] ?? 'pending';
            $row->save();
            $this->mappedRemember($storeId, (int) $connection->id, 'order', $sid, (int) $row->id);
        } catch (\Throwable $e) {
            $this->fail($job, 'order', $sid, $order['name'] ?? 'Order', $this->safe($e->getMessage()));
            $this->bump($job, 'orders', 'failed');
        }
    }

    protected function firstMappedCollection(int $storeId, array $sp): ?int
    {
        foreach ($sp['collection_ids'] ?? [] as $cid) {
            $local = $this->mappedLocal($storeId, 'collection', $cid);
            if ($local) {
                return $local;
            }
        }
        return ShopifyResourceMap::withoutStore()
            ->where('store_id', $storeId)
            ->where('resource_type', $this->mapType('collection'))
            ->value('local_id');
    }

    protected function finish(ShopifyImportJob $job, $connection): void
    {
        $job->status = 'completed';
        $job->finished_at = now();
        $job->setCursor(['stage' => 'done']);
        $job->save();
        $connection->last_synced_at = now();
        $connection->save();
    }

    public static function normalizeHandle(?string $handle, string $fallbackName = 'product'): string
    {
        $raw = strtolower(trim((string) $handle, "/ \t\n\r"));
        if ($raw !== '' && preg_match('/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/', $raw)) {
            return $raw;
        }
        if ($raw !== '') {
            $slug = Str::slug($raw);
            if ($slug !== '') {
                return $slug;
            }
        }
        return Str::slug($fallbackName) ?: 'product';
    }

    protected function uniqueStoreSlug(string $table, int $storeId, string $preferred, $ignoreId = null): string
    {
        $base = self::normalizeHandle($preferred, 'item');
        $slug = $base;
        $n = 2;
        while (
            DB::table($table)
                ->where('store_id', $storeId)
                ->where('slug', $slug)
                ->when($ignoreId, function ($q) use ($ignoreId) {
                    $q->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $base . '-' . $n;
            $n++;
            if ($n > 50) {
                $slug = $base . '-' . substr(md5($preferred . $storeId), 0, 6);
                break;
            }
        }
        return $slug;
    }

    protected function productStatuses(array $opt): array
    {
        $out = [];
        if (!empty($opt['active'])) {
            $out[] = 'active';
        }
        if (!empty($opt['draft'])) {
            $out[] = 'draft';
        }
        if (!empty($opt['archived'])) {
            $out[] = 'archived';
        }
        return $out ?: ['active'];
    }

    protected function noteBatch(string $title, string $sku, string $price, string $status): void
    {
        $this->lastBatch[] = [
            'title' => $title,
            'sku' => $sku,
            'price' => $price,
            'status' => $status,
        ];
    }

    protected function bump(ShopifyImportJob $job, string $group, string $key, int $by = 1): void
    {
        $counts = $job->counts();
        if (!isset($counts[$group][$key])) {
            $counts[$group][$key] = 0;
        }
        $counts[$group][$key] += $by;
        $job->setCounts($counts);
        $job->save();
    }

    protected function fail(ShopifyImportJob $job, string $type, $shopifyId, string $name, string $message): void
    {
        ShopifyImportError::create([
            'store_id' => $job->store_id,
            'job_id' => $job->id,
            'resource_type' => $type,
            'shopify_id' => $shopifyId ? (string) $shopifyId : null,
            'item_name' => Str::limit($name, 180),
            'message' => Str::limit($this->safe($message), 1000),
            'retry_status' => 'pending',
        ]);
    }

    protected function safe(string $message): string
    {
        $message = preg_replace('/shpat_[a-zA-Z0-9]+|shpss_[a-zA-Z0-9]+|ck_[A-Za-z0-9]+|cs_[A-Za-z0-9]+/', '[redacted]', $message);
        return preg_replace('/[A-Za-z0-9]{40,}/', '[redacted]', $message);
    }

    protected function isAuthError(\Throwable $e): bool
    {
        return str_contains(strtolower($e->getMessage()), 'no longer valid');
    }
}
