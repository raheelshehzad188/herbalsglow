<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admins\Admin;
use App\Models\Store;
use App\Models\StoreDomain;
use App\Models\StoreIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function login()
    {
        if (Session::has('admin') && (($admin = Session::get('admin')) && (($admin->role ?? '') === 'super_admin'))) {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Admin::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with(['msg' => 'Invalid credentials', 'msg_type' => 'error']);
        }

        if (($user->role ?? 'store_admin') !== 'super_admin') {
            // Bootstrap first admin as super if role column empty/missing legacy
            if (empty($user->role) || $user->role === 'store_admin') {
                $superCount = Admin::where('role', 'super_admin')->count();
                if ($superCount === 0) {
                    $user->role = 'super_admin';
                    $user->name = $user->name ?: 'Super Admin';
                    $user->save();
                } else {
                    return back()->with(['msg' => 'Super admin access only', 'msg_type' => 'error']);
                }
            }
        }

        Session::put('admin', $user);
        return redirect()->route('superadmin.dashboard');
    }

    public function logout()
    {
        Session::forget('admin');
        return redirect()->route('superadmin.login');
    }

    public function dashboard()
    {
        $stats = [
            'stores' => Store::count(),
            'domains' => StoreDomain::count(),
            'active_stores' => Store::where('status', 'active')->count(),
            'meta_enabled' => Store::where('meta_enabled', 1)->count(),
            'tiktok_enabled' => Store::where('tiktok_enabled', 1)->count(),
        ];
        $stores = Store::with(['domains', 'integrations'])->orderByDesc('id')->limit(8)->get();
        return view('superadmin.dashboard', compact('stats', 'stores'));
    }

    public function stores()
    {
        $stores = Store::with(['domains', 'integrations'])->orderByDesc('id')->get();
        return view('superadmin.stores.index', compact('stores'));
    }

    public function storeCreate()
    {
        return view('superadmin.stores.form', ['store' => null]);
    }

    public function storeEdit($id)
    {
        $store = Store::with(['domains', 'integrations'])->findOrFail($id);
        return view('superadmin.stores.form', compact('store'));
    }

    public function storeSave(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:190',
            'domain' => 'required|string|max:190',
            'active_theme' => 'required|in:1,2,3',
            'status' => 'required|in:active,paused,draft',
            'owner_email' => 'nullable|email',
            'owner_password' => 'nullable|min:6',
        ]);

        $domainHost = strtolower(trim($request->domain));
        $domainHost = preg_replace('#^https?://#', '', $domainHost);
        $domainHost = rtrim($domainHost, '/');
        $domainHost = preg_replace('/:\d+$/', '', $domainHost);
        if (strpos($domainHost, 'www.') === 0) {
            $domainHost = substr($domainHost, 4);
        }

        $slug = Str::slug($request->slug ?: $request->name);
        if ($id) {
            $store = Store::findOrFail($id);
            if (Store::where('slug', $slug)->where('id', '!=', $store->id)->exists()) {
                $slug .= '-' . $store->id;
            }
        } else {
            $base = $slug ?: 'store';
            $slug = $base;
            $i = 1;
            while (Store::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $store = new Store();
        }

        $store->name = $request->name;
        $store->slug = $slug;
        $store->email = $request->email;
        $store->active_theme = (int) $request->active_theme;
        $store->status = $request->status;
        $store->currency = $request->currency ?: 'PKR';
        $store->timezone = $request->timezone ?: 'Asia/Karachi';
        $store->meta_enabled = $request->boolean('meta_enabled');
        $store->tiktok_enabled = $request->boolean('tiktok_enabled');
        $store->save();

        $domain = StoreDomain::where('store_id', $store->id)->where('is_primary', 1)->first();
        if (!$domain) {
            $domain = new StoreDomain(['store_id' => $store->id, 'is_primary' => 1]);
        }
        // Ensure unique domain
        $clash = StoreDomain::where('domain', $domainHost)->where('store_id', '!=', $store->id)->first();
        if ($clash) {
            return back()->withInput()->with(['msg' => 'Domain already assigned to another store.', 'msg_type' => 'error']);
        }
        $domain->domain = $domainHost;
        $domain->is_active = 1;
        $domain->store_id = $store->id;
        $domain->save();

        foreach (['meta', 'tiktok'] as $provider) {
            $row = StoreIntegration::firstOrNew(['store_id' => $store->id, 'provider' => $provider]);
            $enabled = $provider === 'meta' ? $store->meta_enabled : $store->tiktok_enabled;
            $row->is_enabled = $enabled;
            $row->catalog_enabled = $request->boolean($provider . '_catalog');
            $row->events_enabled = $request->boolean($provider . '_events');
            if ($request->filled($provider . '_access_token')) {
                $row->access_token = $request->input($provider . '_access_token');
            }
            if ($request->filled($provider . '_catalog_id')) {
                $row->catalog_id = $request->input($provider . '_catalog_id');
            }
            if ($request->filled($provider . '_pixel_id')) {
                $row->pixel_id = $request->input($provider . '_pixel_id');
            }
            if ($request->filled($provider . '_ad_account_id')) {
                $row->ad_account_id = $request->input($provider . '_ad_account_id');
            }
            if ($enabled && !$row->connected_at) {
                $row->connected_at = now();
            }
            $row->save();
        }

        // Optional store owner account
        if ($request->filled('owner_email')) {
            $owner = Admin::where('email', $request->owner_email)->first();
            if (!$owner) {
                $owner = new Admin();
                $owner->email = $request->owner_email;
                $owner->password = Hash::make($request->owner_password ?: Str::random(10));
            } elseif ($request->filled('owner_password')) {
                $owner->password = Hash::make($request->owner_password);
            }
            $owner->name = $request->owner_name ?: $store->name . ' Owner';
            $owner->role = 'store_admin';
            $owner->store_id = $store->id;
            $owner->status = 'active';
            $owner->save();
        }

        return redirect()->route('superadmin.stores')->with([
            'msg' => 'Store saved successfully.',
            'msg_type' => 'success',
        ]);
    }

    public function storeDelete($id)
    {
        $store = Store::findOrFail($id);
        StoreDomain::where('store_id', $store->id)->delete();
        StoreIntegration::where('store_id', $store->id)->delete();
        Admin::where('store_id', $store->id)->where('role', 'store_admin')->update(['status' => 'paused']);
        $store->delete();
        return redirect()->route('superadmin.stores')->with([
            'msg' => 'Store deleted.',
            'msg_type' => 'success',
        ]);
    }
}
