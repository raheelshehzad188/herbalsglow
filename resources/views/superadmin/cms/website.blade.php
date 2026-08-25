@extends('superadmin.layout')
@section('title', 'Website')
@section('page_title', 'Website content')
@section('page_subtitle', 'Headings, CTAs, hero image, footer — live on /platform')
@section('nav_website', 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-secondary" href="{{ platform_url() }}" target="_blank">View site</a>
@endsection
@section('content')
<form method="post" action="{{ route('superadmin.cms.website.save') }}">
    @csrf
    @php
        $groups = [
            'Brand' => ['site_name','logo_text','nav_signin','nav_start','support_email','whatsapp','footer_about'],
            'Home hero' => ['hero_title','hero_subtitle','hero_body','hero_cta_primary','hero_cta_secondary','hero_image','badge_1','badge_2','badge_3'],
            'Stats bar' => ['stat_trial','stat_trial_label','stat_commission','stat_commission_label','stat_currency','stat_currency_label','stat_support','stat_support_label'],
            'Section headings' => ['local_heading','dashboard_heading','channels_heading','tools_heading','products_heading','products_sub','themes_heading','themes_sub','apps_heading','apps_sub','pricing_heading','pricing_sub','faq_heading','final_heading','final_cta_primary','final_cta_secondary'],
        ];
        $areas = ['hero_body','footer_about','products_sub','themes_sub','apps_sub','pricing_sub'];
    @endphp
    @foreach($groups as $title => $keys)
        <div class="sa-card">
            <h3>{{ $title }}</h3>
            <div class="sa-grid sa-grid-2">
                @foreach($keys as $key)
                    <div class="sa-field" @if(in_array($key, $areas)) style="grid-column:1/-1" @endif>
                        <label>{{ str_replace('_', ' ', $key) }}</label>
                        @if(in_array($key, $areas))
                            <textarea name="{{ $key }}" rows="3">{{ $settings[$key] ?? '' }}</textarea>
                        @else
                            <input type="text" name="{{ $key }}" value="{{ $settings[$key] ?? '' }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <button class="sa-btn sa-btn-primary" type="submit">Save website</button>
</form>
@endsection
