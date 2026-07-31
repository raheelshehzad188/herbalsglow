<!--Feater start-->
<div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Recent Products</span></h2>
        <div class="row px-xl-5">
            @foreach ($aproducts as  $k=>$v)
            @include('includes/parts/product_box')
            @endforeach
        </div>
    </div>
<!--Feater end-->