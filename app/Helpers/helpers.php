<?php

if (!function_exists('format_amount')) {
    function format_amount($amount = 0) {
        return env('CUR').' '.$amount;
    }
}

if (!function_exists('footer_menu_types')) {
    function footer_menu_types(): array {
        return [
            'footer_policies' => 'Policies',
            'footer_help' => 'Help',
            'footer_information' => 'Information',
        ];
    }
}

if (!function_exists('page_menu_types')) {
    function page_menu_types(): array {
        return array_merge([
            'top_bar' => 'Top Bar',
            'header' => 'Header',
            'quick_links' => 'Quick Links',
        ], footer_menu_types());
    }
}

if (!function_exists('page_url')) {
    function page_url($page): string {
        if (!$page) {
            return url('/');
        }

        if (!empty($page->route)) {
            $route = trim((string) $page->route);
            if ($route === '/') {
                return url('/');
            }

            return url('/' . ltrim($route, '/'));
        }

        if (!empty($page->slug)) {
            return url('/' . ltrim((string) $page->slug, '/'));
        }

        return url('/');
    }
}

if (!function_exists('footer_menus')) {
    function footer_menus(): array {
        $menus = [];

        foreach (footer_menu_types() as $type => $title) {
            $menus[$type] = [
                'title' => $title,
                'pages' => DB::table('pages')
                    ->where('menu_type', $type)
                    ->where('status', 1)
                    ->orderByRaw('position IS NULL, position ASC')
                    ->orderBy('id', 'ASC')
                    ->get(),
            ];
        }

        return $menus;
    }
}

if (!function_exists('img_url')) {
    /**
     * Build image URL from .env IMG_URL (fallback: APP_URL).
     * Use for products, categories, sliders, logos, and all DB-stored paths.
     */
    function img_url($path = ''): string {
        $base = rtrim((string) (env('IMG_URL') ?: config('app.url')), '/');

        if ($path === null || trim((string) $path) === '') {
            return $base;
        }

        $path = trim((string) $path);

        if (preg_match('#^https?://#i', $path)) {
            if (!preg_match('#^https?://(127\.0\.0\.1|localhost)(:\d+)?/#i', $path)) {
                $path = preg_replace('#^http://#i', 'https://', $path);
            }
            return $path;
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        // Prefer local public file when it exists (admin uploads on local/dev).
        $localRelative = $path;
        if (strpos($localRelative, 'public/') === 0) {
            $localRelative = substr($localRelative, 7);
        }
        $localAbsolute = public_path($localRelative);
        if ($localRelative !== '' && is_file($localAbsolute)) {
            // Root-relative so preview works on current host (not remote APP_URL/IMG_URL).
            return '/' . ltrim($localRelative, '/') . '?v=' . @filemtime($localAbsolute);
        }

        $url = $base . '/' . $path;
        if (!preg_match('#^https?://(127\.0\.0\.1|localhost)(:\d+)?/#i', $url)) {
            $url = preg_replace('#^http://#i', 'https://', $url);
        }

        return $url;
    }
}

if (!function_exists('custom_assets')) {
    function custom_assets($path = '') {
        return img_url($path);
    }
}

if (!function_exists('client_image_url')) {
    function client_image_url(?string $filename): string {
        if (empty($filename)) {
            return '';
        }

        return img_url('img/clients/' . basename($filename));
    }
}

if (!function_exists('theme3_dummy')) {
    /**
     * Dummy homepage data from public/theme3 mockup.
     */
    function theme3_dummy(?string $key = null) {
        static $data = null;

        if ($data === null) {
            $trendingCategories = [];
            $trendingFile = base_path('public/theme3/sections/trending-data.php');
            if (is_file($trendingFile)) {
                require $trendingFile;
            }

            $data = array_merge(config('theme3_dummy', []), [
                'trending_categories' => $trendingCategories ?? [],
            ]);
        }

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? null;
    }
}

if (!function_exists('theme3_slider_lines')) {
    function theme3_slider_lines($slide): array {
        if (empty($slide->p)) {
            return [];
        }

        $html = trim((string) $slide->p);

        if (preg_match('/<p[^>]*>/i', $html)) {
            preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $matches);
            if (!empty($matches[1])) {
                return array_values(array_filter(array_map('trim', $matches[1])));
            }
        }

        if (preg_match('/<br\s*\/?>/i', $html)) {
            return array_values(array_filter(array_map('trim', preg_split('/<br\s*\/?>/i', $html))));
        }

        return [$html];
    }
}

if (!function_exists('theme3_slider_banners')) {
    /**
     * Map DB slider rows to bento-banners.php format.
     */
    function theme3_slider_banners($sliders): array {
        $items = $sliders instanceof \Illuminate\Support\Collection ? $sliders : collect($sliders);

        return $items
            ->filter(function ($slide) {
                return !isset($slide->status) || (int) $slide->status === 1;
            })
            ->sortBy(function ($slide) {
                return [(int) ($slide->sort ?? 0), (int) ($slide->id ?? 0)];
            })
            ->values()
            ->map(function ($slide) {
                $image = !empty($slide->image_url)
                    ? $slide->image_url
                    : (!empty($slide->slider_image) ? img_url('img/slider/' . $slide->slider_image) : '');

                return [
                    'image' => $image,
                    'link' => !empty($slide->button) ? $slide->button : url('/shop'),
                    'title' => $slide->heading ?? '',
                    'lines' => theme3_slider_lines($slide),
                    'ga_id' => $slide->ga_id ?? '',
                    'ga_name' => $slide->ga_name ?? '',
                    'title_size' => $slide->title_size ?? '18px',
                ];
            })
            ->filter(function ($banner) {
                return !empty($banner['image']);
            })
            ->values()
            ->all();
    }
}

