@if(isset($clients) && count($clients) > 0)
@php
    $clientSlides = $clients->filter(function ($client) {
        return !empty($client->image);
    });
@endphp
@if($clientSlides->count() > 0)

<section class="happy-clients-section" aria-label="Happy clients testimonials">
    <div class="container">
        <div class="happy-clients-header">
            <h2 class="happy-clients-title">HAPPY CLIENTS</h2>
            <span class="happy-clients-line" aria-hidden="true"></span>
        </div>
        <div class="slider-wrapper" data-slider="clients" data-visible-desktop="6" data-visible-mobile="2">
            <button type="button" class="product-slide-btn left prev" aria-label="Previous client">&#10094;</button>
            <div class="products-section">
                @foreach($clientSlides as $client)
                <div class="single-product-section happy-clients-card">
                    <div class="happy-clients-item">
                        <img
                            src="{{ client_image_url($client->image) }}"
                                alt="{{ $client->label ?: 'Happy client' }}"
                            width="112"
                            height="200"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="product-slide-btn right next" aria-label="Next client">&#10095;</button>
        </div>
    </div>
</section>
@endif
@endif

