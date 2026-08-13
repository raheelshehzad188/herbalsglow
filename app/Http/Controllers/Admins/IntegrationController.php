<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreIntegration;
use App\Services\Integrations\MetaCatalogSync;
use App\Services\Integrations\MetaEventsService;
use App\Services\Integrations\TikTokCatalogSync;
use App\Services\Integrations\TikTokEventsService;
use App\Support\StoreContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IntegrationController extends Controller
{
    protected function resolveStoreId()
    {
        $id = StoreContext::id();
        if ($id) {
            return $id;
        }
        $admin = Session::get('admin');
        if ($admin && !empty($admin->store_id)) {
            return (int) $admin->store_id;
        }
        $store = Store::orderBy('id')->first();
        return $store ? (int) $store->id : null;
    }

    public function index()
    {
        $storeId = $this->resolveStoreId();
        $store = $storeId ? Store::with('integrations')->find($storeId) : null;
        $meta = $store ? $store->integrations->firstWhere('provider', 'meta') : null;
        $tiktok = $store ? $store->integrations->firstWhere('provider', 'tiktok') : null;
        return view('admins.integrations', compact('store', 'meta', 'tiktok'));
    }

    public function save(Request $request)
    {
        $storeId = $this->resolveStoreId();
        if (!$storeId) {
            return back()->with(['msg' => 'No store linked to this admin.', 'msg_type' => 'error']);
        }
        $store = Store::findOrFail($storeId);

        if (!$store->meta_enabled && !$store->tiktok_enabled) {
            return back()->with(['msg' => 'Ask Super Admin to enable Meta/TikTok for this store first.', 'msg_type' => 'error']);
        }

        foreach (['meta', 'tiktok'] as $provider) {
            $flag = $provider === 'meta' ? $store->meta_enabled : $store->tiktok_enabled;
            if (!$flag) {
                continue;
            }
            $row = StoreIntegration::firstOrNew(['store_id' => $store->id, 'provider' => $provider]);
            $row->is_enabled = $request->boolean($provider . '_enabled');
            $row->catalog_enabled = $request->boolean($provider . '_catalog');
            $row->events_enabled = $request->boolean($provider . '_events');
            if ($request->filled($provider . '_access_token')) {
                $row->access_token = $request->input($provider . '_access_token');
            }
            $row->catalog_id = $request->input($provider . '_catalog_id');
            $row->pixel_id = $request->input($provider . '_pixel_id');
            $row->ad_account_id = $request->input($provider . '_ad_account_id');
            if ($row->is_enabled && !$row->connected_at) {
                $row->connected_at = now();
            }
            $row->save();
        }

        return back()->with(['msg' => 'Integrations saved.', 'msg_type' => 'success']);
    }

    public function syncCatalog(Request $request, string $provider)
    {
        $storeId = $this->resolveStoreId();
        if (!$storeId) {
            return back()->with(['msg' => 'No store linked.', 'msg_type' => 'error']);
        }
        $store = Store::findOrFail($storeId);

        if ($provider === 'meta') {
            $result = (new MetaCatalogSync())->syncStore($store);
        } elseif ($provider === 'tiktok') {
            $result = (new TikTokCatalogSync())->syncStore($store);
        } else {
            return back()->with(['msg' => 'Unknown provider.', 'msg_type' => 'error']);
        }

        return back()->with([
            'msg' => $result['message'] ?? 'Sync finished.',
            'msg_type' => !empty($result['ok']) ? 'success' : 'error',
        ]);
    }

    public function testEvent(Request $request, string $provider)
    {
        $storeId = $this->resolveStoreId();
        if (!$storeId) {
            return back()->with(['msg' => 'No store linked.', 'msg_type' => 'error']);
        }
        $store = Store::findOrFail($storeId);

        if ($provider === 'meta') {
            $result = (new MetaEventsService())->send($store, 'PageView', [
                'event_source_url' => url('/'),
                'value' => 0,
            ]);
        } elseif ($provider === 'tiktok') {
            $result = (new TikTokEventsService())->send($store, 'Pageview', [
                'url' => url('/'),
                'value' => 0,
            ]);
        } else {
            return back()->with(['msg' => 'Unknown provider.', 'msg_type' => 'error']);
        }

        return back()->with([
            'msg' => $result['message'] ?? 'Event finished.',
            'msg_type' => !empty($result['ok']) ? 'success' : 'error',
        ]);
    }
}
