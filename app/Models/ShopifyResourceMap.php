<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;

class ShopifyResourceMap extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'connection_id',
        'resource_type',
        'shopify_id',
        'local_id',
    ];

    public static function findLocal(int $storeId, string $type, $shopifyId): ?int
    {
        $row = static::withoutStore()
            ->where('store_id', $storeId)
            ->where('resource_type', $type)
            ->where('shopify_id', (string) $shopifyId)
            ->first();
        return $row ? (int) $row->local_id : null;
    }

    public static function remember(int $storeId, int $connectionId, string $type, $shopifyId, int $localId): void
    {
        static::withoutStore()->updateOrCreate(
            [
                'store_id' => $storeId,
                'resource_type' => $type,
                'shopify_id' => (string) $shopifyId,
            ],
            [
                'connection_id' => $connectionId,
                'local_id' => $localId,
            ]
        );
    }
}
