<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // PHP 8.4+/8.5 deprecations from older Symfony/Laravel 8 vendor code
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        require_once app_path('Helpers/helpers.php');

        if (!app()->runningInConsole()) {
            $host = strtolower(preg_replace('/:\d+$/', '', request()->getHost() ?? ''));
            $root = request()->getSchemeAndHttpHost();
            $isStoreVhost = (bool) preg_match('/^(classic|wellness|shopus)\.herbalsglow\.test$/', $host);
            $isSaasHost = \App\Support\DomainResolver::isSaasDomain($host);
            $script = (string) request()->server->get('SCRIPT_NAME', '');
            $uri = (string) request()->getRequestUri();
            if (
                !$isStoreVhost && !$isSaasHost && (
                    strpos($script, '/herbalsglow/') !== false
                    || strpos($uri, '/herbalsglow/') === 0
                    || $uri === '/herbalsglow'
                )
            ) {
                $root .= '/herbalsglow';
            }
            \Illuminate\Support\Facades\URL::forceRootUrl($root);
        } elseif ($root = config('app.url')) {
            \Illuminate\Support\Facades\URL::forceRootUrl($root);
        }
    }
}
