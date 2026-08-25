<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SaasSetting extends Model
{
    protected $table = 'saas_settings';
    public $timestamps = false;
    protected $fillable = ['skey', 'svalue', 'updated_at'];

    public static function get($key, $default = '')
    {
        $all = static::allCached();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function put($key, $value)
    {
        static::updateOrCreate(['skey' => $key], ['svalue' => $value, 'updated_at' => now()]);
        Cache::forget('saas_settings');
    }

    public static function putMany(array $pairs)
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['skey' => $key], ['svalue' => (string) $value, 'updated_at' => now()]);
        }
        Cache::forget('saas_settings');
    }

    public static function allCached(): array
    {
        return Cache::remember('saas_settings', 60, function () {
            try {
                return static::query()->pluck('svalue', 'skey')->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }
}
