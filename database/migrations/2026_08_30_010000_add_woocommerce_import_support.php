<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWoocommerceImportSupport extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('woocommerce_connections')) {
            Schema::create('woocommerce_connections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->string('shop_url');
                $table->string('shop_host');
                $table->string('shop_name')->nullable();
                $table->string('consumer_key');
                $table->text('consumer_secret_encrypted');
                $table->string('status', 20)->default('connected');
                $table->timestamp('last_connected_at')->nullable();
                $table->timestamp('last_synced_at')->nullable();
                $table->timestamps();
                $table->unique('store_id');
                $table->index('shop_host');
            });
        }

        if (Schema::hasTable('shopify_import_jobs') && !Schema::hasColumn('shopify_import_jobs', 'source')) {
            Schema::table('shopify_import_jobs', function (Blueprint $table) {
                $table->string('source', 20)->default('shopify')->after('connection_id');
                $table->index(['store_id', 'source', 'status'], 'shopify_jobs_store_source_status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('shopify_import_jobs') && Schema::hasColumn('shopify_import_jobs', 'source')) {
            Schema::table('shopify_import_jobs', function (Blueprint $table) {
                $table->dropIndex('shopify_jobs_store_source_status');
                $table->dropColumn('source');
            });
        }
        Schema::dropIfExists('woocommerce_connections');
    }
}
