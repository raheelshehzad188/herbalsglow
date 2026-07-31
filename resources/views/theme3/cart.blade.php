@extends($layout)

@section('content')
@php
    $currency = env('CUR', 'Rs.');
    $cartSession = Session::get('cart');
    $cartProducts = $cartProducts ?? App\Helpers\Cart::products();
    $recommendedProducts = $recommendedProducts ?? collect();
    $cartCount = (int) ($cartSession['qty'] ?? 0);
    $itemsTotal = 0;

    foreach ($cartProducts as $product) {
        $itemsTotal += cart_product_unit_price($product) * (int) $product->qty;
    }

    $shippingFee = $cartCount > 0 ? (float) ($setting->shipping_charges ?? 0) : 0;
    $grandTotal = $itemsTotal + $shippingFee;
    $upsellProducts = $recommendedProducts->take(4);
    $deliveryFrom = now()->addDays(10)->format('M j');
    $deliveryTo = now()->addDays(12)->format('M j');
                        @endphp

<link rel="stylesheet" href="{{ asset('theme3/css/t3-cart.css') }}">

<div id="cartv2" class="t3-cart-page">
    <div class="t3-cart-shell">
        <div class="t3-cart-main">
            @if($cartCount > 0)
                <div class="t3-cart-note" data-qa-element="gl-special-note">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" width="24" height="24" aria-hidden="true">
                        <path fill="#004E9B" d="M24 44C12.954 44 4 35.046 4 24S12.954 4 24 4s20 8.955 20 20-8.954 20-20 20m0-24a2 2 0 0 0-2 2v12a2 2 0 0 0 4 0V22a2 2 0 0 0-2-2m0-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4"></path>
                    </svg>
                    <div>
                        <strong>Special note:</strong>
                        Orders are shipped from our warehouses via air freight. You may be responsible for import taxes, customs duties, or brokerage fees. These charges are not included in your shipping fee and will be billed directly by the carrier.
                    </div>
                </div>

                @if($upsellProducts->count())
                    <div class="t3-cart-offer" data-qa-element="new-rec-carousel">
                        <div class="t3-cart-offer__head">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true">
                                <path fill="#333" d="m19.757 29.414-9.693-1.385a2.2 2.2 0 0 1-1.407-3.552L23.98 5.323a2.2 2.2 0 0 1 3.896 1.685l-1.33 9.315a2 2 0 0 0 1.697 2.263l9.693 1.385a2.2 2.2 0 0 1 1.407 3.552L24.019 42.677a2.2 2.2 0 0 1-3.895-1.685l1.33-9.315a2 2 0 0 0-1.697-2.263"></path>
                                            </svg>
                            <strong>Time-limited offer</strong>
                        </div>
                        <div class="t3-cart-scroll">
                            @foreach($upsellProducts as $offer)
                                @php
                                    $offerUnit = cart_product_unit_price($offer);
                                    $offerList = cart_product_list_price($offer);
                                    $offerHasSale = $offerList > $offerUnit;
                                @endphp
                                <div class="t3-cart-mini-card">
                                    <a href="{{ url('/product/' . $offer->slug) }}" aria-label="{{ $offer->product_name }}">
                                        <img src="{{ img_url($offer->image_one) }}" alt="{{ $offer->product_name }}" loading="lazy">
                                    </a>
                                    <div>
                                        <a href="{{ url('/product/' . $offer->slug) }}" class="t3-cart-mini-card__title">{{ $offer->product_name }}</a>
                                        <div class="t3-cart-mini-card__price">
                                            <span>{{ $currency }}{{ number_format($offerUnit, 2) }}</span>
                                            @if($offerHasSale)
                                                <del>{{ $currency }}{{ number_format($offerList, 2) }}</del>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="t3-cart-mini-card__btn t3-add-to-cart" data-product-id="{{ $offer->id }}" title="Add to cart" aria-label="Add to cart">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" width="15" height="15" aria-hidden="true">
                                            <g fill="#fff">
                                                <path d="M7 8a2 2 0 1 1 0-4h.276a6 6 0 0 1 5.984 5.573l1.47 20.57A2 2 0 0 0 16.724 32h15.568a2 2 0 0 0 1.857-1.257L39 18c1.22-3.05 5.051-1.743 3.714 1.486l-4.851 12.742A6 6 0 0 1 32.292 36H16.724a6 6 0 0 1-5.984-5.573L9.27 9.857A2 2 0 0 0 7.276 8zm9 36a3 3 0 1 1 0-6 3 3 0 0 1 0 6m16 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6"></path>
                                                <path d="M28 12a2 2 0 1 0-4 0v4h-4a2 2 0 1 0 0 4h4v4a2 2 0 1 0 4 0v-4h4a2 2 0 1 0 0-4h-4z"></path>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                                </div>
                            </div>
                @endif

                <div class="t3-cart-header">
                    <div class="t3-cart-header__top">
                        <span class="t3-cart-header__title" data-qa-element="shopping-cart-text">Cart ({{ $cartCount }})</span>
                        <button type="button" class="t3-cart-header__ship" data-qa-element="cart-shipping-destination">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" width="16" height="16" aria-hidden="true">
                                <path fill="#333" d="M24 44Q8 30 8 20c0-8.837 7.163-16 16-16s16 7.163 16 16q0 10-16 24m0-18a6 6 0 1 0 0-12 6 6 0 0 0 0 12"></path>
                            </svg>
                            <span>Ship to Pakistan</span>
                        </button>
                    </div>
                    <div class="t3-cart-header__actions">
                        <button type="button" id="t3-cart-remove-all" data-qa-element="cart-remove-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true">
                                <g fill="currentColor">
                                    <path d="M19 18a2 2 0 0 1 2 2v14a2 2 0 1 1-4 0V20a2 2 0 0 1 2-2M31 20a2 2 0 1 0-4 0v14a2 2 0 1 0 4 0z"></path>
                                    <path d="M16.252 10a8.003 8.003 0 0 1 15.496 0H42a2 2 0 1 1 0 4h-2v24a6 6 0 0 1-6 6H14a6 6 0 0 1-6-6V14H6a2 2 0 1 1 0-4zm4.283 0h6.93A4 4 0 0 0 24 8c-1.48 0-2.773.804-3.465 2M12 38a2 2 0 0 0 2 2h20a2 2 0 0 0 2-2V14H12z"></path>
                                </g>
                            </svg>
                            Remove all
                                            </button>
                                        </div>
                </div>

                <div data-qa-element="cart-product-list-wrapper">
                    @foreach($cartProducts as $product)
                        @php
                            $lineUnit = cart_product_unit_price($product);
                            $lineTotal = $lineUnit * (int) $product->qty;
                            $partNumber = $product->sku ?: ($product->product_code ?: ('P' . $product->id));
                            $maxQty = max(1, (int) ($product->product_quantity ?? $product->stock ?? 10));
                            if ($maxQty > 10) {
                                $maxQty = 10;
                            }
                        @endphp
                        <div class="t3-cart-line" data-qa-element="line-item" data-product-id="{{ $product->id }}">
                            <div class="t3-cart-line__image">
                                <a href="{{ url('/product/' . $product->slug) }}" data-qa-element="product-item-image">
                                    <img src="{{ img_url($product->image_one) }}" alt="{{ $product->product_name }}" loading="lazy">
                                </a>
                            </div>
                            <div>
                                <div class="t3-cart-line__row">
                                    <div>
                                        <a href="{{ url('/product/' . $product->slug) }}" class="t3-cart-line__title" data-qa-element="product-item-title">{{ $product->product_name }}</a>
                                        <div class="t3-cart-line__code" data-qa-element="product-display-name-part-number">Product code: {{ $partNumber }}</div>
                                    </div>
                                    <div class="t3-cart-line__price" data-line-total>{{ $currency }}{{ number_format($lineTotal, 2) }}</div>
                                </div>
                                <div class="t3-cart-line__controls">
                                    <select class="t3-cart-qty t3-cart-qty-select" data-product-id="{{ $product->id }}" data-qa-element="product-quantity-select" aria-label="Quantity selector">
                                        @for($q = 1; $q <= $maxQty; $q++)
                                            <option value="{{ $q }}" {{ (int) $product->qty === $q ? 'selected' : '' }}>{{ $q }}</option>
                                        @endfor
                                    </select>
                                    <button type="button" class="t3-cart-line__remove t3-cart-remove-item" data-product-id="{{ $product->id }}" data-qa-element="btn-item-remove" title="Delete Product">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <g fill="#333">
                                                <path d="M9.5 9a1 1 0 0 1 1 1v7a1 1 0 1 1-2 0v-7a1 1 0 0 1 1-1ZM15.5 10a1 1 0 1 0-2 0v7a1 1 0 1 0 2 0v-7Z"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.126 5a4.002 4.002 0 0 1 7.748 0H21a1 1 0 1 1 0 2h-1v12a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7H3a1 1 0 0 1 0-2h5.126Zm2.142 0a2 2 0 0 1 3.464 0h-3.464ZM6 19V7h12v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1Z"></path>
                                            </g>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                                </div>
                                </div>
                    @endforeach
                                </div>
                                
                <div id="SHIPPING-ESTIMATE-WRAPPER--DESKTOP" class="t3-cart-shipping-box">
                    <h3>Shipping estimates</h3>
                    <div class="t3-cart-shipping-meta">
                        <span>Ship to <strong>Pakistan</strong></span>
                        <span>Total weight: {{ number_format($cartCount * 0.21, 2) }} lb</span>
                            </div>
                    <div class="t3-cart-scroll">
                        <div class="t3-cart-shipping-card">
                            <div class="t3-cart-shipping-card__date">{{ $deliveryFrom }} - {{ $deliveryTo }}</div>
                            <div class="t3-cart-shipping-card__price">{{ $currency }}{{ number_format($shippingFee, 2) }}</div>
                            <div class="t3-cart-shipping-card__name">Standard Delivery</div>
                            <div class="t3-cart-shipping-card__name">Duties &amp; Taxes may be collected at delivery.</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="t3-cart-empty">
                    <h2>Your cart is empty</h2>
                    <p>Looks like you have not added anything to your cart yet.</p>
                    <a href="{{ url('/') }}">Continue shopping</a>
                </div>
            @endif

            @if($recommendedProducts->count())
                <div class="t3-cart-reco" data-qa-element="recommendation-carousel-wrapper">
                    <h2>Recommended for you</h2>
                    <div class="t3-cart-reco-grid">
                        @foreach($recommendedProducts as $index => $reco)
                            @php
                                $recoUnit = cart_product_unit_price($reco);
                                $inCart = App\Helpers\Cart::has($reco->id);
                            @endphp
                            <div class="t3-cart-reco-card" data-qa-element="recommendation-product-{{ $index }}">
                                <a href="{{ url('/product/' . $reco->slug) }}">
                                    <img src="{{ img_url($reco->image_one) }}" alt="{{ $reco->product_name }}" loading="lazy">
                                </a>
                                <a href="{{ url('/product/' . $reco->slug) }}">{{ $reco->product_name }}</a>
                                <div class="t3-cart-reco-card__price">{{ $currency }}{{ number_format($recoUnit, 2) }}</div>
                                @if($inCart)
                                    <button type="button" disabled>In cart</button>
                                @else
                                    <button type="button" class="t3-add-to-cart" data-product-id="{{ $reco->id }}">Add to Cart</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($cartCount > 0)
            <aside class="t3-cart-aside">
                <div id="summary" class="t3-cart-summary">
                    <div data-qa-element="promo-code-section">
                        <h2>Promo code</h2>
                        <div class="t3-cart-promo">
                            <input id="coupon-input" type="text" placeholder="Enter promo code" data-qa-element="input-apply-code" aria-label="Enter promo code">
                            <button type="button" id="coupon-apply" data-qa-element="apply-button">Apply</button>
                        </div>
                    </div>

                    <h2>Order summary</h2>
                    <div class="t3-cart-summary-row">
                        <div>Items total ({{ $cartCount }})</div>
                        <div data-qa-element="items-total" id="t3-items-total">{{ $currency }}{{ number_format($itemsTotal, 2) }}</div>
                    </div>
                    <div class="t3-cart-summary-row" data-qa-element="total-weight">Total weight: {{ number_format($cartCount * 0.21, 2) }} lb</div>
                    <div class="t3-cart-summary-row">
                        <div>Subtotal</div>
                        <div data-qa-element="subtotal" id="t3-subtotal">{{ $currency }}{{ number_format($itemsTotal, 2) }}</div>
                    </div>
                    <div class="t3-cart-summary-row">
                        <div>Shipping</div>
                        <span data-qa-element="shipping-total" id="t3-shipping-total">{{ $currency }}{{ number_format($shippingFee, 2) }}</span>
                    </div>
                    <div class="t3-cart-summary-row">
                        <div>Duties &amp; Taxes may be collected at delivery.</div>
                    </div>
                    <div class="t3-cart-summary-row total">
                        <span>Total</span>
                        <span data-qa-element="total-price" id="t3-grand-total">{{ $currency }}{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <a href="{{ url('/checkout') }}" class="t3-cart-checkout" data-qa-element="btn-to-checkout">Proceed to Checkout</a>
                    <div class="t3-cart-edd" data-qa-element="cart-edd-message">Estimated delivery date {{ $deliveryFrom }} – {{ $deliveryTo }}</div>

                    <div class="t3-cart-payments">Accepted payment methods</div>
                    <div>
                        <img src="https://s3.images-iherb.com/static/i/payment/adyen_visa.svg" alt="Visa" loading="lazy" height="24">
                        <img src="https://s3.images-iherb.com/static/i/payment/mc.svg" alt="Master Card" loading="lazy" height="24">
                    </div>
                </div>
            </aside>
        @endif
    </div>
</div>
  
<script>
(function () {
    var csrf = '{{ csrf_token() }}';
    var shippingFee = {{ $shippingFee }};
    var currency = @json($currency);

    function postJson(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        }).then(function (r) { return r.json(); });
    }

    document.querySelectorAll('.t3-cart-remove-item').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-product-id');
            window.location.href = '{{ url('cart/remove') }}/' + id;
          });
      }); 
      
    var removeAllBtn = document.getElementById('t3-cart-remove-all');
    if (removeAllBtn) {
        removeAllBtn.addEventListener('click', function () {
            postJson('{{ url('cart/clear') }}', {}).then(function () {
                window.location.reload();
          });
      }); 
    }

    document.querySelectorAll('.t3-cart-qty-select').forEach(function (select) {
        select.addEventListener('change', function () {
            var id = select.getAttribute('data-product-id');
            var qty = parseInt(select.value, 10);
            var formData = new FormData();
            formData.append('_token', csrf);
            formData.append('id', id);
            formData.append('qty', qty);

            fetch('{{ url('cart/add') }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function () {
                window.location.reload();
            });
          });
      });

    document.querySelectorAll('.t3-add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-product-id');
            if (typeof addToCart === 'function') {
                addToCart(id, 1, 0, btn);
                return;
            }

            var formData = new FormData();
            formData.append('_token', csrf);
            formData.append('id', id);
            formData.append('qty', 1);

            fetch('{{ url('cart/add') }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function () {
                window.location.reload();
          });
      });
    });
})();
</script>
@endsection
