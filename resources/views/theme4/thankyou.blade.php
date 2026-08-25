@extends($layout)
@section('content')
<section class="t4-page">
    <div class="container" style="max-width:800px">
        <h2>Thank you. Your order has been received.</h2>
        @if(isset($order) && $order)
            @php $orderData = $order->first(); @endphp
            @if($orderData)
                <p>Order number: <strong>{{ $orderData->order_no }}</strong></p>
                <p>Total: {{ format_amount($orderData->amount + ($setting->shipping_charges ?? 0)) }}</p>
            @endif
        @endif
        <a href="{{ url('/') }}" class="shop-btn">Back to home</a>
    </div>
</section>
@endsection
