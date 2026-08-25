@extends($layout)
@section('content')
@php $pro = $setting ?? DB::table('setting')->first(); @endphp
<section class="t4-page">
    <div class="container">
        <h1>Contact</h1>
        <form action="{{ url('/contact_us') }}" method="POST" class="t4-checkout-grid">
            @csrf
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="subject" placeholder="Subject" class="full">
            <textarea name="message" class="full" placeholder="Message" required></textarea>
            <button type="submit" class="shop-btn">Send</button>
        </form>
        @if(!empty($pro->phone))<p class="mt-4">Phone: {{ $pro->phone }}</p>@endif
        @if(!empty($pro->email))<p>Email: {{ $pro->email }}</p>@endif
    </div>
</section>
@endsection
