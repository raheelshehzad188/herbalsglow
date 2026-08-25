<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessShopifyImportJob;
use App\Models\ShopifyConnection;
use App\Models\ShopifyImportError;
use App\Models\ShopifyImportJob;
use App\Services\Shopify\ShopifyAdminClient;
use App\Services\Shopify\ShopifyImporter;
use App\Services\Shopify\ShopifyOAuth;
use App\Support\StoreContext;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
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
        $connection = $this->connection();
        $job = null;
        if ($connection) {
            $job = ShopifyImportJob::withoutStore()
                ->where('store_id', $this->storeId())
                ->where('connection_id', $connection->id)
                ->orderByDesc('id')
                ->first();
        }
        $oauthReady = $oauth->isConfigured();
        $types = $this->availableTypes();
        $mapping = ShopifyImporter::defaultMapping();
        return view('admins.import_data', compact('connection', 'job', 'oauthReady', 'types', 'mapping'));
    }

    public function connectOAuth(Request $request, ShopifyOAuth $oauth)
    {
        $request->validate(['shop' => 'required|string|max:120']);
        $admin = Session::get('admin');
        try {
            $url = $oauth->authorizeUrl($request->shop, $this->storeId(), (int) ($admin->id ?? 0));
        } catch (\Throwable $e) {
            return back()->with(['msg' => $e->getMessage(), 'msg_type' => 'error']);
        }
        Session::put('shopify_oauth_store_id', $this->storeId());
        return redirect()->away($url);
    }

    public function callback(Request $request, ShopifyOAuth $oauth)
    {
        $storeId = $this->storeId();
        try {
            if (!$oauth->verifyHmac($request->query())) {
                return redirect('/admin/import-data')->with(['msg' => 'Shopify authorization could not be verified.', 'msg_type' => 'error']);
            }
            $state = $oauth->decodeState((string) $request->query('state'));
            if ((int) $state['store_id'] !== $storeId) {
                return redirect('/admin/import-data')->with(['msg' => 'This Shopify connection belongs to another store.', 'msg_type' => 'error']);
            }
            $shop = ShopifyAdminClient::normalizeShop((string) $request->query('shop'));
            if ($shop !== $state['shop']) {
                return redirect('/admin/import-data')->with(['msg' => 'Shopify shop mismatch.', 'msg_type' => 'error']);
            }
            $token = $oauth->exchangeCode($shop, (string) $request->query('code'));
            $this->persistConnection($storeId, $shop, $token, 'oauth');
        } catch (\Throwable $e) {
            return redirect('/admin/import-data')->with(['msg' => $e->getMessage(), 'msg_type' => 'error']);
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify store connected.', 'msg_type' => 'success']);
    }

    public function connectManual(Request $request)
    {
        $request->validate([
            'shop_url' => 'required|string|max:120',
            'admin_api_token' => 'required|string|max:255',
        ]);
        try {
            $shop = ShopifyAdminClient::normalizeShop($request->shop_url);
            $token = trim($request->admin_api_token);
            $client = new ShopifyAdminClient($shop, $token);
            $client->getShop();
            $this->persistConnection($this->storeId(), $shop, $token, 'manual');
        } catch (\Throwable $e) {
            return back()->with(['msg' => $e->getMessage(), 'msg_type' => 'error'])->withInput($request->only('shop_url'));
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify store connected.', 'msg_type' => 'success']);
    }

    public function disconnect()
    {
        $row = $this->connection();
        if ($row) {
            $row->access_token_encrypted = '';
            $row->status = 'disconnected';
            $row->save();
        }
        return redirect('/admin/import-data')->with(['msg' => 'Shopify disconnected.', 'msg_type' => 'success']);
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

    public function start(ShopifyImporter $importer)
    {
        $connection = $this->requireConnection();
        $job = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id)
            ->orderByDesc('id')
            ->first();
        if (!$job || empty($job->config()['types'])) {
            return back()->with(['msg' => 'Choose what to import first.', 'msg_type' => 'error']);
        }
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
        return redirect('/admin/import-data?step=progress');
    }

    public function progress(ShopifyImporter $importer)
    {
        $connection = $this->connection();
        if (!$connection) {
            return response()->json(['error' => 'Not connected'], 404);
        }
        $job = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id)
            ->orderByDesc('id')
            ->first();
        if (!$job) {
            return response()->json(['error' => 'No import'], 404);
        }
        if (in_array($job->status, ['queued', 'running'], true)) {
            $importer->tick($job, 7);
            $job->refresh();
        }
        $counts = $job->counts();
        $totals = 0;
        $done = 0;
        foreach ($counts as $group) {
            $totals += (int) ($group['total'] ?? 0);
            $done += (int) ($group['done'] ?? 0) + (int) ($group['imported'] ?? 0) + (int) ($group['updated'] ?? 0) + (int) ($group['skipped'] ?? 0);
        }
        $pct = $totals > 0 ? min(99, (int) floor(($done / $totals) * 100)) : ($job->status === 'completed' ? 100 : 5);
        if ($job->status === 'completed' || $job->status === 'cancelled') {
            $pct = 100;
        }
        return response()->json([
            'status' => $job->status,
            'counts' => $counts,
            'percent' => $pct,
            'failed' => $job->errors()->count(),
        ]);
    }

    public function cancel()
    {
        $job = $this->latestJob();
        if ($job && in_array($job->status, ['queued', 'running'], true)) {
            $job->cancel_requested = true;
            $job->save();
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
        return view('admins.import_data_errors', compact('errors', 'job'));
    }

    protected function persistConnection(int $storeId, string $shop, string $token, string $method): void
    {
        $client = new ShopifyAdminClient($shop, $token);
        $shopInfo = $client->getShop();
        $row = ShopifyConnection::withoutStore()->firstOrNew(['store_id' => $storeId]);
        $row->store_id = $storeId;
        $row->shop_domain = $shop;
        $row->shop_name = $shopInfo['name'] ?? $shop;
        $row->shopify_shop_id = isset($shopInfo['id']) ? (string) $shopInfo['id'] : null;
        $row->setAccessToken($token);
        $row->connection_method = $method;
        $row->status = 'connected';
        $row->scopes = config('shopify.scopes');
        $row->save();
    }

    protected function requireConnection(): ShopifyConnection
    {
        $row = $this->connection();
        if (!$row || !$row->isConnected()) {
            throw new HttpResponseException(
                redirect('/admin/import-data')->with(['msg' => 'Connect Shopify first.', 'msg_type' => 'error'])
            );
        }
        return $row;
    }

    protected function draftJob(ShopifyConnection $connection): ShopifyImportJob
    {
        $job = ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->where('connection_id', $connection->id)
            ->whereIn('status', ['draft', 'previewing'])
            ->orderByDesc('id')
            ->first();
        if (!$job) {
            $job = new ShopifyImportJob();
            $job->store_id = $this->storeId();
            $job->connection_id = $connection->id;
            $job->status = 'draft';
            $job->duplicate_mode = 'update';
        }
        return $job;
    }

    protected function latestJob(): ?ShopifyImportJob
    {
        return ShopifyImportJob::withoutStore()
            ->where('store_id', $this->storeId())
            ->orderByDesc('id')
            ->first();
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
