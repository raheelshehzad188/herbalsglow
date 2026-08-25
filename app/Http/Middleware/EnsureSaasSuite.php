<?php

namespace App\Http\Middleware;

use App\Support\DomainResolver;
use Closure;
use Illuminate\Http\Request;

class EnsureSaasSuite
{
    public function handle(Request $request, Closure $next)
    {
        if (!DomainResolver::allowsSaasSuite($request->getHost())) {
            abort(404);
        }

        return $next($request);
    }
}
