<?php

return [
    /*
    | Shopify Admin API (official REST + OAuth authorization code grant).
    | Version: https://shopify.dev/docs/api/admin-rest
    */
    'api_key' => env('SHOPIFY_API_KEY', ''),
    'api_secret' => env('SHOPIFY_API_SECRET', ''),
    'api_version' => env('SHOPIFY_API_VERSION', '2024-10'),
    'scopes' => env('SHOPIFY_SCOPES', 'read_products,read_product_listings,read_inventory,read_customers,read_orders'),
];
