<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ShopusHome1Seeder extends Seeder
{
    public function run()
    {
        $store = Store::where('slug', 'shopus-store')->first();
        if (!$store) {
            return;
        }

        $sid = (int) $store->id;
        if (Schema::hasTable('setting')) {
            $settingUpdate = [
                'site_title' => 'Shopus: Your One-Stop Destination for Fashion and Style',
                'title' => 'Shopus: Your One-Stop Destination for Fashion and Style',
                'phone' => '+ 00645 4568',
                'logo' => 'theme4/assets/images/logos/logo.webp',
                'wlogo' => 'theme4/assets/images/logos/footer-logo.webp',
                'logo1' => 'theme4/assets/images/homepage-one/icon.png',
                'footer_text' => '4517 Washington Ave. Manchester, Kentucky 39495',
            ];
            if (Schema::hasColumn('setting', 'home_layout')) {
                $settingUpdate['home_layout'] = 1;
            }
            DB::table('setting')->where('store_id', $sid)->update($settingUpdate);
        }
        $img = 'theme4/assets/images/homepage-one';

        $cats = [
            ['Dresses', 'dresses.webp'],
            ['Leather Bags', 'bags.webp'],
            ['Sweaters', 'sweaters.webp'],
            ['Boots', 'shoes.webp'],
            ['Gift for Him', 'gift.webp'],
            ['Sneakers', 'sneakers.webp'],
            ['Watch', 'watch.webp'],
            ['Gold Rings', 'ring.webp'],
            ['Cap', 'cap.webp'],
            ['Sunglass', 'glass.webp'],
            ['Baby Shop', 'baby.webp'],
            ['Women Shoes', 'shoes.webp'],
            ['Makeup Box', 'gift.webp'],
            ['Floral Dresses', 'dresses.webp'],
        ];

        $catIds = [];
        DB::table('categories')->where('store_id', $sid)->where('slug', 'not like', '%-shopus')->update(['show_on_home' => 0]);
        foreach ($cats as $i => [$name, $file]) {
            $slug = Str::slug($name) . '-shopus';
            $data = [
                'store_id' => $sid,
                'name' => $name,
                'slug' => $slug,
                'status' => 1,
                'image' => $img . '/category-img/' . $file,
                'show_on_home' => 1,
                'sort' => $i + 1,
                'home_sort' => $i + 1,
                'updated_at' => now(),
            ];
            $existing = DB::table('categories')->where('store_id', $sid)->where('slug', $slug)->first();
            if ($existing) {
                DB::table('categories')->where('id', $existing->id)->update($data);
                $catIds[] = $existing->id;
            } else {
                $data['created_at'] = now();
                $catIds[] = DB::table('categories')->insertGetId($data);
            }
        }

        $catByName = [];
        foreach ($cats as $i => [$name]) {
            $catByName[$name] = $catIds[$i];
        }

        $products = [
            ['Rainbow Sequin Dress', 1, 1299, 699, 1, 'Dresses'],
            ['Feminine Wrap Blouse', 2, 999, 699, 1, 'Dresses'],
            ['Trendy Bucket Hat', 3, 1899, 1099, 1, 'Cap'],
            ['Boho Maxi Dress', 4, 2099, 1099, 1, 'Dresses'],
            ['Casual Denim Jacket', 5, 2099, 1099, 1, 'Sweaters'],
            ['Stylish Statement Earrings', 6, 2099, 999, 1, 'Gold Rings'],
            ['Leather Dress Shoes', 7, 1999, 1899, 1, 'Boots'],
            ['Wool Peacoat', 8, 2599, 1399, 1, 'Sweaters'],
            ['Classic Party Dress', 9, 2999, 1699, 1, 'Dresses'],
            ['Rainbow Dress', 10, 1299, 699, 1, 'Floral Dresses'],
            ['Red Sequin Hat', 11, 1399, 799, 0, 'Cap'],
            ['Blue Suit', 12, 1099, 599, 1, 'Sweaters'],
            ['Gradient Party Shirt', 13, 1999, 1099, 0, 'Dresses'],
            ['Slim-Fit Shirt', 5, 1499, 699, 1, 'Dresses'],
            ['Half Sleeve Dress', 9, 1299, 699, 1, 'Dresses'],
            ['Feminine Wrap Coat', 10, 1899, 1099, 1, 'Sweaters'],
            ['Black Suit', 2, 1099, 899, 1, 'Sweaters'],
            ['Rainbow Party Dress', 4, 1999, 899, 1, 'Floral Dresses'],
            ['Rainbow Sequin Skart', 1, 1599, 799, 1, 'Dresses'],
            ['Sequin Dress', 3, 3099, 1599, 1, 'Dresses'],
            ['Red Sequin Dress', 6, 2099, 1399, 1, 'Dresses'],
            ['White Hat', 6, 2999, 2699, 0, 'Cap'],
            ['White Checked Shirt', 5, 1999, 1699, 1, 'Dresses'],
            ['Flower Design Dress', 1, 1999, 899, 1, 'Floral Dresses'],
            ['Stylish Earrings', 6, 1799, 999, 1, 'Gold Rings'],
            ['Classic Design Skart', 1, 2000, 0, 0, 'Dresses'],
            ['Blue Party Dress', 3, 1500, 0, 1, 'Dresses'],
            ['Classic Red Dress', 4, 1800, 0, 1, 'Dresses'],
            ['City Leather Tote', 2, 2499, 1899, 1, 'Leather Bags'],
            ['Weekend Crossbody Bag', 8, 1899, 1299, 1, 'Leather Bags'],
            ['School Canvas Bag', 10, 999, 699, 0, 'Leather Bags'],
            ['Cable Knit Sweater', 8, 2299, 1499, 1, 'Sweaters'],
            ['Chunky Winter Boots', 7, 3499, 2499, 1, 'Boots'],
            ['Gift Hamper Set', 6, 1599, 999, 1, 'Gift for Him'],
            ['Classic White Sneakers', 7, 2799, 1999, 1, 'Sneakers'],
            ['Street Runner Sneakers', 5, 2199, 1599, 1, 'Sneakers'],
            ['Classic Analog Watch', 14, 4599, 3299, 1, 'Watch'],
            ['Gold Band Ring', 6, 1299, 899, 0, 'Gold Rings'],
            ['Summer Cap', 3, 799, 499, 1, 'Cap'],
            ['Aviator Sunglasses', 11, 1499, 999, 1, 'Sunglass'],
            ['Baby Soft Romper', 10, 899, 599, 1, 'Baby Shop'],
            ['Kids Party Dress', 9, 1199, 799, 1, 'Baby Shop'],
            ['Heeled Women Shoes', 7, 2699, 1899, 1, 'Women Shoes'],
            ['Makeup Organizer Box', 6, 1399, 899, 0, 'Makeup Box'],
        ];

        foreach ($products as $i => [$name, $n, $sell, $disc, $sale, $catName]) {
            $slug = Str::slug($name) . '-shopus';
            $catId = $catByName[$catName] ?? $catIds[$i % count($catIds)];
            $file = $img . '/product-img/product-img-' . $n . '.webp';
            $data = [
                'store_id' => $sid,
                'product_name' => $name,
                'slug' => $slug,
                'category_id' => $catId,
                'product_details' => '<p>' . $name . ' — ShopUS demo product for Home 1.</p>',
                'short_discriiption' => $name,
                'selling_price' => $sell,
                'discount_price' => $disc,
                'product_quantity' => 50,
                'status' => 1,
                'image_one' => $file,
                'New_Arrival' => 1,
                'Featured' => $i < 8 ? 1 : 0,
                'Sale' => $sale,
                'view' => 20 - $i,
                'updated_at' => now(),
            ];
            $existing = DB::table('products')->where('store_id', $sid)->where('slug', $slug)->first();
            if ($existing) {
                DB::table('products')->where('id', $existing->id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('products')->insert($data);
            }
        }

        $slides = [
            ['UP TO <span class="wrapper-inner-title">70%</span> OFF', 'Fashion Collection Summer Sale', 'hero-slider-one.webp', 1],
            ['UP TO <span class="wrapper-inner-title">70%</span> OFF', 'Fashion Collection Summer Sale', 'hero-slider-two.webp', 2],
            ['UP TO <span class="wrapper-inner-title">70%</span> OFF', 'Fashion Collection Summer Sale', 'hero-slider-three.webp', 3],
        ];
        foreach ($slides as [$heading, $p, $file, $sort]) {
            $row = [
                'store_id' => $sid,
                'slider_image' => $img . '/' . $file,
                'heading' => $heading,
                'p' => $p,
                'button' => 'Shop Now',
                'image_url' => '/shop',
                'sort' => $sort,
                'status' => 1,
                'updated_at' => now(),
            ];
            $existing = DB::table('sliders')->where('store_id', $sid)->where('sort', $sort)->first();
            if ($existing) {
                DB::table('sliders')->where('id', $existing->id)->update($row);
            } else {
                $row['created_at'] = now();
                DB::table('sliders')->insert($row);
            }
        }

        if (Schema::hasTable('brands') && Schema::hasColumn('brands', 'image')) {
            for ($i = 1; $i <= 12; $i++) {
                $slug = 'shopus-brand-' . $i;
                $row = [
                    'store_id' => $sid,
                    'name' => 'Brand ' . $i,
                    'slug' => $slug,
                    'status' => 1,
                    'image' => $img . '/brand-img-' . $i . '.webp',
                    'updated_at' => now(),
                ];
                $existing = DB::table('brands')->where('store_id', $sid)->where('slug', $slug)->first();
                if ($existing) {
                    DB::table('brands')->where('id', $existing->id)->update($row);
                } else {
                    $row['created_at'] = now();
                    DB::table('brands')->insert($row);
                }
            }
        }

        if (Schema::hasTable('posts')) {
            $blogImg = $img . '/about/';
            $posts = [
                ['It’s official! The iPhone 14 Series is on its way! Rumors turned out', 'iphone-14-series-shopus', 'blog-img-1.webp'],
                ['Must-Have WordPress Plugins for Ecommerce Websites in 2022', 'wordpress-plugins-ecommerce-2022-shopus', 'blog-img-2.webp'],
                ['15 Best WordPress Newspaper Themes to Look Out for in 2022', 'wordpress-newspaper-themes-2022-shopus', 'blog-img-3.webp'],
                ['6 Best WordPress E-commerce Plugins for Online Stores in 2022', 'wordpress-ecommerce-plugins-2022-shopus', 'blog-img-2.webp'],
                ['Top 10 Best Professional Ecommerce Blogging Platforms for 2022', 'professional-ecommerce-blogging-platforms-shopus', 'blog-img-3.webp'],
                ['Business-to-consumer Ecommerce that involves selling fight products', 'b2c-ecommerce-selling-products-shopus', 'blog-img-1.webp'],
            ];
            $body = '<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>';
            foreach ($posts as $row) {
                [$title, $slug, $file] = $row;
                $data = [
                    'title' => $title,
                    'slug' => $slug,
                    'image' => $blogImg . $file,
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('posts', 'content')) {
                    $data['content'] = $body;
                } elseif (Schema::hasColumn('posts', 'description')) {
                    $data['description'] = $body;
                } elseif (Schema::hasColumn('posts', 'description_english')) {
                    $data['description_english'] = $body;
                }
                if (Schema::hasColumn('posts', 'status')) {
                    $data['status'] = 1;
                }
                $existing = DB::table('posts')->where('slug', $slug)->first();
                if ($existing) {
                    DB::table('posts')->where('id', $existing->id)->update($data);
                } else {
                    $data['created_at'] = now();
                    DB::table('posts')->insert($data);
                }
            }
        }
    }
}
