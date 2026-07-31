
<?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
    $rating = DB::table('rating')
    ->where('pid', '=', $v->id)
    ->get();
    $sum = 0;
    $count = 0;
    foreach($rating as $k=> $p)
    {
        $count++;
        $sum = $sum + $p->rate;
    }
    $rate = 0;
    if($count)
    $rate = $sum /$count;
    
    
$cate = DB::table('categories')->get();
// shaheer 
    $saling_price = intval($v->selling_price);
$discount_price = intval($v->discount_price);

$tot_price = $saling_price - $discount_price;

$perctg = 0;
if($saling_price)
$perctg = ($tot_price / $saling_price) * 100;
$final_percntage = ceil($perctg);

    
?><div class="card-product style-skincare">
                                                <div class="card-product-wrapper">
                                                    <a href="{{url('/')}}/product/{{$v->slug}}" class="product-img">
                                                        <div class="list-product-btn">
                                <a href="#quick_add" class="box-icon bg_white quick-add tf-btn-loading add-to-cart" id="{{$v->id}}">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Quick Add</span>
                                </a>
                                <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="{{url('/')}}/product/{{$v->slug}}" data-bs-toggle="modal" class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                            <a href="{{url('/')}}/product/{{$v->slug}}">
                                                        <img class="1st lazyload img-product" data-src="{{env('IMG_URL')}}{{$v->image_one}}" src="{{env('APP_URL')}}{{$v->image_one}}" alt="image-product">
                                                        </a>
                                                </div>
                                                <div class="card-product-info text-center">
                                                    <a href="{{url('/')}}/product/{{$v->slug}}" class="title link">{{$v->product_name}}</a>
                                                    <span class="price">Rs{{$v->discount_price}}</span>
                                                </div>
                                            </div>