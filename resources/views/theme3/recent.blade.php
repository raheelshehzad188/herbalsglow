@if(isset($aproducts) && count($aproducts) > 0)
<section class="t3-section">
    <div class="container">
        <h2 class="t3-section-title">New <span>Arrivals</span></h2>
        <div class="t3-product-grid">
            @foreach ($aproducts as $k => $v)
                @include('theme3/product_box_new')
            @endforeach
        </div>
    </div>
</section>
@endif
