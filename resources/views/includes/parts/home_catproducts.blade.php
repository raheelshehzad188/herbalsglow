<?php
$setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
?>
<div class="featured-product">
    <div class="container">
      
           @foreach ($maincategories as $category)
          
              <div class="section-header">
            <h3> {{ $category->name }}</h3>
             <p>
            @if(isset($category->short_description))
           {{ str_replace('&nbsp;' , '' , strip_tags($category->short_description))}}

  
       @endif
              </p>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
         
              
                   
                    <?php
                    $productsByCat = DB::table('products')->where('category_id', $category->id)
                        ->where('status', '1')
                        ->orderBy('id', 'DESC')
                        ->limit(4)
                        ->get();
                    ?>
                    @foreach ($productsByCat as $k=>$v)
                        <div class="col-lg-12">
                            @include('includes/parts/product_box')
                        </div>
                    @endforeach
                    @if ($productsByCat->isEmpty())
                        <p>No products available in this category.</p>
                    @endif
            
          
        </div>
        
          @endforeach
    </div>
</div>
