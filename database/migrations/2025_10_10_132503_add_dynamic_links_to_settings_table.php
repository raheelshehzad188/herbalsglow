<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDynamicLinksToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('track_order_link')->nullable()->after('whatsapp');
            $table->string('about_us_link')->nullable()->after('track_order_link');
            $table->string('contact_us_link')->nullable()->after('about_us_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'track_order_link', 'about_us_link', 'contact_us_link']);
        });
    }
}
