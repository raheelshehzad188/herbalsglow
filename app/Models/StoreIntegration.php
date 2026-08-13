<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIntegration extends Model
{
    protected $table = 'store_integrations';

    protected $fillable = [
        'store_id',
        'provider',
        'is_enabled',
        'catalog_enabled',
        'events_enabled',
        'access_token',
        'catalog_id',
        'pixel_id',
        'ad_account_id',
        'extra_json',
        'connected_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'catalog_enabled' => 'boolean',
        'events_enabled' => 'boolean',
        'connected_at' => 'datetime',
    ];

    protected $hidden = [
        'access_token',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
