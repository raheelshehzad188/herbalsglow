                                    @foreach($rproducts as $k=> $v)
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

    
?>
                                    <div class="tf-loop-item">
                                    <div class="image">
                                        <a href="{{url('/')}}/product/{{$v->slug}}">
                                            <img src="{{env('IMG_URL')}}{{$v->image_one}}" alt="">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <a href="{{url('/')}}/product/{{$v->slug}}">{{$v->product_name}}</a>
                                        <div class="tf-product-info-price">
                                            <div class="price-on-sale fw-6">Rs{{$v->discount_price}}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach