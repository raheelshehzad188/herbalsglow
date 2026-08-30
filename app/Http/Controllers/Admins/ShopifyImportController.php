<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessShopifyImportJob;
use App\Models\ShopifyConnection;
use App\Models\ShopifyImportError;
use App\Models\ShopifyImportJob;
use App\Models\WooCommerceConnection;
use App\Services\Shopify\ShopifyAdminClient;
use App\Services\Shopify\ShopifyAuthService;
use App\Services\Shopify\ShopifyImporter;
use App\Services\Shopify\ShopifyOAuth;
use App\Support\StoreContext;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class ShopifyImportController extends Controller
{
    protected function storeId(): int
    {
        $id = StoreContext::id();
        if (!$id) {
            abort(403, 'No store is linked to this account.');
        }
        return (int) $id;
    }

    protected function connection(): ?ShopifyConnection
    {
        return ShopifyConnection::withoutStore()
            ->where('store_id', $this->storeId())
            ->first();
    }

    protected function assertOwns(?int $storeId): void
    {
        if ((int) $storeId !== $this->storeId()) {
            abort(403);
        }
    }

    public function index(ShopifyOAuth $oauth)
    {
        $source = request('source') === 'woocommerce' ? 'woocommerce' : 'shopify';
        if ($source === 'woocommerce') {
            return $this->wooIndex();
        }
        $connection = $this->connection();
        $job = null;
        if ($connection) {
            $query = ShopifyImportJob::withoutStore()
                ->where('store_id', $this->storeId())
                ->where('connection_id', $connection->id);
            $this->applySource($query, 'shopify');
            $job = $query->orderByDesc('id')->first();
        }
        $oauthReady = $oauth->isConfigured();
        $types = $this->availableTypes();
        $mapping = ShopifyImporter::defaultMapping();
        $importsQuery = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->whereIn('status', ['completed', 'failed', 'cancelled']);
        $this->applySource($importsQuery, 'shopify');
        $imports = $importsQuery->orderByDesc('id')->limit(8)->get();
        return view('admins.import_data', compact('connection', 'job', 'oauthReady', 'types', 'mapping', 'imports', 'source'));
    }

    protected function wooIndex()
    {
        $source = 'woocommerce';
        $connection = Schema::hasTable('woocommerce_connections')
            ? WooCommerceConnection::withoutStore()->where('store_id', $this->storeId())->first()
            : null;
        $job = null;
        if ($connection) {
            $query = ShopifyImportJob::withoutStore()
                ->where('store_id', $this->storeId())
                ->where('connection_id', $connection->id);
            $this->applySource($query, 'woocommerce');
            $job = $query->orderByDesc('id')->first();
        }
        $oauthReady = false;
        $types = $this->availableTypes();
        $types['collections'] = 'Product categories';
        $types['brands'] = 'Brands';
        $mapping = ShopifyImporter::defaultMapping();
        $importsQuery = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->whereIn('status', ['completed', 'failed', 'cancelled']);
        $this->applySource($importsQuery, 'woocommerce');
        $imports = $importsQuery->orderByDesc('id')->limit(8)->get();
        return view('admins.import_data', compact('connection', 'job', 'oauthReady', 'types', 'mapping', 'imports', 'source'));
    }

    public function connect(Request $request, ShopifyAuthService $auth)
    {
        $request->validate([
            'shop_url' => 'required|string|max:120',
            'client_id' => 'required|string|max:191',
            'client_secret' => 'required|string|max:255',
        ]);
        try {
            $auth->connect($this->storeId(), $request->shop_url, $request->client_id, $request->client_secret);
        } catch (\Throwable $e) {
            return back()->with(['msg' => $this->publicError($e), 'msg_type' => 'error'])->withInput($request->only('shop_url', 'client_id'));
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify connected successfully.', 'msg_type' => 'success']);
    }

    public function testConnection(ShopifyAuthService $auth)
    {
        $row = $this->requireConnection();
        try {
            $shop = $auth->test($row);
            $row->shop_name = $shop['name'] ?? $row->shop_name;
            $row->last_connected_at = now();
            $row->status = 'connected';
            $row->save();
        } catch (\Throwable $e) {
            return back()->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        return back()->with(['msg' => 'Shopify connection is working.', 'msg_type' => 'success']);
    }

    public function fetchProducts(ShopifyImporter $importer)
    {
        return $this->beginDefaultImport($importer);
    }

    public function connectOAuth(Request $request, ShopifyOAuth $oauth)
    {
        $request->validate(['shop' => 'required|string|max:120']);
        $admin = Session::get('admin');
        try {
            $url = $oauth->authorizeUrl($request->shop, $this->storeId(), (int) ($admin->id ?? 0));
        } catch (\Throwable $e) {
            return redirect('/admin/import-data')->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        return redirect()->away($url);
    }

    public function callback(Request $request, ShopifyOAuth $oauth)
    {
        $storeId = $this->storeId();
        try {
            $payload = $oauth->assertValidCallback($request->query(), $storeId);
            $exchanged = $oauth->exchangeCode($payload['shop'], $payload['code']);
            $this->persistConnection(
                $storeId,
                $payload['shop'],
                $exchanged['access_token'],
                'oauth',
                $exchanged['scope'] ?? null,
                $exchanged['refresh_token'] ?? null
            );
        } catch (\Throwable $e) {
            return redirect('/admin/import-data')->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify connected successfully.', 'msg_type' => 'success']);
    }

    public function disconnect()
    {
        $row = $this->connection();
        if ($row) {
            $row->access_token_encrypted = '';
            if (Schema::hasColumn('shopify_connections', 'refresh_token_encrypted')) {
                $row->refresh_token_encrypted = null;
            }
            if (Schema::hasColumn('shopify_connections', 'client_secret_encrypted')) {
                $row->client_secret_encrypted = null;
            }
            $row->status = 'disconnected';
            if (Schema::hasColumn('shopify_connections', 'token_expires_at')) {
                $row->token_expires_at = null;
            }
            $row->save();
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify disconnected. Imported products were not deleted.', 'msg_type' => 'success']);
    }

    public function saveConfig(Request $request, ShopifyImporter $importer)
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
        return redirect('/admin/import-data?step=preview');
    }

    public function start(Request $request, ShopifyImporter $importer)
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

    public function tickBatch(Request $request, ShopifyImporter $importer)
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

    protected function beginDefaultImport(ShopifyImporter $importer)
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
            return redirect('/admin/import-data')->with(['msg' => $this->publicError($e), 'msg_type' => 'error']);
        }
        $job->save();
        return $this->queueImport($importer, $job);
    }

    protected function queueImport(ShopifyImporter $importer, ShopifyImportJob $job)
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
        return redirect('/admin/import-data?step=progress')->with(['msg' => 'Fetching products from Shopify…', 'msg_type' => 'success']);
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

    public function progress(ShopifyImporter $importer)
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
        return redirect('/admin/import-data')->with(['msg' => 'Import will stop after the current batch.', 'msg_type' => 'info']);
    }

    public function retryFailed(ShopifyImporter $importer)
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
        return redirect('/admin/import-data?step=progress')->with(['msg' => 'Retrying failed items.', 'msg_type' => 'success']);
    }

    public function failedItems()
    {
        $job = $this->latestJob();
        $errors = $job
            ? ShopifyImportError::withoutStore()->where('store_id', $this->storeId())->where('job_id', $job->id)->orderByDesc('id')->limit(200)->get()
            : collect();
        $source = 'shopify';
        return view('admins.import_data_errors', compact('errors', 'job', 'source'));
    }

    protected function persistConnection(int $storeId, string $shop, string $token, string $method, ?string $scopes = null, ?string $refreshToken = null): void
    {
        $client = new ShopifyAdminClient($shop, $token);
        $shopInfo = $client->getShop();
        $row = ShopifyConnection::withoutStore()->firstOrNew(['store_id' => $storeId]);
        $row->store_id = $storeId;
        $row->shop_domain = $shop;
        $row->shop_name = $shopInfo['name'] ?? $shop;
        $row->shopify_shop_id = isset($shopInfo['id']) ? (string) $shopInfo['id'] : null;
        $row->setAccessToken($token);
        if (Schema::hasColumn('shopify_connections', 'refresh_token_encrypted')) {
            $row->setRefreshToken($refreshToken);
        }
        $row->connection_method = $method;
        $row->status = 'connected';
        $row->scopes = $scopes ?: config('shopify.scopes');
        if (Schema::hasColumn('shopify_connections', 'last_connected_at')) {
            $row->last_connected_at = now();
        }
        if (Schema::hasColumn('shopify_connections', 'installed_at') && !$row->installed_at) {
            $row->installed_at = now();
        }
        $row->save();
    }

    protected function publicError(\Throwable $e): string
    {
        $known = [
            'Shopify authorization was cancelled.',
            'Shopify authorization could not be verified.',
            'Shopify authorization expired. Please try again.',
            'This Shopify connection belongs to another store.',
            'Shopify shop mismatch.',
            'Shopify connection failed. Please try again.',
            'Shopify connection is not available yet. Please try again later.',
            'Shopify connection is no longer valid. Reconnect your store.',
            'Enter a valid Shopify store URL, like your-store.myshopify.com',
            'Could not reach Shopify. Please try again.',
            'Unable to authenticate with Shopify. Please verify your store domain, Client ID, Client Secret, and app installation.',
            'This Shopify store is not in the same Shopify organization as this app. Client Credentials authentication cannot be used for this store.',
            'This store is not in the same Shopify organization as the app. The current Client Credentials flow cannot connect to this store.',
            'Shopify is rate limiting requests. Please try again in a moment.',
        ];
        $message = $e->getMessage();
        foreach ($known as $ok) {
            if (strcasecmp($message, $ok) === 0) {
                return $ok;
            }
        }
        if (str_starts_with($message, 'Shopify OAuth error')) {
            return $this->sanitizePublic($message);
        }
        if (str_contains(strtolower($message), 'no longer valid') || str_contains(strtolower($message), 'expired')) {
            return 'Your Shopify connection has expired. Please reconnect.';
        }
        return 'Shopify connection failed. Please try again.';
    }

    protected function sanitizePublic(string $message): string
    {
        $message = preg_replace('/shpat_[A-Za-z0-9]+|shpss_[A-Za-z0-9]+|shpca_[A-Za-z0-9]+|shpua_[A-Za-z0-9]+/', '[redacted]', $message);
        return preg_replace('/[A-Za-z0-9]{40,}/', '[redacted]', $message);
    }

    protected function requireConnection(): ShopifyConnection
    {
        $row = $this->connection();
        if (!$row || !$row->isConnected()) {
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                throw new HttpResponseException(
                    response()->json(['ok' => false, 'error' => 'Connect Shopify first.'], 422)
                );
            }
            throw new HttpResponseException(
                redirect('/admin/import-data')->with(['msg' => 'Connect Shopify first.', 'msg_type' => 'error'])
            );
        }
        return $row;
    }

    protected function draftJob(ShopifyConnection $connection): ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id)
            ->whereIn('status', ['draft', 'previewing']);
        $this->applySource($query, 'shopify');
        $job = $query->orderByDesc('id')->first();
        if (!$job) {
            $job = new ShopifyImportJob();
            $job->store_id = $this->storeId();
            $job->connection_id = $connection->id;
            $job->status = 'draft';
            $job->duplicate_mode = 'update';
            $this->assignSource($job, 'shopify');
        }
        return $job;
    }

    protected function latestJob(): ?ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()->where('store_id', $this->storeId());
        $this->applySource($query, 'shopify');
        return $query->orderByDesc('id')->first();
    }

    protected function latestJobFor(ShopifyConnection $connection): ?ShopifyImportJob
    {
        $query = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id);
        $this->applySource($query, 'shopify');
        return $query->orderByDesc('id')->first();
    }

    protected function applySource($query, string $source)
    {
        if (Schema::hasColumn('shopify_import_jobs', 'source')) {
            $query->where('source', $source);
        }
        return $query;
    }

    protected function assignSource(ShopifyImportJob $job, string $source): void
    {
        if (Schema::hasColumn('shopify_import_jobs', 'source')) {
            $job->source = $source;
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
            'collections' => 'Product categories / collections',
            'brands' => 'Brands / vendors',
            'images' => 'Product images',
            'variants' => 'Product variants',
            'options' => 'Product options',
            'inventory' => 'Inventory',
            'tags' => 'Product tags',
            'orders' => 'Orders',
        ];
    }
}
