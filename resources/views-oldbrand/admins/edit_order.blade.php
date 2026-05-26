@extends('admins.master')
<?php 
    
use App\Models\Admins\Size;
use App\Models\Admins\Colors;
use App\Models\Admins\Shap;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Product;

?>
  <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
$cate = DB::table('categories')->get();
?>
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
@section('title','Orders')

@section('order','active')



@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Order Form</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="" method="post" action="/admin/order/up_delivery_status" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$edit->id}}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Customer Name:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->customer_name) ? htmlspecialchars($edit->customer_name) : null; ?>" readonly required class="form-control" name=""></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Customer Phone:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" name="" readonly value="<?php echo isset($edit->phone) ? htmlspecialchars($edit->phone) : null; ?>"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Customer City:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" readonly value="<?php echo isset($edit->city) ? htmlspecialchars($edit->city) : null; ?>"  name=""></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Customer Address:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->address) ? htmlspecialchars($edit->address) : null; ?>" readonly required class="form-control" name=""></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Customer Country:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" name="" readonly value="<?php echo isset($edit->country) ? htmlspecialchars($edit->country) : null; ?>"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Shipping Company:</label>
                                
                                    <div class="col-sm-12"><input type="text"  class="form-control"  value="<?php echo isset($edit->shipping_company) ? htmlspecialchars($edit->shipping_company) : null; ?>"  name="company"></div>
                                </div>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Tracking Url:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->track_url) ? htmlspecialchars($edit->track_url) : null; ?>"   class="form-control" name="track_url"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Tracking No:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" name="track_no"  value="{{$edit->track_no }}"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Note:</label>
                                    <div class="col-sm-12"><input type="text"  class="form-control" name="note"  value="{{$edit->note }}"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group"><label class="col-sm-12 control-label">Delivery Status:</label>
                                
                                    <div class="col-sm-12">
                                        <select  class="form-control" name="dstatus">
                                        <option <?php if($edit->dstatus == 0 ){echo "selected"; } ?> value="0">Panding</option>
                                        <option <?php if($edit->dstatus == 1 ){echo "selected"; } ?> value="1">Completed</option>
                                        <option <?php if($edit->dstatus == 2 ){echo "selected"; } ?> value="2">Deliverd</option>
                                        <option <?php if($edit->dstatus == 3 ){echo "selected"; } ?> value="3">Cancel</option>
                                        <option <?php if($edit->dstatus == 4 ){echo "selected"; } ?> value="4">Dispatched </option>
                                        
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                       
                        
                        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                        <div class="form-group">
                            <div class="col-sm-10"><button class="btn btn-md btn-primary" type="submit"><strong>Save</strong></button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Product Name</th>
                          <th scope="col">Qutantity</th>
                          <th scope="col">Amount</th>
                        </tr>
                        
                       
                      </thead>
                      <tbody>
                          <?php $pro = json_decode($edit->product_detail); 
                          $no = 1;
                          $tot = 0;
                           foreach($pro as $v){
                           
                           $product = Product::where(['id'=>$v->id])->first();
                           $tot = $tot + ($v->qty*$product->discount_price);
                           ?>
                        <tr>
                          <th scope="row">{{$no++}}</th>
                          <td>{{$product->product_name}}</td>
                          <td>{{$v->qty}}</td>
                          <td>Rs:{{$v->qty*$product->discount_price}}</td>
                        </tr>
                        <?php } ?>
                        
                      </tbody>
                      <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <th scope="row">Shipping charges</th>
                                <td>Rs:{{$setting->shipping_charges}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <th scope="row">Shipping charges</th>
                                <td>Rs:{{$tot + $setting->shipping_charges}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div>
                    
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
@endpush