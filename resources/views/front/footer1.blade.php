
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
<div class="container-fluid bg-dark text-secondary mt-5">
   <div class="px-xl-5">
    <div class="footer_subscribe">
		<div class="subscribe_flex" data-version="2" id="HTML3">
           <h3 class="title">
           <b>SignUp to Our Newsletter</b> and recieve amazing products
           </h3>
           <div class="subscribe_form">
                                         
                        <form action="{{url('/subcribe_newsletter')}}" method="post">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input type="email" name="email" class="email" placeholder="Your Email Address">
                                <div class="input-group-append sign_up">
                                    <button class="btn bg-dark submits" >Sign Up</button>
                                </div>
                            <div data-lastpass-icon-root="true" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div></div>
                        </form>
           </div>
           </div>
       </div>
    </div>
        <div class="row px-xl-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p class="mb-4">{!!$setting->footer_text!!}</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{strip_tags($setting->homepage_footer)}}</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{$setting->email}}</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{$setting->phone}}</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Shop</h5>
                        <div class="d-flex flex-column justify-content-start">
                            @foreach($header_menu as $k=> $v)
                            @if($k <=4)
                        
                        <a class="text-secondary mb-2" href="
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
                        "><i class="fa fa-angle-right" style="margin-right: 8px;"></i>{{$v->name}}</a>
                        @endif
                        @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <div class="d-flex flex-column justify-content-start">
                            @foreach($header_menu as $k=> $v)
                            @if($k > 4)
                        
                        <a class="text-secondary mb-2" href="
                        @if($v->route)
                        {{ url('/'); }}/{{$v->route}}
                        @else
                        {{ url('/')}}/{{$v->slug}}
                        @endif
                        "><i class="fa fa-angle-right" style="margin-right: 8px;"></i>{{$v->name}}</a>
                        @endif
                        @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                              {!! $setting->news_text !!}
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            @foreach($media as $k=> $v)
                            <a class="btn btn-primary btn-square mr-2" href="{{$v->link}}" target="_blank"><i class="fab fa-{{$v->icon}}"></i></a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    <?php $setting = DB::table('setting')->first(); ?>
                    © <a class="text-primary" href="{{ url('/'); }}">{{ $setting->site_title ?? 'Shop Pakistan' }}</a></a>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="{{ url(''); }}/front/img/payments.png" alt="">
            </div>
        </div>
    </div>
    <script>
  document.addEventListener("DOMContentLoaded", function() {
    const lazyImages = document.querySelectorAll('img.lazy');
    
    const lazyLoad = function() {
      lazyImages.forEach(img => {
        if (img.getBoundingClientRect().top < window.innerHeight && img.getBoundingClientRect().bottom > 0) {
          img.src = img.dataset.src;
          img.classList.remove('lazy');
        }
      });
    };

    window.addEventListener('scroll', lazyLoad);
    window.addEventListener('resize', lazyLoad);
    lazyLoad(); // Initial load in case images are already in view
  });
</script>