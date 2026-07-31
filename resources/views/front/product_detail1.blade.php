@extends('layout.app2')

<?php
use App\Models\Admins\Category;
use App\Models\product;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Rating;
  ?>
  <?php
$setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();

?>


@section('content')
@foreach($product as $item)
<style>
.reviews__comment--content a{
    margin:3px 0px;
}
.item_row {
    padding: 10px 10px;
    background: #f2f2f2;
    align-items: baseline;
}

.item_row h5 {
    font-weight: 500;
    width: 180px;
    margin: 0;
}
.transparent_row{
    padding: 10px 10px;
    background: transparent;
    align-items: baseline;
}
.transparent_row h5{
    font-weight: 500;
    width: 180px;
    margin: 0;
}
.btn-cart{
    border: 0;
    background-color: antiquewhite;
    padding: 12px 20px;
    margin-bottom: 13px;
    background-color: #3f69aa;
    color: white;
}
.btn-cart i{
        margin-right: 11px;
}
@media (max-width: 680px){
    .item_row h5 {
    font-weight: 500;
    width: 200px;
    margin: 0;
    font-size:15px;
}
.transparent_row h5 {
    font-weight: 500;
    width: 200px;
    margin: 0;
    font-size:15px;
}
.transparent_row{
    font-size:12px;
}
.item_row{
    font-size:12px;
}
}
</style>


    <!-- Shop Detail Start -->
                <?php
                    $count = 0;
                    $totalrating = 0;
                    $getreview = DB::table('rating')->where('status', '1')->where('pid',
                        $item->id)->orderby('id', 'desc')->get();
                    $countcustomer = DB::table('rating')->where('status', '1')->where('pid',
                        $item->id)
                        ->count();
                    if($countcustomer != 0 && $getreview){
                    foreach ($getreview as $avg){
                        $count = $count + $avg->rate;
                    }
                    $totalrating = $count / $countcustomer;
                    $finalresult = round($totalrating);
                ?>
                <?php }?>
                        <!-- Product Detail Start -->
<div class="product-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row align-items-center product-detail-top">
                    <div class="col-md-5">
                        <div class="product-slider-single">

                            <img src="{{env('APP_URL')}}{{$item->image_one}}" alt="Product Image">
                                <?php $Galleries = Gallerie::where(['product_id'=>$item->id])->get(); ?>
                            @foreach($Galleries as $Gallerie)

                                <img src="{{env('APP_URL')}}{{$Gallerie->photo}}" alt="Product Image">
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="product-content">
                            <div class="title"><h2>{{$item->product_name}}</h2></div>
                            <div class="ratting">

                                    <?php
                                    $rating = $data=Rating::where('pid',$item->id)->where('status',1)->sum('rate');
                                    $count = Rating::where(['pid'=>$item->id])->where('status',1)->count();
                                    if($count && $data){
                                        $rate = $data/$count;
                                    }else{
                                        $rate = 0;
                                    }
                                    for ($i = 0;$i <$rate;$i++)
                                    {
                                        ?>
                                <i class="fa fa-star"></i>
                                        <?php

                                    }
                                    ?>
                                <small class="pt-1">({{number_format($rate,2)}}/5.00)</small>
                            </div>
                            <div class="price">Rs{{$item->discount_price}} <span>Rs {{$item->selling_price}}</span></div>
                            <div class="details">
                                <p>
                                        <?= $item->short_discriiption; ?>
                                </p>
                            </div>

                            <div class="quantity">
                                <h4>Quantity:</h4>
                                <div class="qty">
                                    <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                    <input type="text" title="qty" id="qty" name="qty" value="1" value="1">
                                    <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                          
                            <div class="d-flex pt-2">
                                  <div class="d-inline-flex">
                                 <a href="#" class="add-to-cart-item1"  id="{{$item->id}}"><button class="btn-cart"><i class="fa fa-cart-plus"></i>Buy Now</button></a>
                                  <button onclick="window.location='https://api.whatsapp.com/send?phone=<?= $setting->phone?>&amp;text=Hello, I want to purchase:*{{$item->product_name}}* Price:*{{$item->discount_price}} URL:*{{ url('/'); }}/{{$item->slug}}* Thank You !';" class="btn-cart px-3 ml-2" ><i class="fa fa-phone mr-1"></i> Chat On Whatsapp</button>
                                    </div>
                            </div>
                            
                            <!--<div class="action">-->
                            <!--    <a href="#" class="add-to-cart" title="cart"  id="{{$item->id}}"><i class="fa fa-cart-plus"></i></a>-->
                            <!--    <a href="#"><i class="fa fa-heart"></i></a>-->
                            <!--    <a href="#"><i class="fa fa-search"></i></a>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>

                <div class="row product-detail-bottom">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#description">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#specification">Specification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#reviews">Reviews ({{$rcount}})</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="description" class="container tab-pane active"><br>
                                <h4>Product description</h4>
                                <p>
                                    <?= $item->product_details?>
                                </p>
                            </div>
                            <div id="specification" class="container tab-pane fade"><br>
                                <h4>Product specification</h4>
                                <ul>
                                    
                                     @foreach(explode(',', $item->specification) as $spec)
                                       <li>{{ trim($spec) }}</li>
                                   @endforeach
                                    
                                  
                                </ul>
                            </div>
                            <div id="reviews" class="container tab-pane fade"><br>
                            @php
                            $rating = Rating::where('pid',$item->id)->where('status',1)->get();
                            @endphp
                                 @foreach($rating as $v)
                                 <div class="reviews-submitted">
                                    <div class="reviewer">{{$v->name}}- <span>{{date(" F d Y ",strtotime($v->created_at))}}</span></div>
                                    <div class="ratting">
                                        <?php
                            for($i= 1; $i<=5;$i++)
                            {
                                if($i <= $v->rate)
                                {
                                ?>
                            <i class="fa fa-star"></i>
                            <?php
                                }
                                else
                                {
                                 ?>
                            <i class="fa fa-star-o"></i>
                            <?php   
                                }
                            }
                            ?>
                                    </div>
                                    <p>
                                        {{$v->review}}
                                    </p>
                                </div>
                                @endforeach
                                
                                <div class="reviews-submit">
                                    <h4>Give your Review:</h4>
                                    <div class="ratting">
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                    <form action="/rating_submit" method="POST">
                                                @csrf
                                                <input type="hidden" name="pid" value="{{$item->id}}">
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" cols="10" rows="3" class="form-control" name="review" required  placeholder="Your Comments...."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" class="form-control" id="name"  name="name" required placeholder="Your Name....">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" required placeholder="Your Email....">
                                    </div
                                    <div class="form-group">
                                        <label for="email">Rating *</label>
                                        <select class="form-control" name="rating">
                                            <option value="5">5 Star(Excellent)</option>
                                            <option value="4">4 Star(Better)</option>
                                            <option value="3">3 Star(Good)</option>
                                            <option value="2">2 Star(Poor)</option>
                                            <option value="1" >1 Star(Very bad)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-0">
                                        <input name="submit" type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="section-header">
                        <h3>Related Products</h3>
                        <p>
                              {!! $setting->homepage_img6d !!}
                        </p>
                    </div>
                </div>

            <div class="row align-items-center product-slider product-slider-3">
    @if(!empty($rproducts) && $rproducts->count())
        @foreach ($rproducts as $k=>$v)
            @if($v->status == 1)
                <div class="col-lg-3">
                    
                        @include('includes/parts/product_box')
                    
                </div>
            @endif
        @endforeach
    @endif
