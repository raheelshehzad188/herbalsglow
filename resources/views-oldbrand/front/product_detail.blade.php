@extends('layout.app2')

<?php
use App\Models\Admins\Category;
use App\Models\product;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Rating;

$files = Gallerie::where('product_id',$item->id)->get();
  ?>


@section('content')
@foreach($product as $item)
<!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    @if($cate)
                    <a class="breadcrumb-item text-dark" href="/category/{{$cate->slug}}">{{$cate->name}}</a>
                    @endif
                    <span class="breadcrumb-item active">{{$item->product_name}}</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
    <!-- Shop Detail Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="/{{$item->image_one}}" alt="Image">
                        </div>
                        @foreach($files as $k=> $v)
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="{{url($v->photo)}}" alt="Image">
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
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

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light">
                    <h3>{{$item->product_name}}</h3>
               <div class="flexx">
                    <h3 class="width font-weight-semi-bold mb-4">Rs:</h3>
                    <h3 class="">{{$item->discount_price}}</h3>
                    </div>
                    <p class="mb-4">{!! $item->short_discriiption!!}</p>
                    <style>
                    .width{
                        
                        width:40%;
                    }
                    .flexx h3{
                        margin:0!important;
                    }
                    .flexx{
                         display:flex;
                        align-items:center;
                    
                    
                        background: #f3f3f3;
                        padding: 7px 10px;
                    }
                        .bd{
    display: flex;
    align-items: center;
    padding: 10px;
    
}
.bd p{
    width: 42%;
    margin: 0!important;
}
.tags{
    margin:2px 0px;
}
@media screen and (max-width: 700px) {
.bd p{
    width:40%!important;
  }
  div{
      overflow: hidden;
  }
}

                    </style>
                         <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <?php
                            if(isset($finalresult))
                            {
                            for($i = 1 ; $i <=$finalresult ;$i++ )
                            {
                                ?>
                            <small class="fas fa-star"></small>
                            <?php
                            }
                            }
                            ?>
                        </div>
                        <small class="pt-1">(<?= count($getreview); ?> Reviews)</small>
                    </div>
                    @if(isset($cate) && $cate)
                    <div class="brand_us">
                        <div class="bd" style="border: 1px solid #8080801f; background:#f3f3f3;">
                          <p><b>Category:</b></p><a href="{{url('/category');}}/{{$cate->slug}}">{{$cate->name}}</a><br>
                    
                    </div>
                    @endif
                    <div class="bd">
                    <p><b>Size:</b></p>{{$item->size}}</p>
                    </div>
                    <div class="brand_us">
                        <div class="bd" style="border: 1px solid #8080801f; background:#f3f3f3;">
                          <p><b>Made in :</b></p>{{$item->made_in}}<br>
                    
                    </div>
                    <div class="brand_us">
                        <div class="bd">
                          <p><b>Shipping :</b></p> 2 to 3 days in pakistan<br>
                        </div>
                    </div>
                    </div>
                    <div class="brand_us">
                        <div class="bd" style="border: 1px solid #8080801f; background:#f3f3f3;">
                          <p><b>Avalibility :</b></p> <span class="badge bg-primary">In stock</span>
<br>
                    
                    </div>
                    
                    </div>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus" title="minus">
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center text-black" title="qty" id="qty" name="qty" value="1">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus" title="plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button @if(!$item->status)disabled="true"@endif class="btn btn-primary px-3 add-to-cart" title="cart"  id="{{$item->id}}"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</button><br><br>
                    </div>
                    <div class="d-flex pt-2">
                        <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
