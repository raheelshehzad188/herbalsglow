<?php

namespace Tests\Unit;

use App\Services\Shopify\ShopifyAdminClient;
use App\Services\Shopify\ShopifyOAuth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use RuntimeException;
use Tests\TestCase;

class ShopifyOAuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'shopify.api_key' => 'test-client-id',
            'shopify.api_secret' => 'test-client-secret',
            'shopify.scopes' => 'read_products,read_inventory,read_orders',
            'shopify.app_url' => 'https://shopus.test',
            'app.url' => 'https://shopus.test',
            'app.key' => config('app.key') ?: 'base64:'.base64_encode(str_repeat('a', 32)),
        ]);
    }

    public function test_is_configured_requires_global_app_credentials()
    {
        $oauth = new ShopifyOAuth();
        $this->assertTrue($oauth->isConfigured());
        config(['shopify.api_key' => '', 'shopify.api_secret' => 'x']);
        $this->assertFalse($oauth->isConfigured());
    }

    public function test_normalize_shop_accepts_handle_and_rejects_junk()
    {
        $this->assertSame('demo-store.myshopify.com', ShopifyAdminClient::normalizeShop('demo-store'));
        $this->assertSame('demo-store.myshopify.com', ShopifyAdminClient::normalizeShop('https://demo-store.myshopify.com/admin'));
        $this->expectException(RuntimeException::class);
        ShopifyAdminClient::normalizeShop('https://evil.example.com');
    }

    public function test_authorize_url_uses_platform_app_and_stores_nonce()
    {
        Session::start();
        $oauth = new ShopifyOAuth();
        $url = $oauth->authorizeUrl('demo-store.myshopify.com', 4, 12);
        $this->assertStringContainsString('https://demo-store.myshopify.com/admin/oauth/authorize?', $url);
        $this->assertStringContainsString('client_id=test-client-id', $url);
        $this->assertStringContainsString(urlencode('read_products,read_inventory,read_orders'), $url);
        $this->assertStringContainsString(urlencode('https://shopus.test/admin/import-data/shopify/callback'), $url);
        $session = Session::get('shopify_oauth');
        $this->assertSame(4, $session['store_id']);
        $this->assertSame('demo-store.myshopify.com', $session['shop']);
        $this->assertNotEmpty($session['nonce']);
    }

    public function test_hmac_and_state_must_match_current_store()
    {
        Session::start();
        $oauth = new ShopifyOAuth();
        $oauth->authorizeUrl('demo-store.myshopify.com', 4, 12);
        $session = Session::get('shopify_oauth');
        $state = Crypt::encryptString(json_encode([
            'store_id' => 4,
            'admin_id' => 12,
            'shop' => 'demo-store.myshopify.com',
            'nonce' => $session['nonce'],
            'exp' => time() + 600,
        ]));
        $query = [
            'code' => 'abc123',
            'shop' => 'demo-store.myshopify.com',
            'state' => $state,
            'timestamp' => '1710000000',
        ];
        $query['hmac'] = $this->sign($query);

        $ok = $oauth->assertValidCallback($query, 4);
        $this->assertSame('demo-store.myshopify.com', $ok['shop']);
        $this->assertSame('abc123', $ok['code']);
    }

    public function test_invalid_hmac_is_rejected()
    {
        Session::start();
        $oauth = new ShopifyOAuth();
        $oauth->authorizeUrl('demo-store.myshopify.com', 4, 12);
        $this->expectException(RuntimeException::class);
        $oauth->assertValidCallback([
            'code' => 'abc123',
            'shop' => 'demo-store.myshopify.com',
            'state' => 'nope',
            'hmac' => 'deadbeef',
        ], 4);
    }

    public function test_wrong_store_is_rejected()
    {
        Session::start();
        $oauth = new ShopifyOAuth();
        $oauth->authorizeUrl('demo-store.myshopify.com', 4, 12);
        $session = Session::get('shopify_oauth');
        $state = Crypt::encryptString(json_encode([
            'store_id' => 4,
            'admin_id' => 12,
            'shop' => 'demo-store.myshopify.com',
            'nonce' => $session['nonce'],
            'exp' => time() + 600,
        ]));
        $query = [
            'code' => 'abc123',
            'shop' => 'demo-store.myshopify.com',
            'state' => $state,
        ];
        $query['hmac'] = $this->sign($query);
        $this->expectException(RuntimeException::class);
        $oauth->assertValidCallback($query, 99);
    }

    public function test_cancelled_authorization_has_friendly_message()
    {
        Session::start();
        $oauth = new ShopifyOAuth();
        $this->expectExceptionMessage('Shopify authorization was cancelled.');
        $oauth->assertValidCallback(['error' => 'access_denied', 'hmac' => 'x'], 4);
    }

    protected function sign(array $query): string
    {
        unset($query['hmac'], $query['signature']);
        ksort($query);
        $parts = [];
        foreach ($query as $key => $value) {
            $parts[] = $key . '=' . $value;
        }
        return hash_hmac('sha256', implode('&', $parts), 'test-client-secret');
    }
}
