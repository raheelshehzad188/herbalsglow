<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateMultiStorePlatformTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('email')->nullable();
                $table->unsignedTinyInteger('active_theme')->default(3);
                $table->string('status')->default('active'); // active|paused|draft
                $table->string('currency', 10)->default('PKR');
                $table->string('timezone')->default('Asia/Karachi');
                $table->text('logo')->nullable();
                $table->text('wlogo')->nullable();
                $table->boolean('meta_enabled')->default(false);
                $table->boolean('tiktok_enabled')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('store_domains')) {
            Schema::create('store_domains', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->string('domain')->unique();
                $table->boolean('is_primary')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('store_id');
            });
        }

        if (!Schema::hasTable('store_integrations')) {
            Schema::create('store_integrations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->string('provider'); // meta|tiktok
                $table->boolean('is_enabled')->default(false);
                $table->boolean('catalog_enabled')->default(false);
                $table->boolean('events_enabled')->default(false);
                $table->text('access_token')->nullable();
                $table->text('catalog_id')->nullable();
                $table->text('pixel_id')->nullable();
                $table->text('ad_account_id')->nullable();
                $table->text('extra_json')->nullable();
                $table->timestamp('connected_at')->nullable();
                $table->timestamps();
                $table->unique(['store_id', 'provider']);
            });
        }

        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                if (!Schema::hasColumn('admins', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('admins', 'role')) {
                    $table->string('role')->default('store_admin')->after('password'); // super_admin|store_admin
                }
                if (!Schema::hasColumn('admins', 'store_id')) {
                    $table->unsignedBigInteger('store_id')->nullable()->after('role');
                }
                if (!Schema::hasColumn('admins', 'status')) {
                    $table->string('status')->default('active')->after('store_id');
                }
            });
        }

        if (Schema::hasTable('setting') && !Schema::hasColumn('setting', 'store_id')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->unsignedBigInteger('store_id')->nullable()->after('id');
            });
        }

        // Seed default store for existing single-tenant install
        $exists = DB::table('stores')->count();
        if ($exists === 0) {
            $setting = Schema::hasTable('setting') ? DB::table('setting')->where('id', 1)->first() : null;
            $storeId = DB::table('stores')->insertGetId([
                'name' => $setting->site_title ?? 'Default Store',
                'slug' => 'default',
                'email' => $setting->email ?? null,
                'active_theme' => (int) ($setting->active_theme ?? 3),
                'status' => 'active',
                'currency' => 'PKR',
                'timezone' => 'Asia/Karachi',
                'logo' => $setting->logo ?? null,
                'wlogo' => $setting->wlogo ?? null,
                'meta_enabled' => false,
                'tiktok_enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $host = parse_url(config('app.url'), PHP_URL_HOST) ?: '127.0.0.1';
            DB::table('store_domains')->insert([
                'store_id' => $storeId,
                'domain' => $host,
                'is_primary' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Also map coupletoy if used
            if ($host !== 'coupletoy.pk') {
                try {
                    DB::table('store_domains')->insert([
                        'store_id' => $storeId,
                        'domain' => 'coupletoy.pk',
                        'is_primary' => 0,
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                    // ignore duplicate
                }
            }

            if (Schema::hasColumn('setting', 'store_id')) {
                DB::table('setting')->where('id', 1)->update(['store_id' => $storeId]);
            }

            if (Schema::hasTable('admins')) {
                DB::table('admins')->update([
                    'role' => 'super_admin',
                    'store_id' => null,
                    'name' => 'Super Admin',
                ]);
            }

            foreach (['meta', 'tiktok'] as $provider) {
                DB::table('store_integrations')->insert([
                    'store_id' => $storeId,
                    'provider' => $provider,
                    'is_enabled' => 0,
                    'catalog_enabled' => 0,
                    'events_enabled' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('store_integrations');
        Schema::dropIfExists('store_domains');
        Schema::dropIfExists('stores');
    }
}
