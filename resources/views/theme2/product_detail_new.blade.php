@extends($layout)

<?php

use App\Models\Admins\Category;
use App\Models\product;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Rating;
$faq= DB::table('pfaqs')->where('product_id',$item->id)->get();
$files = Gallerie::where('product_id',$item->id)->get();

function get_rating_html($rating) {
    $rating = (int)$rating;
    $html = '<div class="star-rating">';
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<img src="' . $assets_url . 'images/star-fill-large.svg" alt="star">';
        } else {
            $html .= '<img src="' . $assets_url . 'images/star-empty.svg" alt="star">';
        }
    }

    $html .= '</div>';
    return $html;
}

// Calculate average rating
$count = 0;
$totalrating = 0;
$getreview = DB::table('rating')->where('status', '1')->where('pid', $item->id)->orderby('id', 'desc')->get();
$countcustomer = DB::table('rating')->where('status', '1')->where('pid', $item->id)->count();
if($countcustomer != 0 && $getreview){
    foreach ($getreview as $avg){
        $count = $count + $avg->rate;
    }
    $totalrating = $count / $countcustomer;
    $finalresult = round($totalrating);
}

// Calculate discount percentage
$discount_percentage = 0;
if($item->selling_price > 0 && $item->discount_price > 0) {
    $discount_percentage = round((($item->selling_price - $item->discount_price) / $item->selling_price) * 100);
}

// Get related products
$rproducts = product::where('category_id','=',$item->category_id)->where('id','!=',$item->id)->limit(8)->get();

// Get settings for WhatsApp
$setting = DB::table('setting')->where('id', '=', '1')->first();
?>

@section('content')
<div class="content-indicator">
    <div class="container">
        <div class="inside-content-indicator">
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                @if($cate)
                <li><a href="{{ url('/category/'.$cate->slug) }}">{{ $cate->name }}</a></li>
                @endif
                @if($sub_cat)
                <li><a href="{{ url('/subcategory/'.$sub_cat->slug) }}">{{ $sub_cat->name }}</a></li>
                @endif
            </ul>
        </div><!--inside-content-indicator-->
    </div><!--container-->
</div><!--content-indicator-->

