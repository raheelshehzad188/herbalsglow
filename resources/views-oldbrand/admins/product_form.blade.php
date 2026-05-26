@extends('admins.master')
<?php 
    
use App\Models\Admins\Size;
use App\Models\Admins\Colors;
use App\Models\Admins\Shap;
use App\Models\Admins\Gallerie;

?>

@section('title','Product Form')

@section('product_active','active')

@section('product_active_c1','collapse in')

@section('product_child_1_active','active')


@section('content')
<style>
    
    .preview-images-zone {
        width: 100%;
        border: 1px solid #ddd;
        min-height: 180px;
        /* display: flex; */
        padding: 5px 5px 0px 5px;
        position: relative;
        overflow:auto;
    }
    .preview-images-zone > .preview-image:first-child {
        height: 185px;
        width: 185px;
        position: relative;
        margin-right: 5px;
    }
    .preview-images-zone > .preview-image {
        height: 90px;
        width: 90px;
        position: relative;
        margin-right: 5px;
        float: left;
        margin-bottom: 5px;
    }
    .preview-images-zone > .preview-image > .image-zone {
        width: 100%;
        height: 100%;
    }
    .preview-images-zone > .preview-image > .image-zone > img {
        width: 100%;
        height: 100%;
    }
    .preview-images-zone > .preview-image > .tools-edit-image {
        position: absolute;
        z-index: 100;
        color: #fff;
        bottom: 0;
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
        display: none;
    }
    .preview-images-zone > .preview-image > .image-cancel {
        font-size: 18px;
        position: absolute;
        top: 0;
        right: 0;
        font-weight: bold;
        margin-right: 10px;
        cursor: pointer;
        display: none;
        z-index: 100;
    }
    .preview-image:hover > .image-zone {
        cursor: move;
        opacity: .5;
    }
    .preview-image:hover > .tools-edit-image,
    .preview-image:hover > .image-cancel {
        display: block;
    }
    .ui-sortable-helper {
        width: 90px !important;
        height: 90px !important;
    }
    
    .container {
        padding-top: 50px;
    }
    label{
        text-align: left !important;
    }
    .bootstrap-tagsinput{
        width:100% !important;
    }
    span.select2-selection.select2-selection--multiple {
        width: 302px;
    }
    span.select2-dropdown.select2-dropdown--below {
        width: 301px !important;
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Product Form</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="product_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="sub_cat_id" value="<?php echo isset($edit->subcategory_id) ? $edit->subcategory_id : 0; ?>">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label">Product Name:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->product_name) ? htmlspecialchars($edit->product_name) : null; ?>" onkeyup="create_slug()" required class="form-control" name="product_name" id="product_name"></div>
                                </div>
                            </div>
                            
                            <!--<div class="col-sm-4">-->
                            <!--    <div class="form-group"><label class="col-sm-12 control-label">Product Code:</label>-->
                            <!--        <div class="col-sm-12"><input type="text"  class="form-control" name="product_code" value="<?php echo isset($edit->product_code) ? htmlspecialchars($edit->product_code) : null; ?>"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Quantity:</label>
                                    <div class="col-sm-12"><input type="number"  class="form-control" value="<?php echo isset($edit->product_quantity) ? htmlspecialchars($edit->product_quantity) : null; ?>" min="1" name="product_quantity"></div>
                                </div>
                            </div>
                             <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Made in:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" min="1" value="<?php echo isset($edit->made_in) ? htmlspecialchars($edit->made_in) : null; ?>" name="made_in"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Size:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" min="1" value="<?php echo isset($edit->size) ? htmlspecialchars($edit->size) : null; ?>" name="size"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <?php
                                $cats = array();
                                if(isset($edit->category_id))
                                {
                                    $cats = explode(',',$edit->category_id);
                                }
                                ?>
                                <div class="form-group"><label class="col-sm-12 control-label">Category:</label>
                                    <div class="col-sm-12">
                                    <select  class="js-example-basic-multiple form-control" name="category_id" >
                                        @foreach($categories as $category)
                                        
                                        <option <?php echo isset($edit->category_id) && in_array($category->id,$cats) ? "selected" : null; ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Sub Category:</label>
                                    <div class="col-sm-12">
                                    <select  class=" form-control" name="subcategory_id" >
                                        @foreach($scategories as $category)
                                        
                                        <option {{ (isset($edit->subcategory_id) && ($edit->subcategory_id == $category->id))?'selected':'' }}  value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Product Slug:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->slug) ? htmlspecialchars($edit->slug) : null; ?>" required class="form-control" name="slug" id="slug"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Shipping Percebntge:</label>
                                    <div class="col-sm-12"><input  readonly="true" id="percent" type="number"  class="form-control" min="1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Product Tags:</label>
                                    <div class="col-sm-12"><input type="text" data-role="tagsinput" class="form-control" value="<?php echo isset($edit->tags) ? htmlspecialchars($edit->tags) : null; ?>" name="tags" ></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Selling Price:</label>
                                    <div class="col-sm-12"><input type="number"  class="form-control" onkeyup="calper()" min="1" value="<?php echo isset($edit->selling_price) ? htmlspecialchars($edit->selling_price) : null; ?>" name="selling_price"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Discount Price:</label>
                                    <div class="col-sm-12"><input type="number"  class="form-control" onkeyup="calper()" min="1" value="<?php echo isset($edit->discount_price) ? htmlspecialchars($edit->discount_price) : null; ?>" name="discount_price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label">Product Short Discription:</label>
                                    <div class="col-sm-12">
                                        <textarea class="summernote" name="short_discriiption" id="short_discriiption" style="height:500px">
                                            <?php echo isset($edit->short_discriiption) ? htmlspecialchars($edit->short_discriiption) : null; ?>
        
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label">Product Details:</label>
                                    <div class="col-sm-12">
                                        <textarea class="summernote" name="product_details" id="product_details" rows="15">
                                            <?php echo isset($edit->product_details) ? htmlspecialchars($edit->product_details) : null; ?>
        
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Featured image (Main Thumbnail):</label>
                                    <div class="col-sm-12">
                                        <!--<input type="file" require  accept="image/png, image/gif, image/jpeg" class="form-control" <?php echo isset($edit->video_link) ? 'required' : ''; ?>   name="image_one">-->
                                        <div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg" class="form-control" name="image_one">
                                        <img src="<?php echo isset($edit->image_one) ? asset($edit->image_one) : null; ?>"  alt="" <?php echo isset($edit->image_one) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-sm-4">
                                <!--<input type="hidden" name="gallary_images" id="gallary_images" value="<?php echo isset($edit->gallary_images) ? $edit->gallary_images : null; ?>" >-->
                                <?php 
                                    if(isset($edit)){
                                        $gimage = '';
                                        $files = Gallerie::where('product_id',$edit->id)->get();
                                        foreach ($files as  $key => $file){
                                            $gimage = $gimage."_____".$file->photo;
                                        }
                                    }
                                    
                                ?>
                                <input type="hidden" name="gallary_images" id="gallary_images" value="<?= (isset($edit))?'':''; ?>" >
                                <input type="hidden" name="delete_gallary_images" id="delete_gallary_images" value="" >
                            </div>
                        </div>
                        
                        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                        
                        <!-- Latest compiled and minified JavaScript -->
                        
                        <div class="container">
                            <fieldset class="form-group">
                                <a href="javascript:void(0)">Gallery Images</a>
                                <input type="file"  name="gallary_images[]" style="display: ;" class="form-control" multiple>
                            </fieldset>
                            <div class="preview-images-zone">
                                <?php 
                                
                                    if(isset($edit)){
                                        foreach ($files as  $key => $file){
                                            // dd($file);
                                            if($file !== ''){
                                                ?>
                                                <div class="preview-image preview-show-<?= $key; ?>">
                                                    <div class="image-cancel" ><a href="{{route('admins.gallery_delete',['id'=>$file->id])}}">x</a></div>
                                                    <div class="image-zone"><img id="pro-img-<?= $key; ?>" src="<?= $file->photo; ?>"></div>
                                                    
                                                </div>
                                                <?php
                                            }
                                                
                                        }
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Seo Title:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control"  value="<?php echo isset($seo[0]->title) ? htmlspecialchars($seo[0]->title) : null; ?>" name="stitle" ></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Seo Description:</label>
                                    <div class="col-sm-12"><input type="text" class="form-control"  value="<?php echo isset($seo[0]->description) ? htmlspecialchars($seo[0]->description) : null; ?>" name="sdescription"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Seo keywords:</label>
                                    <div class="col-sm-12"><input type="text" class="form-control"  value="<?php echo isset($seo[0]->keywords) ? htmlspecialchars($seo[0]->keywords) : null; ?>" name="skeywords" ></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <!--<div class="row d-none">-->
                                            <?php
                                           $hcats = array();
                                          if(isset($edit->home_cats) && $edit->home_cats)
                                            {
                                                $hcats = explode(',',$edit->home_cats);
                                            }
                                            ?>
                                        <!--    @foreach($home_cats as $category)-->
                                            
                                        <!--    <div class="col-sm-12">-->
                                                <!--<input type="checkbox" id="trendproduct" <?php echo in_array($category->id,$hcats) ? "checked" : null; ?> value="{{$category->id}}" name="home_cats[]">-->
                                        <!--        <input type="checkbox" id="trendproduct" <?php if($category->name == 1){ echo "checked";} ?> value="1" name="{{$category->name}}">-->
                                        <!--        <label for="trendproduct">-->
                                        <!--            {{$category->name}}-->
                                        <!--        </label>-->
                                        <!--    </div>-->
                                        <!--@endforeach-->
                                        <!--</div>-->
                                        <div class="row">
                                           
            
                                            
                                            <div class="col-sm-12">
                                                <!--<input type="checkbox" id="trendproduct" <?php echo in_array($category->id,$hcats) ? "checked" : null; ?> value="{{$category->id}}" name="home_cats[]">-->
                                                <input type="checkbox" id="trendproduct" <?php if(isset($edit->New_Arrival) && $edit->New_Arrival == 1){ echo "checked";} ?> value="1" name="New_Arrival">
                                                <label for="trendproduct">
                                                    New_Arrival
                                                </label>
                                            </div>
                                            <div class="col-sm-12">
                                                <!--<input type="checkbox" id="trendproduct" <?php echo in_array($category->id,$hcats) ? "checked" : null; ?> value="{{$category->id}}" name="home_cats[]">-->
                                                <input type="checkbox" id="trendproduct" <?php if(isset($edit->Featured) && $edit->Featured == 1){ echo "checked";} ?> value="1" name="Featured">
                                                <label for="trendproduct">
                                                    Featured
                                                </label>
                                            </div>
                                            <div class="col-sm-12">
                                                <!--<input type="checkbox" id="trendproduct" <?php echo in_array($category->id,$hcats) ? "checked" : null; ?> value="{{$category->id}}" name="home_cats[]">-->
                                                <input type="checkbox" id="trendproduct" <?php if(isset($edit->Sale) && $edit->Sale == 1){ echo "checked";} ?> value="1" name="Sale">
                                                <label for="trendproduct">
                                                    Sale
                                                </label>
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($edit->id))
                        <input type="hidden" name="hidden_id" value="{{$edit->id}}">
                        @endif
                        <div class="form-group">
                            <div class="col-sm-10"><button class="btn btn-md btn-primary" type="submit"><strong>Save</strong></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
  </div>
@endsection

@push('scripts')

<script>

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });

    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(input).parent().find('img').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(input.files[0]);
    }
    }
    $(function(){
        let sub_cat_id=$('input[name="sub_cat_id"]').val();
        let cat_id=$('select[name="category_id"]').val();
        getSubCats(cat_id,sub_cat_id);
        $('select[name="category_id"]').change(function(){
            getSubCats($(this).val(),0);
        });
        function getSubCats(cat_id,sub_cat_id)
        {
            if(cat_id!=''){
                $.ajax({
                    url : "{{route('admins.get_subCategory_html')}}",
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    type : "POST",
                    data : "category_id="+cat_id+"&sub_category_id="+sub_cat_id,
                    success : function(response){
                        console.log(response);
                        $('select[name="subcategory_id"]').html("").html(response);
                    }
                });
            }

        }
 
    });
    
    
        
    $(document).ready(function() {
        $(document).on("submit","#product_form",function(e){
            // alert( $('#short_discriiption').summernote('code'));
    $("#short_discriiption").val($('#short_discriiption').summernote('code'));
    $("#product_details").val($('#product_details').summernote('code'));
    
    return true;
    // e.preventDefault();
});

$(document).on("submit","#product_form",function(e){
    if ($('#product_details').summernote('codeview.isActivated')) {
        $('#product_details').summernote('codeview.deactivate'); 
    }
    if ($('#short_discriiption').summernote('codeview.isActivated')) {
        $('#short_discriiption').summernote('codeview.deactivate'); 
    }
});
      document.getElementById('pro-image').addEventListener('change', readImage, false);
      
      $( ".preview-images-zone" ).sortable();
      
      $(document).on('click', '.image-cancel', function() {
            
            let no = $(this).data('no');
          
            let image_id = $(this).attr('id');
            console.log(image_id);
          
            deleteimagevalue = $('#delete_gallary_images').val()
            newdeleteimagevalue = deleteimagevalue+","+image_id;
            $('#delete_gallary_images').val(newdeleteimagevalue);

          
        //   var img = $(".preview-image.preview-show-"+no).find('img').attr('src');
        //   console.log(imagevalue);
        //   console.log(img);
          
        //   let result = imagevalue.includes("_____"+img);
        // //   console.log(result);
        //   if(result){
        //       newimagetext = imagevalue.replace('_____'+img,'');
        //   }else{
        //       let result2 = imagevalue.includes(img+"_____");
        //       if(result2){
        //           newimagetext = imagevalue.replace(img+'_____','');
        //       }
              
        //   }
        //   console.log(newimagetext);
          
        //   $('#gallary_images').val(imagevalue);
          
          $(".preview-image.preview-show-"+no).remove();
        //   console.log($(".preview-image.preview-show-"+no).children().css({"color": "red", "border": "2px solid red"}));
          
      });
    });
    
    
    var num = 0;
    function readImage() {
      if (window.File && window.FileList && window.FileReader) {
          var files = event.target.files; //FileList object
          var output = $(".preview-images-zone");
    
          for (let i = 0; i < files.length; i++) {
              var file = files[i];
              var checkdiv = $('div.preview-image').length;
             // lemit line
              if (num <= 5 || checkdiv <= 5){  
               
                var num = checkdiv;
                if (!file.type.match('image')) continue;
                
                var picReader = new FileReader();
                
                picReader.addEventListener('load', function (event) {
                    var picFile = event.target;
                    imagevalue = $('#gallary_images').val()
                    addvalue = picFile.result;
                    console.log(picFile.result);
                    newvalue = imagevalue+"_____"+addvalue
                    $('#gallary_images').val(newvalue);
                    var html =  '<div class="preview-image preview-show-' + num + '">' +
                                '<div class="image-cancel" data-no="' + num + '">x</div>' +
                                '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                                '<div class="tools-edit-image"><a href="javascript:void(0)" data-no="' + num + '" class="btn btn-light btn-edit-image">edit</a></div>' +
                                '</div>';
    
                    output.append(html);
                    num = num + 1;
                });
            }
              picReader.readAsDataURL(file);
          }
          $("#pro-image").val('');
      } else {
          console.log('Browser not support');
      }
    }
    

    
    
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var slug = function(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
  var to   = "aaaaaeeeeeiiiiooooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
           .replace(/\s+/g, '-') // collapse whitespace and replace by -
           .replace(/-+/g, '-'); // collapse dashes

  return str;
};
function create_slug(){
                            var tit = $('#product_name').val();
                            $('#slug').val(slug(tit));
                            }
                            function calper()
                            {
                                var saling_price = parseFloat($('input[name ="selling_price"]').val());
                                var discount_price = parseFloat($('input[name ="discount_price"]').val());
                                var tot_price = saling_price - discount_price;

var perctg = 0;
if(saling_price)
{
perctg = (tot_price / saling_price) * 100;
var final_percntage = Math.ceil(perctg);
$('#percent').val(final_percntage);
}
else
{
    $('#percent').val(0);
}
                                //percent
                            }
</script>
@endpush