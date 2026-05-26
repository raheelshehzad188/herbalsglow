<?php

if (!function_exists('format_amount')) {
    function format_amount($amount = 0) {
        return env('CUR').' '.$amount;
    }
}

if (!function_exists('img_url')) {
    /**
     * Build image URL from .env IMG_URL (fallback: APP_URL).
     */
    function img_url($path = ''): string {
        $path = preg_replace('/^public\//', '', $path);
        $pth = 'https://harbbels.shoppingeasy.pk/public/';
        return $pth.$path;
        $base = rtrim((string) (env('IMG_URL') ?: config('app.url')), '/');

        $path = trim((string) $path);
        if ($path === '') {
            return $base;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (strpos($path, 'public/') === 0) {
            $path = substr($path, 7);
        }

        return $base . '/' . $path;
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
