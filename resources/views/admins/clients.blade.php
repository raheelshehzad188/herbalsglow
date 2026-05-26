@extends('admins.master')

@section('title','Clients')

@section('clients','active')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    @if(session('msg'))
    <div class="alert alert-{{ in_array(session('msg_type'), ['error', 'danger']) ? 'danger' : 'success' }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ in_array(session('msg_type'), ['error', 'danger']) ? 'Error:' : 'Success:' }}</strong>
        {{ session('msg') }}
    </div>
    @endif
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Client Form</h5>
                </div>
                <div class="ibox-content">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-b-none" style="padding-left:18px;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form class="form-horizontal" method="post" action="{{ isset($edit->id) ? route('admins.clients', $edit->id) : route('admins.clients') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($edit->id))
                        <input type="hidden" name="hidden_id" value="{{ $edit->id }}">
                        @endif
                        <div class="form-group">
                            <label class="col-lg-12">Label:</label>
                            <div class="col-lg-12">
                                <input type="text" class="form-control" name="label" value="{{ old('label', $edit->label ?? '') }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12">Sort:</label>
                            <div class="col-lg-12">
                                <input type="number" class="form-control" name="sort" value="{{ old('sort', $edit->sort ?? 0) }}" min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12">Image:</label>
                            <div class="col-lg-12">
                                <input type="file" name="image" class="form-control" accept="image/*" {{ isset($edit->id) ? '' : 'required' }} />
                                <span class="help-block m-b-none text-muted">JPG, PNG, GIF or WEBP — max 10MB</span>
                                @error('image')
                                <span class="help-block m-b-none text-danger">{{ $message }}</span>
                                @enderror
                                @if(!empty($edit->image))
                                <p class="m-t-sm m-b-none"><strong>Current image:</strong></p>
                                <img style="width:120px; max-height:200px; object-fit:contain; margin-top:8px; border:1px solid #ddd; padding:4px;" src="{{ client_image_url($edit->image) }}" alt="Current image" />
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"></label>
                            <div class="col-lg-8">
                                <button class="btn btn-sm btn-primary" type="submit"><strong>Save</strong></button>
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
                    <h5>Clients List</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Image</th>
                                    <th>Label</th>
                                    <th>Sort</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sr=1; @endphp
                                @foreach ($clients as $item)
                                <tr>
                                    <td>{{$sr++}}</td>
                                    <td>
                                        @if($item->image)
                                        <img style="width:100px; max-height:120px; object-fit:contain;" src="{{ client_image_url($item->image) }}" alt="" />
                                        @endif
                                    </td>
                                    <td>{{$item->label}}</td>
                                    <td>{{ $item->sort ?? 0 }}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <a href="{{route('admins.clients')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                                        <a href="javascript:void(0)" data-href="{{route('admins.clients')}}/{{$item->id}}/delete" class="btn btn-danger delete_record">Delete</a>
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
