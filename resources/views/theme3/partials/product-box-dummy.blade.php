@php
    $product = $product ?? [];
    if (empty($product)) {
        return;
    }

    $pid = $product['id'] ?? '';
    $title = $product['title'] ?? '';
    $href = $product['href'] ?? '#';
    $image = $product['image'] ?? '';
    $discountPrice = $product['discount_price'] ?? '';
    $listPrice = $product['list_price'] ?? $discountPrice;
    $ratingTitle = $product['rating_title'] ?? '';
    $ratingCount = $product['rating_count'] ?? '';
    $reviewHref = $product['review_href'] ?? $href;
    $boxClass = $productBoxClass ?? 'product-box';

    $hasDiscount = $listPrice !== '' && $discountPrice !== '' && (float) $listPrice > (float) $discountPrice;
    $discountDisplay = $discountPrice !== '' ? '₨' . number_format((float) $discountPrice, 2) : '';
    $listDisplay = $listPrice !== '' ? '₨' . number_format((float) $listPrice, 2) : '';
@endphp

<div class="{{ $boxClass }} product" data-pid="{{ $pid }}">
    <a class="product-box__image-link" href="{{ $href }}">
        <img src="{{ $image }}" alt="{{ $title }}" width="120" height="120" loading="lazy">
    </a>
    <a class="product-box__title product-title" href="{{ $href }}">{{ $title }}</a>
    @if($ratingCount !== '' || $ratingTitle !== '')
    <div class="product-box__rating">
        <a href="{{ $reviewHref }}" title="{{ $ratingTitle }}">
            @if($ratingCount !== '')
                {{ $ratingCount }} reviews
            @else
                {{ $ratingTitle }}
            @endif
        </a>
    </div>
    @endif
    <div class="product-box__price product-price">
        @if($hasDiscount)
            <span class="price discount-red">{{ $discountDisplay }}</span>
            <span class="price-olp">{{ $listDisplay }}</span>
        @else
            <span class="price">{{ $discountDisplay }}</span>
        @endif
    </div>
</div>
