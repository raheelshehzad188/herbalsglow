<!--photos-section-->
<div class="photos-section">
	<div class="container">
		<div class="inside-photos-section">
			@if(isset($setting->homepage_image_one) && !empty($setting->homepage_image_one))
			<div class="photos-section-part">
				<a href="#"> <img src="{{ img_url($setting->homepage_image_one) }}"> </a>
			</div><!--photos-section-->
			@endif
			@if(isset($setting->homepage_image_two) && !empty($setting->homepage_image_two))
			<div class="photos-section-part">
				<a href="#"> <img src="{{ img_url($setting->homepage_image_two) }}"> </a>
			</div><!--photos-section-->
			@endif
			@if(isset($setting->homepage_image_3) && !empty($setting->homepage_image_3))
			<div class="photos-section-part">
				<a href="#"> <img src="{{ img_url($setting->homepage_image_3) }}"> </a>
			</div><!--photos-section-->
			@endif
		</div><!--inside-photos-section-->
	</div><!--cont-->
</div><!--photos-section-->

        <!--new-arrivals-->

        @include('theme2/recent')
        @if(isset($setting->homepage_image_4) && !empty($setting->homepage_image_4))
        <div class="single-photos-section">
            <div class="container">
                <div class="sin-photos-section-part">
                    <a href="#"> <img src="{{ img_url($setting->homepage_image_4) }}"> </a>
                </div><!--photos-section-->
            </div><!--cont-->
        </div><!--single-photos-section-->
        @endif