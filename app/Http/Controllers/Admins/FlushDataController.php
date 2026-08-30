<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admins\Brand;
use App\Models\Admins\Category;
use App\Models\Admins\Product;
use App\Models\Admins\SubCategory;
use App\Models\ShopifyResourceMap;
use App\Support\StoreContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FlushDataController extends Controller
{
    public function index()
    {
        $storeId = $this->storeId();
        $counts = $this->counts($storeId);

        return view('admins.flush_data', compact('counts'));
    }

    public function flush(Request $request)
    {
        $request->validate([
            'scope' => 'required|in:all,products,brands,categories',
            'confirm' => 'accepted',
        ]);

        $storeId = $this->storeId();
        $scope = $request->input('scope');
        try {
            $deleted = $this->runFlush($storeId, $scope);
        } catch (\Throwable $e) {
            report($e);
            return redirect('/admin/flush-data')->with([
                'msg' => 'Flush failed. Please try again.',
                'msg_type' => 'error',
            ]);
        }

        return redirect('/admin/flush-data')->with([
            'msg' => $this->summary($scope, $deleted),
            'msg_type' => 'success',
        ]);
    }

    protected function runFlush(int $storeId, string $scope): array
    {
        $deleted = ['products' => 0, 'brands' => 0, 'categories' => 0, 'subcategories' => 0];

        DB::transaction(function () use ($storeId, $scope, &$deleted) {
            if (in_array($scope, ['all', 'products'], true)) {
                $deleted['products'] = $this->flushProducts($storeId);
            }
            if (in_array($scope, ['all', 'categories'], true)) {
                $cats = $this->flushCategories($storeId);
                $deleted['categories'] = $cats['categories'];
                $deleted['subcategories'] = $cats['subcategories'];
            }
            if (in_array($scope, ['all', 'brands'], true)) {
                $deleted['brands'] = $this->flushBrands($storeId);
            }
        });

        return $deleted;
    }

    protected function flushProducts(int $storeId): int
    {
        $ids = Product::withoutStore()->where('store_id', $storeId)->pluck('id');
        if ($ids->isEmpty()) {
            $this->forgetMaps($storeId, ['product', 'variant', 'image']);
            return 0;
        }

        $this->deleteLinkedRows('galleries', ['product_id'], $ids);
        $this->deleteLinkedRows('products_to_meta', ['product_id', 'pid'], $ids);
        $this->deleteLinkedRows('rating', ['product_id', 'pid'], $ids);
        $this->deleteLinkedRows('pfaqs', ['product_id', 'pid'], $ids);
        $this->deleteLinkedRows('carts', ['product_id', 'pid'], $ids);

        $count = Product::withoutStore()->where('store_id', $storeId)->delete();
        $this->forgetMaps($storeId, ['product', 'variant', 'image']);

        return $count;
    }

    protected function flushCategories(int $storeId): array
    {
        $sub = 0;
        if (Schema::hasTable('sub_categories')) {
            $q = SubCategory::withoutStore();
            if (Schema::hasColumn('sub_categories', 'store_id')) {
                $q->where('store_id', $storeId);
            } else {
                $catIds = Category::withoutStore()->where('store_id', $storeId)->pluck('id');
                $q->whereIn('category_id', $catIds);
            }
            $sub = $q->delete();
        }

        $catIds = Category::withoutStore()->where('store_id', $storeId)->pluck('id');
        $this->deleteLinkedRows('categories_to_meta', ['category_id', 'cid'], $catIds);

        if (Schema::hasColumn('products', 'category_id')) {
            Product::withoutStore()->where('store_id', $storeId)->update(['category_id' => null]);
        }

        $count = Category::withoutStore()->where('store_id', $storeId)->delete();
        $this->forgetMaps($storeId, ['collection']);

        return ['categories' => $count, 'subcategories' => $sub];
    }

    protected function flushBrands(int $storeId): int
    {
        if (Schema::hasColumn('products', 'brand')) {
            Product::withoutStore()->where('store_id', $storeId)->update(['brand' => null]);
        }

        $count = Brand::withoutStore()->where('store_id', $storeId)->delete();
        $this->forgetMaps($storeId, ['vendor']);

        return $count;
    }

    protected function forgetMaps(int $storeId, array $types): void
    {
        if (!Schema::hasTable('shopify_resource_maps')) {
            return;
        }
        $all = $types;
        foreach ($types as $type) {
            $all[] = 'woo_' . $type;
        }
        ShopifyResourceMap::withoutStore()
            ->where('store_id', $storeId)
            ->whereIn('resource_type', $all)
            ->delete();
    }

    protected function deleteLinkedRows(string $table, array $columns, $ids): void
    {
        if (!Schema::hasTable($table) || $ids->isEmpty()) {
            return;
        }
        $column = null;
        foreach ($columns as $candidate) {
            if (Schema::hasColumn($table, $candidate)) {
                $column = $candidate;
                break;
            }
        }
        if (!$column) {
            return;
        }
        foreach ($ids->chunk(400) as $chunk) {
            DB::table($table)->whereIn($column, $chunk->all())->delete();
        }
    }

    protected function counts(int $storeId): array
    {
        return [
            'products' => Product::withoutStore()->where('store_id', $storeId)->count(),
            'brands' => Brand::withoutStore()->where('store_id', $storeId)->count(),
            'categories' => Category::withoutStore()->where('store_id', $storeId)->count(),
            'subcategories' => $this->subcategoryCount($storeId),
        ];
    }

    protected function subcategoryCount(int $storeId): int
    {
        if (!Schema::hasTable('sub_categories')) {
            return 0;
        }
        $q = SubCategory::withoutStore();
        if (Schema::hasColumn('sub_categories', 'store_id')) {
            return $q->where('store_id', $storeId)->count();
        }
        $catIds = Category::withoutStore()->where('store_id', $storeId)->pluck('id');
        if ($catIds->isEmpty()) {
            return 0;
        }
        return $q->whereIn('category_id', $catIds)->count();
    }

    protected function summary(string $scope, array $deleted): string
    {
        $parts = [];
        if ($deleted['products']) {
            $parts[] = $deleted['products'] . ' products';
        }
        if ($deleted['brands']) {
            $parts[] = $deleted['brands'] . ' brands';
        }
        if ($deleted['categories']) {
            $parts[] = $deleted['categories'] . ' categories';
        }
        if ($deleted['subcategories']) {
            $parts[] = $deleted['subcategories'] . ' subcategories';
        }
        if (!$parts) {
            return 'Nothing to flush for this store.';
        }
        $label = [
            'all' => 'All catalog data',
            'products' => 'Products',
            'brands' => 'Brands',
            'categories' => 'Categories',
        ][$scope] ?? 'Data';

        return $label . ' flushed: ' . implode(', ', $parts) . '.';
    }

    protected function storeId(): int
    {
        $id = StoreContext::id();
        if (!$id) {
            abort(403, 'No store is linked to this account.');
        }
        return (int) $id;
    }
}
