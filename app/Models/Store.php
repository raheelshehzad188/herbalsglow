<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'active_theme',
        'status',
        'currency',
        'timezone',
        'logo',
        'wlogo',
        'meta_enabled',
        'tiktok_enabled',
    ];

    protected $casts = [
        'meta_enabled' => 'boolean',
        'tiktok_enabled' => 'boolean',
        'active_theme' => 'integer',
    ];

    public function domains()
    {
        return $this->hasMany(StoreDomain::class, 'store_id');
    }

    public function integrations()
    {
        return $this->hasMany(StoreIntegration::class, 'store_id');
    }

    public function primaryDomain()
    {
        return $this->hasOne(StoreDomain::class, 'store_id')->where('is_primary', 1);
    }

    public function metaIntegration()
    {
        return $this->hasOne(StoreIntegration::class, 'store_id')->where('provider', 'meta');
    }

    public function tiktokIntegration()
    {
        return $this->hasOne(StoreIntegration::class, 'store_id')->where('provider', 'tiktok');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
