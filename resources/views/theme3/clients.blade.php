@if(isset($clients) && count($clients) > 0)
@php
    $clientSlides = $clients->filter(function ($client) {
        return !empty($client->image);
    });
@endphp
@if($clientSlides->count() > 0)
<section class="t3-section" style="background:var(--t3-bg,#f7f8f9);">
    <div class="container">
        <h2 class="t3-section-title">Trusted by <span>Our Clients</span></h2>
        <div class="t3-clients-row">
            @foreach($clientSlides as $client)
            <div class="t3-client-card">
                <img src="{{ client_image_url($client->image) }}"
                     alt="{{ $client->label ?: 'Client' }}"
                     loading="lazy">
                @if(!empty($client->label))
                <p style="margin:8px 0 0;font-weight:600;color:var(--t3-text);">{{ $client->label }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
