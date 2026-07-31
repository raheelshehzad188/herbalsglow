@extends($layout)

<?php

use App\Models\Admins\Category;
use App\Models\Admins\Product;
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

// Get related products from same category
$rproducts = Product::where('category_id','=',$item->category_id)->where('id','!=',$item->id)->where('status','1')->limit(8)->get();

// If less than 6 products, fill with recent products
if(count($rproducts) < 6) {
    $existingIds = $rproducts->pluck('id')->toArray();
    $existingIds[] = $item->id; // Exclude current product
    $needed = 8 - count($rproducts); // How many more products we need
    
    $recentProducts = Product::whereNotIn('id', $existingIds)
        ->where('status','1')
        ->orderBy('id', 'DESC')
        ->limit($needed)
        ->get();
    
    // Merge related products with recent products
    $rproducts = $rproducts->merge($recentProducts);
}

// Get settings for WhatsApp
$setting = DB::table('setting')->where('id', '=', '1')->first();

// Generate random number that changes every 6 hours (5-12 items)
$sixHourInterval = floor(time() / (6 * 3600)); // Changes every 6 hours
$randomSeed = $item->id . $sixHourInterval; // Unique per product and time interval
srand(crc32($randomSeed)); // Set seed for consistent random within 6 hours
$soldItems = rand(5, 12);
$viewItems = rand(5, 50);
srand(); // Reset random seed
?>

@section('content')
        <div class="content-indicator">
    <div class="container">
        <div class="inside-content-indicator">
            <ul>
				<li> <a href="{{ url('/') }}"> Home </a> </li>
				@if(isset($cate) && $cate)
				<li> <a href="{{ url('/category/'.$cate->slug) }}"> {{ $cate->name }} </a> </li>
				@endif
				<li> <a href="#"> {{ $item->product_name }} </a> </li>
            </ul>
        </div><!--inside-content-indicator-->
    </div><!--container-->
</div><!--content-indicator-->

<!--single-page-->
        
<div class="single-page">
    <div class="container">
        <div class="inside-single-page">
			<div class="sing-pg-row-sec">
				<div class="in-sing-pg-row-sec">
					<div class="single-page-product-image">
						<?php
						$mainImage = null;
						if (!empty($item->image_one)) {
							$mainImage = img_url($item->image_one);
						} elseif (isset($files) && count($files) > 0) {
							$mainImage = url($files[0]->photo);
						} else {
							$mainImage = $assets_url . 'img/solo.webp';
						}
						?>

						<img class="mainProductImage" id="mainProductImage"
						 src="{{ $mainImage }}"
						 alt="Main Product" width="487" height="365" fetchpriority="high">


						<div class="single-page-product-image-section">
							@if(!empty($item->image_one))
								<img src="{{ img_url($item->image_one) }}" alt="thumb" onclick="changeImage(this)" width="62" height="80" style="object-fit: cover;">
							@endif

							@if(isset($files) && count($files) > 0)
								@foreach($files as $galleryImage)
									<img src="{{ $galleryImage->photo }}" alt="thumb" onclick="changeImage(this)" >
								@endforeach
							@endif
						</div>
					</div><!--single-page-product-image-->
					<div class="single-page-product-details">
						<h1>{{ $item->product_name }}</h1> 
						<div class="rating-media-single-page">
							<div class="rating-review">
								@php
									$avg_rating = isset($finalresult) ? $finalresult : 0;
									$avg_rating = min(5, max(0, $avg_rating));
								@endphp
								@for($i = 1; $i <= 5; $i++)
									@if($i <= $avg_rating)
										<i class="fa fa-star" style="color: #ffc107;"></i>
									@else
										<i class="fa fa-star-o" style="color: #ccc;"></i>
									@endif
								@endfor
								@if(isset($totalrating) && $totalrating > 0)
									<span style="margin-left: 5px; font-size: 14px;">({{ number_format($totalrating, 1) }})</span>
								@endif
								<div class="rating-reviews-btn">
									<button> (<?= count($getreview); ?> Reviews) </button>
								</div>
							</div><!--rating-media-single-page-->
							<div class="sin-pg-short-desc-demo">
								<p>{!!$item->short_discriiption!!}</p>
							</div>
							
						</div><!--rating-media-single-page-->
						<div class="was-price">	
							<span class="discounted-span"> Rs: {{ $item->selling_price }} </span>
							<span class="now-price-span"> Rs: {{ $item->discount_price }} </span>
							<!--<span class="savings-price-span"> Rs: {{ $item->selling_price - $item->discount_price }} </span>--> <span class="savings-price-of-span"> {{ $discount_percentage }}% Off </span>
						</div>
						<div class="estimate-time">
							<p> <i class="fa-solid fa-truck-moving"></i> Estimate Delivery Time In 1 To 3 Working Days </p>
						</div>
					<div class="confirm-sold">
						<p> <i class="fa-solid fa-fire icon-fire"></i> Sold {{ $soldItems }} items in last 5 to 12 hours </p>
					</div>
						<div class="confirm-views">
							<p> <i class="fa-solid fa-eye"></i> {{ $viewItems }} People are viewing this right now </p>
						</div>
						<div class="buy-cart-buttons">
							<button type="button" onclick="addToCart({{ $item->id }},1,1)" class="buy-now-btn"> ORDER NOW </button>
							<button class="add-to-cart-btn" onclick="addToCart({{ $item->id }})"> ADD TO CART </button>
						</div>
						<div class="border-bottom-single-page">
						</div>	
					</div><!--single-page-product-details-->
					
				</div><!--in-sing-pg-row-sec-->
			</div><!--sing-pg-row-sec-->	
		</div><!--inside-single-page-->		
	</div><!--container-->			