<!--single-page-->
<div class="single-page">
    <div class="container">
        <div class="inside-single-page">
            <div class="single-page-product-image">
                <img id="mainProductImage" src="{{ img_url($item->image_one) }}" alt="{{$item->product_name}}">
                
                <div class="single-page-product-image-section">
                    <img src="{{ img_url($item->image_one) }}" alt="thumb 1" onclick="changeImage(this)">
                    @foreach($files as $k=> $v)
                    <img src="{{$v->photo}}" alt="thumb {{$k+2}}" onclick="changeImage(this)">
                    @endforeach
                </div>
            </div>
            <div class="single-page-product-details">
                <h1>{{$item->product_name}}</h1>
                @if($brand)
                <h2><a href="{{ url('/brand/'.$brand->slug) }}">{{ $brand->name }}</a></h2>
                @endif
                <div class="rating-media-single-page">
                    <div class="rating-review">
                        {!! get_rating_html($finalresult) !!}
                        <div class="rating-reviews-btn">
                            <button>({{$rcount}} Reviews)</button>
                        </div>
                    </div><!--rating-media-single-page-->
                    <div class="single-page-media">
                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/product/'.$item->slug)) }}" target="_blank"><img src="{{ $assets_url }}images/facebook-icon.svg"></a></li>
                            <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/product/'.$item->slug)) }}&text={{ urlencode($item->product_name) }}" target="_blank"><img src="{{ $assets_url }}images/twitter-icon-large.svg"></a></li>
                            <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/product/'.$item->slug)) }}" target="_blank"><img src="{{ $assets_url }}images/linkedin-icon-01.svg"></a></li>
                            <li><a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url('/product/'.$item->slug)) }}&media={{ urlencode(img_url($item->image_one)) }}&description={{ urlencode($item->product_name) }}" target="_blank"><img src="{{ $assets_url }}images/pinterest-icon-large.svg"></a></li>
                            <li><a href="https://api.whatsapp.com/send?text={{ urlencode($item->product_name . ' - ' . url('/product/'.$item->slug)) }}" target="_blank"><img src="{{ $assets_url }}images/whatsapp-icon-large.svg"></a></li> 
                       </ul>
                    </div><!--single-page-media-->
                </div><!--rating-media-single-page-->
                @if($item->selling_price > $item->discount_price)
                <div class="was-price">
                    <span class="was-span">Was:</span>
                    <span class="discounted-span">Rs: {{$item->selling_price}}</span>
                </div>
                @endif
                <div class="now-price">
                    <span class="now-span">Now:</span>
                    <span class="now-price-span">Rs: {{$item->discount_price}}</span>
                </div>
                @if($discount_percentage > 0)
                <div class="savings">
                    <span class="savings-span">You Save:</span>
                    <span class="savings-price-span">Rs: {{$item->selling_price - $item->discount_price}}</span><span class="savings-price-of-span">{{$discount_percentage}}% Off</span>
                </div>
                @endif
                <div class="quantity-selector" style="margin: 20px 0;">
                    <label for="product-qty" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Quantity:</label>
                    <div class="qty-controls" style="display: flex; align-items: center; gap: 10px; max-width: 200px;">
                        <button type="button" class="qty-minus-btn" id="qty-minus" style="width: 45px; height: 45px; border: 2px solid #ddd; background: #fff; cursor: pointer; font-size: 22px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #333; transition: all 0.3s ease; font-weight: bold;">-</button>
                        <input type="number" id="product-qty" name="qty" value="1" min="1" max="{{$item->product_quantity ?? 999}}" style="width: 90px; height: 45px; text-align: center; border: 2px solid #ddd; border-radius: 4px; font-size: 18px; font-weight: 600; color: #333; outline: none;">
                        <button type="button" class="qty-plus-btn" id="qty-plus" style="width: 45px; height: 45px; border: 2px solid #ddd; background: #fff; cursor: pointer; font-size: 22px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #333; transition: all 0.3s ease; font-weight: bold;">+</button>
                    </div>
                    @if($item->product_quantity)
                    <small style="display: block; margin-top: 8px; color: #666; font-size: 13px;">Available: <strong>{{$item->product_quantity}}</strong> units</small>
                    @endif
                </div>
                <div class="buy-cart-buttons">
                    <a href="#" class="add-to-cart-item1" id="{{$item->id}}" @if(!$item->status)disabled="true"@endif>
                        <button class="buy-now-btn">BUY NOW</button>
                    </a>
                    <a href="#" class="add-to-cart" id="{{$item->id}}" @if(!$item->status)disabled="true"@endif>
                        <button class="add-to-cart-btn">ADD TO CART</button>
                    </a>    
                </div>
                <div class="border-bottom-single-page"></div>
                <h3>{{$item->product_name}} Overview</h3>
                <p>{!! $item->short_discriiption !!}</p>
            </div><!--single-page-product-details-->
            <div class="warrenty-detail-single-pg">
                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/auth.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>Warranty</h3>
                        <p>7 Days Check Warranty</p>
                    </div>
                </div>
                
                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/delivery.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>Delivery</h3>
                        <p>Expected Delivery | 3-6 Days (Pakistan)</p>
                    </div>
                </div>

                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/availablity.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>Availability</h3>
                        <p>@if($item->status)Yes@else Out of Stock@endif</p>
                    </div>
                </div>

                <div class="border-bottom-single-page"></div>

                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/payments.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>Payment</h3>
                        <p>We offer easy and secure payment options: bank transfer, JazzCash, or EasyPaisa.</p>
                    </div>
                </div>

                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/policy.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>7 days return policy</h3>
                        <p>We offer a 7-day easy return or exchange policy. Customers only cover delivery charges.</p>
                    </div>
                </div>

                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/originalp.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>100% Original Products</h3>
                        <p>Guaranteed: 100% original products.</p>
                    </div>
                </div>

                <div class="warrenty-section">
                    <div class="warenty-section-image">
                        <img src="{{ $assets_url }}images/Satisfaction.png">
                    </div>
                    <div class="warrenty-heading">
                        <h3>Customer Satisfaction</h3>
                        <p>Our priority: Your satisfaction. We value your feedback to improve.</p>
                    </div>
                </div>
            </div><!--warrenty-detail-single-pg-->
        </div><!--inside-single-page-->
    </div><!--container-->
</div><!--single-page-->

