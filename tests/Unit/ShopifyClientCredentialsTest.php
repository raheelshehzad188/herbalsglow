<?php

namespace Tests\Unit;

use App\Services\Shopify\ShopifyAdminClient;
use App\Services\Shopify\ShopifyAuthService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class ShopifyClientCredentialsTest extends TestCase
{
    public function test_normalizes_allowed_shop_urls()
    {
        $this->assertSame('zenmart.myshopify.com', ShopifyAdminClient::normalizeShop('zenmart.myshopify.com'));
        $this->assertSame('zenmart.myshopify.com', ShopifyAdminClient::normalizeShop('https://zenmart.myshopify.com'));
        $this->assertSame('zenmart.myshopify.com', ShopifyAdminClient::normalizeShop('https://zenmart.myshopify.com/'));
        $this->assertSame('zenmart.myshopify.com', ShopifyAdminClient::normalizeShop('www.zenmart.myshopify.com'));
        $this->assertSame('zenmart.myshopify.com', ShopifyAdminClient::normalizeShop('zenmart'));
    }

    public function test_rejects_invalid_shop_urls()
    {
        $bad = ['https://evil.example.com', 'localhost', 'http://127.0.0.1', 'javascript:alert(1)', '10.0.0.8'];
        foreach ($bad as $url) {
            try {
                ShopifyAdminClient::normalizeShop($url);
                $this->fail('Accepted invalid shop: ' . $url);
            } catch (RuntimeException $e) {
                $this->assertStringContainsString('valid Shopify store URL', $e->getMessage());
            }
        }
    }

    public function test_request_token_success()
    {
        Http::fake([
            'https://zenmart.myshopify.com/admin/oauth/access_token' => Http::response([
                'access_token' => 'tok_test_value',
                'scope' => 'read_products',
                'expires_in' => 86399,
            ], 200),
        ]);
        $auth = new ShopifyAuthService();
        $out = $auth->requestToken('zenmart.myshopify.com', 'cid', 'csecret');
        $this->assertSame('tok_test_value', $out['access_token']);
        $this->assertSame('read_products', $out['scope']);
        $this->assertSame(86399, $out['expires_in']);
    }

    public function test_request_token_shop_not_permitted()
    {
        Http::fake([
            '*' => Http::response(['error' => 'shop_not_permitted'], 403),
        ]);
        $this->expectExceptionMessage('same Shopify organization');
        (new ShopifyAuthService())->requestToken('zenmart.myshopify.com', 'cid', 'csecret');
    }

    public function test_request_token_failure_is_generic()
    {
        Http::fake([
            '*' => Http::response(['error' => 'invalid_client', 'error_description' => 'bad secret xyz'], 401),
        ]);
        $this->expectExceptionMessage('Unable to authenticate with Shopify');
        (new ShopifyAuthService())->requestToken('zenmart.myshopify.com', 'cid', 'csecret');
    }
}
