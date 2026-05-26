@extends('layout.app')

<?php
use App\Models\Admins\Category;
use App\Models\product;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Rating;
  ?>


@section('content')
@foreach($product as $item)
<!-- Page Header Start -->
   <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{$item->product_name}}</h1>
            <div class="d-inline-flex">
               <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList" style=" background-color: transparent; ">
                        <li itemprop="itemListElement" itemscope
                            itemtype="https://schema.org/ListItem" class="breadcrumb-item"><a itemtype="https://schema.org/Thing"
                                                                      itemprop="item"  href="/"><span itemprop="name"><i class="icofont icofont-ui-home"></i> Home</span></a></a>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li itemprop="itemListElement" itemscope
                              itemtype="https://schema.org/ListItem" class="breadcrumb-item" >
                                    <a itemtype="https://schema.org/Thing"
                                       itemprop="item" href="/category/{{$cate->slug}}">
                                        <span itemprop="name">{{$cate->name}}</span>  </a>
                                    <meta itemprop="position" content="2" />
                                    </li>
                                                        <li itemprop="itemListElement" itemscope
                            itemtype="https://schema.org/ListItem" class="breadcrumb-item" class="breadcrumb-item active" >
                            <a itemtype="https://schema.org/Thing"
                               itemprop="item" href="/product/{{$item->slug}}">
                                <span itemprop="name">{{$item->product_name}}</span>
                                <meta itemprop="position" content="3" />
                            </a>
                        </li>
                   </ul>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <meta itemprop="mpn" content="{{$item->mpn}}" />
            <meta itemprop="name" content="{{$item->product_name}}" />
            <?php
            
            
            ?>
            <link itemprop="image" href="/{{$item->image_one}}" />
            
            
            <div itemprop="offers" itemtype="https://schema.org/Offer" itemscope>
                <meta itemprop="priceCurrency" content="PKR" />
                <meta itemprop="price" content="{{$item->discount_price}}" />
                <meta itemprop="priceValidUntil" content="2023-12-30" />
                <meta itemprop="availability" content="https://schema.org/InStock" />
                <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
                <link itemprop="url" href="https://etsystore.detox.com.pk/product/{{$item->slug}}" />
                <div itemprop="seller" itemtype="https://schema.org/Organization" itemscope>
                    <meta itemprop="name" content="{{$sett->site_title}}" />
                </div>
            </div>

            <div itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating" itemscope>
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
                <meta itemprop="reviewCount" content="{{$countcustomer}}" />
                <meta itemprop="ratingValue" content="{{$finalresult}}" />
                <?php }?>
            </div>
                 <div itemprop="review" itemtype="https://schema.org/Review" itemscope>
                    <?php
                    foreach ($getreview as $avg){ ?>
                    <div itemprop="author" itemtype="https://schema.org/Person" itemscope>
                        <meta itemprop="name" content="{{$avg->name}}" />
                    </div>
                    <div itemprop="reviewRating" itemtype="https://schema.org/Rating" itemscope>
                        <meta itemprop="ratingValue" content="{{$avg->rate}}" />
                        <meta itemprop="bestRating" content="5" />
                    </div>
                    <?php }?>
                </div>
                <meta itemprop="sku" content="{{$item->sku}}" />
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="/{{$item->image_one}}" alt="Image">
                        </div>
                        <?php $Galleries = Gallerie::where(['product_id'=>$item->id])->get(); ?>
                        @foreach($Galleries as $Gallerie)
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="{{$Gallerie->photo}}" alt="Image">
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" title="prev" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" title="next" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            
            
            

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{$item->product_name}}</h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                    </div>
                    <?php 
                    $data=Rating::where('pid',$item->id)->where('status',1)->sum('rate');
                    $count = Rating::where(['pid'=>$item->id])->where('status',1)->count();
                    if($count && $data){
                        $rate = $data/$count;
                    }else{
                    $rate = 0;
                    }
                    ?>
                    <small class="pt-1">({{number_format($rate,2)}}/5.00)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">Rs:{{$item->discount_price}}</h3>
                <span><h4>Size</h4>:{{$item->size}}</span>
                <span><h4>Made in</h4>:{{$item->made_in}}</span>
                <span><h4>Availablity</h4><spam>:<?php if($item->product_quantity > 0){?>In Stock<?php }else{?>Out Of Stock<?php }?></span>
                <span><h4>Quantity</h4>:{{$item->product_quantity}}</span>
                <span><h4>Shipping</h4>:2 - 3 Business Days (in Pakistan)</span>
                <p class="mb-4"><?= $item->short_discriiption; ?></p>
                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus" title="minus">
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center" title="qty" id="qty" name="qty" value="1">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus" title="plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary px-3 add-to-cart" title="cart"  id="{{$item->id}}"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</button><br><br>
                  
                </div>
                <div class="d-flex pt-2">
                   
                    <div class="d-inline-flex">
                         <button class="btn btn-primary px-3 add-to-cart-item1" id="{{$item->id}}" href="#"><i class="fa fa-shopping-bag"></i>Buy Now</button>
                    <button onclick="window.location='https://api.whatsapp.com/send?phone=<?= $sett->phone?>&amp;text=Hello, I want to purchase:*{{$item->product_name}}* Price:*{{$item->discount_price}} URL:*{{ url('/'); }}/{{$item->slug}}* Thank You !';" class="btn btn-primary px-3 ml-2" ><i class="fa fa-whatsapp"></i> Chat On Whatsapp</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Tags & Keywords</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews ({{$rcount}})</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        <p><?= $item->product_details?></p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <div class="items-Product-Tags">
                        <div class="reviews__comment--content">
                                        <h3>Tag's</h3>
                                        <?php 
                                        $tags = explode(',',$item['tags']);
                                        foreach($tags as $k=> $v){
                                            if(!empty($v))
                                                {
                                            // $tag = str_replace(' ', '-', $v);
                                        ?>
                                        <a href="/product-tag/{{$v}}" class="btn btn-primary"><?= $v; ?></a>
                                        <?php }}?>
                                    </div>
                    </div>
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
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                 @if(!empty($rproducts) && $rproducts->count())
                  @foreach ($rproducts as  $k=>$aproduct)
                  @if($aproduct->status == 1)
                    <div class="card product-item border-0">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class="img-fluid w-100" src="{{asset($aproduct->image_one)}}" width="319px" height="319px" alt="">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">{{$aproduct->product_name}}</h6>
                            <div class="d-flex justify-content-center">
                                <h6>RS {{$aproduct->discount_price}}</h6><h6 class="text-muted ml-2"><del>RS {{$aproduct->selling_price}}</del></h6>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <a href="/product/{{$aproduct->slug}}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                            <a  class="btn btn-sm text-dark p-0 add-to-cart-item" id="{{$aproduct->id}}"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                        </div>
                    </div>
                    @endif
                   @endforeach
                   @endif
                </div>
            </div>
        </div>
    </div>
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