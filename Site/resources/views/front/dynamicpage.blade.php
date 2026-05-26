@extends('layout.app')

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
<div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3"><?php echo $item->name; ?></h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="/">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0"><?php echo $item->name; ?></p>
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