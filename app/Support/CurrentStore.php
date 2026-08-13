<?php

namespace App\Support;

use App\Models\Store;

class CurrentStore
{
    public static function get(): ?Store
    {
        if (app()->bound('currentStore')) {
            return app('currentStore');
        }
        return null;
    }

    public static function id(): ?int
    {
        $store = self::get();
        return $store ? (int) $store->id : null;
    }

    public static function theme(): int
    {
        $store = self::get();
        $theme = $store ? (int) $store->active_theme : 3;
        return in_array($theme, [1, 2, 3], true) ? $theme : 3;
    }
}
