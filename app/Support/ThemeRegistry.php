<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeRegistry
{
    public static function themePath(int $id, string $file = 'settings.json'): ?string
    {
        $candidates = [
            resource_path('themes/theme' . $id . '/' . $file),
            resource_path('views/theme' . $id . '/' . $file),
        ];
        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }
        return null;
    }

    public static function schema(int $id): array
    {
        $loader = function () use ($id) {
            $path = self::themePath($id, 'settings.json');
            if (!$path) {
                return self::emptySchema($id);
            }
            $raw = File::get($path);
            $json = json_decode($raw, true);
            if (!is_array($json)) {
                return self::emptySchema($id, 'Invalid settings.json');
            }
            $json['id'] = (int) ($json['id'] ?? $id);
            $json['theme_name'] = $json['theme_name'] ?? ('Theme ' . $id);
            $json['groups'] = is_array($json['groups'] ?? null) ? $json['groups'] : [];
            $json['_path'] = $path;
            return $json;
        };

        if (config('app.debug')) {
            return $loader();
        }

        return Cache::remember('theme_schema_' . $id, 300, $loader);
    }

    public static function all(): array
    {
        $themes = [];
        foreach ([1, 2, 3, 4, 5, 6] as $id) {
            $path = self::themePath($id, 'settings.json');
            $php = resource_path('themes/theme' . $id . '/settings.php');
            if (!$path && !is_file($php)) {
                continue;
            }
            $schema = self::schema($id);
            $phpMeta = is_file($php) ? (include $php) : [];
            if (!is_array($phpMeta)) {
                $phpMeta = [];
            }
            $themes[$id] = array_merge($phpMeta, [
                'id' => $id,
                'name' => $schema['theme_name'] ?? ($phpMeta['name'] ?? ('Theme ' . $id)),
                'homes' => self::homesFromSchema($schema, $phpMeta['homes'] ?? [1 => 'Default home']),
                'schema' => $schema,
            ]);
        }
        ksort($themes);
        return $themes ?: self::fallback();
    }

    public static function get(int $id): array
    {
        $all = self::all();
        return $all[$id] ?? [
            'id' => $id,
            'name' => 'Theme ' . $id,
            'homes' => [1 => 'Default home'],
            'schema' => self::schema($id),
        ];
    }

    public static function homeIds(int $themeId): array
    {
        return array_map('intval', array_keys(self::get($themeId)['homes'] ?? [1 => 'Default']));
    }

    public static function fields(int $id): array
    {
        $fields = [];
        foreach (self::schema($id)['groups'] ?? [] as $group) {
            foreach ($group['fields'] ?? [] as $field) {
                if (!empty($field['key'])) {
                    $fields[$field['key']] = $field;
                }
            }
        }
        return $fields;
    }

    private static function homesFromSchema(array $schema, array $fallback): array
    {
        foreach ($schema['groups'] ?? [] as $group) {
            foreach ($group['fields'] ?? [] as $field) {
                if (($field['key'] ?? '') === 'layout.home_layout' && !empty($field['options'])) {
                    return $field['options'];
                }
            }
        }
        return $fallback;
    }

    private static function emptySchema(int $id, string $error = ''): array
    {
        return [
            'id' => $id,
            'theme_name' => 'Theme ' . $id,
            'version' => '1.0.0',
            'groups' => [],
            'error' => $error ?: 'settings.json not found',
        ];
    }

    private static function fallback(): array
    {
        return [
            1 => ['id' => 1, 'name' => 'Theme 1 — Classic', 'homes' => [1 => 'Default']],
            2 => ['id' => 2, 'name' => 'Theme 2 — Ayanstore', 'homes' => [1 => 'Default']],
            3 => ['id' => 3, 'name' => 'Theme 3 — iHerb', 'homes' => [1 => 'Default']],
            4 => ['id' => 4, 'name' => 'Theme 4 — ShopUS', 'homes' => [1 => 'Home 1', 2 => 'Home 2', 3 => 'Home 3']],
        ];
    }
}
