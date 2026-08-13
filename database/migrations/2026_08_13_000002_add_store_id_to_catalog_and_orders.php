<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStoreIdToCatalogAndOrdersTables extends Migration
{
    protected array $tables = [
        'products',
        'categories',
        'sub_categories',
        'brands',
        'orders',
        'custom_order',
        'products_to_meta',
        'categories_to_meta',
        'galleries',
        'faq',
        'rating',
        'sliders',
        'pages',
    ];

    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            if (!Schema::hasColumn($table, 'store_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->unsignedBigInteger('store_id')->nullable()->after('id');
                    $blueprint->index('store_id');
                });
            }
        }

        $defaultStoreId = DB::table('stores')->orderBy('id')->value('id');
        if ($defaultStoreId) {
            foreach ($this->tables as $table) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'store_id')) {
                    DB::table($table)->whereNull('store_id')->update(['store_id' => $defaultStoreId]);
                }
            }
            if (Schema::hasTable('setting') && Schema::hasColumn('setting', 'store_id')) {
                DB::table('setting')->whereNull('store_id')->update(['store_id' => $defaultStoreId]);
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'store_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropIndex(['store_id']);
                    $blueprint->dropColumn('store_id');
                });
            }
        }
    }
}
