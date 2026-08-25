<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyImportTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('shopify_connections')) {
            Schema::create('shopify_connections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->string('shop_domain');
                $table->string('shop_name')->nullable();
                $table->string('shopify_shop_id')->nullable();
                $table->text('access_token_encrypted');
                $table->string('connection_method', 20)->default('oauth');
                $table->string('status', 20)->default('connected');
                $table->string('scopes')->nullable();
                $table->timestamp('last_synced_at')->nullable();
                $table->timestamps();
                $table->unique('store_id');
                $table->index('shop_domain');
            });
        }

        if (!Schema::hasTable('shopify_import_jobs')) {
            Schema::create('shopify_import_jobs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->unsignedBigInteger('connection_id');
                $table->string('status', 30)->default('draft');
                $table->string('duplicate_mode', 20)->default('update');
                $table->longText('config_json')->nullable();
                $table->longText('counts_json')->nullable();
                $table->longText('preview_json')->nullable();
                $table->longText('cursor_json')->nullable();
                $table->boolean('cancel_requested')->default(false);
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->timestamps();
                $table->index(['store_id', 'status']);
            });
        }

        if (!Schema::hasTable('shopify_resource_maps')) {
            Schema::create('shopify_resource_maps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->unsignedBigInteger('connection_id');
                $table->string('resource_type', 40);
                $table->string('shopify_id', 64);
                $table->unsignedBigInteger('local_id');
                $table->timestamps();
                $table->unique(['store_id', 'resource_type', 'shopify_id'], 'shopify_map_unique');
            });
        }

        if (!Schema::hasTable('shopify_import_errors')) {
            Schema::create('shopify_import_errors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->unsignedBigInteger('job_id');
                $table->string('resource_type', 40);
                $table->string('shopify_id', 64)->nullable();
                $table->string('item_name')->nullable();
                $table->text('message');
                $table->string('retry_status', 20)->default('pending');
                $table->timestamp('retried_at')->nullable();
                $table->timestamps();
                $table->index(['store_id', 'job_id']);
            });
        }

        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('shopify_import_errors');
        Schema::dropIfExists('shopify_resource_maps');
        Schema::dropIfExists('shopify_import_jobs');
        Schema::dropIfExists('shopify_connections');
    }
}
