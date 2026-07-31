@extends('layout.app2')

<?php
use App\Models\Admins\Category;
use App\Models\Admins\SubCategory;
use App\Models\Childcatagorie;
use App\Models\product;
use App\Models\Admins\Gallerie;
  ?>


@section('content')

@foreach($pages as $item)
<?php
$img = '';
if(isset($item->page_image) && $item->page_image && $item->page_image_status == 1)
{
$img = url('/').'/public/img/slider/'.$item->page_image;
}
?>
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    <span class="breadcrumb-item active"><?php echo $item->name; ?></span>
                </nav>
            </div>
        </div>
    </div>

  <!-- CONTAIN START ptb-95-->
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="row">
            <div class="col-xs-12">
             @if($img)
                <img src="<?= $img; ?>" />
                @endif
                <h1 style="margin-bottom: 40px;text-align: center;"><?php echo $item->name; ?></h1>
               <?php echo $item->content; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- CONTAINER END --> 
@endforeach


@endsection