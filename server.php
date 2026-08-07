<?php

/**
 * Laravel development server front controller.
 * Used by: php artisan serve
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// Serve existing files from /public directly (logos, css, uploads, etc.)
$publicPath = __DIR__ . '/public' . $uri;
if ($uri !== '/' && is_file($publicPath)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