<div class="overview-rating-section">
    <div class="container">
        <!-- Tab Buttons -->
        <ul class="tab-menu">
            <li class="tab-link active" data-tab="overview">Overview</li>
            <li class="tab-link" data-tab="reviews">Rating & Reviews</li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content active" id="overview">
            <h2>Buy {{$item->product_name}} Price In Pakistan Online in Pakistan From {{$setting->name ?? 'Our Store'}}.Pk</h2>
            {!! $item->product_details !!}
        </div>

        <div class="tab-content" id="reviews">
            <div class="review-box">
                <h2>Customer Reviews for {{$item->product_name}}</h2>
                <div class="write-review-section">
                    <div class="review-box-ratings">
                        {!! get_rating_html($finalresult) !!}
                        <p>Based on {{$rcount}} reviews</p>
                    </div>
                    <div class="write-a-review">
                        <button type="button" id="toggleReviewForm">Write a Review</button>
                    </div>
                </div><!--write-review-section-->
                
                <div class="review-form" id="reviewForm" style="display:none;">
                    <form action="/rating_submit" method="POST">
                        @csrf
                        <input type="hidden" name="pid" value="{{$item->id}}">
                        
                        <div class="form-row full-width">
                            <label for="stars">Select Stars</label>
                            <select id="stars" name="rating" required>
                                <option value="5">5 Star [Excellent]</option>
                                <option value="4">4 Star [Very Good]</option>
                                <option value="3">3 Star [Good]</option>
                                <option value="2">2 Star [Fair]</option>
                                <option value="1">1 Star [Poor]</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Name" required>
                        </div>

                        <div class="form-row">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>

                        <div class="form-row full-width">
                            <label for="review">Your Review</label>
                            <textarea id="review" name="review" placeholder="Please write your feedback to us" required></textarea>
                        </div>

                        <div class="form-row full-width">
                            <button type="submit">Submit <br> Review</button>
                        </div>
                    </form>
                </div><!--review-form-->
                
                @foreach($rating as $v)
                <div class="write-review-section2">
                    <div class="review-box-ratings">
                        {!! get_rating_html($v->rate) !!}
                        <h3>Verified Purchase</h3>
                        <p>{{$v->review}}</p>
                    </div>
                    <div class="write-a-review">
                        <span class="review-passed"><strong>{{$v->name}}</strong> on <strong>{{date("d M Y",strtotime($v->created_at))}}</strong></span>
                    </div>
                </div><!--write-review-section-->
                @endforeach
            </div>
        </div><!--tab-content-->
    </div><!--container-->
</div><!--overview-rating-section-->

<!--most-buying-products-->
@if($rproducts)
<div class="new-arrivals">
    <div class="container">
        <div class="inside-new-arrivals">
            <div class="new-arrivals-heading">
                <h1><a href="#">Most Buying Products</a></h1>
            </div><!--new-arrivals-heading-->

            <div class="slider-wrapper" data-slider="hair">
                <button class="product-slide-btn left prev">&#10094;</button>
                <div class="products-section">
                    @foreach($rproducts as $k=>$v)
                    <div class="single-product-section product">
                        <div class="product-image">
                            <a href="{{ url('/product/'.$v->slug) }}">
                                <img src="{{ img_url($v->image_one) }}" alt="{{$v->product_name}}">
                            </a>
                        </div><!--product-image-->
                        <div class="product-detail-section">
                            <h3><a href="{{ url('/product/'.$v->slug) }}">{{$v->product_name}}</a></h3>
                            <p><strong>Rs. {{$v->discount_price}}</strong> 
                            @if($v->selling_price > $v->discount_price)
                            <span class="discounted-price">{{$v->selling_price}}</span>
                            @endif
                            </p>
                        </div><!--product-detail-section-->
                        <div class="product-rating-section">
                            <div class="ratings">
                                <img src="{{ $assets_url }}images/rating.png">
                            </div><!--rating-->
                            @php
                            $product_discount = 0;
                            if($v->selling_price > 0 && $v->discount_price > 0) {
                                $product_discount = round((($v->selling_price - $v->discount_price) / $v->selling_price) * 100);
                            }
                            @endphp
                            @if($product_discount > 0)
                            <div class="sale-button">
                                <button>{{$product_discount}}% Off</button>
                            </div><!--sale-button-->
                            @endif
                        </div><!--product-rating-section-->
                    </div><!--single-product-section-->
                    @endforeach
                </div><!--products-section-->
                <button class="product-slide-btn right next">&#10095;</button>
            </div><!--slider-wrapper-->
        </div><!--inside-new-arrivals-->
    </div><!--container-->