if (!function_exists('cart_product_unit_price')) {
    function cart_product_unit_price($product): float {
        $list = (float) ($product->selling_price ?? 0);
        $sale = (float) ($product->discount_price ?? 0);

        return ($sale > 0 && ($list <= 0 || $sale < $list)) ? $sale : $list;
    }
}

if (!function_exists('cart_product_list_price')) {
    function cart_product_list_price($product): float {
        $list = (float) ($product->selling_price ?? 0);
        $unit = cart_product_unit_price($product);

        return ($list > $unit) ? $list : $unit;
    }
}

if (!function_exists('theme3_section')) {
    /**
     * Render a public/theme3/sections/*.php file with optional variables.
     */
    function theme3_section(string $name, array $vars = []): void {
        $path = base_path('public/theme3/sections/' . $name . '.php');
        if (!is_file($path)) {
            return;
        }

        extract($vars, EXTR_SKIP);
        include $path;
    }
}

if (!function_exists('seo_site_name')) {
    function seo_site_name($setting = null): string {
        $setting = $setting ?: (object) [];
        return (string) ($setting->site_title ?? $setting->title ?? env('WEB_NAME', 'Store'));
    }
}

if (!function_exists('seo_canonical')) {
    function seo_canonical(?string $url = null): string {
        if ($url && trim($url) !== '') {
            return $url;
        }
        return url()->current();
    }
}

if (!function_exists('seo_organization_schema')) {
    function seo_organization_schema($setting = null): array {
        $setting = $setting ?: \DB::table('setting')->where('id', 1)->first();
        $name = seo_site_name($setting);
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => url('/'),
        ];
        if (!empty($setting->logo) || !empty($setting->logo1)) {
            $schema['logo'] = img_url($setting->logo ?? $setting->logo1);
        }
        if (!empty($setting->phone) || !empty($setting->whatsapp)) {
            $schema['telephone'] = (string) ($setting->phone ?? $setting->whatsapp);
        }
        $sameAs = array_values(array_filter([
            $setting->facebook ?? null,
            $setting->instagram ?? null,
            $setting->twitter ?? null,
            $setting->youtube ?? null,
            $setting->tiktok ?? null,
            $setting->pinterest ?? null,
        ]));
        if ($sameAs) {
            $schema['sameAs'] = $sameAs;
        }
        return $schema;
    }
}

if (!function_exists('seo_website_schema')) {
    function seo_website_schema($setting = null): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => seo_site_name($setting),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/search') . '?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}

if (!function_exists('seo_breadcrumb_schema')) {
    function seo_breadcrumb_schema(array $items): array {
        $list = [];
        $position = 1;
        foreach ($items as $item) {
            if (empty($item['name'])) {
                continue;
            }
            $entry = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
            ];
            if (!empty($item['item'])) {
                $entry['item'] = $item['item'];
            }
            $list[] = $entry;
        }
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }
}

if (!function_exists('seo_product_schema')) {
    function seo_product_schema($product, $meta = null, $brandName = null, $ratingAvg = 0, $ratingCount = 0): array {
        $list = (float) ($product->selling_price ?? 0);
        $sale = (float) ($product->discount_price ?? 0);
        $price = ($sale > 0 && ($list <= 0 || $sale < $list)) ? $sale : ($list > 0 ? $list : $sale);
        $currency = env('CUR_CODE', 'PKR');
        $url = url('/product/' . $product->slug);
        $inStock = ((int) ($product->product_quantity ?? $product->stock ?? 0)) > 0;

        $schema = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $meta->title ?? $product->product_name,
            'description' => strip_tags((string) ($meta->description ?? $product->short_discriiption ?? $product->product_name)),
            'sku' => (string) ($product->sku ?: ($product->product_code ?: ('P' . $product->id))),
            'image' => [img_url($product->image_one ?? '')],
            'url' => $url,
            'offers' => [
                '@type' => 'Offer',
                'url' => $url,
                'priceCurrency' => $currency,
                'price' => number_format($price, 2, '.', ''),
                'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ];

        if ($brandName) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brandName,
            ];
        }

        if ($ratingCount > 0 && $ratingAvg > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($ratingAvg, 1),
                'bestRating' => 5,
                'worstRating' => 1,
                'ratingCount' => (int) $ratingCount,
                'reviewCount' => (int) $ratingCount,
            ];
        }

        return $schema;
    }
}

if (!function_exists('seo_collection_schema')) {
    function seo_collection_schema(string $name, string $description, string $url): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $name,
            'description' => strip_tags($description),
            'url' => $url,
        ];
    }
}
