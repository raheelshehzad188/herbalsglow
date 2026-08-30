<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopifyClientCredentialsColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('shopify_connections')) {
            return;
        }
        $this->add('client_id', function (Blueprint $table) {
            $table->string('client_id')->nullable();
        });
        $this->add('client_secret_encrypted', function (Blueprint $table) {
            $table->text('client_secret_encrypted')->nullable();
        });
        $this->add('token_expires_at', function (Blueprint $table) {
            $table->timestamp('token_expires_at')->nullable();
        });
    }

    public function down()
    {
        // Keep columns.
    }

    protected function add(string $column, \Closure $definition): void
    {
        if (Schema::hasColumn('shopify_connections', $column)) {
            return;
        }
        Schema::table('shopify_connections', $definition);
    }
}
