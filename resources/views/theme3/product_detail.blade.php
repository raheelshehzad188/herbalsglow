@extends($layout)

@section('content')
@php
    use App\Models\Admins\Gallerie;
    use Illuminate\Support\Str;

    $currency = env('CUR', 'Rs.');
    $listPrice = (float) ($item->selling_price ?? 0);
    $salePrice = (float) ($item->discount_price ?? 0);
    $hasDiscount = $salePrice > 0 && ($listPrice <= 0 || $salePrice < $listPrice);
    $finalPrice = $hasDiscount ? $salePrice : ($listPrice > 0 ? $listPrice : $salePrice);
    $discountPct = ($hasDiscount && $listPrice > 0) ? round((($listPrice - $salePrice) / $listPrice) * 100) : 0;
    $partNumber = $item->sku ?: ($item->product_code ?: ('P' . $item->id));
    $galleryItems = $galleries ?? Gallerie::where('product_id', $item->id)->get();
    $productImages = collect([$item->image_one])
        ->merge($galleryItems->pluck('photo'))
        ->filter()
        ->unique()
        ->values();
    $fallbackImage = ($assets_url ?? url('/theme3/')) . 'img/solo.webp';
    $avgRate = ($rcount ?? 0) > 0 && isset($rating) && count($rating)
        ? (float) collect($rating)->avg('rate')
        : 0;
    $maxQty = max(1, min(10, (int) ($item->product_quantity ?? $item->stock ?? 10)));
    $shortDescription = $item->short_discriiption ?? $item->short_description ?? '';
    $ratingTitle = number_format($avgRate, 1) . '/5 - ' . number_format($rcount ?? 0) . ' Reviews';
    $productTags = collect(explode(',', (string) ($item->tags ?? '')))
        ->map(function ($tag) { return trim($tag); })
        ->filter()
        ->values();
    $hasOverview = !empty($item->product_details) || (!empty($faq) && count($faq) > 0);
    $reviewCount = $rcount ?? 0;
    $starPath = 'M12.2328 18.5589L12.0001 18.4366L11.7674 18.5589L5.61072 21.796L6.78655 14.9398L6.83098 14.6807L6.64276 14.4972L1.66182 9.6416L8.54528 8.64129L8.80542 8.60349L8.92175 8.36776L12.0001 2.12985L15.0784 8.36776L15.1947 8.60349L15.4549 8.64129L22.3383 9.6416L17.3574 14.4972L17.1692 14.6807L17.2136 14.9398L18.3894 21.796L12.2328 18.5589Z';
@endphp

<link rel="stylesheet" href="https://s3.images-iherb.com/static/catalog/desktop/iherb/product-details.min_9b45d0a1cb72300d123c10690b0a3ca2.css">
<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-detail.css') }}">
<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-slider.css') }}">

