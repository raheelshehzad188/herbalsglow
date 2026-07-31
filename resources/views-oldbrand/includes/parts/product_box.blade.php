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
<div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <a class="h6 text-decoration-none text-truncate" href="{{url('/')}}/product/{{$v->slug}}">
                        @if($final_percntage)
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{asset($v->image_one)}}" alt="">
                        @if($v->status)
                        <span class="discout_price">-{{ $final_percntage }}%</span>
                        @else
                        <span class="discout_price">Out of stock</span>
                        @endif
                    </div>
                    @endif
                    </a>
                    <div class="text-center py-4">
                        <a></a>{{$v->product_name}}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>Rs{{$v->discount_price}}</h5><h6 class="text-muted ml-2"><del>Rs {{$v->selling_price}}</del></h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <?php
                            for($i= 1; $i<=5;$i++)
                            {
                                if($i <= $rate)
                                {
                                ?>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <?php
                                }
                                else
                                {
                                 ?>
                            <small class="far fa-star text-primary mr-1"></small>
                            <?php   
                                }
                            }
                            ?>
                            <small>(<?= $count ?>)</small>
                        </div>
                    </div>
                </div>
            </div>