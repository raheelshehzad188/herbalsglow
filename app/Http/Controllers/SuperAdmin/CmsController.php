<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasApp;
use App\Models\SaasFaq;
use App\Models\SaasFeature;
use App\Models\SaasPlan;
use App\Models\SaasSetting;
use App\Models\SaasTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function website()
    {
        return view('superadmin.cms.website', [
            'settings' => SaasSetting::allCached(),
        ]);
    }

    public function websiteSave(Request $request)
    {
        $keys = [
            'site_name','logo_text','nav_signin','nav_start',
            'hero_title','hero_subtitle','hero_body','hero_cta_primary','hero_cta_secondary','hero_image',
            'badge_1','badge_2','badge_3',
            'stat_trial','stat_trial_label','stat_commission','stat_commission_label',
            'stat_currency','stat_currency_label','stat_support','stat_support_label',
            'local_heading','dashboard_heading','channels_heading',
            'pricing_heading','pricing_sub','tools_heading',
            'apps_heading','apps_sub','themes_heading','themes_sub',
            'faq_heading','final_heading','final_cta_primary','final_cta_secondary',
            'footer_about','support_email','whatsapp','products_heading','products_sub',
        ];
        $pairs = [];
        foreach ($keys as $key) {
            $pairs[$key] = (string) $request->input($key, '');
        }
        SaasSetting::putMany($pairs);

        return back()->with(['msg' => 'Website content saved. View it on /platform', 'msg_type' => 'success']);
    }

    public function plans()
    {
        return view('superadmin.cms.list', $this->listData('plans', SaasPlan::orderBy('sort')->get()));
    }

    public function themes()
    {
        return view('superadmin.cms.list', $this->listData('themes', SaasTheme::orderBy('sort')->get()));
    }

    public function apps()
    {
        return view('superadmin.cms.list', $this->listData('apps', SaasApp::orderBy('sort')->get()));
    }

    public function faqs()
    {
        return view('superadmin.cms.list', $this->listData('faqs', SaasFaq::orderBy('sort')->get()));
    }

    public function features()
    {
        return view('superadmin.cms.list', $this->listData('features', SaasFeature::orderBy('section')->orderBy('sort')->get()));
    }

    public function form($type, $id = null)
    {
        $model = $this->model($type);
        $item = $id ? $model::findOrFail($id) : null;
        return view('superadmin.cms.form', [
            'type' => $type,
            'item' => $item,
            'fields' => $this->fields($type),
            'label' => $this->label($type),
        ]);
    }

    public function save(Request $request, $type, $id = null)
    {
        $model = $this->model($type);
        $item = $id ? $model::findOrFail($id) : new $model();
        foreach ($this->fields($type) as $name => $meta) {
            if ($name === 'slug' && !$request->filled('slug') && $request->filled('name')) {
                $item->slug = Str::slug($request->name);
                continue;
            }
            if (($meta['type'] ?? '') === 'checkbox') {
                $item->{$name} = $request->boolean($name);
                continue;
            }
            $item->{$name} = $request->input($name);
        }
        if (empty($item->slug) && $request->filled('name')) {
            $item->slug = Str::slug($request->name);
        }
        $item->save();
        return redirect()->route('superadmin.cms.'.$type)->with(['msg' => 'Saved.', 'msg_type' => 'success']);
    }

    public function delete($type, $id)
    {
        $model = $this->model($type);
        $model::findOrFail($id)->delete();
        return back()->with(['msg' => 'Deleted.', 'msg_type' => 'success']);
    }

    protected function model($type)
    {
        $map = [
            'plans' => SaasPlan::class,
            'themes' => SaasTheme::class,
            'apps' => SaasApp::class,
            'faqs' => SaasFaq::class,
            'features' => SaasFeature::class,
        ];
        abort_unless(isset($map[$type]), 404);
        return $map[$type];
    }

    protected function label($type)
    {
        return [
            'plans' => 'Plans / products',
            'themes' => 'Theme gallery',
            'apps' => 'Apps',
            'faqs' => 'FAQs',
            'features' => 'Feature cards',
        ][$type] ?? $type;
    }

    protected function listData($type, $items)
    {
        return [
            'type' => $type,
            'items' => $items,
            'label' => $this->label($type),
            'columns' => $this->columns($type),
        ];
    }

    protected function columns($type)
    {
        return [
            'plans' => ['name' => 'Name', 'price_label' => 'Price', 'highlight' => 'Featured', 'status' => 'Status'],
            'themes' => ['name' => 'Theme', 'category' => 'Category', 'status' => 'Status'],
            'apps' => ['name' => 'App', 'category' => 'Category', 'status' => 'Status'],
            'faqs' => ['question' => 'Question', 'status' => 'Status'],
            'features' => ['section' => 'Section', 'title' => 'Title', 'status' => 'Status'],
        ][$type];
    }

    protected function fields($type)
    {
        $commonStatus = ['status' => ['label' => 'Active', 'type' => 'checkbox']];
        $map = [
            'plans' => [
                'name' => ['label' => 'Name', 'type' => 'text'],
                'slug' => ['label' => 'Slug', 'type' => 'text'],
                'audience' => ['label' => 'Audience line', 'type' => 'text'],
                'price_label' => ['label' => 'Price label', 'type' => 'text'],
                'price_amount' => ['label' => 'Price amount (number)', 'type' => 'number'],
                'highlight' => ['label' => 'Highlight this plan', 'type' => 'checkbox'],
                'features' => ['label' => 'Features (one per line)', 'type' => 'textarea'],
                'button_text' => ['label' => 'Button text', 'type' => 'text'],
                'sort' => ['label' => 'Sort', 'type' => 'number'],
            ] + $commonStatus,
            'themes' => [
                'name' => ['label' => 'Name', 'type' => 'text'],
                'slug' => ['label' => 'Slug', 'type' => 'text'],
                'category' => ['label' => 'Category', 'type' => 'text'],
                'description' => ['label' => 'Description', 'type' => 'textarea'],
                'image' => ['label' => 'Image URL', 'type' => 'text'],
                'demo_url' => ['label' => 'Demo URL', 'type' => 'text'],
                'engine_theme' => ['label' => 'Store engine theme (1-3)', 'type' => 'number'],
                'sort' => ['label' => 'Sort', 'type' => 'number'],
            ] + $commonStatus,
            'apps' => [
                'name' => ['label' => 'Name', 'type' => 'text'],
                'slug' => ['label' => 'Slug', 'type' => 'text'],
                'category' => ['label' => 'Category', 'type' => 'text'],
                'description' => ['label' => 'Description', 'type' => 'textarea'],
                'icon' => ['label' => 'Icon / letter', 'type' => 'text'],
                'color' => ['label' => 'Color', 'type' => 'text'],
                'sort' => ['label' => 'Sort', 'type' => 'number'],
            ] + $commonStatus,
            'faqs' => [
                'question' => ['label' => 'Question', 'type' => 'text'],
                'answer' => ['label' => 'Answer', 'type' => 'textarea'],
                'sort' => ['label' => 'Sort', 'type' => 'number'],
            ] + $commonStatus,
            'features' => [
                'section' => ['label' => 'Section (local, dashboard, tools)', 'type' => 'text'],
                'title' => ['label' => 'Title', 'type' => 'text'],
                'body' => ['label' => 'Body', 'type' => 'textarea'],
                'icon' => ['label' => 'Icon key', 'type' => 'text'],
                'sort' => ['label' => 'Sort', 'type' => 'number'],
            ] + $commonStatus,
        ];
        return $map[$type];
    }
}
