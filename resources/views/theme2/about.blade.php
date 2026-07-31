@extends($layout)
@section('content')
<?php 
    $pro = DB::table('setting')->first();
?>
    
 <!-- Page Header Start -->
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 top-nav-section-all">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    <span class="breadcrumb-item active">About Us</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- About Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">About Us</span></h2>
        </div>

        <div class="row px-xl-5">
            <div class="col-lg-12 mb-5">
                <div class="contact-form">
                    <div id="success"></div>
                    <div class="about-content">
                        @if(isset($pro) && !empty($pro->about_us))
                            {!! $pro->about_us !!}
                        @else
                            <h3>Welcome to Our Store</h3>
                            <p>We are committed to providing you with the best shopping experience. Our mission is to offer high-quality products at competitive prices with excellent customer service.</p>
                            
                            <h4>Our Story</h4>
                            <p>Founded with a vision to make online shopping accessible and convenient for everyone, we have grown to become a trusted name in the industry.</p>
                            
                            <h4>Our Values</h4>
                            <ul>
                                <li>Customer Satisfaction</li>
                                <li>Quality Products</li>
                                <li>Fast Delivery</li>
                                <li>Excellent Service</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

@endsection

