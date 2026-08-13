<?php

namespace App\Models\Concerns;

use App\Support\StoreContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToStore
{
    public static function bootBelongsToStore()
    {
        static::addGlobalScope('store', function (Builder $builder) {
            try {
                $table = (new static)->getTable();
                if (!Schema::hasColumn($table, 'store_id')) {
                    return;
                }
                $storeId = StoreContext::id();
                if ($storeId) {
                    $builder->where($table . '.store_id', $storeId);
                }
            } catch (\Throwable $e) {
                // tables may be missing during install
            }
        });

        static::creating(function ($model) {
            StoreContext::assignToModel($model);
        });
    }

    public function scopeWithoutStore($query)
    {
        return $query->withoutGlobalScope('store');
    }
}
