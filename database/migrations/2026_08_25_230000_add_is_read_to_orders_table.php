<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadToOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'is_read')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->tinyInteger('is_read')->default(0)->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'is_read')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
    }
}
