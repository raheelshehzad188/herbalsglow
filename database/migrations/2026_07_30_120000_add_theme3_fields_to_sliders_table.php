<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTheme3FieldsToSlidersTable extends Migration
{
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('sliders', 'image_url')) {
                $table->string('image_url', 500)->nullable()->after('slider_image');
            }
            if (!Schema::hasColumn('sliders', 'ga_id')) {
                $table->string('ga_id', 50)->nullable()->after('button');
            }
            if (!Schema::hasColumn('sliders', 'ga_name')) {
                $table->string('ga_name', 120)->nullable()->after('ga_id');
            }
            if (!Schema::hasColumn('sliders', 'title_size')) {
                $table->string('title_size', 20)->nullable()->default('18px')->after('heading');
            }
            if (!Schema::hasColumn('sliders', 'sort')) {
                $table->unsignedInteger('sort')->default(0)->after('p');
            }
            if (!Schema::hasColumn('sliders', 'status')) {
                $table->boolean('status')->default(true)->after('sort');
            }
        });

        if (DB::table('sliders')->count() < 5) {
            DB::table('sliders')->delete();

            $now = now();
            $rows = array_slice(config('theme3_dummy.slider_banners', []), 0, 5);

            foreach ($rows as $index => $banner) {
                DB::table('sliders')->insert([
                    'slider_image' => '',
                    'image_url' => $banner['image'],
                    'cid' => null,
                    'button' => $banner['link'],
                    'heading' => $banner['title'],
                    'title_size' => $banner['title_size'] ?? '18px',
                    'p' => implode('<br>', $banner['lines'] ?? []),
                    'ga_id' => $banner['ga_id'] ?? null,
                    'ga_name' => $banner['ga_name'] ?? null,
                    'sort' => $index + 1,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $columns = ['image_url', 'ga_id', 'ga_name', 'title_size', 'sort', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('sliders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
