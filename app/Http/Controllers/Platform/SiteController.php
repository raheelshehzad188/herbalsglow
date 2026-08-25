<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Admins\Admin;
use App\Models\SaasApp;
use App\Models\SaasFaq;
use App\Models\SaasFeature;
use App\Models\SaasPlan;
use App\Models\SaasSetting;
use App\Models\SaasTheme;
use App\Models\Store;
use App\Models\StoreDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    protected function payload(array $extra = []): array
    {
        $settings = SaasSetting::allCached();
        return array_merge([
            'settings' => $settings,
            'brand' => $settings['site_name'] ?? 'Herbals Glow',
            'plans' => SaasPlan::where('status', 1)->orderBy('sort')->orderBy('id')->get(),
            'themes' => SaasTheme::where('status', 1)->orderBy('sort')->orderBy('id')->get(),
            'apps' => SaasApp::where('status', 1)->orderBy('sort')->orderBy('id')->get(),
            'faqs' => SaasFaq::where('status', 1)->orderBy('sort')->orderBy('id')->get(),
            'localFeatures' => SaasFeature::where('status', 1)->where('section', 'local')->orderBy('sort')->get(),
            'dashFeatures' => SaasFeature::where('status', 1)->where('section', 'dashboard')->orderBy('sort')->get(),
            'toolFeatures' => SaasFeature::where('status', 1)->where('section', 'tools')->orderBy('sort')->get(),
        ], $extra);
    }

    public function home()
    {
        return view('platform.home', $this->payload(['page' => 'home']));
    }

    public function products()
    {
        return view('platform.products', $this->payload(['page' => 'products']));
    }

    public function themes(Request $request)
    {
        $query = SaasTheme::where('status', 1);
        $category = trim((string) $request->get('cat', 'All'));
        $search = trim((string) $request->get('q', ''));
        if ($category && strtolower($category) !== 'all') {
            $query->where('category', $category);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
        }
        $themes = $query->orderBy('sort')->orderBy('id')->get();
        $categories = SaasTheme::where('status', 1)->orderBy('category')->pluck('category')->unique()->values();

        return view('platform.themes', $this->payload([
            'page' => 'themes',
            'themes' => $themes,
            'themeCategories' => $categories,
            'activeCat' => $category ?: 'All',
            'themeSearch' => $search,
        ]));
    }

    public function apps(Request $request)
    {
        $query = SaasApp::where('status', 1);
        $category = trim((string) $request->get('cat', 'All'));
        $search = trim((string) $request->get('q', ''));
        if ($category && strtolower($category) !== 'all') {
            $query->where('category', $category);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }
        $apps = $query->orderBy('sort')->orderBy('id')->get();
        $categories = SaasApp::where('status', 1)->orderBy('category')->pluck('category')->unique()->values();

        return view('platform.apps', $this->payload([
            'page' => 'apps',
            'apps' => $apps,
            'appCategories' => $categories,
            'activeCat' => $category ?: 'All',
            'appSearch' => $search,
        ]));
    }

    public function pricing()
    {
        return view('platform.pricing', $this->payload(['page' => 'pricing']));
    }

    public function start(Request $request)
    {
        $plan = null;
        if ($request->get('plan')) {
            $plan = SaasPlan::where('slug', $request->get('plan'))->first();
        }
        return view('platform.start', $this->payload(['page' => 'start', 'selectedPlan' => $plan]));
    }

    public function startSubmit(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:190',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $slug = Str::slug($request->store_name) ?: 'store';
        $base = $slug;
        $i = 1;
        while (Store::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $store = Store::create([
            'name' => $request->store_name,
            'slug' => $slug,
            'email' => $request->email,
            'active_theme' => 3,
            'status' => 'active',
            'currency' => 'PKR',
            'timezone' => 'Asia/Karachi',
        ]);

        $host = \App\Support\DomainResolver::isSaasDomain($request->getHost())
            ? \App\Support\DomainResolver::primaryDomain()
            : \App\Support\DomainResolver::fromRequest($request);
        $domainName = $slug . '.' . $host;
        if (!StoreDomain::where('domain', $domainName)->exists()) {
            StoreDomain::create([
                'store_id' => $store->id,
                'domain' => $domainName,
                'is_primary' => 1,
                'is_active' => 1,
            ]);
        }

        $owner = Admin::where('email', $request->email)->first() ?: new Admin();
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->name = $request->store_name . ' Owner';
        $owner->role = 'store_admin';
        $owner->store_id = $store->id;
        $owner->status = 'active';
        $owner->save();

        return redirect('/admin/login')->with([
            'msg' => 'Store created. Sign in to your admin.',
            'msg_type' => 'success',
        ]);
    }
}
