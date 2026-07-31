<?php
use App\Models\Admins\Pages;
use App\Models\Admins\Setting;
$Site= Setting::where(['id'=>'1'])->first();


  ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Basic page needs -->
<meta charset="utf-8">
<!--[if IE]>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <![endif]-->
<meta http-equiv="x-ua-compatible" content="ie=edge">

<!-- Mobile specific metas  -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Favicon  -->

   @if(isset($meta) && $meta)

    @if(Session::has('title'))
    <title>{{$meta->title}} | {{$Site->site_title}}</title>
    @else
      <title>{{ Session::get('title')}}</title>
      @endif
    
    

    <meta name="title" content="{{ $meta->title }}" />
    <meta name="description" content="{{ $meta->description }}" />
    <meta name="keywords" content="{{ $meta->keywords }}" />
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <meta name="bingbot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <link rel="canonical" href="" />
    <link rel="alternate" type="application/rss+xml" href="{{ url('/'); }}/sitemap.xml" />
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SEY02DFD03"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-SEY02DFD03');
</script>
    @if(isset($meta->scheme1))
    <script type="application/ld+json">
    {{$meta->scheme1}}
    </script>
    @endif
    @if(isset($meta->scheme))
    <script type="application/ld+json">
    @json($meta->scheme);
    </script>
    @endif
    

@elseif(isset($tags) && !is_array($tags))

    <title>{{ $tags }}</title>
    <meta name="title" content="{{ $tags }}" />
    <meta name="description" content="{{ $tags }}" />
    <meta name="keywords" content="{{ $tags }}" />
    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <meta name="bingbot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <link rel="canonical" href="{{ url('/tags/').$slug; }}" />
    <link rel="alternate" type="application/rss+xml" href="{{ url('/'); }}/sitemap.xml" />


@else
@if(Session::has('title'))
    <title>{{Session::get('title')}} | {{$Site->site_title}}</title>
    @else
      <title>{{$Site->site_title}}</title>
      @endif
@endif

    <?php $Settings = Setting::where(['id'=>'1'])->get(); ?>
    @foreach($Settings as $Setting)
    <!--<link rel="icon" type="image/x-icon" href="{{asset('')}}/images/favicon.png">-->
    <link rel="shortcut icon" href="{{asset('')}}{{$Setting->logo1}}" type="image/x-icon"/ >
    @endforeach
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <!-- BEGIN: Page CSS-->
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('')}}front/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{asset('')}}front/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('')}}front/css/style.css" rel="stylesheet">
    <?php
    $color = '#fff';
    ?>
<style>
</style>

</head> 

<body>

   
   <!-- BEGIN: Header-->
        <!-- END: Header-->
   @include('includes.front.header3')

        <!-- BEGIN: Content-->
        @yield('content')
        
        <!-- BEGIN: End content-->

        <!-- Footer Area -->
        {{ view('front.footer1') }}
        <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <ul class="cart-list link-dropdown-list">
                    <table class="table table-image">
          <thead>
            <tr>
              <th scope="col"></th>
              <th scope="col">Product</th>
              <th scope="col">Price</th>
              <th scope="col">Qty</th>
              <th scope="col">Total</th>
              <!--<th scope="col">Actions</th>-->
            </tr>
          </thead>
          <tbody id="cart_data">
             
          </tbody>
        </table> 
                    
                      
                     
                    </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        <!-- End Footer Area -->

   <!-- Back to Top -->
   <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>


   <!-- JavaScript Libraries -->
   <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
   <script src="{{asset('')}}front/lib/easing/easing.min.js"></script>
   <script src="{{asset('')}}front/lib/slick/slick.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



   <!-- Template Javascript -->
   <script src="{{asset('')}}front/js/main.js"></script>
   <script>
  $(document).ready(function() {
      $('.btn-plus').click(function(){
          var qty = $('#qty').val();
          qty++;
          $('#qty').val(qty);
      });
      $('.btn-minus').click(function(){
          var qty = $('#qty').val();
          qty--;
          $('#qty').val(qty);
      });
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let qty = $('#qty').val();
        $.ajax({
            url: "/cart/add",
            method: "POST",
            data: {
                id: id,
                qty: qty,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.error) {
                    // Handle error response
                } else {
                    $('#cartValue').html(response.qty);
                    $('#cartValue1').html(response.qty);
                    $('#cartValue2').html(response.qty);
                    Swal.fire({
  title: "Good job!",
  text: response.msg,
  icon: "success"
});
                    $.post('{{ route('cart_data') }}', {_token:'{{ csrf_token() }}'}, function(data){
                        $('#cart_data').html(data);
                    });
                    $.post('{{ route('hearder_cart') }}', {_token:'{{ csrf_token() }}'}, function(data){
                        $('#hearder_cart').html(data);
                    });
                    $('#myModal').modal({ show: true });
                }
            },
            cache: false // Disable caching for the AJAX response
        });
    });
});
       $(document).ready(function() {
    $('.add-to-cart-item').click(function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let qty = $('#qty').val();
        $.ajax({
            url: "/cart/add",
            method: "POST",
            data: {
                id: id,
                qty: qty,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.error) {
                    // Handle error response
                } else {
                    $('#cartValue').html(response.qty);
                    $('#cartValue1').html(response.qty);
                    $('#cartValue2').html(response.qty);
                    Swal.fire({
  title: "Good job!",
  text: response.msg,
  icon: "success"
});
                    $.post('{{ route('hearder_cart') }}', {_token:'{{ csrf_token() }}'}, function(data){
                        $('#hearder_cart').html(data);
                    });
                }
            },
            cache: false // Disable caching for the AJAX response
        });
    });
});

         $(document).ready(function() {
            $('.add-to-cart-item1').click(function(e) {
               e.preventDefault();
               
                let id = $(this).attr('id');

                $.ajax({
                    url: "/cart/add",
                    method: "POST",
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.error) {


                        } else {
                             window.location.href = "/checkout";
                            
                           
                        }
                    }
                });
            });
        });
        
        function showToastr(msg,msg_type)
        {
            switch(msg_type)
                {
                    case "success":
                    toastr.success(msg);
                    break;

                    case "danger":
                    toastr.error(msg)
                    break;

                    case "info":
                    toastr.info(msg)
                    break;
                    
                    case "warning":
                    toastr.warning(msg)
                    break;
                }
        }
        
        $(document).ready(function(){

            let msg_type="";
            let msg="";
            @if(Session::has('msg'))
            msg_type="{{Session::get('msg_type')}}";
            // alert();
            msg="{{Session::get('msg')}}";
            @endif

            if(msg!="")
            {
                switch(msg_type)
                {
                    case "success":
                    Swal.fire({
  title: "Good job!",
  text: msg,
  icon: "success"
});
                    break;

                    case "danger":
                    Swal.fire({
  title: "Oops...",
  text: msg,
  icon: "error"
});
                    break;

                    case "info":
                    toastr.info(msg)
                    break; 
                    
                    case "warning":
                    toastr.warning(msg)
                    break;
                }
            }





        });
        
        
    </script>
    @yield('script')
</body>
</html>

</body>
</html>
