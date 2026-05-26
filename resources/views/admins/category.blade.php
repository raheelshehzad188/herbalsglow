@extends('admins.master')

@section('title','Category')

@section('category_active','active')

@section('category_child_1_active','active')

@section('category_active_c1','collapse in')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <h5>Category Form</h5>
              <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      <i class="fa fa-wrench"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-user">
                      <li><a href="#">Config option 1</a>
                      </li>
                      <li><a href="#">Config option 2</a>
                      </li>
                  </ul>
                  <a class="close-link">
                      <i class="fa fa-times"></i>
                  </a>
              </div>
          </div>
          <div class="ibox-content">
              <form role="form" class="form-inline" autocomplete="off" method="post" enctype="multipart/form-data">
                  @csrf
                        <div class="form-group" style="display: flex;flex-direction: column;">
                          <label for="exampleInputEmail2">Category</label>
                          <input type="text" required name="name" value="{{isset($edit->name) ? $edit->name : ""}}" id="exampleInputEmail2" class="form-control">
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;">
                          <label for="sort">Sort Order:</label>
                          <input type="number" name="sort" value="{{isset($edit->sort) ? $edit->sort : 0}}" id="sort" class="form-control" min="0">
                          <small class="text-muted">Lower numbers appear first</small>
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;">
                          <label for="home_sort">Home Page Sort Order:</label>
                          <input type="number" name="home_sort" value="{{isset($edit->home_sort) ? $edit->home_sort : 0}}" id="home_sort" class="form-control" min="0">
                          <small class="text-muted">Sort order for featured categories on homepage (lower numbers appear first)</small>
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;">
                            <label for="exampleInputEmail2">Featured image (Main Thumbnail):</label>
                            <input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg" class="form-control" name="image_one">
                            <img src="<?php echo isset($edit->image) ? asset($edit->image) : null; ?>"  alt="" <?php echo isset($edit->image) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>>
                       
                        </div>
                        
                        <!--<div class="form-group">-->
                        <!--    <label>Featured:</label>-->
                        <!--    <input type="checkbox" required="" <?=  (isset($edit->is_featured) && $edit->is_featured == 1)?'Checked':''; ?>  name="featured" value="1" style="margin: 18px;">-->
                        <!--</div>-->
                        
                        <!--<div class="form-group">-->
                        <!--    <label>Collection:</label>-->
                        <!--    <input type="checkbox" required=""  <?=  (isset($edit->is_collection) && $edit->is_collection == 1)?'Checked':''; ?> name="collection" value="1" style="margin: 18px;">-->
                        <!--</div>-->
                    
                        <div class="form-group" style="display: flex;flex-direction: column;">
                            <label>Seo Title:</label>
                            <input type="text"  class="form-control" required value="<?php echo isset($seo->title) ? htmlspecialchars($seo->title) : null; ?>" name="stitle" >
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;">
                            <label>Seo Description:</label>
                            <input type="text"  class="form-control" required value="<?php echo isset($seo->description) ? htmlspecialchars($seo->description) : null; ?>" name="sdescription" >
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;margin-bottom: 8px;">
                            <label>Seo keywords:</label>
                            <input type="text"  class="form-control" required value="<?php echo isset($seo->keywords) ? htmlspecialchars($seo->keywords) : null; ?>" name="skeywords" >
                        </div>
                        @if(isset($edit->show_on_home) && $edit->show_on_home == 1)
                        <div class="form-group"  style="display: flex;flex-direction: column;margin-bottom: 8px;">
                           <label>Description:</label>
                                   
                                        <textarea class="summernote form-control" name="short_discriiption" id="short_discriiption" style="height:500px">
                                            <?php echo isset($edit->short_description) ? htmlspecialchars($edit->short_description) : null; ?>
        
                                        </textarea>
                                  
                        </div>
                        @endif
                        <div class="form-group" style="display: flex;flex-direction: column;margin-bottom: 8px;">
                            <select name="status" class="form-control" required>
                                <option value="1" <?=  (isset($edit->status) && $edit->status == 1)?'selected':''; ?>>Active</option>
                                <option value="0" <?=  (isset($edit->status) && $edit->status == 0)?'selected':''; ?> >Inactive</option>
                            </select>
                        </div>
                        
                        
                        
                      @error('name')
                      <span class="help-block m-b-none text-danger">{{$message}}</span>
                      @enderror
                      @if(isset($edit->id))
                      <input type="hidden" name="hidden_id" value="{{$edit->id}}">
                      @endif
                  
                  <button class="btn btn-sm btn-primary" type="submit"><strong>Save</strong></button>
              </form>
          </div>
      </div>
  </div>
    </div>
  <div class="row">
      <div class="col-lg-12">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <h5>Category List</h5>
              <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      <i class="fa fa-wrench"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-user">
                      <li><a href="#">Config option 1</a>
                      </li>
                      <li><a href="#">Config option 2</a>
                      </li>
                  </ul>
                  <a class="close-link">
                      <i class="fa fa-times"></i>
                  </a>
              </div>
          </div>
          <div class="ibox-content">

              <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover dataTables-example" >
          <thead>
          <tr>
              <th>Sr.No</th>
              <th>Image</th>
              <th>Category</th>
              <th>Sort</th>
              <th>Home Sort</th>
              <th>Creation Ago</th>
              <th>Show on Home</th>
              <th>Action</th>
          </tr>
          </thead>
          <tbody>
            @php $sr=1; @endphp
            @foreach ($categories as $item)
                <tr>
                  <td>{{$sr++}}</td>
                 <td><img src="{{asset($item->image)}}" style="width:50px;" ></td>
                  <td>{{$item->name}}</td>
                  <td>{{$item->sort ?? 0}}</td>
                  <td>{{$item->home_sort ?? 0}}</td>
                  <td>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
                    <td>
                        <div class="switch">
                            <div class="onoffswitch">
                                <input type="checkbox" name="product_status" data-id="{{$item->id}}" <?php echo $item->show_on_home==1 ? 'checked' : null; ?> class="onoffswitch-checkbox" id="example-{{$item->id}}">
                                <label class="onoffswitch-label" for="example-{{$item->id}}">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </td>
                  <td>
                    <a href="{{route('admins.category')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                    <a href="javascript:void(0)" data-href="{{route('admins.category')}}/{{$item->id}}/{{'delete'}}"  class="btn btn-danger delete_record">Delete</a>
                  </td>
                </tr>
            @endforeach
          </tbody>
          </table>
              </div>

          </div>
      </div>
  </div>
  </div>
</div>
@endsection


@push('scripts')
  <script>
      
       function updateStatus(status,product_id)
       {
           
       
            if(product_id>0){
                $.ajax({
                    headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}",
                        },
                    url : "{{route('admins.show_on_home')}}",
                    type : "POST",
                    data : {
                        product_id : product_id,
                        Status : status,
                    },
                    success : function(response){
                        showToastr(response.msg,response.msg_type);
                    }
                });
            }
       }
      $(document).ready(function(){
          let status=0;
          let product_id=0;
          $('input[name="product_status"]').change(function(){
            if($(this).is(':checked')){
                status=1;
                product_id=$(this).data('id');
            }else{
                status=0;
                product_id=$(this).data('id');
            }
            updateStatus(status,product_id);
          });
      });
      
       $(document).ready(function() {
        $(document).on("submit","#product_form",function(e){
            // alert( $('#short_discriiption').summernote('code'));
    $("#short_discriiption").val($('#short_discriiption').summernote('code'));

    
    return true;
    // e.preventDefault();
});
});
  </script>
@endpush