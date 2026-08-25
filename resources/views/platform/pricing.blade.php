@extends('platform.layout')
@section('title', 'Pricing — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <h1>{{ $settings['pricing_heading'] ?? 'Start free. Pay only when you continue.' }}</h1>
    <p class="sg-lead">{{ $settings['pricing_sub'] ?? '' }}</p>
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
    <h2>{{ $settings['faq_heading'] ?? '' }}</h2>
    <div class="sg-faq">
        @foreach($faqs as $faq)
            <details>
                <summary>{{ $faq->question }}</summary>
                <p>{{ $faq->answer }}</p>
            </details>
        @endforeach
    </div>
</section>
@endsection
