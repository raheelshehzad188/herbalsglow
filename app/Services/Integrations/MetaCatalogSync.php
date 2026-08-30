<?php

namespace App\Services\Integrations;

use App\Models\Admins\Product;
use App\Models\Store;
use App\Models\StoreIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCatalogSync
{
    public function syncStore(Store $store): array
    {
        $integration = StoreIntegration::where('store_id', $store->id)
            ->where('provider', 'meta')
            ->first();

        if (!$integration || !$integration->is_enabled || !$integration->catalog_enabled) {
            return ['ok' => false, 'message' => 'Meta catalog not enabled.'];
        }
        if (!$integration->access_token || !$integration->catalog_id) {
            return ['ok' => false, 'message' => 'Meta access token / catalog ID missing.'];
        }

        $products = Product::withoutGlobalScope('store')
            ->where('store_id', $store->id)
            ->where('status', 1)
            ->orderBy('id')
            ->limit(200)
            ->get();

        $requests = [];
        foreach ($products as $product) {
            $price = (float) ($product->discount_price ?: $product->selling_price ?: 0);
            $image = $product->image_one
                ? (str_starts_with((string) $product->image_one, 'http')
                    ? $product->image_one
                    : rtrim((string) env('IMG_URL', url('/')), '/') . '/' . ltrim((string) $product->image_one, '/'))
                : null;

            $item = [
                'method' => 'UPDATE',
                'retailer_id' => (string) $product->id,
                'data' => [
                    'name' => $product->product_name,
                    'description' => strip_tags((string) ($product->short_discriiption ?: $product->product_details)),
                    'availability' => ((int) $product->product_quantity > 0) ? 'in stock' : 'out of stock',
                    'condition' => 'new',
                    'price' => number_format($price, 2, '.', '') . ' PKR',
                    'url' => product_url($product),
                    'brand' => (string) ($product->brand ?: $store->name),
                ],
            ];
            if ($image) {
                $item['data']['image_url'] = $image;
            }
            $requests[] = $item;
        }

        if (empty($requests)) {
            return ['ok' => true, 'message' => 'No products to sync.', 'count' => 0];
        }

        $endpoint = 'https://graph.facebook.com/v19.0/' . $integration->catalog_id . '/items_batch';
        $response = Http::asForm()->post($endpoint, [
            'access_token' => $integration->access_token,
            'item_type' => 'PRODUCT_ITEM',
            'requests' => json_encode($requests),
        ]);

        if (!$response->successful()) {
            Log::warning('Meta catalog sync failed', [
                'store_id' => $store->id,
                'body' => $response->body(),
            ]);
            return [
                'ok' => false,
                'message' => 'Meta API error: ' . substr($response->body(), 0, 240),
                'count' => count($requests),
            ];
        }

        $integration->extra_json = json_encode([
            'last_catalog_sync_at' => now()->toDateTimeString(),
            'last_catalog_count' => count($requests),
            'last_catalog_response' => $response->json(),
        ]);
        $integration->save();

        return [
            'ok' => true,
            'message' => 'Meta catalog sync queued for ' . count($requests) . ' products.',
            'count' => count($requests),
        ];
    }
}
