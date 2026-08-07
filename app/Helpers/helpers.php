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
