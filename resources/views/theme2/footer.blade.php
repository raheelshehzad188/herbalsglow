
<?php 

use App\Models\Admins\Setting;
use App\Models\Admins\Category;
use App\Models\Admins\Media;
$pro= Setting::where(['id'=>'1'])->first();
$media = Media::get();
$cate = Category::limit('6')->get();
?>
<?php $setting = DB::table('setting') 
    ->where('id', '=', '1')
    ->first();
    $header_menu = DB::table('pages')
    ->where('menu_type', '=', 'quick_links')->where('status','=','1')->orderBy('position', 'asc')
    ->get();
$cate = DB::table('categories')->get();
?>
<!-- Bottom Navigation Bar -->
<div class="bottom-bar">
  <a href="{{ url('/') }}" class="bar-item"><i class="fa-solid fa-shop"></i><span>Home</span></a>
  <a href="{{ url('/shop') }}" class="bar-item"><i class="fa-solid fa-store"></i><span>Shop</span></a>
  <div class="bar-item search-btn open-mobile-search-btn"><i class="fa-solid fa-magnifying-glass"></i><span>Search</span></div>
</div>

<div class="footer">
	<div class="footer-border">
		<div class="container">
			<div class="inside-footer">
				<div class="footer-image-section">
					<a href="{{ url('/') }}"> <img src="{{ img_url($setting->wlogo ?? '') }}" alt="logo" width="116" height="56" style="object-fit: cover;"> </a>
					<ul> 
						@if($setting->youtube)
						<li> <a href="{{ $setting->youtube }}" target="_blank" aria-label="Follow us on YouTube"> <i class="fa-brands fa-youtube"></i> </a> </li>
						@endif
						@if($setting->facebook)
						<li> <a href="{{ $setting->facebook }}" target="_blank" aria-label="Follow us on Facebook"> <i class="fa-brands fa-facebook-f"></i> </a> </li>
						@endif
						@if($setting->instagram)
						<li> <a href="{{ $setting->instagram }}" target="_blank" aria-label="Follow us on Instagram"> <i class="fa-brands fa-instagram"></i> </a> </li>
						@endif
						@if($setting->twitter)
						<li> <a href="{{ $setting->twitter }}" target="_blank" aria-label="Follow us on Twitter"> <i class="fa-brands fa-twitter"></i> </a> </li>
						@endif
						@if($setting->tiktok)
						<li> <a href="{{ $setting->tiktok }}" target="_blank" aria-label="Follow us on Tiktok"> <i class="fa-brands fa-tiktok"></i> </a> </li>
						@endif
						@if($setting->pinterest)
						<li> <a href="{{ $setting->pinterest }}" target="_blank" aria-label="Follow us on Pintrest"> <i class="fa-brands fa-pinterest"></i> </a> </li>
						@endif
					</ul>
				</div><!--footer-image-section-->
				<div class="sec-one">
					<div class="in-s-one">
					<h3> Useful links </h3>
					@foreach($header_menu as $k=> $v)
					<a href="
						@if($v->route)
							<?php
							if($v->route == '/')
							{
							?>
							{{ '/'; }}
							<?php
							}
							else
							{
							?>
							{{ url(''); }}/{{$v->route}}
							<?php
							}
							?>
							@else
							{{ url('/')}}/{{$v->slug}}
							@endif
						">{{$v->name}}</a>
					@endforeach
					</div>
				</div><!--sec-one-->
				<div class="footer-sign-up">
					<h4> Sign Up For New Products Update </h4> 
					<p> Don’t miss out on our latest arrivals and special promotions. Sign up now and be the first to know what’s new! </p>
					<form class="form-newsletter" id="subscribe-form" action="{{url('/subcribe_newsletter')}}" method="post">
						{{ csrf_field() }}
						<div class="form-wraper">
							<div class="sign-up-input">
								<input type="email" name="email" placeholder="Enter Your Email Address" required>
								<button type="submit" class="subscribe-button" aria-label="Email Us">
									<i class="fa-solid fa-arrow-right"></i>
								</button>
							</div><!--sign-up-input-->
						</div><!--form-wraper-->
					</form>

				</div><!--footer-sign-up-->
				
				
			</div><!--inside-footer-->
		</div><!--container-->
	</div><!--footer-border-->
	
	<div class="footer-bottom">
		<div class="container">
			<div class="inside-footer-bottom">
			
				<div class="sec-two">
					<p> Copyright © {{date('Y')}} <a href="{{ url('/') }}">{{$setting->site_title}}</a> - All Rights Reserved </p>
				</div>
			</div><!--inside-footer-bottom-->
		</div>
	</div><!--footer-bottom-->    
</div><!--fter-->

