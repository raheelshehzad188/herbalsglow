
@extends($layout)
<?php
use App\Models\Catagorie;
use App\Models\Subcatagorie;
use App\Models\Childcatagorie;
use App\Models\Admins\Product;
use App\Models\Gallerie;
use Illuminate\Support\Facades\Session;
use App\Models\Admins\Setting;
use App\Models\Admins\Rating;
use App\Models\Admins\Slider;
  ?>
  @section('content')
  <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
?>


      @include('theme1/slider')
      @include('theme1/info')
      @include('theme1/home_categoy')
      @include('theme1/feature')
      @include('theme1/recent')




     @endsection