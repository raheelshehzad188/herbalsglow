@php $cartQty = Session::has('cart') ? App\Helpers\Cart::qty() : 0; @endphp
<div id="cartSidebar" class="cart-sidebar t3-cart-sidebar">
    <div class="cart-header">
        <h3>Shopping Cart</h3>
        <span id="closeCart" role="button" aria-label="Close cart">&times;</span>
    </div>
    <div class="cart-content">
        @if($cartQty > 0)
            <p><strong>{{ $cartQty }}</strong> item(s) in your cart</p>
            <a href="{{ url('/cart') }}" class="t3-slide-btn" style="display:inline-block;margin:12px 0;">View Cart</a>
            <a href="{{ url('/checkout') }}" class="t3-slide-btn" style="display:inline-block;background:#181b1f;">Checkout</a>
        @else
            <img src="{{ $assets_url }}img/cart-cut-icon.svg" alt="" width="48" height="48" style="opacity:0.5;margin:20px auto;display:block;">
            <p style="text-align:center;color:#666;">No products in the cart.</p>
        @endif
        <button type="button" onclick="window.location.href='{{ url('/shop') }}'" style="margin-top:16px;width:100%;padding:10px;border:1px solid #ddd;background:#fff;border-radius:8px;cursor:pointer;">
            Continue Shopping
        </button>
    </div>
</div>
<div id="cartOverlay" class="t3-cart-overlay"></div>
