@extends($layout)
@section('content')
@php $pro = $setting ?? DB::table('setting')->first(); @endphp
<section class="t4-page">
    <div class="container">
        <h1>About Us</h1>
        @if(!empty($pro->about_us))
            {!! $pro->about_us !!}
        @else
            <p>Welcome to {{ $pro->site_title ?? 'our store' }}.</p>
        @endif
    </div>
</section>
@endsection
