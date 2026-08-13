<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreDomain extends Model
{
    protected $table = 'store_domains';

    protected $fillable = [
        'store_id',
        'domain',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
