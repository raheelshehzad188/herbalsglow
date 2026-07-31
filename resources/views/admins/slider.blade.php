@extends('admins.master')

@section('title','Slider')

 @section('slider','active')


@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Home Slider</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="slider_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group"><label class="col-lg-3 control-label">Slider Image</label>
                            <div class="col-lg-9"><input type="file" name="slider_image" class="form-control">
                                @error('slider_image')
                                <span class="help-block m-b-none text-danger">{{$message}}</span>
                                @enderror
                                @if(isset($edit->id) && !empty($edit->slider_image))
                                <img style="width:100px;" src="{{ custom_assets('public/img/slider/' . $edit->slider_image) }}" />
                                @elseif(isset($edit->image_url) && !empty($edit->image_url))
                                <img style="width:100px;" src="{{ $edit->image_url }}" />
                                @endif
                                <p class="help-block m-b-none">Upload image, or use external URL below.</p>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">External Image URL</label>
                            <div class="col-lg-9">
                                <input type="url" name="image_url" class="form-control" placeholder="https://..." value="{{ isset($edit->image_url) ? $edit->image_url : '' }}"/>
                                <p class="help-block m-b-none">Optional. Overrides uploaded image on homepage bento banners.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label">Heading (Title):</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" name="heading" id="heading" value="<?php echo isset($edit->heading) ? htmlspecialchars($edit->heading) : ''; ?>"/>
                                        <p class="help-block m-b-none">HTML allowed, e.g. 15% Off &lt;br&gt;Creatine</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">Title Font Size</label>
                            <div class="col-lg-9">
                                <input type="text" name="title_size" class="form-control" placeholder="18px" value="{{ isset($edit->title_size) ? $edit->title_size : '18px' }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label">Description Lines:</label>
                                    <div class="col-sm-12">
                                        <textarea class="summernote" name="p" id="short_discriiption" style="height:500px">
                                            <?php echo isset($edit->p) ? htmlspecialchars($edit->p) : ''; ?>
                                        </textarea>
                                        <p class="help-block m-b-none">Each line or paragraph shows as a subtitle on the bento banner.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 control-label"> Button Link:</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" name="button" id="button" value="<?php echo isset($edit->button) ? $edit->button : ''; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">GA Promotion ID</label>
                            <div class="col-lg-9">
                                <input type="text" name="ga_id" class="form-control" value="{{ isset($edit->ga_id) ? $edit->ga_id : '' }}"/>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">GA Promotion Name</label>
                            <div class="col-lg-9">
                                <input type="text" name="ga_name" class="form-control" value="{{ isset($edit->ga_name) ? $edit->ga_name : '' }}"/>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">Sort Order</label>
                            <div class="col-lg-9">
                                <input type="number" name="sort" class="form-control" min="0" value="{{ isset($edit->sort) ? $edit->sort : 0 }}"/>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">Active</label>
                            <div class="col-lg-9">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="status" value="1" {{ !isset($edit) || !isset($edit->status) || $edit->status ? 'checked' : '' }}> Show on homepage
                                </label>
                            </div>
                        </div>
                        @if(isset($edit->id))
                        <input type="hidden" name="hidden_id" value="{{$edit->id}}">
                        @endif
                        <div class="form-group"><label class="col-lg-3 control-label"></label>

                            <div class="col-lg-9">                  <button class="btn btn-sm btn-primary" type="submit"><strong>Save</strong></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Slider List</h5>
            </div>
            <div class="ibox-content">
  
                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>Sr.No</th>
                <th>Image</th>
                <th>Heading</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
              @php $sr=1; @endphp
              @foreach ($sliders as $item)
                  <tr>
                    <td>{{$sr++}}</td>
                    <td>
                        @if(!empty($item->slider_image))
                        <img style="width:100px;" src="{{ custom_assets('public/img/slider/' . $item->slider_image) }}"/>
                        @elseif(!empty($item->image_url))
                        <img style="width:100px;" src="{{ $item->image_url }}"/>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($item->heading), 40) }}</td>
                    <td>{{ $item->sort ?? 0 }}</td>
                    <td>{{ ($item->status ?? 1) ? 'Active' : 'Hidden' }}</td>
                    <td>{{$item->created_at}}</td>
            
                    <td>
                        <a href="javascript:void(0)" data-href="{{route('admins.slider')}}/{{$item->id}}/{{'delete'}}"  class="btn btn-danger delete_record">Delete</a>
                        <a href="{{route('admins.slider')}}/{{$item->id}}" class="btn btn-warning ">Edit</a>
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
$(document).ready(function() {
        $(document).on("submit","#slider_form",function(e){
    $("#short_discriiption").val($('#short_discriiption').summernote('code'));
    
    return true;
    // e.preventDefault();
});

$(document).on("submit","#slider_form",function(e){
    if ($('#short_discriiption').summernote('codeview.isActivated')) {
        $('#short_discriiption').summernote('codeview.deactivate'); 
    }
});
});
</script>
@endpush
