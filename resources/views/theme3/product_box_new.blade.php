@php
    use Illuminate\Support\Str;

    $ratingRows = DB::table('rating')->where('pid', '=', $v->id)->get();
    $ratingSum = 0;
    $ratingCount = $ratingRows->count();
    foreach ($ratingRows as $row) {
        $ratingSum += (int) $row->rate;
    }
    $rate = $ratingCount ? ($ratingSum / $ratingCount) : 0;

    $listPrice = (float) ($v->selling_price ?? 0);
    $salePrice = (float) ($v->discount_price ?? 0);
    $hasDiscount = $salePrice > 0 && $salePrice < $listPrice;
    $finalPrice = $hasDiscount ? $salePrice : $listPrice;

    $productUrl = url('/product/' . $v->slug);
    $imageUrl = img_url($v->image_one);
    $fallbackImage = ($assets_url ?? url('/theme3/')) . 'img/solo.webp';
    $partNumber = $v->sku ?: ($v->product_code ?: ('P' . $v->id));
    $currency = env('CUR', 'Rs.');
    $ratingTitle = number_format($rate, 1) . '/5 - ' . number_format($ratingCount) . ' Reviews';
    $productColClass = $productColClass ?? 'col-xs-8 col-md-6 col-lg-4';
@endphp

@once
<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-box.css') }}">
@endonce

<div data-pid="{{ $v->id }}"
     class="product {{ $productColClass }}"
     data-part-number="{{ $partNumber }}">
    <div class="product-inner">
        <div class="absolute-link-wrapper">
            <div class="product-image-wrapper">
                <a class="product-image-link product-link"
                   href="{{ $productUrl }}"
                   aria-label="{{ $v->product_name }}"
                   data-product-id="{{ $v->id }}"
                   data-part-number="{{ $partNumber }}">
                    <span class="product-image image-available">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $v->product_name }}"
                             title="{{ $v->product_name }}"
                             width="120"
                             height="120"
                             onerror="this.src='{{ $fallbackImage }}'">
                    </span>
                </a>

                <div class="form-add-to-cart add-to-cart-wrapper">
                    <button class="btn btn-primary btn-add-to-cart gtm-add-to-cart"
                            name="AddToCart"
                            type="button"
                            data-intermediary-close-event-exception="true"
                            data-product-id="{{ $v->id }}"
                            data-quantity="1">
                        <bdi>Add to Cart</bdi>
                    </button>
                </div>
            </div>

            <div class="product-title" title="{{ $v->product_name }}">
                <a class="product-title-link product-link"
                   href="{{ $productUrl }}"
                   data-product-id="{{ $v->id }}"
                   data-part-number="{{ $partNumber }}">
                    <bdi>{{ Str::limit($v->product_name, 80) }}</bdi>
                </a>
            </div>
        </div>

        @if(($alwaysShowRating ?? false) || $ratingCount > 0)
            @include('theme3.partials.product-stars', [
                'rate' => $rate,
                'count' => $ratingCount,
                'reviewUrl' => $productUrl,
                'ratingTitle' => $ratingTitle,
            ])
        @endif

        <a class="product-price-link product-link" href="{{ $productUrl }}" aria-label="{{ $v->product_name }} price">
            <div class="product-price">
                @if($hasDiscount)
                    <span class="price discount-red">
                        <bdi>{{ $currency }} {{ number_format($finalPrice) }}</bdi>
                    </span>
                    <span class="price-olp">
                        <bdi>{{ $currency }} {{ number_format($listPrice) }}</bdi>
                    </span>
                @else
                    <span class="price">
                        <bdi>{{ $currency }} {{ number_format($finalPrice) }}</bdi>
                    </span>
                @endif
            </div>
        </a>
    </div>
</div>