</div><!--new-arrivals-->
@endif

@endsection

@section('script')
<style>
.qty-minus-btn:hover, .qty-plus-btn:hover {
    background: #f0f0f0 !important;
    border-color: #999 !important;
    transform: scale(1.05);
}
.qty-minus-btn:active, .qty-plus-btn:active {
    background: #e0e0e0 !important;
    transform: scale(0.95);
}
#product-qty:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}
</style>
<script>
// Image switching functionality
function changeImage(element) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.src = element.src;
    mainImage.alt = element.alt;
}

// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Review form toggle
    const toggleReviewForm = document.getElementById('toggleReviewForm');
    const reviewForm = document.getElementById('reviewForm');
    
    if (toggleReviewForm && reviewForm) {
        toggleReviewForm.addEventListener('click', function() {
            if (reviewForm.style.display === 'none') {
                reviewForm.style.display = 'block';
            } else {
                reviewForm.style.display = 'none';
            }
        });
    }

    // Product slider functionality
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const productsSection = document.querySelector('.products-section');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    
    if (sliderWrapper && productsSection && prevBtn && nextBtn) {
        let currentIndex = 0;
        const products = document.querySelectorAll('.single-product-section');
        const productsPerView = 4; // Adjust based on your design
        const maxIndex = Math.max(0, products.length - productsPerView);
        
        function updateSlider() {
            const translateX = -currentIndex * (100 / productsPerView);
            productsSection.style.transform = `translateX(${translateX}%)`;
        }
        
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        });
    }
});

// Quantity controls
$(document).ready(function(){
    // Quantity minus button
    $('#qty-minus').click(function(e){
        e.preventDefault();
        var qtyInput = $('#product-qty');
        var currentQty = parseInt(qtyInput.val()) || 1;
        var minQty = parseInt(qtyInput.attr('min')) || 1;
        if(currentQty > minQty) {
            qtyInput.val(currentQty - 1);
        }
    });
    
    // Quantity plus button
    $('#qty-plus').click(function(e){
        e.preventDefault();
        var qtyInput = $('#product-qty');
        var currentQty = parseInt(qtyInput.val()) || 1;
        var maxQty = parseInt(qtyInput.attr('max')) || 999;
        if(currentQty < maxQty) {
            qtyInput.val(currentQty + 1);
        }
    });
    
    // Validate quantity input
    $('#product-qty').on('change blur', function(){
        var qtyInput = $(this);
        var currentQty = parseInt(qtyInput.val()) || 1;
        var minQty = parseInt(qtyInput.attr('min')) || 1;
        var maxQty = parseInt(qtyInput.attr('max')) || 999;
        
        if(currentQty < minQty) {
            qtyInput.val(minQty);
        } else if(currentQty > maxQty) {
            qtyInput.val(maxQty);
        }
    });

    // Add to cart functionality
    $('.add-to-cart').click(function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var qty = parseInt($('#product-qty').val()) || 1; // Get quantity from input
        
        $.ajax({
            url: "{{url('cart/add')}}",
            type: "POST",
            data: {
                id: id,
                qty: qty,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response){
                if(response.msg_type === 'success' || response.success) {
                    // Update cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(response.qty || 0);
                    }
                    // Update cart sidebar
                    if (typeof updateCartSidebar === 'function') {
                        updateCartSidebar();
                    }
                    // Show notification
                    if (typeof showNotification === 'function') {
                        showNotification(response.msg || 'Product added to cart successfully!', 'success');
                    } else {
                        alert('Product added to cart successfully!');
                    }
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(response.msg || 'Error adding product to cart', 'error');
                    } else {
                        alert('Error adding product to cart');
                    }
                }
            }
        });
    });

    $('.add-to-cart-item1').click(function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var qty = parseInt($('#product-qty').val()) || 1; // Get quantity from input
        
        $.ajax({
            url: "{{url('cart/add')}}",
            type: "POST",
            data: {
                id: id,
                qty: qty,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response){
                if(response.msg_type === 'success' || response.success) {
                    // Update cart count before redirect
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(response.qty || 0);
                    }
                    window.location.href = "{{url('checkout')}}";
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(response.msg || 'Error adding product to cart', 'error');
                    } else {
                        alert('Error adding product to cart');
                    }
                }
            }
        });
    });
});
</script>
@endsection
