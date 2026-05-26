@extends($layout)
@section('content')
<?php 
use App\Models\Admins\Category;
?>
<!-- page-title -->
        <div class="tf-page-title style-2">
            <div class="container-full">
                <div class="heading text-center">{{ $title }}</div>
            </div>
        </div>
        <!-- /page-title -->
        <section class="flat-spacing-25">
            <div class="container">
                <div class="tf-main-area-page">
                    <?= $pages[0]->content ?>
                </div>
            </div>
        </section>
    <div class="container-fluid">
        <div class="row px-xl-5">

                
               
                 
                
             
                       
                     
@endsection
