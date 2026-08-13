<?php

namespace App\Services\Integrations;

use App\Models\Store;
use App\Models\StoreIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokEventsService
{
    public function send(Store $store, string $eventName, array $properties = [], array $user = []): array
    {
        $integration = StoreIntegration::where('store_id', $store->id)
            ->where('provider', 'tiktok')
            ->first();

        if (!$integration || !$integration->is_enabled || !$integration->events_enabled) {
            return ['ok' => false, 'message' => 'TikTok events not enabled.'];
        }
        if (!$integration->access_token || !$integration->pixel_id) {
            return ['ok' => false, 'message' => 'TikTok pixel / token missing.'];
        }

        $payload = [
            'pixel_code' => $integration->pixel_id,
            'event' => $eventName,
            'event_id' => uniqid('tt_', true),
            'timestamp' => (string) time(),
            'context' => [
                'ip' => $user['ip'] ?? request()->ip(),
                'user_agent' => $user['ua'] ?? request()->userAgent(),
                'page' => [
                    'url' => $properties['url'] ?? url()->current(),
                ],
            ],
            'properties' => array_filter([
                'currency' => $properties['currency'] ?? 'PKR',
                'value' => $properties['value'] ?? null,
                'contents' => $properties['contents'] ?? null,
                'content_type' => $properties['content_type'] ?? 'product',
            ]),
        ];

        $endpoint = 'https://business-api.tiktok.com/open_api/v1.3/event/track/';
        $response = Http::withHeaders([
            'Access-Token' => $integration->access_token,
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        if (!$response->successful() || (int) data_get($response->json(), 'code', 1) !== 0) {
            Log::warning('TikTok event failed', ['store_id' => $store->id, 'event' => $eventName, 'body' => $response->body()]);
            return ['ok' => false, 'message' => substr($response->body(), 0, 240)];
        }

        return ['ok' => true, 'message' => 'Event sent: ' . $eventName];
    }
}
