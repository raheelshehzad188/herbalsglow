<?php

$setting = DB::table('setting')->where('id', '=', '1')->first();
$rating = DB::table('rating')->where('pid', '=', $v->id)->get();
$sum = 0;
$count = 0;
foreach($rating as $k=> $p) {
    $count++;
    $sum = $sum + $p->rate;
}
$rate = 0;
if($count) {
    $rate = $sum / $count;
}

// Calculate discount percentage
$saling_price = intval($v->selling_price);
$discount_price = intval($v->discount_price);
$tot_price = $saling_price - $discount_price;
$perctg = 0;
if($saling_price) {
    $perctg = ($tot_price / $saling_price) * 100;
}
$final_percntage = ceil($perctg);
?>
@php 
    use Illuminate\Support\Str; 
@endphp
<div class="single-product-section product">
    <div class="product-image">
        <a href="{{ url('/product/' . $v->slug) }}">
            <img src="{{ img_url($v->image_one) }}" alt="{{ $v->product_name }}">
        </a>
    </div><!--product-image-->
    <div class="product-detail-section">
        <h3> 
            <a href="{{ url('/product/' . $v->slug) }}">
				{{ Str::limit($v->product_name, 40, '....') }}
			</a>
        </h3>
        <p> 
            <strong>Rs. {{ number_format($v->selling_price) }}</strong> 
            @if($v->discount_price > 0)
                <span class="discounted-price">Rs. {{ number_format($v->discount_price) }}</span>
            @endif
        </p>
    </div><!--product-detail-section-->
    <div class="product-rating-section">
        <div class="ratings">
            @php
                $rounded_rate = round($rate);
                $full_stars = min(5, max(0, $rounded_rate));
            @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $full_stars)
                    <i class="fa fa-star" style="color: #ffc107;"></i>
                @else
                    <i class="fa fa-star-o" style="color: #ccc;"></i>
                @endif
            @endfor
        </div><!--rating-->
        @if($v->discount_price > 0 && $final_percntage > 0)
            <div class="sale-button">
                <button>{{ $final_percntage }}% Off</button>
            </div><!--sale-button-->
        @endif
    </div><!--product-rating-section-->
</div><!--single-product-section-->
