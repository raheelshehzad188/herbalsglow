<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\StoreDomain;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ResolveStore
{
    public function handle(Request $request, Closure $next)
    {
        $host = strtolower($request->getHost());
        $host = preg_replace('/:\d+$/', '', $host);
        if (strpos($host, 'www.') === 0) {
            $host = substr($host, 4);
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

            // Local / fallback: first active store
            if (!$store) {
                $store = Store::where('status', 'active')->orderBy('id')->first();
            }
        } catch (\Throwable $e) {
            $store = null;
        }

        app()->instance('currentStore', $store);
        $request->attributes->set('store', $store);
        View::share('currentStore', $store);

        return $next($request);
    }
}
