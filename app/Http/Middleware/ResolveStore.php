<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\StoreDomain;
use App\Support\DomainResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ResolveStore
{
    public function handle(Request $request, Closure $next)
    {
        $host = DomainResolver::fromRequest($request);
        $isSaas = DomainResolver::isSaasDomain($host);

        app()->instance('isSaasDomain', $isSaas);
        $request->attributes->set('isSaasDomain', $isSaas);
        View::share('isSaasDomain', $isSaas);

        if ($isSaas) {
            app()->instance('currentStore', null);
            $request->attributes->set('store', null);
            View::share('currentStore', null);
            return $next($request);
        }

        $store = null;
        try {
            $domain = StoreDomain::where('domain', $host)
                ->where('is_active', 1)
                ->with('store')
                ->first();

            if ($domain && $domain->store && $domain->store->isActive()) {
                $store = $domain->store;
            }

            if (!$store && DomainResolver::allowsDevStoreFallback($host)) {
                $store = Store::where('status', 'active')->orderBy('id')->first();
            }
        } catch (\Throwable $e) {
            $store = null;
        }

        if (!$store && DomainResolver::hasPrimaryDomain() && !DomainResolver::allowsDevStoreFallback($host)) {
            abort(404);
        }

        app()->instance('currentStore', $store);
        $request->attributes->set('store', $store);
        View::share('currentStore', $store);

        return $next($request);
    }
}
