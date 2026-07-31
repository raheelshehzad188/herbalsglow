
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
<!-- footer -->
        <footer id="footer" class="footer md-pb-70">
            <div class="footer-wrap wow fadeIn" data-wow-delay="0s">
                <div class="footer-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="footer-infor">
                                    <div class="footer-logo">
                                        <a href="{{ url('/'); }}">
                                            <img src="{{env('IMG_URL')}}{{$setting->wlogo}}" alt="">
                                        </a>
                                    </div>
                                    <ul>
                                        <li>
                                        {!!$setting->homepage_footer !!}
                                        </li>
                                        <li>
                                            <p>Email: <a class="link" href="#">{{$setting->email}}</a></p>
                                        </li>
                                        <li>
                                            <p>Phone: <a href="#">{{$setting->phone}}</a></p>
                                        </li>
                                    </ul>
                                    @if($setting->dir_link)
                                    <a href="{{$setting->dir_link}}" class="tf-btn btn-line">Get direction<i class="icon icon-arrow1-top-left"></i></a>
                                    @endif
                                    <ul class="tf-social-icon d-flex gap-10">
                                        @if($setting->facebook)
                                        <li><a href="{{ $setting->facebook }}" class="box-icon w_34 round social-facebook social-line"><i class="icon fs-14 icon-fb"></i></a></li>
                                        @endif
                                        @if($setting->twitter)
                                        <li><a href="{{$setting->twitter}}" class="box-icon w_34 round social-twiter social-line"><i class="icon fs-12 icon-Icon-x"></i></a></li>
                                        @endif
                                        @if($setting->instagram)
                                        
                                        <li><a href="{{$setting->instagram}}" class="box-icon w_34 round social-instagram social-line"><i class="icon fs-14 icon-instagram"></i></a></li>
                                        @endif
                                        @if($setting->tiktok)
                                        <li><a href="{{$setting->tiktok}}" class="box-icon w_34 round social-tiktok social-line"><i class="icon fs-14 icon-tiktok"></i></a></li>
                                        @endif
                                        @if($setting->pinterest)
                                        <li><a href="{{$setting->pinterest}}" class="box-icon w_34 round social-pinterest social-line"><i class="icon fs-14 icon-pinterest-1"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                                <div class="footer-heading footer-heading-desktop">
                                    <h6>Quick Links</h6>
                                </div>
                                <div class="footer-heading footer-heading-moblie">
                                    <h6>Quick Links</h6>
                                </div>
                                <ul class="footer-menu-list tf-collapse-content">
                                    @foreach($header_menu as $k=> $v)
                            @if($k <=4)
                        <li>
                        <a class="text-secondary mb-2 link" href="
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
                        "><i class="fa fa-angle-right" class="link footer-menu_item"></i>{{$v->name}}</a></li>
                        @endif
                        @endforeach
                                </ul>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                                <div class="footer-heading footer-heading-desktop">
                                    <h6>About us</h6>
                                </div>
                                <div class="footer-heading footer-heading-moblie">
                                    <h6>About us</h6>
                                </div>
                                <ul class="footer-menu-list tf-collapse-content">
                                    @foreach($header_menu as $k=> $v)
                            @if($k > 4)
                        <li>
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
                        "><i class="fa fa-angle-right" class="link footer-menu_item"></i>{{$v->name}}</a></li>
                        @endif
                        @endforeach
                                    
                                </ul>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="footer-newsletter footer-col-block">
                                    <div class="footer-heading footer-heading-desktop">
                                        <h6>Sign Up for Email</h6>
                                    </div>
                                    <div class="footer-heading footer-heading-moblie">
                                        <h6>Sign Up for Email</h6>
                                    </div>
                                    <div class="tf-collapse-content">
                                        <div class="footer-menu_item">Sign up to get first dibs on new arrivals, sales, exclusive content, events and more!</div>
                                        <form class="form-newsletter" id="subscribe-form" action="{{url('/subcribe_newsletter')}}" method="post">
                            {{ csrf_field() }}
                            <div id="subscribe-content">
                                <input type="email" name="email" class="email" placeholder="Your Email Address">
                                <div class="button-submit">
                                                    <button   class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" type="submit">Subscribe<i class="icon icon-arrow1-top-left"></i></button>
                                                </div>
                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="footer-bottom-wrap d-flex gap-20 flex-wrap justify-content-between align-items-center">
                                    <div class="footer-menu_item">© {{date('Y')+1}} {{$setting->site_title}}. All Rights Reserved</div>
                                    <div class="tf-payment">
                                        <img src="{{ $assets_url }}images//payments/visa.png" alt="">
                                        <img src="{{ $assets_url }}images//payments/img-1.png" alt="">
                                        <img src="{{ $assets_url }}images//payments/img-2.png" alt="">
                                        <img src="{{ $assets_url }}images//payments/img-3.png" alt="">
                                        <img src="{{ $assets_url }}images//payments/img-4.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /footer -->