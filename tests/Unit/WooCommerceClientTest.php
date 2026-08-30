<?php

namespace Tests\Unit;

use App\Services\WooCommerce\WooCommerceClient;
use RuntimeException;
use Tests\TestCase;

class WooCommerceClientTest extends TestCase
{
    public function test_normalizes_allowed_store_urls()
    {
        $this->assertSame('https://yourstore.com', WooCommerceClient::normalizeShop('yourstore.com'));
        $this->assertSame('https://yourstore.com', WooCommerceClient::normalizeShop('https://yourstore.com'));
        $this->assertSame('https://yourstore.com', WooCommerceClient::normalizeShop('https://www.yourstore.com/'));
        $this->assertSame('https://yourstore.com', WooCommerceClient::normalizeShop('https://yourstore.com/wp-json/wc/v3'));
        $this->assertSame('https://shop.yourstore.com/store', WooCommerceClient::normalizeShop('https://shop.yourstore.com/store/'));
        $this->assertSame('http://demo.yourstore.com', WooCommerceClient::normalizeShop('http://demo.yourstore.com'));
    }

    public function test_uses_wordpress_slug_or_permalink()
    {
        $this->assertSame('virilo-max-capsules', WooCommerceClient::productSlug([
            'slug' => 'virilo-max-capsules',
            'permalink' => 'https://shop.example.com/product/other-slug/',
        ]));
        $this->assertSame('vitamin-discount-center-virilo-max-capsules', WooCommerceClient::productSlug([
            'slug' => '',
            'permalink' => 'https://shop.example.com/product/vitamin-discount-center-virilo-max-capsules/',
        ]));
        $this->assertSame('epimedyumlu-macun', WooCommerceClient::productSlug([
            'permalink' => 'https://shop.example.com/products/epimedyumlu-macun',
        ]));
        $this->assertNull(WooCommerceClient::productSlug([]));
    }

    public function test_rejects_invalid_store_urls()
    {
        $bad = ['javascript:alert(1)', 'localhost', 'http://127.0.0.1', '10.0.0.8', 'ftp://shop.example.com', ''];
        foreach ($bad as $url) {
            try {
                WooCommerceClient::normalizeShop($url);
                $this->fail('Accepted invalid store: ' . $url);
            } catch (RuntimeException $e) {
                $this->assertStringContainsString('valid WooCommerce store URL', $e->getMessage());
            }
        }
    }
}