<div class="t3-pdp-page">
    <div class="container-fluid">
        <div id="pagetitlewrapper">
            <div id="breadCrumbs">
                <a href="{{ url('/') }}">Home</a>
                @if($cate)
                    <a href="{{ url('/' . $cate->slug) }}">{{ $cate->name }}</a>
                @endif
                @if(!empty($sub_cat))
                    <a href="{{ url('/' . $sub_cat->slug) }}">{{ $sub_cat->name }}</a>
                @endif
                <span class="last">{{ $item->product_name }}</span>
            </div>
        </div>
    </div>

    <div class="product-detail-container ga-product" data-product-id="{{ $item->id }}">
        <section class="product-summary-main">
            <section class="image-fixed hidden-window-sm">
                <div class="image-container">
                    <div id="product-image">
                        <div class="image-main-container">
                            <div class="product-summary-image-main">
                                <div class="product-summary-image-container">
                                    <div class="product-summary-image product-easyzoom">
                                        <img id="iherb-product-image"
                                             src="{{ img_url($productImages->first()) }}"
                                             width="400"
                                             height="400"
                                             alt="{{ $item->product_name }}"
                                             onerror="this.src='{{ $fallbackImage }}'">
                                    </div>
                                </div>
                            </div>

                            @if($productImages->count() > 1)
                                <div class="thumbnail-container-wrapper">
                                    <div class="thumbnail-container-scroll">
                                        <div class="thumbnail-container">
                                            @foreach($productImages as $imgIndex => $image)
                                                <button type="button"
                                                        class="thumbnail-item {{ $imgIndex === 0 ? 'selected' : '' }}"
                                                        aria-label="Product image {{ $imgIndex + 1 }}"
                                                        aria-pressed="{{ $imgIndex === 0 ? 'true' : 'false' }}">
                                                    <img src="{{ img_url($image) }}"
                                                         alt=""
                                                         width="72"
                                                         height="72"
                                                         data-large-img="{{ img_url($image) }}"
                                                         onerror="this.src='{{ $fallbackImage }}'">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <div style="flex-grow: 1">
                <section class="column product-description-title">
                    <div id="product-summary-header">
                        <h1 id="name" data-testid="product-name">{{ $item->product_name }}</h1>

                        @if($brand)
                            <div id="brand">
                                <bdi>By</bdi>
                                <a href="{{ url('/brand/' . $brand->slug) }}" data-testid="product-brand-link">
                                    <span><bdi>{{ $brand->name }}</bdi></span>
                                </a>
                            </div>
                        @endif

                        @if(($rcount ?? 0) > 0)
                            <div class="product-review-summary-v2">
                                @include('theme3.partials.product-stars', [
                                    'rate' => $avgRate,
                                    'count' => $rcount,
                                    'reviewUrl' => '#tab-reviews',
                                    'ratingTitle' => $ratingTitle,
                                ])
                            </div>
                        @endif

                        @if($shortDescription)
                            <div class="t3-pdp-short-desc" style="margin-top:12px;color:#666;">{!! $shortDescription !!}</div>
                        @endif
                    </div>
                </section>

                <div class="image-container hidden-window-lg hidden-window-md">
                    <div id="product-image">
                        <div class="image-main-container">
                            <div class="product-summary-image-main">
                                <div class="product-summary-image-container">
                                    <div class="product-summary-image">
                                        <img src="{{ img_url($productImages->first()) }}"
                                             width="400"
                                             height="400"
                                             alt="{{ $item->product_name }}"
                                             onerror="this.src='{{ $fallbackImage }}'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="column action-fixed">
                    <div id="product-action">
                        <div class="product-action-container">
                            <section data-pricing class="pricing-wrapper pricing">
                                <section id="product-price">
                                    @if($hasDiscount)
                                        <section class="original-price-wrapper original-price-config show">
                                            <div class="list-price-content">
                                                <span class="list-price">{{ $currency }}{{ number_format($listPrice, 2) }}</span>
                                            </div>
                                        </section>
                                        <div class="strike-through-price-wrapper strike-through-config show">
                                            <div class="discount-price-wrapper">
                                                <div class="discount-price-content">
                                                    <b class="discount-price">{{ $currency }}{{ number_format($finalPrice, 2) }}</b>
                                                    <span class="percent-off">({{ $discountPct }}% off)</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <section class="original-price-wrapper original-price-config show">
                                            <div class="list-price-content">
                                                <span class="list-price">{{ $currency }}{{ number_format($finalPrice, 2) }}</span>
                                            </div>
                                        </section>
                                    @endif
                                </section>
                            </section>

                            <section id="btn-add-to-cart" class="row button-container">
                                <div class="col-xs-24">
                                    <div class="t3-pdp-actions">
                                        <div class="t3-pdp-qty">
                                            <button type="button" data-qty-minus aria-label="Decrease quantity">−</button>
                                            <input id="t3-pdp-qty" type="number" min="1" max="{{ $maxQty }}" value="1" aria-label="Quantity">
                                            <button type="button" data-qty-plus aria-label="Increase quantity">+</button>
                                        </div>
                                        <button type="button"
                                                class="btn btn-primary btn-block btn-lg btn-add-to-cart gtm-add-to-cart"
                                                name="AddToCart"
                                                data-product-id="{{ $item->id }}"
                                                data-quantity="1">
                                            <strong>Add to Cart</strong>
                                        </button>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </div>

    <div class="container-fluid">
        <section class="t3-pdp-tabs" id="product-tabs">
            <nav class="t3-pdp-tabs__nav" role="tablist" aria-label="Product information">
                @if($hasOverview)
                    <button type="button"
                            class="t3-pdp-tabs__btn is-active"
                            role="tab"
                            data-tab="overview"
                            aria-selected="true"
                            aria-controls="tab-overview">
                        Overview
                    </button>
                @endif
                <button type="button"
                        class="t3-pdp-tabs__btn {{ !$hasOverview ? 'is-active' : '' }}"
                        role="tab"
                        data-tab="specs"
                        aria-selected="{{ !$hasOverview ? 'true' : 'false' }}"
                        aria-controls="tab-specs">
                    Specifications
                </button>
                <button type="button"
                        class="t3-pdp-tabs__btn"
                        role="tab"
                        data-tab="reviews"
                        aria-selected="false"
                        aria-controls="tab-reviews"
                        id="product-reviews">
                    Reviews{{ $reviewCount > 0 ? ' (' . $reviewCount . ')' : '' }}
                </button>
                @if($productTags->count() > 0)
                    <button type="button"
                            class="t3-pdp-tabs__btn"
                            role="tab"
                            data-tab="tags"
                            aria-selected="false"
                            aria-controls="tab-tags">
                        Tags ({{ $productTags->count() }})
                    </button>
                @endif
            </nav>

            @if($hasOverview)
                <div class="t3-pdp-tabs__panel is-active"
                     data-panel="overview"
                     id="tab-overview"
                     role="tabpanel">
                    @if(!empty($item->product_details))
                        <h3>Product Overview</h3>
                        <div class="t3-pdp-tabs__content">{!! $item->product_details !!}</div>
                    @endif

                    @if(!empty($faq) && count($faq) > 0)
                        <h3 style="margin-top:{{ !empty($item->product_details) ? '28px' : '0' }};">FAQs</h3>
                        @foreach($faq as $faqItem)
                            <div class="t3-pdp-faq-item">
                                <strong>{{ $faqItem->que ?? $faqItem->question ?? '' }}</strong>
                                @if(!empty($faqItem->ans ?? $faqItem->answer ?? null))
                                    <p>{!! $faqItem->ans ?? $faqItem->answer !!}</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            <div class="t3-pdp-tabs__panel {{ !$hasOverview ? 'is-active' : '' }}"
                 data-panel="specs"
                 id="tab-specs"
                 role="tabpanel"
                 {{ $hasOverview ? 'hidden' : '' }}>
                <h3>Specifications</h3>
                <ul class="t3-pdp-specs">
                    <li><span>Product code</span><span>{{ $partNumber }}</span></li>
                    @if(!empty($item->sku) && $item->sku !== $partNumber)
                        <li><span>SKU</span><span>{{ $item->sku }}</span></li>
                    @endif
                    @if($brand)
                        <li><span>Brand</span><span>{{ $brand->name }}</span></li>
                    @endif
                    @if($cate)
                        <li><span>Category</span><span>{{ $cate->name }}</span></li>
                    @endif
                    @if(!empty($sub_cat))
                        <li><span>Subcategory</span><span>{{ $sub_cat->name }}</span></li>
                    @endif
                    @if(!empty($item->product_quantity))
                        <li><span>Stock</span><span>{{ $item->product_quantity }}</span></li>
                    @endif
                </ul>
            </div>

            <div class="t3-pdp-tabs__panel"
                 data-panel="reviews"
                 id="tab-reviews"
                 role="tabpanel"
                 hidden>
                @if(session('message'))
                    <div class="t3-pdp-alert t3-pdp-alert--success">{{ session('message') }}</div>
                @endif

                <div class="t3-pdp-reviews-grid">
                    <div>
                        <h3>Customer Reviews{{ $reviewCount > 0 ? ' (' . $reviewCount . ')' : '' }}</h3>
                        @if(isset($rating) && count($rating) > 0)
                            @foreach($rating as $review)
                                <div class="t3-pdp-review-card">
                                    <div class="t3-pdp-review-card__head">
                                        <span class="t3-pdp-review-card__name">{{ $review->name ?? 'Customer' }}</span>
                                        @if(!empty($review->rate))
                                            <span class="t3-pdp-review-card__stars" aria-label="{{ (int) $review->rate }} out of 5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="{{ $i <= (int) $review->rate ? 'full' : 'empty' }}" width="24" height="24" viewBox="0 0 24 24">
                                                        <path d="{{ $starPath }}"></path>
                                                    </svg>
                                                @endfor
                                            </span>
                                        @endif
                                    </div>
                                    @if(!empty($review->review))
                                        <p>{{ $review->review }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="t3-pdp-empty">No reviews yet. Be the first to review this product.</p>
                        @endif
                    </div>

                    <div>
                        <h3>Write a Review</h3>
                        <form action="{{ url('/rating_submit') }}" method="POST" class="t3-pdp-review-form">
                            @csrf
                            <input type="hidden" name="pid" value="{{ $item->id }}">
                            <div class="form-group">
                                <label for="t3-review-text">Your Review *</label>
                                <textarea id="t3-review-text" name="review" class="form-control" required placeholder="Share your experience with this product..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="t3-review-name">Your Name *</label>
                                <input type="text" id="t3-review-name" name="name" class="form-control" required placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <label for="t3-review-email">Your Email *</label>
                                <input type="email" id="t3-review-email" name="email" class="form-control" required placeholder="your@email.com">
                            </div>
                            <div class="form-group">
                                <label for="t3-review-rating">Rating *</label>
                                <select id="t3-review-rating" name="rating" class="form-control" required>
                                    <option value="5">5 Stars — Excellent</option>
                                    <option value="4">4 Stars — Good</option>
                                    <option value="3">3 Stars — Average</option>
                                    <option value="2">2 Stars — Poor</option>
                                    <option value="1">1 Star — Very Bad</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-submit-review">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>

            @if($productTags->count() > 0)
                <div class="t3-pdp-tabs__panel"
                     data-panel="tags"
                     id="tab-tags"
                     role="tabpanel"
                     hidden>
                    <h3>Product Tags</h3>
                    <div class="t3-pdp-tags">
                        @foreach($productTags as $tag)
                            @php
                                $tagSlug = preg_replace('/\s+/', '-', $tag);
                            @endphp
                            <a class="t3-pdp-tag" href="{{ url('/tags/' . $tagSlug) }}">{{ $tag }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>

    @if(isset($rproducts) && count($rproducts) > 0)
        <div class="container-fluid t3-pdp-similar" style="margin-bottom: 24px">
            <section id="product-similar-items-to-consider" class="product-carousel">
                <div id="product-similar-items-to-consider-content" class="carousel-container">
                    <h2 class="title-container">Similar items to consider</h2>
                    <div class="carousel-container product-carousel home-module">
                        <div class="carousel">
                            <div id="carousel-product-similar"
                                 class="carousel slide iherb-carousel-items clearfix t3-product-carousel"
                                 data-interval="false">
                                <div class="carousel-inner product-carousels rounded-product-cells col-xs-24 col-md-24">
                                    @foreach($rproducts as $related)
                                        @php
                                            $v = $related;
                                            $productColClass = 'col-xs-24 col-md-12 col-lg-4';
                                        @endphp
                                        @include('theme3.product_box_new')
                                    @endforeach
                                </div>
                                <a class="left carousel-control" href="#" role="button" data-slider-prev aria-label="Previous products">
                                    <span class="scroll-icon scroll-l"><svg width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#icon-chevron-left" fill="#333333"></use></svg></span>
                                </a>
                                <a class="right carousel-control" href="#" role="button" data-slider-next aria-label="Next products">
                                    <span class="scroll-icon scroll-r"><svg width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#icon-chevron-right" fill="#333333"></use></svg></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
</div>

<script src="{{ asset('theme3/js/t3-product-detail.js') }}"></script>
<script src="{{ asset('theme3/js/t3-product-slider.js') }}"></script>
@endsection
