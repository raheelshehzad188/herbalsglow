<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveThemeToSettingsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('setting') && !Schema::hasColumn('setting', 'active_theme')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->unsignedTinyInteger('active_theme')->default(2)->after('theme_style');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('setting') && Schema::hasColumn('setting', 'active_theme')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->dropColumn('active_theme');
            });
        }
    }
}
