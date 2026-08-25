@extends($layout)
@section('content')
@php
    $cartProducts = App\Helpers\Cart::products();
    $tot = 0;
    foreach ($cartProducts as $product) {
        $tot += ($product->discount_price ?: $product->selling_price) * $product->qty;
    }
    $userData = isset($user) && count($user) ? $user[0] : null;
@endphp
<section class="t4-page">
    <div class="container">
        <form action="{{ url('/order_submit') }}" method="post">
            @csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <h2>Billing details</h2>
                    <div class="t4-checkout-grid">
                        <input type="text" name="name" placeholder="Your name" required value="{{ $userData->name ?? '' }}">
                        <input type="email" name="email" placeholder="Email" required value="{{ $userData->email ?? '' }}">
                        <input type="text" name="phone" placeholder="Phone" required value="{{ $userData->phone ?? '' }}">
                        <input type="text" name="city" placeholder="City">
                        <select name="country" class="full"><option selected>Pakistan</option></select>
                        <textarea name="address" class="full" placeholder="Address" required>{{ $userData->address ?? '' }}</textarea>
                    </div>
                </div>
                <div class="col-lg-5">
                    <h2>Your order</h2>
                    <ul class="list-unstyled">
                        @foreach($cartProducts as $product)
                        <li class="d-flex justify-content-between py-2">
                            <span>{{ $product->product_name }} × {{ $product->qty }}</span>
                            <span>{{ format_amount(($product->discount_price ?: $product->selling_price) * $product->qty) }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <p>Shipping: {{ format_amount($setting->shipping_charges ?? 0) }}</p>
                    <h4>Total: {{ format_amount($tot + ($setting->shipping_charges ?? 0)) }}</h4>
                    @if(!empty($payment_methods) && count($payment_methods))
                        @foreach($payment_methods as $pm)
                            <label class="d-block"><input type="radio" name="payment_method" value="{{ $pm->id }}" required> {{ $pm->name }}</label>
                        @endforeach
                    @else
                        <label><input type="radio" name="payment" value="cod" checked> Cash on delivery</label>
                    @endif
                    <button type="submit" class="shop-btn mt-3">Place order</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
