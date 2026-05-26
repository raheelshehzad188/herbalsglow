@extends($layout)

<?php
use App\Models\Admins\Category;
use App\Models\Admins\SubCategory;
use App\Models\Childcatagorie;
use App\Models\product;
use App\Models\Admins\Gallerie;
  ?>
  <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
$cate = DB::table('categories')->get();
?>


@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<form action="/order_submit" method="post" class="form-checkout">
@csrf
<section class="flat-spacing-11 billing-details-page">
            <div class="container">
                <div class="tf-page-cart-wrap layout-2 checkout-layout">

                    <div class="tf-page-cart-item">
                        <h5 class="fw-5 mb_20">Billing details</h5>
                        
                            <div class="box grid-2">
                                <fieldset class="fieldset">
                                    <label for="first-name">Your Name</label>
                                    <input type="text" name="name" id="first-name" placeholder="Your name" required>
                                </fieldset>
                                <fieldset class="fieldset">
                                    <label for="last-name">Email</label>
                                    <input type="text" name="email" required>
                                </fieldset>
                                <fieldset class="fieldset">
                                    <label for="last-name">Phone</label>
                                    <input type="text" name="phone" required>
                                </fieldset>
                                <fieldset class="box fieldset">
									<label for="city">Town/City</label>
									<input type="text" name="city" id="city">
								</fieldset>   
                            </div>
                            <fieldset class="box fieldset">
								<label for="city">Country/Region</label>
								<select class="tf-select w-100" required name="country" >
									<option selected>Pakistan</option>
								</select>
                            </fieldset>
                            <fieldset class="box fieldset">
                                <label for="note">Address</label>
                                <textarea name="address" id="note"></textarea>
                            </fieldset>
                    </div>
                    <div class="tf-page-cart-footer">
                        <div class="tf-cart-footer-inner tf-page-cart-checkout widget-wrap-checkout">
                            <h5 class="fw-5 mb_20">Your order</h5>
                                <ul class="wrap-checkout-product">
                                    @if (Session::has('cart'))
                                                @php
                        $tot = 0;
                        @endphp
                                    @foreach (App\Helpers\Cart::products() as $product)
                                    @php
                        $tot = $tot+ ($product->discount_price* $product->qty);
                        @endphp
                                    <li class="checkout-product-item">
                                        <figure class="img-product">
                                            <img src="{{ img_url($product->image_one) }}" alt="product">
                                            <span class="quantity">{{$product['qty']}}</span>
                                        </figure>
                                        
                                        <div class="content">
                                            <div class="info">
                                                <p class="name">{{$product->product_name}}</p>
                                            </div>
                                            <span class="span-price-total-checkout">  Rs:{{format_amount($product->discount_price)}} x {{ $product['qty'] }} </span>
                                            <div onclick="window.location.href = '{{ url('cart/remove/'.$product->id)}}'
											" class="tf-mini-cart-remove" bis_skin_checked="1">Remove</div>
                                        </div>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                                @php
                                    $shippingCharges = Session::has('cart') ? ($setting->shipping_charges ?? 0) : 0;
                                    $subtotal = $tot;
                                    $grandTotal = $subtotal + $shippingCharges;
                                @endphp
                                <div class="d-flex justify-content-between line pb_10 chk-out-ttl-pr">
                                    <h6 class="fw-5">Subtotal:</h6>
                                    <h6 class="fw-5" id="cartTotal1">Rs:{{format_amount($subtotal)}}</h6>
                                </div>
                                <div class="d-flex justify-content-between line pb_10 chk-out-ttl-pr">
                                    <h6 class="fw-5">Delivery Charges:</h6>
                                    <h6 class="fw-5" id="shippingCharges">Rs:{{format_amount($shippingCharges)}}</h6>
                                </div>
                                <div class="d-flex justify-content-between line pb_20 chk-out-ttl-pr">
                                    <h6 class="fw-5">Total:</h6>
                                    <h6 class="total fw-5" id="cartTotal">Rs:{{format_amount($grandTotal)}}</h6>
                                </div>
                                <div class="wd-check-payment chk-cash-o-d checkout-payment-section">
                                    <h5 class="fw-5 mb_10">Payment</h5>
                                    <p class="text_black-2 mb_15 checkout-payment-note">All transactions are secure and encrypted.</p>
                                    @if(isset($payment_methods) && count($payment_methods) > 0)
                                    <div class="checkout-payment-gateways mb_20">
                                        @foreach($payment_methods as $gateway)
                                        <div class="payment-gateway-item {{ $loop->first ? 'is-selected' : '' }}" data-gateway-id="{{ $gateway->id }}">
                                            <div class="fieldset-radio payment-gateway-head mb_0">
                                                <input
                                                    type="radio"
                                                    name="payment_method_id"
                                                    id="payment-gateway-{{ $gateway->id }}"
                                                    class="tf-check payment-gateway-radio"
                                                    value="{{ $gateway->id }}"
                                                    {{ $loop->first ? 'checked' : '' }}
                                                    required
                                                >
                                                <label for="payment-gateway-{{ $gateway->id }}">{{ $gateway->title }}</label>
                                            </div>
                                            @if(!empty($gateway->detail))
                                            <div class="payment-gateway-detail" {!! $loop->first ? '' : 'style="display:none;"' !!}>
                                                {!! nl2br(e($gateway->detail)) !!}
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="fieldset-radio mb_20">
                                        <input type="radio" name="payment_method_id" id="delivery" class="tf-check" value="0" checked required>
                                        <label for="delivery">Cash on delivery</label>
                                    </div>
                                    @endif
                                    <p class="text_black-2 mb_20">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="{{ url('privacy-policy'); }}" class="text-decoration-underline">privacy policy</a>.</p>
                                    <div class="box-checkbox fieldset-radio mb_20 chk-t-and-c">
                                        <input type="checkbox" id="check-agree" class="tf-check">
                                        <label for="check-agree" class="text_black-2">I have read and agree to the website <a href="{{ url('terms-conditions'); }}" class="text-decoration-underline">terms and conditions</a>.</label>
                                    </div>
                                </div>
                                <button type="submit" class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center">Place order</button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </form>
    
  
