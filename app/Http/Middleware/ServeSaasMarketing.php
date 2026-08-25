<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Platform\SiteController;
use App\Support\DomainResolver;
use Closure;
use Illuminate\Http\Request;

class ServeSaasMarketing
{
    protected $passPrefixes = [
        'admin',
        'superadmin',
        'platform',
        'images',
        'theme1',
        'theme2',
        'theme3',
        'theme4',
        'css',
        'js',
        'backend_assets',
        'front',
        'fonts',
        'storage',
        'vendor',
        'clear-cache',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!DomainResolver::isSaasDomain($request->getHost())) {
            return $next($request);
        }

        $first = strtolower(explode('/', trim($request->path(), '/'))[0] ?? '');
        if ($first !== '' && in_array($first, $this->passPrefixes, true)) {
            return $next($request);
        }

        $path = trim($request->path(), '/');
        $site = app(SiteController::class);

        if ($request->isMethod('get')) {
            if ($path === '') {
                return $this->toResponse($site->home());
            }
            if ($path === 'pricing') {
                return $this->toResponse($site->pricing());
            }
            if ($path === 'themes') {
                return $this->toResponse($site->themes($request));
            }
            if ($path === 'apps') {
                return $this->toResponse($site->apps($request));
            }
            if ($path === 'products') {
                return $this->toResponse($site->products());
            }
            if ($path === 'start' || $path === 'signup') {
                return $this->toResponse($site->start($request));
            }
            if ($path === 'login') {
                return redirect('/superadmin/login');
            }
        }

        if ($request->isMethod('post') && $path === 'start') {
            return $this->toResponse($site->startSubmit($request));
        }

        abort(404);
    }

    protected function toResponse($result)
    {
        if ($result instanceof \Illuminate\Contracts\View\View) {
            return response($result);
        }
        return $result;
    }
}
