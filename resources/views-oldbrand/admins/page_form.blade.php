@extends('admins.master')
<style>
    label{
        text-align: left !important;
    }
    .bootstrap-tagsinput{
        width:100% !important;
    }
</style>
@section('title','Product Form')

@section('product_active','active')

@section('product_active_c1','collapse in')

@section('product_child_1_active','active')


@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Page Form</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ url('/') }}/admin/page_form" id="page_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group"><label class="col-sm-12 control-label">Page Name:</label>
                            <div class="col-sm-12">
                                <input type="text" value="<?php echo isset($edit->name) ? htmlspecialchars($edit->name) : null; ?>" required class="form-control" name="name">
                                
                            </div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Page Slug:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->slug) ? htmlspecialchars($edit->slug) : null; ?>" class="form-control" name="slug">
                            <span>Fill once you want to create new pages in website <br> For example if uou want to create new page which name is abc and you ewant to create url like {{url('/')}}/page/abc then put here <b>abc</b>  therwise leave it empty</span>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Menu Type:</label>
                            <div class="col-sm-12">
                                <?php
                                $types = array(
                                    'top_bar'=>'Top Bar',
                                    'header'=>'Header',
                                    'quick_links'=>'Quick Links',
                                    );
                                ?>
                            <select class="form-control" name="menu_type" id="menu_type">
                                @foreach($types as $k=> $v)
                                <option value="{{ $k }}" <?= (isset($edit) && $edit->menu_type == $k ) ? "Selected" : ''; ?>>{{ $v }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div id="category" class="all_typs" style="display:<?= (isset($edit) && $edit->menu_type == 'category' ) ? "block" : 'none'; ?>">
                            <div class="form-group"><label class="col-sm-12 control-label">Select Category:</label>
                            <div class="col-sm-12">
                            <select required="" class="form-control get_route" name="category">
                                @foreach ($categories as $cat)
                                <?php
                                $slug = 'category/'.$cat->slug;
                                ?>
                                <option value="{{$slug}}"  <?= (isset($edit->route) && $edit->route == $slug)?"selected":"" ?>>{{$cat->name}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Page Route:</label>
                            <div class="col-sm-12"><input type="text" id="route" value="<?php echo isset($edit->route) ? htmlspecialchars($edit->route) : null; ?>" class="form-control" name="route"></div>
                            <span>Fill once you want to add page which defind in laravel routes, Like category menu, Product menu<br>For example {{url('/')}}/category/category_slug is a catgory url then put here <b>category/category_slug</b></span>
                        </div>
                        
                        <div class="form-group"><label class="col-sm-12 control-label">Page Sorting:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->position) ? htmlspecialchars($edit->position) : null; ?>" class="form-control" name="position"></div>
                        </div>
                        
                        @if($edit)
                        <div class="form-group"><label class="col-sm-12 control-label">Publish:</label>
                            <div class="col-sm-12">
                            <select required="" class="form-control" name="status">
                                <option value="1" <?= (isset($edit) && $edit->status == 1 ) ? "Selected" : ''; ?> >Active</option>
                                <option value="2" <?= (isset($edit) && $edit->status == 2 ) ? "Selected" : ''; ?>>Deactive</option>
                            </select>
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-group"><label class="col-sm-12 control-label">Page Content:</label>
                            <div class="col-sm-12">
                                <textarea class="summernote" id="content" name="content" rows="50" required >
                                    <?php echo isset($edit->content) ? htmlspecialchars($edit->content) : null; ?>

                                </textarea>
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
$('.get_route').change(function(){
    // alert($(this).val());
    $('#route').val($(this).val());
    

});
$('#menu_type').change(function(){
    var mid="#"+$(this).val();
    $('.all_typs').each(function(i, obj) {
        $(this).hide();
    });
    $(mid).show();
    

});
function menu_type(){
    alert($(this).val());
}
$(document).ready(function() {
        $(document).on("submit","#page_form",function(e){
    $("#content").val($('#content').summernote('code'));
    
    return true;
    // e.preventDefault();
});

$(document).on("submit","#page_form",function(e){
    if ($('#content').summernote('codeview.isActivated')) {
        $('#content').summernote('codeview.deactivate'); 
    }
});
});
</script>
@endpush