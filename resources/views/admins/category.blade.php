@extends('admins.master')

@section('title','Category')
@section('category_active','active')
@section('category_child_1_active','active')
@section('category_active_c1','collapse in')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="sa-page-head">
        <div>
            <h2 class="sa-h2">Categories</h2>
            <p class="sa-muted">Organize products for this store.</p>
        </div>
    </div>

    <div class="sa-card">
        <h3>{{ isset($edit->id) ? 'Edit category' : 'Add category' }}</h3>
        <form role="form" autocomplete="off" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="sa-field">
                        <label>Title</label>
                        <input type="text" required name="name" value="{{isset($edit->name) ? $edit->name : ""}}" class="form-control">
                    </div>
                    <div class="sa-field">
                        <label>Sort order</label>
                        <input type="number" name="sort" value="{{isset($edit->sort) ? $edit->sort : 0}}" class="form-control" min="0">
                        <small>Lower numbers appear first</small>
                    </div>
                    <div class="sa-field">
                        <label>Home page sort</label>
                        <input type="number" name="home_sort" value="{{isset($edit->home_sort) ? $edit->home_sort : 0}}" class="form-control" min="0">
                    </div>
                    <div class="sa-field">
                        <label>Featured image</label>
                        <input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg" class="form-control" name="image_one">
                        <img src="<?php echo isset($edit->image) ? asset($edit->image) : null; ?>" alt="" <?php echo isset($edit->image) ? 'style="width:100px;margin-top:8px;"' : 'style="display:none;width:100px;margin-top:8px;"'; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sa-field">
                        <label>SEO title</label>
                        <input type="text" class="form-control" required value="<?php echo isset($seo->title) ? htmlspecialchars($seo->title) : null; ?>" name="stitle">
                    </div>
                    <div class="sa-field">
                        <label>SEO description</label>
                        <input type="text" class="form-control" required value="<?php echo isset($seo->description) ? htmlspecialchars($seo->description) : null; ?>" name="sdescription">
                    </div>
                    <div class="sa-field">
                        <label>SEO keywords</label>
                        <input type="text" class="form-control" required value="<?php echo isset($seo->keywords) ? htmlspecialchars($seo->keywords) : null; ?>" name="skeywords">
                    </div>
                    <div class="sa-field">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" <?= (isset($edit->status) && $edit->status == 1)?'selected':''; ?>>Active</option>
                            <option value="0" <?= (isset($edit->status) && $edit->status == 0)?'selected':''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            @if(isset($edit->show_on_home) && $edit->show_on_home == 1)
            <div class="sa-field">
                <label>Description</label>
                <textarea class="summernote form-control" name="short_discriiption" id="short_discriiption" style="height:200px"><?php echo isset($edit->short_description) ? htmlspecialchars($edit->short_description) : null; ?></textarea>
            </div>
            @endif
            @error('name')
            <span class="help-block m-b-none text-danger">{{$message}}</span>
            @enderror
            @if(isset($edit->id))
            <input type="hidden" name="hidden_id" value="{{$edit->id}}">
            @endif
            <button class="sa-btn sa-btn-primary" type="submit">Save category</button>
            @if(isset($edit->id))
            <a href="{{ route('admins.category') }}" class="sa-btn sa-btn-secondary">Cancel</a>
            @endif
        </form>
    </div>

    <div class="sa-card" style="padding:0;overflow:hidden;">
        <div class="table-responsive">
            <table class="sa-table dataTables-example">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Sort</th>
                    <th>Home</th>
                    <th>Created</th>
                    <th>Show on home</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php $sr=1; @endphp
                @foreach ($categories as $item)
                    <tr>
                        <td>{{$sr++}}</td>
                        <td><img src="{{asset($item->image)}}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;"></td>
                        <td><strong>{{$item->name}}</strong></td>
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
                        <td style="white-space:nowrap;">
                            <a href="{{route('admins.category')}}/{{$item->id}}" class="sa-btn sa-btn-secondary">Edit</a>
                            <a href="javascript:void(0)" data-href="{{route('admins.category')}}/{{$item->id}}/{{'delete'}}" class="sa-btn sa-btn-danger delete_record">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(input).next('img').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function updateStatus(status,product_id) {
    if(product_id>0){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            url : "{{route('admins.show_on_home')}}",
            type : "POST",
            data : { product_id : product_id, Status : status },
            success : function(response){ showToastr(response.msg,response.msg_type); }
        });
    }
}
$(document).ready(function(){
    $('input[name="product_status"]').change(function(){
        updateStatus($(this).is(':checked') ? 1 : 0, $(this).data('id'));
    });
    $(document).on("submit","#product_form",function(){
        $("#short_discriiption").val($('#short_discriiption').summernote('code'));
        return true;
    });
});
</script>
@endpush
