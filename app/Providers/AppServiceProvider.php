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

    }
}
