@if(isset($boxes) && count($boxes) > 0)
@php
    $boxIconMap = [
        'smile-o' => 'fa-regular fa-face-smile',
        'smile' => 'fa-regular fa-face-smile',
        'refresh' => 'fa-solid fa-arrows-rotate',
        'undo' => 'fa-solid fa-rotate-left',
        'money' => 'fa-solid fa-wallet',
        'credit-card' => 'fa-solid fa-credit-card',
        'truck' => 'fa-solid fa-truck-fast',
        'support' => 'fa-solid fa-headset',
        'clock-o' => 'fa-regular fa-clock',
        'shield' => 'fa-solid fa-shield-halved',
    ];
@endphp
<section class="home-boxes-section" aria-label="Store features">
    <div class="container">
        <div class="home-boxes-row">
            @foreach($boxes as $box)
            <div class="home-box-item">
                @if(!empty($box->icon))
                @php
                    $rawIcon = trim($box->icon);
                    $iconClass = $boxIconMap[$rawIcon] ?? (
                        str_contains($rawIcon, 'fa-')
                            ? $rawIcon
                            : 'fa-solid fa-' . str_replace('_', '-', $rawIcon)
                    );
                @endphp
                <div class="home-box-icon" aria-hidden="true">
                    <i class="{{ $iconClass }}"></i>
                </div>
                @endif
                @if(!empty($box->heading))
                <h3 class="home-box-title">{{ $box->heading }}</h3>
                @endif
                @if(!empty($box->txt))
                @php
                    $subtitleClass = 'home-box-subtitle' . (preg_match('/[a-z]/', $box->txt) ? ' is-sentence' : '');
                @endphp
                <p class="{{ $subtitleClass }}">{{ $box->txt }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