<script>
    let id,qty,price,productTotal;
    $(document).ready(function(){

        function syncPaymentGatewayDetails() {
            $('.payment-gateway-item').each(function () {
                var $item = $(this);
                var $radio = $item.find('.payment-gateway-radio');
                var $detail = $item.find('.payment-gateway-detail');
                if ($radio.is(':checked')) {
                    $item.addClass('is-selected');
                    $detail.show();
                } else {
                    $item.removeClass('is-selected');
                    $detail.hide();
                }
            });
        }

        $(document).on('change', '.payment-gateway-radio', syncPaymentGatewayDetails);
        syncPaymentGatewayDetails();

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
         id = $(this).attr('productid');
           var qty = $('#qty'+id).val();
           price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/increment')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                 qty++;
                  $('#qty'+id).val(qty);
                  updateView(response,price);
              }
          });
      });

      $('.minus').click(function(){
           id = $(this).attr('productid');
           var qty = $('#qty'+id).val();
           price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/decrement')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                  qty--;
                  $('#qty'+id).val(qty);
                  updateView(response,price);
                 
              }
          });
      });

      function updateView(response){
        productTotal=parseInt(qty*price);
        var shippingCharges = {{ isset($shippingCharges) ? $shippingCharges : ($setting->shipping_charges ?? 0) }};
        var subtotal = response.cart.amount || 0;
        var grandTotal = subtotal + shippingCharges;
        
          $('#cartValue').html(response.cart.qty);
          $('#price').html(response.cart.ship);
          $('#cartTotal1').html('Rs:' + subtotal.toLocaleString());
          $('#shippingCharges').html('Rs:' + shippingCharges.toLocaleString());
          $('#cartTotal').html('Rs:' + grandTotal.toLocaleString());
          $('#productTotal'+id).html(productTotal);
      }
      
    });
</script>



@endsection