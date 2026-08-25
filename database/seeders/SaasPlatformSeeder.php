<?php

namespace Database\Seeders;

use App\Models\SaasApp;
use App\Models\SaasFaq;
use App\Models\SaasFeature;
use App\Models\SaasPlan;
use App\Models\SaasSetting;
use App\Models\SaasTheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaasPlatformSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'site_name' => 'Herbals Glow',
            'logo_text' => 'Herbals Glow',
            'nav_signin' => 'Sign in',
            'nav_start' => 'Start free',
            'hero_title' => 'Start selling online.',
            'hero_subtitle' => '7-day free trial, no card needed.',
            'hero_body' => 'Launch an online store, restaurant ordering, or POS — one platform for Pakistani businesses. PKR pricing, local couriers, and Meta/TikTok built in.',
            'hero_cta_primary' => 'Start for free',
            'hero_cta_secondary' => 'Book a demo',
            'hero_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1400&q=80',
            'badge_1' => 'No hidden fees',
            'badge_2' => 'Quick setup',
            'badge_3' => '24/7 support',
            'stat_trial' => '7 days',
            'stat_trial_label' => 'Free trial',
            'stat_commission' => '0%',
            'stat_commission_label' => 'Order commission',
            'stat_currency' => 'PKR',
            'stat_currency_label' => 'Fixed monthly price',
            'stat_support' => 'Local',
            'stat_support_label' => 'Expert support',
            'local_heading' => 'International polish. Local by default.',
            'dashboard_heading' => 'Run the business without chasing information.',
            'channels_heading' => 'One platform. Three ways to sell.',
            'pricing_heading' => 'Start free. Pay only when you continue.',
            'pricing_sub' => 'Choose what your business needs. 7-day free trial and PKR-based pricing.',
            'tools_heading' => 'Everything needed to run your store.',
            'apps_heading' => 'Add what your business needs next.',
            'apps_sub' => 'Your store stays at the centre. Every app connects to the products, customers and orders you already manage.',
            'themes_heading' => 'Storefronts with a point of view.',
            'themes_sub' => 'Complete ecommerce designs built for mobile shopping, clear product discovery, and your own brand. All themes included with your store.',
            'faq_heading' => 'Clear answers before you start.',
            'final_heading' => 'Build the store your business deserves.',
            'final_cta_primary' => 'Start for free',
            'final_cta_secondary' => 'Contact support',
            'footer_about' => 'Omni-commerce platform for small brands, restaurants and sellers. Built for businesses in Pakistan.',
            'support_email' => 'hello@herbalsglow.test',
            'whatsapp' => '923000000000',
            'products_heading' => 'Choose what your business needs.',
            'products_sub' => '7-day free trial. PKR pricing. No commission on orders.',
        ];
        SaasSetting::putMany($settings);

        if (SaasPlan::count() === 0) {
            $plans = [
                ['Online Store', 'online-store', 'For ecommerce brands', 'Rs. 4,000 / month', 4000, 1, "Products, collections and inventory\nTheme editor with professional themes\nPayments & courier integrations\nMeta Pixel / conversion tracking\nCustom domain", 'Start 7-day free trial'],
                ['Restaurant Ordering', 'restaurant-ordering', 'For food businesses', 'Rs. 3,000 / month', 3000, 0, "Delivery, pickup and dine-in\nMenu management\nOrder alerts\nOpening hours control\nDelivery zones", 'Start 7-day free trial'],
                ['POS Software', 'pos-software', 'For shops and counters', 'Rs. 2,000 / month', 2000, 0, "Works offline, syncs on reconnect\nBarcode billing\nStock alerts\nThermal receipt printing\nCounter sales", 'Start 7-day free trial'],
            ];
            foreach ($plans as $i => $p) {
                SaasPlan::create([
                    'name' => $p[0], 'slug' => $p[1], 'audience' => $p[2],
                    'price_label' => $p[3], 'price_amount' => $p[4], 'highlight' => $p[5],
                    'features' => $p[6], 'button_text' => $p[7], 'sort' => $i + 1, 'status' => 1,
                ]);
            }
        }

        if (SaasTheme::count() === 0) {
            $themes = [
                ['Loom', 'Fashion', 'Ethnic and traditional women’s clothing.', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=80'],
                ['Meridian', 'Fashion', 'Minimalist men’s fashion with a clean grid.', 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?auto=format&fit=crop&w=900&q=80'],
                ['Voltage', 'Electronics', 'Dark, modern storefront for gadgets.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80'],
                ['Vista', 'Fashion', 'Lifestyle apparel with generous imagery.', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80'],
                ['Lucent', 'Food', 'Soft, airy layout for bakeries and groceries.', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80'],
                ['Foundry', 'Industrial', 'Professional look for hardware and supplies.', 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=900&q=80'],
                ['Fitment', 'Auto Parts', 'High-contrast design for automotive.', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80'],
                ['Remedy', 'Beauty', 'Clean cosmetics and skincare layout.', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=900&q=80'],
                ['Keepsake', 'Gifts', 'Warm storefront for gifts and hampers.', 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?auto=format&fit=crop&w=900&q=80'],
                ['Interior', 'Home', 'Spacious furniture and home decor theme.', 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=900&q=80'],
            ];
            foreach ($themes as $i => $t) {
                SaasTheme::create([
                    'name' => $t[0], 'slug' => Str::slug($t[0]), 'category' => $t[1],
                    'description' => $t[2], 'image' => $t[3], 'demo_url' => url('/?theme=3'),
                    'engine_theme' => 3, 'sort' => $i + 1, 'status' => 1,
                ]);
            }
        }

        if (SaasApp::count() === 0) {
            $apps = [
                ['Courier', 'Orders & Shipping', 'Ship, track and manage all in one place.', '🚚', '#0f766e'],
                ['Product Reviews', 'Marketing', 'Turn customer feedback into confidence.', '★', '#6d28d9'],
                ['WhatsApp Marketing', 'Marketing', 'Bring customers back to your store.', '💬', '#15803d'],
                ['Abandoned Checkout', 'Marketing', 'Recover orders that almost happened.', '↩', '#b45309'],
                ['Meta Catalogue', 'Sales', 'Keep Facebook and Instagram in sync.', 'f', '#1d4ed8'],
                ['Invoice & Receipt', 'Operations', 'Professional paperwork for every order.', '🧾', '#334155'],
                ['Product Bundles', 'Sales', 'Sell more products together.', '▦', '#0f172a'],
                ['Customer Loyalty', 'Marketing', 'Reward the customers who return.', '♡', '#be185d'],
                ['Search & Discovery', 'Store design', 'Help shoppers find the right product.', '⌕', '#0369a1'],
                ['COD Manager', 'Orders & Shipping', 'Keep cash-on-delivery orders under control.', '₨', '#065f46'],
                ['Stock Alerts', 'Operations', 'Know what needs attention before it runs out.', '⚠', '#9a3412'],
                ['Discount Engine', 'Sales', 'Run promotions without messy workarounds.', '%', '#1e3a8a'],
            ];
            foreach ($apps as $i => $a) {
                SaasApp::create([
                    'name' => $a[0], 'slug' => Str::slug($a[0]), 'category' => $a[1],
                    'description' => $a[2], 'icon' => $a[3], 'color' => $a[4],
                    'sort' => $i + 1, 'status' => 1,
                ]);
            }
        }

        if (SaasFaq::count() === 0) {
            $faqs = [
                ['Is it a different product for each shop?', 'No. One platform runs online store, restaurant ordering, and POS. You pick the product you need and can add more later.'],
                ['What about shipping?', 'Connect local couriers from the Apps directory. Tracking and COD tools sit next to your orders.'],
                ['Does the platform take commission?', 'No order commission. You pay a fixed monthly PKR price after the free trial.'],
                ['Can I use my own domain?', 'Yes. Point your domain to the store and it stays on your brand.'],
                ['Are Meta and TikTok included?', 'Yes. Connect catalogues, pixels and events from the store admin without pasting tokens into the storefront.'],
            ];
            foreach ($faqs as $i => $f) {
                SaasFaq::create(['question' => $f[0], 'answer' => $f[1], 'sort' => $i + 1, 'status' => 1]);
            }
        }

        if (SaasFeature::count() === 0) {
            $features = [
                ['local', 'Accept PK payments', 'JazzCash, EasyPaisa, cards and COD in one checkout.', 'pay'],
                ['local', 'Pakistan shipping', 'TCS, Leopards, PostEx and more from one connection.', 'ship'],
                ['local', 'PKR dashboard', 'Sales, orders and payouts in the currency you actually use.', 'pkr'],
                ['local', 'Support after sales', 'WhatsApp-first help for you and your customers.', 'chat'],
                ['dashboard', 'Manage inventory', 'Stock, collections and low-stock alerts in one place.', 'box'],
                ['dashboard', 'Deliver orders on time', 'Courier labels, COD and status without spreadsheet chaos.', 'truck'],
                ['dashboard', 'Save time with learning', 'Short guides for themes, apps and first sale.', 'book'],
                ['tools', 'Complete commerce CMS', 'Dashboard for products, orders and customers.', 'cms'],
                ['tools', 'Payments, Meta and couriers', 'Integration hub for the services you already use.', 'plug'],
                ['tools', 'Built for speed', 'Fast storefronts on local mobile networks.', 'bolt'],
                ['tools', 'Theme editor + 10 themes', 'Visual control over layout, logo and colours.', 'theme'],
                ['tools', 'Your brand, your domain', 'Custom domain on every paid plan.', 'globe'],
                ['tools', 'Local support', 'Talk to people who understand Pakistani retail.', 'support'],
            ];
            foreach ($features as $i => $f) {
                SaasFeature::create([
                    'section' => $f[0], 'title' => $f[1], 'body' => $f[2], 'icon' => $f[3],
                    'sort' => $i + 1, 'status' => 1,
                ]);
            }
        }
    }
}
