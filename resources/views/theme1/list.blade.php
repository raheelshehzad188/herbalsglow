@extends($layout)
@section('content')
<?php 
use App\Models\Admins\Category;
?>
<style>
    svg{
        width:50px !important;
    }
</style>
<!-- page-title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center">{{ $title }}</div>
            </div>
        </div>
        <!-- /page-title -->
        
        <!-- Section Product -->
        <section class="flat-spacing-2">
            <div class="container">
                <div class="tf-shop-control grid-3 align-items-center">
                    <div class="tf-control-filter">
                    </div>
                    <ul class="tf-control-layout d-flex justify-content-center">
                        <li class="tf-view-layout-switch sw-layout-2" data-value-grid="grid-2">
                            <div class="item"><span class="icon icon-grid-2"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-3" data-value-grid="grid-3">
                            <div class="item"><span class="icon icon-grid-3"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-4 active" data-value-grid="grid-4">
                            <div class="item"><span class="icon icon-grid-4"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-5" data-value-grid="grid-5">
                            <div class="item"><span class="icon icon-grid-5"></span></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-6" data-value-grid="grid-6">
                            <div class="item"><span class="icon icon-grid-6"></span></div>
                        </li>
                    </ul>
                </div>
                <div class="grid-layout wrapper-shop" data-grid="grid-4">
                    <!-- card product 1 -->
                     @foreach ($products as  $k=>$v)
                    @include('theme1/product_box')
                    @endforeach
                </div>
                @if (!isset($pagination) && $products->hasPages())
    <div class="pagination-wrapper">
         {{ $products->links() }}
    </div>
@endif
                <!-- pagination -->
            </div>
        </section>
        <!-- /Section Product -->
    <div class="container-fluid">
        <div class="row px-xl-5">

                
               
                 
                
             
                       
                     
@endsection
