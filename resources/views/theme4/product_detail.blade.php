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