$cate = DB::table('categories')->get();
?>
                   
                    <div class="d-inline-flex">
                         <button class="btn btn-primary px-3 add-to-cart-item1" id="{{$item->id}}" href="#" @if(!$item->status)disabled="true"@endif><i class="fa fa-shopping-bag mr-1"></i>Buy Now</button>
                    <button onclick="window.location='https://api.whatsapp.com/send?phone=<?= $setting->phone?>&amp;text=Hello, I want to purchase:*{{$item->product_name}}* Price:*{{$item->discount_price}} URL:*{{ url('/'); }}/{{$item->slug}}* Thank You !';" class="btn btn-primary px-3 ml-2" ><i class="fa fa-phone mr-1"></i> Chat On Whatsapp</button>
                    </div>
                    @php
                    $str = 'Add to wishlist';
                    if(isset($_COOKIE['wishlist']) && in_array($item->id,json_decode($_COOKIE['wishlist'])))
                    {
                    $str = 'Remove from wishlist';
                    }
                    @endphp
                    <div class="d-inline-flex">
                         <a style="margin-left:7px;" class="btn btn-primary px-3 " href="{{ url('wishlist/'.$item->id); }}" id="{{$item->id}}" href="#"><i class="fas fa-heart mr-1"></i>{{$str}}</a>
                    </div>
                </div>
                    <div class="d-flex pt-2">
                        <strong class="text-dark mr-2">Share on:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="https://www.facebook.com/sharer/sharer.php?u={{ url($item->slug) }}">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="https://twitter.com/intent/tweet?text={{ url($item->slug) }}">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-dark px-2" href="https://www.linkedin.com/shareArticle?mini=true&url={{ url($item->slug) }}">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-dark px-2" href="https://pinterest.com/pin/create/button/?url=&media={{ url($item->slug) }}">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5 w-100">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Description</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Tag's</a>
                        <a class="nav-item nav-link text-dark"  data-toggle="tab" href="#tab-pane-3">Reviews ({{$rcount}})</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Product Description</h4>
                            <?= $item->product_details?>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-2">
                            <h4 class="mb-3">Tag's</h4>
                                        <?php 
                                        $tags = explode(',',$item['tags']);
                                        foreach($tags as $k=> $v){
                                            if(!empty($v))
                                                {
                                            // $tag = str_replace(' ', '-', $v);
                                        ?>
                                        <a href="/product-tag/{{$v}}" class="tags btn btn-primary"><?= $v; ?></a>
                                        <?php }}?>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">{{$rcount}} Reviews on {{$item->product_name}}</h4>
                                @foreach($rating as $v)
                                <div class="media mb-4">
                                    <img src="/public/images/user.webp" width="100px" height="100px" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                    <div class="media-body">
                                        <h6>{{$v->name}}<small> - <i>{{date(" F d Y ",strtotime($v->created_at))}}</i></small></h6>
                                        <p>{{$v->review}}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                               
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
                                    </div>
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
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->
<div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">YOU MAY ALSO LIKE
</span></h2>
        <div class="row px-xl-5">
            @foreach ($rproducts as  $k=>$v)
            @include('includes/parts/product_box')
            @endforeach
        </div>
    </div>
<!--Feater end-->
    <!-- Products End -->
@endforeach
<script>
    let id,qty,price,productTotal;
    $(document).ready(function(){

        $('.ion-close').click(function(e){
        e.preventDefault();
           id = $(this).attr('productId');
          $.ajax({
              url : "{{url('cart/remove')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                  location.reload();
                  console.log(id);
                  removeFromView(id,response);
                  updateView(response);
                  location.reload();
              }
          });
      }); 
      

        $('.clear').click(function(e){
        e.preventDefault();
        //   id = $(this).attr('productId');
          $.ajax({
              url : "{{url('cart/clear')}}",
              type : "POST",
              data : {
                  
                  "_token": "{{ csrf_token() }}"
              },
              success:function(response){
                  location.reload();
                  
              }
          });
      }); 
      
      $('.plus').click(function(){
         id = $(this).attr('productId');
         price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/increment')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                 if(response.error){
                    alert('Item out of stock');
                 } else {
                      $('#spec'+id).val(parseInt($('#spec'+id).val())+1);
                        qty=$('#spec'+id).val();
                      updateView(response,price);
                  } 
              }
          });
      });

      $('.minus').click(function(){
           id = $(this).attr('productId');
           price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/decrement')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                   qty = (parseInt($('#spec'+id).val())-1);
                  if(qty > 0) $('#spec'+id).val(qty);
                  else {
                      removeFromView(id,response);
                  }
                  updateView(response,price);
              }
          });
      });

      function updateView(response){
        productTotal=parseInt(qty*price);
          $('#cartValue').html(response.cart.qty);
          $('#cartTotal').html(response.cart.amount);
          $('#productTotal'+id).html(productTotal);
      }
      
    });
</script>



@endsection