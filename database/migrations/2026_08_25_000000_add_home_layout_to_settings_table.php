<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHomeLayoutToSettingsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('setting') && !Schema::hasColumn('setting', 'home_layout')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->unsignedTinyInteger('home_layout')->default(1)->after('active_theme');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('setting') && Schema::hasColumn('setting', 'home_layout')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->dropColumn('home_layout');
            });
        }
    }
}
