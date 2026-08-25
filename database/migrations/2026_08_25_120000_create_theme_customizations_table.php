<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemeCustomizationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('theme_customizations')) {
            return;
        }
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedTinyInteger('theme_id');
            $table->json('values')->nullable();
            $table->timestamps();
            $table->unique(['store_id', 'theme_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('theme_customizations');
    }
}
