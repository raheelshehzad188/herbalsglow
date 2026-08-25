@extends('platform.layout')
@section('title', ($settings['products_heading'] ?? 'Products').' — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <p class="sg-kicker">{{ $settings['products_sub'] ?? '' }}</p>
    <h1>{{ $settings['products_heading'] ?? 'Choose what your business needs.' }}</h1>
    <div class="sg-stats">
        <div class="sg-stat"><strong>{{ $settings['stat_trial'] ?? '7 days' }}</strong><span>{{ $settings['stat_trial_label'] ?? 'Free trial' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_commission'] ?? '0%' }}</strong><span>{{ $settings['stat_commission_label'] ?? 'Order commission' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_currency'] ?? 'PKR' }}</strong><span>{{ $settings['stat_currency_label'] ?? 'Fixed monthly price' }}</span></div>
        <div class="sg-stat"><strong>{{ $settings['stat_support'] ?? 'Local' }}</strong><span>{{ $settings['stat_support_label'] ?? 'Expert support' }}</span></div>
    </div>
    <div class="sg-grid-3">
        @foreach($plans as $plan)
            <article class="sg-card sg-plan {{ $plan->highlight ? 'is-hot' : '' }}">
                <h3>{{ $plan->name }}</h3>
                <p>{{ $plan->audience }}</p>
                <div class="price">{{ $plan->price_label }}</div>
                <ul>
                    @foreach($plan->featureList() as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
                <a class="sg-btn {{ $plan->highlight ? 'sg-btn-light' : 'sg-btn-dark' }}" href="{{ platform_url('start?plan='.$plan->slug) }}">{{ $plan->button_text }}</a>
            </article>
        @endforeach
    </div>
</section>
<section class="sg-wrap sg-section">
    <h2>{{ $settings['tools_heading'] ?? '' }}</h2>
    <div class="sg-grid-3">
        @foreach($toolFeatures as $item)
            <article class="sg-card"><h3>{{ $item->title }}</h3><p>{{ $item->body }}</p></article>
        @endforeach
    </div>
</section>
@endsection
