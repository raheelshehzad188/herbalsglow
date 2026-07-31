@extends($layout)

@section('content')
@php
    $currency = env('CUR', 'Rs.');
    $cartSession = Session::get('cart');
    $cartProducts = App\Helpers\Cart::products();
    $cartCount = (int) ($cartSession['qty'] ?? 0);
    $itemsTotal = 0;
    $userData = isset($user) && count($user) ? $user[0] : null;
    $deliveryFrom = now()->addDays(10)->format('M j');
    $deliveryTo = now()->addDays(12)->format('M j');

    foreach ($cartProducts as $product) {
        $itemsTotal += cart_product_unit_price($product) * (int) $product->qty;
    }

    $shippingFee = $cartCount > 0 ? (float) ($setting->shipping_charges ?? 0) : 0;
    $grandTotal = $itemsTotal + $shippingFee;
@endphp

<link rel="stylesheet" href="{{ asset('theme3/css/t3-cart.css') }}">
<link rel="stylesheet" href="{{ asset('theme3/css/t3-checkout.css') }}">

<div class="t3-cart-page t3-checkout-page">
    <form action="{{ url('/order_submit') }}" method="post" class="t3-checkout-form" id="t3-checkout-form">
        @csrf
        <div class="t3-cart-shell">
            <div class="t3-cart-main">
                <div class="t3-checkout-header">
                    <div class="t3-checkout-header__top">
                        <span class="t3-checkout-header__title">Checkout</span>
                        <a href="{{ url('/cart') }}" class="t3-checkout-header__back">← Back to cart</a>
                    </div>
                </div>

                <div class="t3-checkout-card">
                    <h2>Shipping details</h2>
                    <div class="t3-checkout-grid">
                        <div class="t3-checkout-field">
                            <label for="checkout-name">Full name</label>
                            <input type="text" name="name" id="checkout-name" placeholder="Your name" value="{{ old('name', $userData->name ?? '') }}" required>
                        </div>
                        <div class="t3-checkout-field">
                            <label for="checkout-email">Email</label>
                            <input type="email" name="email" id="checkout-email" placeholder="you@example.com" value="{{ old('email', $userData->email ?? '') }}" required>
                        </div>
                        <div class="t3-checkout-field">
                            <label for="checkout-phone">Phone</label>
                            <input type="text" name="phone" id="checkout-phone" placeholder="03XX XXXXXXX" value="{{ old('phone', $userData->phone ?? '') }}" required>
                        </div>
                        <div class="t3-checkout-field">
                            <label for="checkout-city">City</label>
                            <input type="text" name="city" id="checkout-city" placeholder="City" value="{{ old('city') }}" required>
                        </div>
                        <div class="t3-checkout-field t3-checkout-field--full">
                            <label for="checkout-country">Country / Region</label>
                            <select name="country" id="checkout-country" required>
                                <option value="Pakistan" selected>Pakistan</option>
                            </select>
                        </div>
                        <div class="t3-checkout-field t3-checkout-field--full">
                            <label for="checkout-address">Delivery address</label>
                            <textarea name="address" id="checkout-address" placeholder="House no, street, area" required>{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="t3-checkout-card">
                    <h2>Payment method</h2>
                    <p class="t3-checkout-note">All transactions are secure. Choose how you would like to pay.</p>

                    <div class="t3-checkout-payment">
                        @if(isset($payment_methods) && count($payment_methods) > 0)
                            @foreach($payment_methods as $gateway)
                                <div class="t3-checkout-payment-item {{ $loop->first ? 'is-selected' : '' }}" data-gateway-id="{{ $gateway->id }}">
                                    <div class="t3-checkout-payment-head">
                                        <input
                                            type="radio"
                                            name="payment_method_id"
                                            id="payment-gateway-{{ $gateway->id }}"
                                            class="t3-checkout-payment-radio"
                                            value="{{ $gateway->id }}"
                                            {{ $loop->first ? 'checked' : '' }}
                                            required
                                        >
                                        <label for="payment-gateway-{{ $gateway->id }}">{{ $gateway->title }}</label>
                                    </div>
                                    @if(!empty($gateway->detail))
                                        <div class="t3-checkout-payment-detail" {!! $loop->first ? '' : 'style="display:none;"' !!}>
                                            {!! nl2br(e($gateway->detail)) !!}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="t3-checkout-payment-item is-selected">
                                <div class="t3-checkout-payment-head">
                                    <input type="radio" name="payment_method_id" id="payment-cod" value="0" checked required>
                                    <label for="payment-cod">Cash on delivery</label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="t3-cart-aside">
                <div class="t3-cart-summary">
                    <h2>Order summary</h2>

                    <div class="t3-checkout-items">
                        @foreach($cartProducts as $product)
                            @php
                                $lineUnit = cart_product_unit_price($product);
                                $lineTotal = $lineUnit * (int) $product->qty;
                            @endphp
                            <div class="t3-checkout-item">
                                <img src="{{ img_url($product->image_one) }}" alt="{{ $product->product_name }}" loading="lazy">
                                <div>
                                    <a href="{{ url('/product/' . $product->slug) }}" class="t3-checkout-item__title">{{ $product->product_name }}</a>
                                    <div class="t3-checkout-item__meta">Qty: {{ $product->qty }}</div>
                                    <div class="t3-checkout-item__price">{{ $currency }}{{ number_format($lineTotal, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="t3-cart-summary-row">
                        <div>Items total ({{ $cartCount }})</div>
                        <div>{{ $currency }}{{ number_format($itemsTotal, 2) }}</div>
                    </div>
                    <div class="t3-cart-summary-row">
                        <div>Shipping</div>
                        <span>{{ $currency }}{{ number_format($shippingFee, 2) }}</span>
                    </div>
                    <div class="t3-cart-summary-row total">
                        <span>Total</span>
                        <span>{{ $currency }}{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <div class="t3-checkout-terms">
                        <input type="checkbox" id="checkout-agree" required>
                        <label for="checkout-agree">
                            I have read and agree to the
                            <a href="{{ url('/terms-conditions') }}" target="_blank">terms and conditions</a>
                            and
                            <a href="{{ url('/privacy-policy') }}" target="_blank">privacy policy</a>.
                        </label>
                    </div>

                    <button type="submit" class="t3-checkout-submit" id="t3-checkout-submit">Place order</button>
                    <div class="t3-cart-edd">Estimated delivery {{ $deliveryFrom }} – {{ $deliveryTo }}</div>

                    <div class="t3-cart-payments">Accepted payment methods</div>
                    <div>
                        <img src="https://s3.images-iherb.com/static/i/payment/adyen_visa.svg" alt="Visa" loading="lazy" height="24">
                        <img src="https://s3.images-iherb.com/static/i/payment/mc.svg" alt="Master Card" loading="lazy" height="24">
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    function syncPaymentItems() {
        document.querySelectorAll('.t3-checkout-payment-item').forEach(function (item) {
            var radio = item.querySelector('.t3-checkout-payment-radio, input[type="radio"]');
            var detail = item.querySelector('.t3-checkout-payment-detail');
            if (!radio) {
                return;
            }
            if (radio.checked) {
                item.classList.add('is-selected');
                if (detail) {
                    detail.style.display = '';
                }
            } else {
                item.classList.remove('is-selected');
                if (detail) {
                    detail.style.display = 'none';
                }
            }
        });
    }

    document.querySelectorAll('.t3-checkout-payment-radio, .t3-checkout-payment-item input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', syncPaymentItems);
    });

    document.querySelectorAll('.t3-checkout-payment-item').forEach(function (item) {
        item.addEventListener('click', function (event) {
            if (event.target.tagName === 'A') {
                return;
            }
            var radio = item.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                syncPaymentItems();
            }
        });
    });

    syncPaymentItems();

    var form = document.getElementById('t3-checkout-form');
    var submitBtn = document.getElementById('t3-checkout-submit');
    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Placing order...';
        });
    }
})();
</script>
@endsection
