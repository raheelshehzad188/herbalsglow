<?php

namespace App\Support;

use App\Models\Admins\Setting;
use App\Models\Store;
use App\Models\ThemeCustomization;
use Illuminate\Support\Facades\Schema;

class ThemeSettings
{
    public static function assignedThemeId(?Store $store = null): int
    {
        $store = $store ?: CurrentStore::get() ?: StoreContext::store();
        $theme = $store ? (int) $store->active_theme : 0;
        if ($theme && in_array($theme, storefront_theme_ids(), true)) {
            return $theme;
        }
        $setting = self::settingRow($store);
        $fromSetting = (int) ($setting->active_theme ?? 0);
        return in_array($fromSetting, storefront_theme_ids(), true) ? $fromSetting : 3;
    }

    public static function schema(?Store $store = null): array
    {
        return ThemeRegistry::schema(self::assignedThemeId($store));
    }

    public static function values(?Store $store = null): array
    {
        $store = $store ?: CurrentStore::get() ?: StoreContext::store();
        $themeId = self::assignedThemeId($store);
        $fields = ThemeRegistry::fields($themeId);
        $defaults = [];
        foreach ($fields as $key => $field) {
            $defaults[$key] = $field['default'] ?? null;
        }

        $setting = self::settingRow($store);
        foreach ($fields as $key => $field) {
            $column = $field['maps_to'] ?? null;
            if ($column && $setting && isset($setting->{$column}) && $setting->{$column} !== null && $setting->{$column} !== '') {
                $defaults[$key] = $setting->{$column};
            }
        }

        $stored = [];
        if ($store && Schema::hasTable('theme_customizations')) {
            $row = ThemeCustomization::where('store_id', $store->id)->where('theme_id', $themeId)->first();
            if ($row && is_array($row->values)) {
                $stored = $row->values;
            }
        }

        $merged = array_merge($defaults, array_intersect_key($stored, $fields));
        foreach ($fields as $key => $field) {
            if (($field['type'] ?? '') === 'boolean') {
                $merged[$key] = filter_var($merged[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
            }
        }
        return $merged;
    }

    public static function get(string $key, $default = null, ?Store $store = null)
    {
        $values = self::values($store);
        return array_key_exists($key, $values) ? $values[$key] : $default;
    }

    public static function save(array $input, ?Store $store = null): void
    {
        $store = $store ?: StoreContext::store();
        if (!$store) {
            return;
        }

        $themeId = self::assignedThemeId($store);
        $fields = ThemeRegistry::fields($themeId);
        $custom = [];
        $setting = self::settingRow($store);

        foreach ($fields as $key => $field) {
            $type = $field['type'] ?? 'text';
            if ($type === 'boolean') {
                $raw = $input[$key] ?? '0';
                $posted = !in_array((string) $raw, ['0', 'false', ''], true);
            } else {
                if (!array_key_exists($key, $input)) {
                    continue;
                }
                $posted = $input[$key];
            }
            $column = $field['maps_to'] ?? null;
            if ($column === 'home_layout') {
                $posted = (int) $posted;
            }
            if ($column && $setting && Schema::hasColumn('setting', $column)) {
                $setting->{$column} = $posted;
            } else {
                $custom[$key] = $posted;
            }
        }

        if ($setting) {
            $setting->save();
        }

        if (!Schema::hasTable('theme_customizations')) {
            return;
        }

        $row = ThemeCustomization::firstOrNew([
            'store_id' => $store->id,
            'theme_id' => $themeId,
        ]);
        $row->values = array_merge(is_array($row->values) ? $row->values : [], $custom);
        $row->save();
    }

    private static function settingRow(?Store $store): ?Setting
    {
        if ($store && Schema::hasColumn('setting', 'store_id')) {
            return Setting::where('store_id', $store->id)->orderBy('id')->first();
        }
        return Setting::orderBy('id')->first();
    }
}
