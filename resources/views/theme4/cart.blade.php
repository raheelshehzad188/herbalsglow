@extends($layout)
@section('content')
<section class="t4-page">
    <div class="container">
        <div class="section-title"><h5>Shopping Cart</h5></div>
        @if(!empty($cartProducts) && count($cartProducts))
        @php $tot = 0; @endphp
        <table class="t4-cart-table table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartProducts as $product)
                    @php $line = ($product->discount_price ?: $product->selling_price) * $product->qty; $tot += $line; @endphp
                    <tr>
                        <td>
                            <a href="{{ product_url($product) }}">
                                <img src="{{ img_url($product->image_one) }}" alt="">
                                {{ $product->product_name }}
                            </a>
                        </td>
                        <td>{{ format_amount($product->discount_price ?: $product->selling_price) }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ format_amount($line) }}</td>
                        <td><a href="{{ url('cart/remove/' . $product->id) }}">Remove</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <h4>Total: {{ format_amount($tot) }}</h4>
            <a href="{{ url('/checkout') }}" class="shop-btn">Checkout</a>
        </div>
        @else
            <p>Your cart is empty.</p>
            <a href="{{ url('/shop') }}" class="shop-btn">Continue shopping</a>
        @endif
    </div>
</section>
@endsection
