<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessShopifyImportJob;
use App\Models\ShopifyImportError;
use App\Models\ShopifyImportJob;
use App\Models\WooCommerceConnection;
use App\Services\Shopify\ShopifyImporter;
use App\Services\WooCommerce\WooCommerceClient;
use App\Services\WooCommerce\WooCommerceImporter;
use App\Support\StoreContext;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class WooCommerceImportController extends Controller
{
    protected function storeId(): int
    {
        $id = StoreContext::id();
        if (!$id) {
            abort(403, 'No store is linked to this account.');
        }
        return (int) $id;
    }

    protected function connection(): ?WooCommerceConnection
    {
        return WooCommerceConnection::withoutStore()
            ->where('store_id', $this->storeId())
            ->first();
    }

    protected function assertOwns(?int $storeId): void
    {
        if ((int) $storeId !== $this->storeId()) {
            abort(403);
        }
    }

    public function connect(Request $request, WooCommerceClient $client)
    {
        $request->validate([
            'shop_url' => 'required|string|max:255',
            'consumer_key' => 'required|string|max:191',
            'consumer_secret' => 'required|string|max:255',
        ]);
        try {
            $url = WooCommerceClient::normalizeShop($request->shop_url);
            $row = WooCommerceConnection::withoutStore()->firstOrNew(['store_id' => $this->storeId()]);
            $row->store_id = $this->storeId();
            $row->shop_url = $url;
            $row->shop_host = WooCommerceClient::hostFromUrl($url);
            $row->consumer_key = trim($request->consumer_key);
            $row->setConsumerSecret(trim($request->consumer_secret));
            $row->status = 'connected';
            $row->last_connected_at = now();
            $row->save();
            $shop = $client->shop($row);
            $row->shop_name = $shop['name'] ?? $row->shop_host;
            $row->save();
        } catch (\Throwable $e) {
            return back()->with(['msg' => $this->publicError($e), 'msg_type' => 'error'])->withInput($request->only('shop_url', 'consumer_key'));
        }
        return redirect('/admin/import-data?source=woocommerce')->with(['msg' => 'WooCommerce connected successfully.', 'msg_type' => 'success']);
    }

    public function testConnection(WooCommerceClient $client)
    {
        $row = $this->requireConnection();
        try {
            $shop = $client->shop($row);
            $row->shop_name = $shop['name'] ?? $row->shop_name;
            $row->last_connected_at = now();
            $row->status = 'connected';
            $row->save();
        } catch (\Throwable $e) {
            return back()->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        return back()->with(['msg' => 'WooCommerce connection is working.', 'msg_type' => 'success']);
    }

    public function fetchProducts(WooCommerceImporter $importer)
    {
        return $this->beginDefaultImport($importer);
    }

    public function disconnect()
    {
        $row = $this->connection();
        if ($row) {
            $row->consumer_secret_encrypted = '';
            $row->consumer_key = '';
            $row->status = 'disconnected';
            $row->save();
        }
        return redirect('/admin/import-data?source=woocommerce')->with(['msg' => 'WooCommerce disconnected. Imported products were not deleted.', 'msg_type' => 'success']);
    }

    public function saveConfig(Request $request, WooCommerceImporter $importer)
    {
        $connection = $this->requireConnection();
        $types = array_values(array_intersect($request->input('types', []), array_keys($this->availableTypes())));
        $options = [
            'active' => $request->boolean('opt_active'),
            'draft' => $request->boolean('opt_draft'),
            'archived' => $request->boolean('opt_archived'),
            'import_descriptions' => $request->boolean('opt_descriptions'),
            'import_images' => $request->boolean('opt_images'),
            'import_variants' => $request->boolean('opt_variants'),
            'import_sku' => $request->boolean('opt_sku'),
            'import_barcode' => $request->boolean('opt_barcode'),
            'import_pricing' => $request->boolean('opt_pricing'),
            'import_compare' => $request->boolean('opt_compare'),
            'import_inventory' => $request->boolean('opt_inventory'),
            'map_collections' => $request->boolean('opt_map_collections'),
            'create_categories' => $request->boolean('opt_create_categories'),
            'create_brands' => $request->boolean('opt_create_brands'),
        ];
        $mapping = array_merge(ShopifyImporter::defaultMapping(), array_intersect_key($request->input('mapping', []), ShopifyImporter::defaultMapping()));
        $job = $this->draftJob($connection);
        $job->duplicate_mode = in_array($request->input('duplicate_mode'), ['skip', 'update', 'duplicate'], true)
            ? $request->input('duplicate_mode') : 'update';
        $job->setConfig(['types' => $types, 'options' => $options, 'mapping' => $mapping]);
        $job->status = 'previewing';
        try {
            $preview = $importer->buildPreview($connection, $job->config());
            $job->preview_json = json_encode($preview);
            $counts = $job->counts();
            foreach ($preview['totals'] as $k => $v) {
                $counts[$k]['total'] = $v;
            }
            $job->setCounts($counts);
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'no longer valid')) {
                $connection->status = 'invalid';
                $connection->save();
            }
            return back()->with(['msg' => $e->getMessage(), 'msg_type' => 'error']);
        }
        $job->save();
        return redirect('/admin/import-data?source=woocommerce&step=preview');
    }

    public function start(Request $request, WooCommerceImporter $importer)
    {
        $connection = $this->requireConnection();
        $job = $this->latestJobFor($connection);
        if (!$job || empty($job->config()['types'])) {
            if ($this->wantsAjax($request)) {
                return response()->json(['ok' => false, 'error' => 'Preview the catalog first, then start import.'], 422);
            }
            return $this->beginDefaultImport($importer);
        }
        if ($this->wantsAjax($request)) {
            return $this->beginAjaxImport($job);
        }
        return $this->queueImport($importer, $job);
    }

    public function tickBatch(Request $request, WooCommerceImporter $importer)
    {
        $job = $this->latestJob();
        if (!$job) {
            return response()->json(['ok' => false, 'error' => 'No import job found.'], 404);
        }
        $this->assertOwns((int) $job->store_id);
        if (in_array($job->status, ['queued', 'running', 'previewing', 'draft'], true)) {
            if (in_array($job->status, ['previewing', 'draft'], true)) {
                $job->status = 'queued';
                $job->cancel_requested = false;
                $job->started_at = $job->started_at ?: now();
                $job->finished_at = null;
                $job->setCursor(['stage' => 'start']);
                $job->save();
            }
            $importer->tick($job, 12);
            $job->refresh();
        }
        return response()->json($this->importPayload($job, $importer->lastBatch));
    }

    public function progress(WooCommerceImporter $importer)
    {
        $connection = $this->connection();
        if (!$connection) {
            return response()->json(['error' => 'Not connected'], 404);
        }
        $job = $this->latestJobFor($connection);
        if (!$job) {
            return response()->json(['error' => 'No import'], 404);
        }
        if (in_array($job->status, ['queued', 'running'], true)) {
            $importer->tick($job, 7);
            $job->refresh();
        }
        return response()->json($this->importPayload($job));
    }

    public function cancel(Request $request)
    {
        $job = $this->latestJob();
        if ($job && in_array($job->status, ['queued', 'running'], true)) {
            $job->cancel_requested = true;
            $job->save();
        }
        if ($this->wantsAjax($request)) {
            return response()->json($job ? $this->importPayload($job) : ['ok' => true, 'status' => 'cancelled']);
        }
        return redirect('/admin/import-data?source=woocommerce')->with(['msg' => 'Import will stop after the current batch.', 'msg_type' => 'info']);
    }

    public function retryFailed(WooCommerceImporter $importer)
    {
        $job = $this->latestJob();
        if (!$job) {
            return back();
        }
        ShopifyImportError::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('job_id', $job->id)
            ->where('retry_status', 'pending')
            ->update(['retry_status' => 'retrying', 'retried_at' => now()]);
        $job->status = 'queued';
        $job->cancel_requested = false;
        $job->setCursor(['stage' => 'start']);
        $job->save();
        ProcessShopifyImportJob::dispatch($job->id, $this->storeId());
        return redirect('/admin/import-data?source=woocommerce&step=progress')->with(['msg' => 'Retrying failed items.', 'msg_type' => 'success']);
    }

    public function failedItems()
    {
        $job = $this->latestJob();
        $errors = $job
            ? ShopifyImportError::withoutStore()->where('store_id', $this->storeId())->where('job_id', $job->id)->orderByDesc('id')->limit(200)->get()
            : collect();
        $source = 'woocommerce';
        return view('admins.import_data_errors', compact('errors', 'job', 'source'));
    }

    protected function beginDefaultImport(WooCommerceImporter $importer)
    {
        $connection = $this->requireConnection();
        $job = $this->draftJob($connection);
        $job->duplicate_mode = 'update';
        $config = [
            'types' => ['products', 'collections', 'brands', 'images', 'variants', 'options', 'inventory', 'tags'],
            'options' => [
                'active' => true,
                'draft' => false,
                'archived' => false,
                'import_descriptions' => true,
                'import_images' => true,
                'import_variants' => true,
                'import_sku' => true,
                'import_barcode' => true,
                'import_pricing' => true,
                'import_compare' => true,
                'import_inventory' => true,
                'map_collections' => true,
                'create_categories' => true,
                'create_brands' => true,
            ],
            'mapping' => ShopifyImporter::defaultMapping(),
        ];
        $job->setConfig($config);
        try {
            $preview = $importer->buildPreview($connection, $config);
            $job->preview_json = json_encode($preview);
            $counts = $job->counts();
            foreach (($preview['totals'] ?? []) as $k => $v) {
                $counts[$k]['total'] = $v;
            }
            $job->setCounts($counts);
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'no longer valid')) {
                $connection->status = 'invalid';
                $connection->save();
            }
            return redirect('/admin/import-data?source=woocommerce')->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        $job->save();
        return $this->queueImport($importer, $job);
    }

    protected function queueImport(WooCommerceImporter $importer, ShopifyImportJob $job)
    {
        $job->status = 'queued';
        $job->cancel_requested = false;
        $job->started_at = now();
        $job->finished_at = null;
        $job->setCursor(['stage' => 'start']);
        $job->save();
        ProcessShopifyImportJob::dispatch($job->id, $this->storeId());
        if (config('queue.default') === 'sync') {
            $importer->tick($job, 6);
        }
        return redirect('/admin/import-data?source=woocommerce&step=progress')->with(['msg' => 'Fetching products from WooCommerce…', 'msg_type' => 'success']);
    }

    protected function beginAjaxImport(ShopifyImportJob $job)
    {
        if (!in_array($job->status, ['queued', 'running'], true)) {
            $job->status = 'queued';
            $job->cancel_requested = false;
            $job->started_at = now();
            $job->finished_at = null;
            $job->setCursor(['stage' => 'start']);
            $job->save();
        }
        return response()->json($this->importPayload($job));
    }

    protected function importPayload(ShopifyImportJob $job, array $batch = []): array
    {
        $counts = $job->counts();
        $totals = 0;
        $done = 0;
        foreach ($counts as $group) {
            $totals += (int) ($group['total'] ?? 0);
            $done += (int) ($group['done'] ?? 0) + (int) ($group['imported'] ?? 0) + (int) ($group['updated'] ?? 0) + (int) ($group['skipped'] ?? 0);
        }
        $pct = $totals > 0 ? min(99, (int) floor(($done / $totals) * 100)) : ($job->status === 'completed' ? 100 : 5);
        if (in_array($job->status, ['completed', 'cancelled'], true)) {
            $pct = 100;
        }
        return [
            'ok' => true,
            'status' => $job->status,
            'counts' => $counts,
            'percent' => $pct,
            'failed' => $job->errors()->count(),
            'batch' => $batch,
            'stage' => $job->cursor()['stage'] ?? null,
        ];
    }

    protected function publicError(\Throwable $e): string
    {
        $known = [
            'Enter a valid WooCommerce store URL, like https://yourstore.com',
            'Could not reach WooCommerce. Please try again.',
            'WooCommerce connection is no longer valid. Reconnect your store.',
            'Unable to authenticate with WooCommerce. Please verify your store URL, Consumer Key, and Consumer Secret.',
        ];
        $message = $e->getMessage();
        foreach ($known as $ok) {
            if (strcasecmp($message, $ok) === 0) {
                return $ok;
            }
        }
        if (str_contains(strtolower($message), 'no longer valid') || str_contains(strtolower($message), '401') || str_contains(strtolower($message), '403')) {
            return 'WooCommerce connection is no longer valid. Reconnect your store.';
        }
        if (str_contains(strtolower($message), 'could not reach')) {
            return 'Could not reach WooCommerce. Please try again.';
        }
        return 'WooCommerce connection failed. Please try again.';
    }

    protected function requireConnection(): WooCommerceConnection
    {
        $row = $this->connection();
        if (!$row || !$row->isConnected()) {
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                throw new HttpResponseException(
                    response()->json(['ok' => false, 'error' => 'Connect WooCommerce first.'], 422)
                );
            }
            throw new HttpResponseException(
                redirect('/admin/import-data?source=woocommerce')->with(['msg' => 'Connect WooCommerce first.', 'msg_type' => 'error'])
            );
        }
        return $row;
    }

    protected function draftJob(WooCommerceConnection $connection): ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id)
            ->whereIn('status', ['draft', 'previewing']);
        $this->applySource($query);
        $job = $query->orderByDesc('id')->first();
        if (!$job) {
            $job = new ShopifyImportJob();
            $job->store_id = $this->storeId();
            $job->connection_id = $connection->id;
            $job->status = 'draft';
            $job->duplicate_mode = 'update';
            $this->assignSource($job);
        }
        return $job;
    }

    protected function latestJob(): ?ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()->where('store_id', $this->storeId());
        $this->applySource($query);
        return $query->orderByDesc('id')->first();
    }

    protected function latestJobFor(WooCommerceConnection $connection): ?ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id);
        $this->applySource($query);
        return $query->orderByDesc('id')->first();
    }

    protected function applySource($query)
    {
        if (Schema::hasColumn('shopify_import_jobs', 'source')) {
            $query->where('source', 'woocommerce');
        }
        return $query;
    }

    protected function assignSource(ShopifyImportJob $job): void
    {
        if (Schema::hasColumn('shopify_import_jobs', 'source')) {
            $job->source = 'woocommerce';
        }
    }

    protected function wantsAjax(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson() || $request->expectsJson();
    }

    protected function availableTypes(): array
    {
        return [
            'products' => 'Products',
            'collections' => 'Product categories',
            'brands' => 'Brands',
            'images' => 'Product images',
            'variants' => 'Product variants',
            'options' => 'Product options',
            'inventory' => 'Inventory',
            'tags' => 'Product tags',
            'orders' => 'Orders',
        ];
    }
}
