@extends('layout.app')
@section('content')
<?php 
use App\Models\Admins\Category;
$cate = Category::limit('6')->get();
?>
  <!-- Bread Crumb STRAT -->
  <div class="banner inner-banner1 ">
    <div class="container">
      <section class="banner-detail center-xs">
        <h1 class="banner-title">Shop</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="/">Home</a>/</li>
            <li><span>Shop</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  <!-- Bread Crumb END -->  
  
  <!-- CONTAIN START -->
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-lg-2 col-md-3 mb-sm-30 col-lgmd-20per">
          <div class="sidebar-block">
            <div class="sidebar-box listing-box mb-40"> <span class="opener plus"></span>
              <div class="sidebar-title">
                <h3>Categories</h3> <span></span>
              </div>
              <div class="sidebar-contant">
                <ul>
                    @foreach($cate as $v)
                    <li><a href="/category/{{$v->name}}">{{$v->name}}<span></span></a></li>
                    @endforeach
                </ul>
              </div>
            </div>
            <!--<div class="sidebar-box mb-40 visible-md visible-lg"> -->
            <!--  <a href="#"> -->
            <!--    <img src="http://aaryaweb.info/html/electro/elc002/images/left-banner.jpg" alt="Electrro"> -->
            <!--  </a> -->
            <!--</div>-->
            <div class="sidebar-box sidebar-item"> <span class="opener plus"></span>
              <div class="sidebar-title">
                <h3>Best Seller</h3> <span></span>
              </div>
              <div class="sidebar-contant">
                <ul>
                @foreach($best as $aproduct)
                  <li>
                    <div class="pro-media"> <a href="/product/{{$aproduct->slug}}"><img alt="T-shirt" src="{{asset($aproduct->image_one)}}"></a> </div>
                    <div class="pro-detail-info"> <a>{{$aproduct->product_name}}</a>
                      <div class="rating-summary-block">
                        <!--<div class="rating-result" title="53%"> <span style="width:53%"></span> </div>-->
                      </div>
                      <div class="price-box"> <span class="price">Rs {{$aproduct->discount_price}}</span> </div>
                      <div class="cart-link">
                        <form>
                          <button title="Add to Cart" id="{{$aproduct->id}}" class="add-to-cart-item">Add To Cart</button>
                        </form>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-10 col-md-9 col-lgmd-80per">
          <div class="inner-banner2 mb-40 visible-md visible-lg">
            <a href="shop.html">
              <img src="http://aaryaweb.info/html/electro/elc002/images/inner-banner2.jpg" alt="Electrro">
            </a>
          </div> 
          <div class="product-listing">
            <div class="inner-listing">
              <div class="row">
                 @php $products = DB::table('products')->orderby('id', 'desc')
              ->paginate(6);
              @endphp
                     @foreach ($products as  $k=>$aproduct)
                <div class="col-md-4 col-xs-6 mb-30">
                  
                  <div class="product-item">
                     
                    <div class="product-image"> <a href="/product/{{$aproduct->slug}}"> <img src="{{asset($aproduct->image_one)}}" alt="{{$aproduct->product_name}}"> </a>
                      <div class="product-detail-inner">
                        <div class="detail-inner-left left-side">
                          <ul>
                            <li class="pro-cart-icon">
                            <form>
                              <button title="Add to Cart" class="add-to-cart-item" id="{{$aproduct->id}}"><span></span>Add to Cart</button>
                            </form>
                          </li>
                          </ul>
                        </div>
                        <div class="detail-inner-left right-side">
                          <!--<ul>-->
                          <!--  <li class="pro-wishlist-icon active"><a href="#" title="Wishlist"></a></li>-->
                          <!--  <li class="pro-compare-icon"><a href="#" title="Compare"></a></li>-->
                          <!--</ul>-->
                        </div>
                      </div>
                    </div>
                    <div class="product-item-details">
                      <div class="product-item-name"> <a href="/product/{{$aproduct->slug}}">{{$aproduct->product_name}}</a> </div>
                      <div class="price-box"> <span class="price">Rs {{$aproduct->discount_price}}</span> <del class="price old-price">Rs {{$aproduct->selling_price}}</del> </div>
                      <div class="rating-summary-block right-side">
                        <div title="100%" class="rating-result"> <span style="width:100%"></span> </div>
                      </div>
                    </div>
                   
                  </div>
                    
                </div>
                 @endforeach
                
              </div>
               <div class="row">
                <div class="col-xs-12">
                  <div class="pagination-bar">
                       
                      {!!$products->render()  !!}
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- CONTAINER END --> 
@endsection