</div><!--single-page-->			
				<div class="desc-outer">
					<div class="container">
						<div class="new-arrivals-heading">
							<p>   Overview </p>
						</div>
						<div class="desc-sec-sing-pg">
							<div class="description-custom" id="descriptionBox">
								<p>{!! $item->product_details !!}</p>
							</div>
							<!--<button id="toggleDescBtn" class="show-more-btn">Show More</button>-->
						</div><!--desc-sec-sing-pg-->
					</div>	
				</div><!--desc-outer-->	
			

				<div class="r-outer"> 
					<div class="container">
						<div class="in-r-outer"> 
							<div class="new-arrivals-heading">
								<p> Customer Reviews </p>
							</div>
							<div class="tab-content" id="reviews">
								<div class="write-3-column-section">
								<?php
								foreach($getreview as $k=> $v)
								{
								    $formattedDate = date("d M", strtotime($v->created_at));

								    ?>
								
									<div class="write-review-section2">	
										<div class="review-box-ratings">
											<?php
											$reviewImage = '';
											if (!empty($v->image)) {
												$reviewImage = custom_assets($v->image);
											}
											?>

											@if(!empty($reviewImage))
												<div class="review-image">
													<img src="{{ $reviewImage }}" alt="Product Image">
												</div>
											@endif
											<ul>
												@php
													$review_rating = isset($v->rate) ? (int)$v->rate : 0;
													$review_rating = min(5, max(0, $review_rating));
												@endphp
												@for($i = 1; $i <= 5; $i++)
													<li>
														@if($i <= $review_rating)
															<i class="fa fa-star" style="color: #ffc107;"></i>
														@else
															<i class="fa fa-star-o" style="color: #ccc;"></i>
														@endif
													</li>
												@endfor
											</ul>	
											<span class="review-passed"><strong> <?= $v->name ?>  </strong> on  <strong> <?= $formattedDate ?>  </strong> </span>
											<h2> Verified Purchase </h2>
											<p><?= $v->review; ?></p>
										</div>

										
									</div><!--write-review-section2-->
								
								<?php
								}
								?>
								</div><!--write-3-column-section-->	
								
								<div class="review-box">
											
									<div class="write-review-section">
										<p> Write a Review </p>
									</div><!--write-review-section-->
									<div class="review-form" id="reviewForm" >
										<form action="/rating_submit" method="POST" enctype="multipart/form-data">	
											@csrf
											<input type="hidden" name="pid" value="{{ $item->id }}">
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
												<label for="reviewImage">Upload Image (optional)</label>
												<input type="file" id="reviewImage" name="reviewImage" accept="image/*">
											</div>
											<div class="form-row full-width">
												<label for="review">Your Review</label>
												<textarea id="review" name="review" placeholder="Please write your feedback to us" required></textarea>
											</div>

											<div class="form-row full-width">
												<button type="submit">Submit Review</button>
											</div>

										</form>
									</div><!--review-form-->
									
								</div><!--review-box-->
							</div><!--tab-content-->
						</div><!--in-r-outer-->	
					</div>
				</div><!--r-outer-->

			
			
<!--most-buying-products-->
@if($rproducts)
<div class="new-arrivals">
    <div class="container">
        <div class="inside-new-arrivals">
            <div class="new-arrivals-heading">
                <p>  You May Also Like  </p>
            </div><!--new-arrivals-heading-->

            <div class="slider-wrapper" data-slider="hair">
                <button class="product-slide-btn left prev">&#10094;</button>
                <div class="products-section" >
					@foreach ($rproducts as  $k=>$v)
					@include('theme2/product_box_new')
					@endforeach
				</div>
				<button class="product-slide-btn right next" >&#10095;</button>
			</div><!--slider-wrapper-->

		</div><!--inside-new-arrivals-->
    </div><!--container-->
</div><!--new-arrivals-->
@endif

<!-- Floating WhatsApp Button -->
@if(isset($setting->whatsapp) && !empty($setting->whatsapp))
<div class="whatsapp-float-btn">
    <a href="https://wa.me/{{ $setting->whatsapp }}?text=Hello! I'm interested in {{ urlencode($item->product_name) }} - {{ urlencode(url('/product/'.$item->slug)) }}" target="_blank" class="whatsapp-btn-link">
        <i class="fa-brands fa-whatsapp"></i>
        <span class="whatsapp-tooltip">Chat on WhatsApp</span>
    </a>
</div>
@endif

<style>
.whatsapp-float-btn {
    position: fixed;
    bottom: 80px;
    right: 20px;
    z-index: 999;
    animation: pulse 2s infinite;
}

.whatsapp-btn-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background-color: #25D366;
    border-radius: 50%;
    color: #fff;
    font-size: 32px;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.whatsapp-btn-link:hover {
    background-color: #20BA5A;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
    color: #fff;
    text-decoration: none;
}

.whatsapp-tooltip {
    position: absolute;
    right: 70px;
    background-color: #333;
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
}

.whatsapp-tooltip::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-left-color: #333;
}

.whatsapp-btn-link:hover .whatsapp-tooltip {
    opacity: 1;
    visibility: visible;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .whatsapp-float-btn {
        bottom: 100px;
        right: 15px;
    }
    
    .whatsapp-btn-link {
        width: 55px;
        height: 55px;
        font-size: 28px;
    }
    
    .whatsapp-tooltip {
        display: none;
    }
}
</style>
@endsection
