<?php

namespace Database\Seeders;

use App\Models\Admins\Admin;
use App\Models\Store;
use App\Models\StoreDomain;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LocalVhostStoresSeeder extends Seeder
{
    public function run()
    {
        $defs = [
            [
                'slug' => 'classic-store',
                'name' => 'Classic Store',
                'theme' => 2,
                'home_layout' => 1,
                'domain' => 'classic.herbalsglow.test',
                'email' => 'classic@herbalsglow.test',
                'password' => 'Classic123!',
            ],
            [
                'slug' => 'wellness-store',
                'name' => 'Wellness Store',
                'theme' => 3,
                'home_layout' => 1,
                'domain' => 'wellness.herbalsglow.test',
                'email' => 'wellness@herbalsglow.test',
                'password' => 'Wellness123!',
            ],
            [
                'slug' => 'shopus-store',
                'name' => 'ShopUS Store',
                'theme' => 4,
                'home_layout' => 1,
                'domain' => 'shopus.herbalsglow.test',
                'email' => 'shopus@herbalsglow.test',
                'password' => 'Shopus123!',
            ],
        ];

        $sourceStoreId = (int) (Store::orderBy('id')->value('id') ?: 1);

        foreach ($defs as $def) {
            $store = Store::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'email' => $def['email'],
                    'active_theme' => $def['theme'],
                    'status' => 'active',
                    'currency' => 'PKR',
                    'timezone' => 'Asia/Karachi',
                ]
            );

            StoreDomain::updateOrCreate(
                ['domain' => $def['domain']],
                [
                    'store_id' => $store->id,
                    'is_primary' => 1,
                    'is_active' => 1,
                ]
            );

            $admin = Admin::where('email', $def['email'])->first() ?: new Admin();
            $admin->name = $def['name'] . ' Admin';
            $admin->email = $def['email'];
            $admin->password = Hash::make($def['password']);
            $admin->role = 'store_admin';
            $admin->store_id = $store->id;
            $admin->status = 'active';
            $admin->save();

            $this->cloneCatalog($sourceStoreId, (int) $store->id, $def);
        }

        $this->call(ShopusHome1Seeder::class);
    }

    private function cloneCatalog(int $fromStoreId, int $toStoreId, array $def): void
    {
        if ($fromStoreId === $toStoreId) {
            return;
        }

        $this->cloneTable('brands', $fromStoreId, $toStoreId);
        $catMap = $this->cloneTable('categories', $fromStoreId, $toStoreId);
        $this->cloneTable('sub_categories', $fromStoreId, $toStoreId, ['category_id' => $catMap]);
        $prodMap = $this->cloneTable('products', $fromStoreId, $toStoreId, ['category_id' => $catMap]);
        $this->cloneGalleries($prodMap);
        $this->cloneTable('sliders', $fromStoreId, $toStoreId);
        $this->cloneTable('pages', $fromStoreId, $toStoreId);
        $this->cloneSetting($fromStoreId, $toStoreId, $def);
    }

    private function cloneTable(string $table, int $fromStoreId, int $toStoreId, array $fkMaps = []): array
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'store_id')) {
            return [];
        }

        if (DB::table($table)->where('store_id', $toStoreId)->exists()) {
            $map = [];
            $from = DB::table($table)->where('store_id', $fromStoreId)->orderBy('id')->pluck('id')->values();
            $to = DB::table($table)->where('store_id', $toStoreId)->orderBy('id')->pluck('id')->values();
            foreach ($from as $i => $oldId) {
                if (isset($to[$i])) {
                    $map[$oldId] = $to[$i];
                }
            }
            return $map;
        }

        $map = [];
        $autoInc = $this->tableHasAutoIncrement($table);
        $nextId = $autoInc ? 0 : ((int) DB::table($table)->max('id') + 1);
        $rows = DB::table($table)->where('store_id', $fromStoreId)->get();
        foreach ($rows as $row) {
            $data = (array) $row;
            $oldId = $data['id'];
            unset($data['id']);
            $data['store_id'] = $toStoreId;
            if (isset($data['slug']) && $data['slug'] !== '') {
                $data['slug'] = $data['slug'] . '-' . $toStoreId;
            }
            foreach ($fkMaps as $col => $idMap) {
                if (array_key_exists($col, $data) && $data[$col] && isset($idMap[$data[$col]])) {
                    $data[$col] = $idMap[$data[$col]];
                }
            }
            if ($autoInc) {
                $map[$oldId] = DB::table($table)->insertGetId($data);
            } else {
                $data['id'] = $nextId++;
                DB::table($table)->insert($data);
                $map[$oldId] = $data['id'];
            }
        }
        return $map;
    }

    private function tableHasAutoIncrement(string $table): bool
    {
        $col = DB::selectOne(
            'SELECT EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, 'id']
        );
        return $col && stripos((string) $col->EXTRA, 'auto_increment') !== false;
    }

    private function cloneGalleries(array $prodMap): void
    {
        if (!Schema::hasTable('galleries') || empty($prodMap)) {
            return;
        }
        foreach ($prodMap as $oldPid => $newPid) {
            $exists = DB::table('galleries')->where('product_id', $newPid)->exists();
            if ($exists) {
                continue;
            }
            $rows = DB::table('galleries')->where('product_id', $oldPid)->get();
            foreach ($rows as $row) {
                $data = (array) $row;
                unset($data['id']);
                $data['product_id'] = $newPid;
                if (array_key_exists('store_id', $data)) {
                    $src = DB::table('products')->where('id', $newPid)->value('store_id');
                    $data['store_id'] = $src;
                }
                DB::table('galleries')->insert($data);
            }
        }
    }

    private function cloneSetting(int $fromStoreId, int $toStoreId, array $def): void
    {
        if (!Schema::hasTable('setting')) {
            return;
        }
        if (DB::table('setting')->where('store_id', $toStoreId)->exists()) {
            DB::table('setting')->where('store_id', $toStoreId)->update([
                'active_theme' => $def['theme'],
                'home_layout' => $def['home_layout'],
            ]);
            return;
        }
        $row = DB::table('setting')->where('store_id', $fromStoreId)->orderBy('id')->first()
            ?: DB::table('setting')->orderBy('id')->first();
        if (!$row) {
            return;
        }
        $data = (array) $row;
        unset($data['id']);
        $data['store_id'] = $toStoreId;
        $data['active_theme'] = $def['theme'];
        if (Schema::hasColumn('setting', 'home_layout')) {
            $data['home_layout'] = $def['home_layout'];
        }
        $data['site_title'] = $def['name'];
        DB::table('setting')->insert($data);
    }
}
