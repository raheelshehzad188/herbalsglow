
<div class="slider" id="slider">
	<div class="container">	
		<div class="track" id="track">
		@foreach ($Slider as $key => $slide)
		<div class="slide">
			<img src="{{ custom_assets('public/img/slider/' . $slide->slider_image) }}" alt="slide{{$key+1}}">
			@if($slide->heading)
			<div class="slide-content">
				<h3>{{$slide->heading}}</h3>
				@if($slide->button)
				<a href="{{$slide->button}}" class="slide-btn">{{$slide->p}}</a>
				@endif
			</div>
			@endif
		</div>
		@endforeach
		</div>
	</div><!--container-->	
</div><!--slider-->