</div>

            </div>

            <div class="col-lg-3 d-none">
                <div class="sidebar-widget category">
                    <h2 class="title">Category</h2>
                    <ul>
                        <li><a href="#">Lorem Ipsum</a><span>(83)</span></li>
                        <li><a href="#">Cras sagittis</a><span>(198)</span></li>
                        <li><a href="#">Vivamus</a><span>(95)</span></li>
                        <li><a href="#">Fusce vitae</a><span>(48)</span></li>
                        <li><a href="#">Vestibulum</a><span>(210)</span></li>
                        <li><a href="#">Proin phar</a><span>(78)</span></li>
                    </ul>
                </div>

                <div class="sidebar-widget image d-none">
                    <h2 class="title">Featured Product</h2>
                    <a href="#">
                        <img src="img/category-1.jpg" alt="Image">
                    </a>
                </div>

                <div class="sidebar-widget brands d-none">
                    <h2 class="title">Our Brands</h2>
                    <ul>
                        <li><a href="#">Nulla </a><span>(45)</span></li>
                        <li><a href="#">Curabitur </a><span>(34)</span></li>
                        <li><a href="#">Nunc </a><span>(67)</span></li>
                        <li><a href="#">Ullamcorper</a><span>(74)</span></li>
                        <li><a href="#">Fusce </a><span>(89)</span></li>
                        <li><a href="#">Sagittis</a><span>(28)</span></li>
                    </ul>
                </div>

                <div class="sidebar-widget tag d-none">
                    <h2 class="title">Tags Cloud</h2>
                    <a href="#">Lorem ipsum</a>
                    <a href="#">Vivamus</a>
                    <a href="#">Phasellus</a>
                    <a href="#">pulvinar</a>
                    <a href="#">Curabitur</a>
                    <a href="#">Fusce</a>
                    <a href="#">Sem quis</a>
                    <a href="#">Mollis metus</a>
                    <a href="#">Sit amet</a>
                    <a href="#">Vel posuere</a>
                    <a href="#">orci luctus</a>
                    <a href="#">Nam lorem</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Detail End -->

@endforeach



@endsection