<?php

return [
    /*
    | GraphQL Admin API version. Per-store Client ID / Secret / access tokens
    | live encrypted on shopify_connections — not in .env.
    */
    'api_key' => env('SHOPIFY_API_KEY', ''),
    'api_secret' => env('SHOPIFY_API_SECRET', ''),
    'app_url' => env('SHOPIFY_APP_URL', env('APP_URL')),
    'api_version' => env('SHOPIFY_API_VERSION', '2026-07'),
    'scopes' => env('SHOPIFY_SCOPES', 'read_products'),
    'redirect_path' => '/admin/import-data/shopify/callback',
];
