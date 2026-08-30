<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopifyOauthAndHandleColumns extends Migration
{
    public function up()
    {
        $this->addColumnIfMissing('shopify_connections', 'refresh_token_encrypted', function (Blueprint $table) {
            $table->text('refresh_token_encrypted')->nullable();
        });
        $this->addColumnIfMissing('shopify_connections', 'installed_at', function (Blueprint $table) {
            $table->timestamp('installed_at')->nullable();
        });
        $this->addColumnIfMissing('shopify_connections', 'last_connected_at', function (Blueprint $table) {
            $table->timestamp('last_connected_at')->nullable();
        });
        $this->addColumnIfMissing('products', 'shopify_product_id', function (Blueprint $table) {
            $table->string('shopify_product_id')->nullable();
        });
        $this->addColumnIfMissing('products', 'shopify_handle', function (Blueprint $table) {
            $table->string('shopify_handle')->nullable();
        });
        $this->addColumnIfMissing('categories', 'shopify_collection_id', function (Blueprint $table) {
            $table->string('shopify_collection_id')->nullable();
        });
        $this->addColumnIfMissing('categories', 'shopify_handle', function (Blueprint $table) {
            $table->string('shopify_handle')->nullable();
        });
    }

    public function down()
    {
        // Keep mapping columns.
    }

    protected function addColumnIfMissing(string $table, string $column, \Closure $definition): void
    {
        if (!Schema::hasTable($table) || Schema::hasColumn($table, $column)) {
            return;
        }
        Schema::table($table, $definition);
    }
}
