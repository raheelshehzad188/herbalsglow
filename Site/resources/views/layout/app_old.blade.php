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
<meta name="description" content="">

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
    

@elseif(isset($tags))

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


    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <?php $Settings = Setting::where(['id'=>'1'])->get(); ?>
    @foreach($Settings as $Setting)
    <!--<link rel="icon" type="image/x-icon" href="{{asset('')}}/images/favicon.png">-->
    <link rel="shortcut icon" href="{{asset('')}}{{$Setting->logo1}}" type="image/x-icon"/ >
    @endforeach
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <!-- BEGIN: Page CSS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/fotorama.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/magnific-popup.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}front/assets/css/responsive.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head> 

<body class="homepage">

   
   <!-- BEGIN: Header-->
        @include('includes.front.header1')
        <!-- END: Header-->

        <!-- BEGIN: Content-->
        @yield('content')
        <!-- BEGIN: End content-->

        <!-- Footer Area -->
        {{ view('front.footer1') }}
        <!-- End Footer Area -->
  <script src="{{asset('')}}front/assets/js/jquery-1.12.3.min.js"></script> 
<script src="{{asset('')}}front/assets/js/bootstrap.min.js"></script>  
<script src="{{asset('')}}front/assets/js/jquery-ui.min.js"></script> 
<script src="{{asset('')}}front/assets/js/fotorama.js"></script>
<script src="{{asset('')}}front/assets/js/jquery.magnific-popup.js"></script> 
<script src="{{asset('')}}front/assets/js/owl.carousel.min.js"></script> 
<script src="{{asset('')}}front/assets/js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
  $(document).ready(function() {
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
                    showToastr(response.msg, response.msg_type);
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
                    showToastr(response.msg, response.msg_type);
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
            msg="{{Session::get('msg')}}";
            @endif

            if(msg!="")
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





        });
        
        
    </script>
</body>
</html>
