@extends('layout.app2')

<?php
use App\Models\Catagorie;
use App\Models\Subcatagorie;
use App\Models\Childcatagorie;
use App\Models\Admins\Category;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Product;
use Illuminate\Support\Facades\Session;
use App\Models\Admins\Setting;
use App\Models\Admins\Rating;
?>
<style>
    li{
        list-style:none;
    }
    .tags{
       display: flex;
       gap: 7px;
       flex-wrap: wrap;
    }
</style>

@section('content')

<!-- Page Header Start -->
                    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ url('search') }}">Search</a>
                    <span class="breadcrumb-item active">Search Result</span>
                </nav>
            </div>
        </div>
    </div

       
        
    @if(!empty($rproducts) && $rproducts->count())
        <!-- Shop Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
           


            <!-- Shop Product Start -->
            <div class="col-lg-12 col-md-12">
                <div class="row pb-3">
                     @foreach ($rproducts as  $k=>$v)
                @if($v->status == 1)
                    
                    @include('includes/parts/product_box')
                    @endif
                @endforeach
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->

               
              
              
           

    @else
    <tr>
        <td colspan="10">There are no data.</td>
    </tr>
    @endif
    @if($brand->keywords)
    @php
    $new = explode(',',$brand->keywords);
    @endphp
           <div class="container-fluid pt-5">
               <div class="row px-xl-5">
                 <ul class="tags">
                        <?php
                                $newi = array_unique($new);
                                $b = 0;

                                foreach ($newi as $flow){
                            $slugbarndtag =preg_replace('/[^A-Za-z0-9-]+/', '-', $flow);
                        if(isset($_GET['d'])){
                            if($cover == 'show-all'){
                        ?>
                        <li><a href="{{url('brand-tags/'.$slugbarndtag)}}" class="tag btn btn-primary">{{str_replace('-', ' ', $flow)}}</a></li>
                        <?php } }elseif($b <= 10){ ?>
                        <li><a href="{{url('brand-tags/'.$slugbarndtag)}}" class="tag btn btn-primary">{{str_replace('-', ' ', $flow)}}</a></li>

                        <?php } $b++; }?>

                    </ul>
                </div>    
                </div>
    @endif
               

@endsection
