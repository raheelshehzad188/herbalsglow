@php
    $fullStars = min(5, max(0, (int) floor($rate ?? 0)));
    $starPath = 'M12.2328 18.5589L12.0001 18.4366L11.7674 18.5589L5.61072 21.796L6.78655 14.9398L6.83098 14.6807L6.64276 14.4972L1.66182 9.6416L8.54528 8.64129L8.80542 8.60349L8.92175 8.36776L12.0001 2.12985L15.0784 8.36776L15.1947 8.60349L15.4549 8.64129L22.3383 9.6416L17.3574 14.4972L17.1692 14.6807L17.2136 14.9398L18.3894 21.796L12.2328 18.5589Z';
    $ratingLabel = $ratingTitle ?? (number_format($rate ?? 0, 1) . '/5 - ' . number_format($count ?? 0) . ' Reviews');
@endphp
<div class="rating">
    <a class="stars scroll-to"
       href="{{ $reviewUrl }}"
       title="{{ $ratingLabel }}"
       aria-label="rating stars">
        @for($i = 1; $i <= 5; $i++)
            <svg class="stars-rating-v2 {{ $i <= $fullStars ? 'full' : '' }}" width="24" height="24" viewBox="0 0 24 24">
                <path d="{{ $starPath }}"></path>
            </svg>
        @endfor
    </a>
    @if(($count ?? 0) > 0)
        <a class="rating-count scroll-to"
           href="{{ $reviewUrl }}"
           title="{{ $ratingLabel }}"
           aria-label="rating count">
            <span>{{ number_format($count) }}</span>
        </a>
    @endif
</div>
