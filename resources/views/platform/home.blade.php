@extends('platform.layout')
@section('title', ($settings['hero_title'] ?? 'Start selling online.').' — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <div class="sg-hero-grid">
        <div>
            <p class="sg-kicker">{{ $settings['hero_subtitle'] ?? '' }}</p>
            <h1>{{ $settings['hero_title'] ?? 'Start selling online.' }}</h1>
            <p class="sg-lead">{{ $settings['hero_body'] ?? '' }}</p>
            <div class="sg-hero-actions">
                <a class="sg-btn sg-btn-dark" href="{{ platform_url('start') }}">{{ $settings['hero_cta_primary'] ?? 'Start for free' }}</a>
                <a class="sg-btn sg-btn-light" href="mailto:{{ $settings['support_email'] ?? '' }}">{{ $settings['hero_cta_secondary'] ?? 'Book a demo' }}</a>
            </div>
            <div class="sg-badges">
                <span>{{ $settings['badge_1'] ?? '' }}</span>
                <span>{{ $settings['badge_2'] ?? '' }}</span>
                <span>{{ $settings['badge_3'] ?? '' }}</span>
            </div>
        </div>
        <div class="sg-hero-visual">
            <img src="{{ $settings['hero_image'] ?? '' }}" alt="{{ $brand }} storefront">
        </div>
    </div>
</section>

<div class="sg-wrap">
    <div class="sg-stats">
        <div class="sg-stat"><strong>{{ $settings['stat_trial'] ?? '7 days' }}</strong><span>{{ $settings['stat_trial_label'] ?? 'Free trial' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_commission'] ?? '0%' }}</strong><span>{{ $settings['stat_commission_label'] ?? 'Order commission' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_currency'] ?? 'PKR' }}</strong><span>{{ $settings['stat_currency_label'] ?? 'Fixed monthly price' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_support'] ?? 'Local' }}</strong><span>{{ $settings['stat_support_label'] ?? 'Expert support' }}</span></div>
    </div>
</div>

<section class="sg-wrap sg-section">
    <h2>{{ $settings['local_heading'] ?? '' }}</h2>
    <div class="sg-grid-4">
        @foreach($localFeatures as $item)
            <article class="sg-card">
                <h3>{{ $item->title }}</h3>
                <p>{{ $item->body }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="sg-wrap sg-section">
    <div class="sg-grid-2" style="align-items:center">
        <div>
            <h2>{{ $settings['dashboard_heading'] ?? '' }}</h2>
            @foreach($dashFeatures as $item)
                <p><strong>{{ $item->title }}.</strong> {{ $item->body }}</p>
            @endforeach
        </div>
        <div class="sg-dash-shot">
            <div class="bar"></div>
            <div class="cols"><div class="side"></div><div class="main"></div></div>
        </div>
    </div>
</section>

<section class="sg-wrap sg-section">
    <h2>{{ $settings['channels_heading'] ?? '' }}</h2>
    <div class="sg-grid-3">
        @foreach($plans as $plan)
            <article class="sg-card sg-plan {{ $plan->highlight ? 'is-hot' : '' }}">
                <h3>{{ $plan->name }}</h3>
                <p>{{ $plan->audience }}</p>
                <div class="price">{{ $plan->price_label }}</div>
                <a class="sg-btn {{ $plan->highlight ? 'sg-btn-light' : 'sg-btn-dark' }}" href="{{ platform_url('start?plan='.$plan->slug) }}">{{ $plan->button_text }}</a>
            </article>
        @endforeach
    </div>
</section>

<section class="sg-wrap sg-section">
    <h2>{{ $settings['tools_heading'] ?? '' }}</h2>
    <div class="sg-grid-3">
        @foreach($toolFeatures as $item)
            <article class="sg-card">
                <h3>{{ $item->title }}</h3>
                <p>{{ $item->body }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="sg-wrap sg-section">
    <h2>{{ $settings['faq_heading'] ?? '' }}</h2>
    <div class="sg-faq">
        @foreach($faqs as $faq)
            <details {{ $loop->first ? 'open' : '' }}>
                <summary>{{ $faq->question }}</summary>
                <p>{{ $faq->answer }}</p>
            </details>
        @endforeach
    </div>
</section>
@endsection
