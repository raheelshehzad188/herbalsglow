<?php

namespace App\Services\Integrations;

use App\Models\Store;
use App\Models\StoreIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaEventsService
{
    public function send(Store $store, string $eventName, array $customData = [], array $userData = []): array
    {
        $integration = StoreIntegration::where('store_id', $store->id)
            ->where('provider', 'meta')
            ->first();

        if (!$integration || !$integration->is_enabled || !$integration->events_enabled) {
            return ['ok' => false, 'message' => 'Meta events not enabled.'];
        }
        if (!$integration->access_token || !$integration->pixel_id) {
            return ['ok' => false, 'message' => 'Meta pixel / token missing.'];
        }

        $event = [
            'event_name' => $eventName,
            'event_time' => time(),
            'action_source' => 'website',
            'event_source_url' => $customData['event_source_url'] ?? url()->current(),
            'user_data' => array_filter([
                'client_ip_address' => $userData['ip'] ?? request()->ip(),
                'client_user_agent' => $userData['ua'] ?? request()->userAgent(),
                'em' => isset($userData['email']) ? hash('sha256', strtolower(trim($userData['email']))) : null,
                'ph' => isset($userData['phone']) ? hash('sha256', preg_replace('/\D+/', '', $userData['phone'])) : null,
            ]),
            'custom_data' => array_filter([
                'currency' => $customData['currency'] ?? 'PKR',
                'value' => $customData['value'] ?? null,
                'content_ids' => $customData['content_ids'] ?? null,
                'content_type' => $customData['content_type'] ?? 'product',
                'contents' => $customData['contents'] ?? null,
            ]),
        ];

        $endpoint = 'https://graph.facebook.com/v19.0/' . $integration->pixel_id . '/events';
        $response = Http::asForm()->post($endpoint, [
            'access_token' => $integration->access_token,
            'data' => json_encode([$event]),
        ]);

        if (!$response->successful()) {
            Log::warning('Meta event failed', ['store_id' => $store->id, 'event' => $eventName, 'body' => $response->body()]);
            return ['ok' => false, 'message' => substr($response->body(), 0, 240)];
        }

        return ['ok' => true, 'message' => 'Event sent: ' . $eventName];
    }
}
