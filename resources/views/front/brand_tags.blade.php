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
          <div>
              <h4>ShopPakistan.Com.PK: Online Shopping In Pakistan</h4>
             <p>
<?php $setting = DB::table('setting')->first(); ?>
<a href="https://shoppakistan.pk/">{{ $setting->site_title ?? 'Shop Pakistan' }}</a> Is Selling Wide Range Of Food Supplements, Health & Beauty, Men's Fashion, Women's Fashion, Household Appliances, <a href="https://shopii.com.pk/collections/malaysian-royal-honey">Malaysian Royal Honey</a>, 
Weight Loss & Personal Care Products. We Import From Amazon, eBay, Alibaba, Ali Express & Wallmart On Customer Demand We Offer Cash On Delivery Service All Over Pakistan 
Shop With Us For Quality Products <a href="https://shopii.com.pk/">Online Shopping In Pakistan</a>. {{ $setting->site_title ?? 'Shop Pakistan' }} Offer Satisfaction Guarantees, 
Which state that if a customer is not satisfied with their purchase for any reason, they can return it for a full refund, Risk Free Shopping Biomanix Capsules Price In Dubai . 
Products Quality Is Our Assurance 100% Risk Free Online Shopping Service Buy <a href="https://shopii.com.pk/products/kamagra-oral-jelly">Kamagra 100mg Oral Jelly Price In Pakistan</a>
</p>
          </div>
    </div>
    <!-- Shop End -->

               
              
              
           

    @else
    <tr>
        <td colspan="10">There are no data.</td>
    </tr>
    @endif
               

@endsection
