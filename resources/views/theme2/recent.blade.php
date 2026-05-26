<div class="new-arrivals">
    <div class="container">
        <div class="inside-new-arrivals">
			<div class="new-arrivals-heading">
				<h1> <a href="#"> NEW ARRIVALS </a> </h1>
			</div><!--new-arrivals-heading-->

			<div class="slider-wrapper" data-slider="products">
				<button class="product-slide-btn left prev">&#10094;</button>
					<div class="products-section" >
						@foreach ($aproducts as $k => $v)
							@include('theme2/product_box')    
						@endforeach
					</div><!--products-section-->
				<button class="product-slide-btn right next" >&#10095;</button>    
			</div><!--slider-wrapper-->    

        </div><!--inside-new-arrivals-->
    </div>
</div><!--new-arrivals-->