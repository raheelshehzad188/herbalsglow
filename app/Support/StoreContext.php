<?php

namespace App\Support;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class StoreContext
{
    public static function id(): ?int
    {
        $admin = Session::get('admin');
        if ($admin && !empty($admin->store_id) && (($admin->role ?? '') !== 'super_admin')) {
            return (int) $admin->store_id;
        }

        // Super admin can optionally pin a store in session (admin panel)
        if ($admin && Session::has('active_store_id')) {
            return (int) Session::get('active_store_id');
        }

        $current = CurrentStore::id();
        if ($current) {
            return $current;
        }

        // Admin panel without domain resolve: use first store as safe default
        if ($admin) {
            try {
                $id = Store::orderBy('id')->value('id');
                return $id ? (int) $id : null;
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    public static function store(): ?Store
    {
        $id = self::id();
        if (!$id) {
            return null;
        }
        try {
            return Store::find($id);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function apply($query, ?string $table = null)
    {
        $storeId = self::id();
        if (!$storeId) {
            return $query;
        }

        try {
            $model = method_exists($query, 'getModel') ? $query->getModel() : null;
            $tableName = $table ?: ($model ? $model->getTable() : null);
            if ($tableName && Schema::hasColumn($tableName, 'store_id')) {
                if ($query instanceof Builder || method_exists($query, 'where')) {
                    return $query->where($tableName . '.store_id', $storeId);
                }
            }
        } catch (\Throwable $e) {
            // ignore when tables missing during install
        }

        return $query;
    }

    public static function stamp(array $data): array
    {
        $storeId = self::id();
        if ($storeId) {
            $data['store_id'] = $storeId;
        }
        return $data;
    }

    public static function assignToModel($model): void
    {
        $storeId = self::id();
        if (!$storeId || !$model) {
            return;
        }
        try {
            $table = $model->getTable();
            if (Schema::hasColumn($table, 'store_id') && empty($model->store_id)) {
                $model->store_id = $storeId;
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public static function owns($model): bool
    {
        if (!$model) {
            return false;
        }
        $storeId = self::id();
        if (!$storeId) {
            return true;
        }
        if (!isset($model->store_id) || $model->store_id === null) {
            return true;
        }
        return (int) $model->store_id === (int) $storeId;
    }
}
