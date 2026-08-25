@extends($layout)
@section('content')
<section class="t4-page">
    <div class="container">
        <div class="section-title"><h5>{{ $title ?? 'Wishlist' }}</h5></div>
        <div class="row g-4">
            @forelse(($best ?? []) as $v)
            <div class="col-xl-2 col-md-4">
                @include('theme4.product_box')
            </div>
            @empty
            <p>No wishlist items.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
