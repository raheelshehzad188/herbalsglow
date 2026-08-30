<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShopifyImportRoutesTest extends TestCase
{
    public function test_import_data_requires_admin()
    {
        $this->get('/admin/import-data')->assertRedirect('admin/login');
        $this->post('/admin/import-data/shopify/connect')->assertRedirect('admin/login');
        $this->post('/admin/import-data/shopify/test')->assertRedirect('admin/login');
        $this->post('/admin/import-data/shopify/fetch-products')->assertRedirect('admin/login');
        $this->post('/admin/import-data/shopify/disconnect')->assertRedirect('admin/login');
        $this->get('/admin/import-data/shopify/status')->assertRedirect('admin/login');
        $this->post('/admin/import-data/tick')->assertRedirect('admin/login');
        $this->postJson('/admin/import-data/start')->assertRedirect('admin/login');
        $this->get('/admin/flush-data')->assertRedirect('admin/login');
        $this->post('/admin/flush-data')->assertRedirect('admin/login');
    }

    public function test_manual_token_route_is_gone()
    {
        $this->post('/admin/import-data/shopify/manual', [
            'shop_url' => 'demo.myshopify.com',
            'admin_api_token' => 'shpat_should_not_work',
        ])->assertStatus(404);
    }
}
