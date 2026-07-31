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
                    <span class="breadcrumb-item active">Contact</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-fluid pt-5 contact-section">
    <div class="text-center mb-4 contact-header">
        <h2 class="section-title px-5"><span class="px-2">Contact For Any Queries</span></h2>
    </div>

    <div class="row px-xl-5 contact-content">
        <div class="col-lg-7 mb-5 contact-left">
            <div class="contact-form-wrapper">
                <div id="success"></div>
                <form action="/contact_us" method="POST">
                    @csrf
                    <div class="control-group">
                        <input type="text" class="form-control" id="name" placeholder="Your Name" required name="name" />
                    </div>
                    <div class="control-group">
                        <input type="email" class="form-control" id="email" placeholder="Your Email" required name="email" />
                    </div>
                    <div class="control-group">
                        <textarea class="form-control" rows="6" name="meg" id="message" placeholder="Message" required></textarea>
                    </div>
                    <div>
                        <button class="btn btn-primary py-2 px-4" name="submit" type="submit" id="sendMessageButton">Send Message</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5 mb-5 contact-right">
            <div class="contact-info">
                <h5 class="font-weight-semi-bold mb-3">Contact Details</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{!!strip_tags($pro->homepage_footer)!!}</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{$pro->email}}</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>{{$pro->phone}}</p>
            </div>
        </div>
    </div>
</div>
    <!-- Contact End -->
 
  @endsection
  