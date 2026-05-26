<!--categories-->
<div class="categories">
    <div class="container">
		<div class="cats-inside-custom"> 	
			<div class="new-cat-slider-wrap">
				

				<div class="new-cat-slider-inner">
					@if(isset($categories) && count($categories) > 0)
						@foreach($categories as $category)
						<div class="new-cat-slide-item">
							<a href="{{ url('category/' . $category->slug) }}">    
								<img src="{{ img_url($category->image) }}" alt="{{ $category->name }}">
								<div class="custome-name-cats-slider">
								<p>{{ $category->name }}</p>
								</div>
							</a>
						</div><!--new-cat-slide-item-->
						@endforeach
					
					@endif
				</div><!--new-cat-slider-inner-->
			
				
			</div><!--new-cat-slider-wrap-->
		</div><!--cats-inside-custom-->	
    </div>
</div><!--categories-->
