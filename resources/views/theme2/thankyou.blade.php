@extends('theme2.layout')

<?php
use App\Models\Admins\Product;
use App\Models\Admins\Setting;
$setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
?>

@section('content')
<div class="thankss" style="max-width: 800px; margin: 50px auto; padding: 20px; ">
	<div class="thankew-page">
		<!-- Thank You Message -->
		<div style="border: 2px dashed #2C0A47; padding: 30px; text-align: center; margin-bottom: 30px; background-color: #f8f9fa;">
			<h2 style="color: #2C0A47; font-weight: bold; margin: 0;">Thank you. Your order has been received.</h2>
		</div>

		@if(isset($order) && $order)
			@php
				$orderData = $order->first();
				$products = json_decode($orderData->product_detail);
				$subtotal = 0;
			@endphp

			<!-- Order Summary -->
			<div style="background-color: #fff; padding: 20px; margin-bottom: 30px; border: 1px solid #ddd;">
				<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
					<div>
						<strong>Order number:</strong><br>
						<span style="color: #2C0A47;">{{ $orderData->order_no }}</span>
					</div>
					<div>
						<strong>Date:</strong><br>
						<span>{{ date('F d, Y', strtotime($orderData->created_at)) }}</span>
					</div>
					<div>
						<strong>Total:</strong><br>
						<span style="color: #2C0A47; font-weight: bold;">Rs: {{ number_format($orderData->amount+$setting->shipping_charges, 2) }}</span>
					</div>
					<div>
						<strong>Payment method:</strong><br>
						<span>Cash on delivery</span>
					</div>
				</div>
				<p style="margin-top: 15px; color: #666; font-size: 14px;">
					<strong>Pay with cash upon delivery.</strong>
				</p>
			</div>

			<!-- Order Details -->
			<div style="background-color: #fff; padding: 20px; border: 1px solid #ddd;">
				<h3 style="text-align: center; margin-bottom: 30px; font-weight: bold;">ORDER DETAILS</h3>
				
				<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
					<thead>
						<tr style="border-bottom: 2px solid #ddd;">
							<th style="text-align: left; padding: 12px; font-weight: bold;">PRODUCT</th>
							<th style="text-align: right; padding: 12px; font-weight: bold;">TOTAL</th>
						</tr>
					</thead>
					<tbody>
						@foreach($products as $product)
							@php
								$productData = Product::where('id', $product->id)->first();
								if($productData) {
									$productTotal = $product->qty * $productData->discount_price;
									$subtotal += $productTotal;
								}
							@endphp
							@if($productData)
							<tr style="border-bottom: 1px solid #eee;">
								<td style="padding: 12px;">
									{{ $productData->product_name }} x {{ $product->qty }}
								</td>
								<td style="text-align: right; padding: 12px;">
									Rs: {{ number_format($productTotal, 2) }}
								</td>
							</tr>
							@endif
						@endforeach
					</tbody>
					<tfoot>
						<tr style="border-top: 2px solid #ddd;">
							<td style="padding: 12px; font-weight: bold;">Subtotal:</td>
							<td style="text-align: right; padding: 12px; font-weight: bold;">
								Rs: {{ number_format($subtotal, 2) }}
							</td>
						</tr>
						<tr>
							<td style="padding: 12px; font-weight: bold;">Shipping:</td>
							<td style="text-align: right; padding: 12px; font-weight: bold;">
								@if($setting && $setting->shipping_charges > 0)
									Rs: {{ number_format($setting->shipping_charges, 2) }}
								@else
									Free shipping
								@endif
							</td>
						</tr>
						<tr>
							<td style="padding: 12px; font-weight: bold;">Payment method:</td>
							<td style="text-align: right; padding: 12px;">Cash on delivery</td>
						</tr>
						<tr style="border-top: 2px solid #2C0A47;">
							<td style="padding: 12px; font-weight: bold; font-size: 18px;">TOTAL:</td>
							<td style="text-align: right; padding: 12px; font-weight: bold; font-size: 18px; color: #2C0A47;">
								Rs: {{ number_format($orderData->amount+$setting->shipping_charges, 2) }}
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<!-- Continue Shopping Button -->
			<div style="text-align: center; margin-top: 30px;">
				<a href="{{ url('/') }}" style="display: inline-block; padding: 12px 30px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
					Continue Shopping
				</a>
			</div>
		@else
			<div style="text-align: center; padding: 40px;">
				<p style="color: #666;">Order not found.</p>
				<a href="{{ url('/') }}" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #2C0A47; color: #fff; text-decoration: none; border-radius: 4px;">
					Go to Homepage
				</a>
			</div>
		@endif
	</div>
</div>
@endsection

