
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



      @include('theme2/slider')
      @include('theme2/cats')
      @include('theme2/photos')
      @include('theme2/featured_categories')
      @include('theme2/clients')
      @include('theme2/boxes')
      
      




     @endsection