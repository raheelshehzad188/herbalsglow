<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHomepageImageDetailsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->text('homepage_img1d')->nullable()->after('homepage_footer');
            $table->text('homepage_img2d')->nullable()->after('homepage_img1d');
            $table->text('homepage_img3d')->nullable()->after('homepage_img2d');
            $table->text('homepage_img4d')->nullable()->after('homepage_img3d');
            $table->text('homepage_img5d')->nullable()->after('homepage_img4d');
            $table->text('homepage_img6d')->nullable()->after('homepage_img5d');
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
            $table->dropColumn([
                'homepage_img1d',
                'homepage_img2d', 
                'homepage_img3d',
                'homepage_img4d',
                'homepage_img5d',
                'homepage_img6d'
            ]);
        });
    }
}
