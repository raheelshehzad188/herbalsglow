<div class="flat-title wow fadeInUp" data-wow-delay="0s" style="visibility: visible; animation-delay: 0s; animation-name: fadeInUp;">
	<span class="title">Featured Products</span>
</div>
	<div class="swiper tf-sw-product-sell-1" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3" data-pagination-lg="3">
		<div class="swiper-wrapper">
			@foreach ($fproducts as  $k=>$v)
			<div class="swiper-slide" lazy="true">
				@include('theme3/product_box_new')	
			</div>
			@endforeach
		</div>
	</div>