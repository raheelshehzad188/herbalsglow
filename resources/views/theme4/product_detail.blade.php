@extends($layout)
@section('content')
@php
    use App\Models\Admins\Gallerie;
    $listPrice = (float) ($item->selling_price ?? 0);
    $salePrice = (float) ($item->discount_price ?? 0);
    $finalPrice = ($salePrice > 0 && ($listPrice <= 0 || $salePrice < $listPrice)) ? $salePrice : ($listPrice > 0 ? $listPrice : $salePrice);
    $galleryItems = Gallerie::where('product_id', $item->id)->get();
@endphp
<section class="product product-info t4-page footer-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <img src="{{ img_url($item->image_one) }}" alt="{{ $item->product_name }}" class="img-fluid">
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach($galleryItems as $g)
                        <img src="{{ img_url($g->photo) }}" alt="" style="width:80px;height:80px;object-fit:cover">
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <h1>{{ $item->product_name }}</h1>
                @php
                    $approvedReviews = $rating ?? collect();
                    $reviewCount = (int) ($rcount ?? $approvedReviews->count());
                    $avgRate = (float) ($rate ?? ($reviewCount ? $approvedReviews->avg('rate') : 0));
                    $avgStars = (int) round($avgRate);
                @endphp
                <div class="t4-review-summary mb-3">
                    <span class="t4-stars" aria-label="{{ number_format($avgRate, 1) }} out of 5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="{{ $i <= $avgStars ? '#FFA800' : '#E5E5E5' }}" xmlns="http://www.w3.org/2000/svg"><path d="M8 0l1.96 5.53h5.82l-4.7 3.42 1.96 5.53L8 11.06l-5.04 3.42 1.96-5.53-4.7-3.42h5.82z"/></svg>
                        @endfor
                    </span>
                    <a href="#t4-reviews">{{ $reviewCount }} {{ $reviewCount === 1 ? 'review' : 'reviews' }}</a>
                </div>
                <div class="price mb-3">
                    <span class="new-price">{{ format_amount($finalPrice) }}</span>
                    @if($listPrice > $finalPrice)
                        <span class="price-cut">{{ format_amount($listPrice) }}</span>
                    @endif
                </div>
                <div class="mb-3">{!! $item->short_discriiption ?? '' !!}</div>
                <div class="d-flex gap-2 align-items-center mb-3">
                    <input type="number" id="product-qty" value="1" min="1" style="width:80px" class="form-control">
                    <a href="javascript:void(0)" class="shop-btn" onclick="addToCart({{ $item->id }}, document.getElementById('product-qty').value)">Add to cart</a>
                    <a href="javascript:void(0)" class="shop-btn" onclick="addToCart({{ $item->id }}, document.getElementById('product-qty').value, 1)">Buy now</a>
                </div>
                <div class="no-style">{!! $item->product_details ?? '' !!}</div>
            </div>
        </div>

        <div class="t4-reviews" id="t4-reviews">
            <div class="section-title">
                <h5>Customer reviews</h5>
            </div>
            @if(session('message'))
                <div class="t4-review-flash {{ session('msg_type') === 'error' ? 'is-error' : 'is-success' }}">{{ session('message') }}</div>
            @endif

            @forelse($approvedReviews as $rev)
                <article class="t4-review-card">
                    <div class="t4-review-card__head">
                        <strong>{{ $rev->name }}</strong>
                        <span class="t4-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="{{ $i <= (int) $rev->rate ? '#FFA800' : '#E5E5E5' }}" xmlns="http://www.w3.org/2000/svg"><path d="M8 0l1.96 5.53h5.82l-4.7 3.42 1.96 5.53L8 11.06l-5.04 3.42 1.96-5.53-4.7-3.42h5.82z"/></svg>
                            @endfor
                        </span>
                        @if($rev->created_at)
                            <small>{{ \Carbon\Carbon::parse($rev->created_at)->format('d M Y') }}</small>
                        @endif
                    </div>
                    <p>{{ $rev->review }}</p>
                </article>
            @empty
                <p class="t4-review-empty">No reviews yet. Be the first to review this product.</p>
            @endforelse

            <div class="review-form t4-review-form">
                <h5 class="comment-title">Write a review</h5>
                <p class="t4-review-note">New reviews appear on this page after admin approval.</p>
                <form action="{{ url('/rating_submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pid" value="{{ $item->id }}">
                    <div class="review-inner-form">
                        <div class="review-form-name">
                            <label for="t4-review-name">Your name *</label>
                            <input class="form-control" id="t4-review-name" type="text" name="name" value="{{ old('name') }}" required maxlength="120" placeholder="Your name">
                        </div>
                        <div class="review-form-name">
                            <label for="t4-review-email">Your email *</label>
                            <input class="form-control" id="t4-review-email" type="email" name="email" value="{{ old('email') }}" required maxlength="191" placeholder="you@email.com">
                        </div>
                    </div>
                    <div class="review-form-name mb-3">
                        <label for="t4-review-rating">Rating *</label>
                        <select class="form-select" id="t4-review-rating" name="rating" required>
                            <option value="5" {{ (string) old('rating', '5') === '5' ? 'selected' : '' }}>5 stars — Excellent</option>
                            <option value="4" {{ (string) old('rating') === '4' ? 'selected' : '' }}>4 stars — Good</option>
                            <option value="3" {{ (string) old('rating') === '3' ? 'selected' : '' }}>3 stars — Average</option>
                            <option value="2" {{ (string) old('rating') === '2' ? 'selected' : '' }}>2 stars — Fair</option>
                            <option value="1" {{ (string) old('rating') === '1' ? 'selected' : '' }}>1 star — Poor</option>
                        </select>
                    </div>
                    <div class="review-form-name mb-3">
                        <label for="t4-review-text">Your review *</label>
                        <textarea class="form-control" id="t4-review-text" name="review" rows="4" required maxlength="2000" placeholder="Share your experience with this product">{{ old('review') }}</textarea>
                    </div>
                    @if($errors->any())
                        <p class="t4-review-flash is-error">{{ $errors->first() }}</p>
                    @endif
                    <div class="review-btn">
                        <button class="shop-btn" type="submit">Submit review</button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $productTags = collect(preg_split('/\s*,\s*/', (string) ($item->tags ?? '')))
                ->map(function ($tag) {
                    $tag = trim(preg_replace('/\s+/', ' ', (string) $tag));
                    return $tag;
                })
                ->filter()
                ->unique(function ($tag) {
                    return \Illuminate\Support\Str::slug($tag);
                })
                ->values();
        @endphp
        @if(theme_setting('products.show_tags', true) && $productTags->count())
        <div class="t4-tags" id="t4-tags">
            <div class="section-title">
                <h5>Product tags</h5>
            </div>
            <div class="t4-tags-list">
                @foreach($productTags as $tag)
                    <a class="t4-tag" href="{{ url('/tags/' . \Illuminate\Support\Str::slug($tag)) }}">{{ $tag }}</a>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($fproducts) && count($fproducts))
        <div class="section-title mt-5">
            <h5>Related products</h5>
        </div>
        <div class="row g-4">
            @foreach($fproducts as $v)
            <div class="col-xl-2 col-md-4">
                @include('theme4.product_box')
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
