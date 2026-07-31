

            @if(isset($featured_categories) && count($featured_categories) > 0)
                @foreach($featured_categories as $category)

                        <!--hair-oil-->

        <div class="new-arrivals">
            <div class="container">
                <div class="inside-new-arrivals">
                    <div class="new-arrivals-heading">
                        <h1> <a href="{{ url('/') }}/{{ $category->slug }}"> {{ $category->name }} </a> </h1>
                        <a href="{{ url('/') }}/{{ $category->slug }}" class="see-all-btn">SEE ALL</a>
                    </div><!--new-arrivals-heading-->
                    @if(isset($category->products) && count($category->products) > 0)
                    <div class="slider-wrapper" data-slider="hair">
				
                        <button class="product-slide-btn left prev">&#10094;</button>
                            <div class="products-section" >
                                
                                @foreach($category->products as $product)
                                    @php $v = $product; @endphp
                                    @include('theme2/product_box_new')
                                @endforeach
                                
                            </div><!--products-section-->
                        <button class="product-slide-btn right next" >&#10095;</button>
                    </div><!--slider-wrapper-->
                    @endif

                </div><!--inside-featured-categories-->
    </div><!--container-->
</div><!--featured-categories-section-->
        @endforeach
    @else
        <div class="no-categories">
            <p>No featured categories available.</p>
        </div>
    @endif