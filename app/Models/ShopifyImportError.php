<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;

class ShopifyImportError extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'job_id',
        'resource_type',
        'shopify_id',
        'item_name',
        'message',
        'retry_status',
        'retried_at',
    ];

    protected $casts = [
        'retried_at' => 'datetime',
    ];
}
