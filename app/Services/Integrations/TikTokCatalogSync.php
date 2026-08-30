<?php

namespace App\Services\Integrations;

use App\Models\Admins\Product;
use App\Models\Store;
use App\Models\StoreIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokCatalogSync
{
    public function syncStore(Store $store): array
    {
        $integration = StoreIntegration::where('store_id', $store->id)
            ->where('provider', 'tiktok')
            ->first();

        if (!$integration || !$integration->is_enabled || !$integration->catalog_enabled) {
            return ['ok' => false, 'message' => 'TikTok catalog not enabled.'];
        }
        if (!$integration->access_token || !$integration->catalog_id) {
            return ['ok' => false, 'message' => 'TikTok access token / catalog ID missing.'];
        }

        $products = Product::withoutGlobalScope('store')
            ->where('store_id', $store->id)
            ->where('status', 1)
            ->orderBy('id')
            ->limit(100)
            ->get();

        $items = [];
        foreach ($products as $product) {
            $price = (float) ($product->discount_price ?: $product->selling_price ?: 0);
            $image = $product->image_one
                ? (str_starts_with((string) $product->image_one, 'http')
                    ? $product->image_one
                    : rtrim((string) env('IMG_URL', url('/')), '/') . '/' . ltrim((string) $product->image_one, '/'))
                : null;

            $row = [
                'sku_id' => (string) $product->id,
                'title' => $product->product_name,
                'description' => strip_tags((string) ($product->short_discriiption ?: $product->product_details)),
                'availability' => ((int) $product->product_quantity > 0) ? 'IN_STOCK' : 'OUT_OF_STOCK',
                'price' => [
                    'amount' => (string) number_format($price, 2, '.', ''),
                    'currency' => 'PKR',
                ],
                'landing_page' => [
                    'url' => product_url($product),
                ],
            ];
            if ($image) {
                $row['image'] = ['url' => $image];
            }
            $items[] = $row;
        }

        if (empty($items)) {
            return ['ok' => true, 'message' => 'No products to sync.', 'count' => 0];
        }

        // TikTok Business Catalog product upload (batch). Endpoint may vary by region/account type.
        $endpoint = 'https://business-api.tiktok.com/open_api/v1.3/catalog/product/upload/';
        $response = Http::withHeaders([
            'Access-Token' => $integration->access_token,
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'catalog_id' => $integration->catalog_id,
            'bc_id' => $integration->ad_account_id,
            'products' => $items,
        ]);

        if (!$response->successful() || (int) data_get($response->json(), 'code', 1) !== 0) {
            Log::warning('TikTok catalog sync failed', [
                'store_id' => $store->id,
                'body' => $response->body(),
            ]);
            return [
                'ok' => false,
                'message' => 'TikTok API error: ' . substr($response->body(), 0, 240),
                'count' => count($items),
            ];
        }

        $integration->extra_json = json_encode([
            'last_catalog_sync_at' => now()->toDateTimeString(),
            'last_catalog_count' => count($items),
            'last_catalog_response' => $response->json(),
        ]);
        $integration->save();

        return [
            'ok' => true,
            'message' => 'TikTok catalog sync sent for ' . count($items) . ' products.',
            'count' => count($items),
        ];
    }
}
