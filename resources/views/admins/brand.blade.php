@extends('admins.master')

@section('title','Brand')
@section('category_active','active')
@section('category_child_1_active','active')
@section('category_active_c1','collapse in')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="sa-page-head">
        <div>
            <h2 class="sa-h2">Brands</h2>
            <p class="sa-muted">Brand list for this store.</p>
        </div>
    </div>

    <div class="sa-card">
        <h3>{{ isset($edit->id) ? 'Edit brand' : 'Add brand' }}</h3>
        <form role="form" autocomplete="off" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="sa-field">
                        <label>Brand name</label>
                        <input type="text" required name="name" value="{{isset($edit->name) ? $edit->name : ""}}" class="form-control">
                    </div>
                    <div class="sa-field">
                        <label>SEO title</label>
                        <input type="text" class="form-control" name="title" value="{{ isset($edit->title) ? $edit->title : '' }}">
                    </div>
                    <div class="sa-field">
                        <label>SEO description</label>
                        <input type="text" class="form-control" name="description" value="{{ isset($edit->description) ? $edit->description : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sa-field">
                        <label>Keywords / tags</label>
                        <input type="text" class="form-control" name="tags" value="{{ isset($edit->keywords) ? $edit->keywords : '' }}">
                    </div>
                    <div class="sa-field">
                        <label>Schema (optional)</label>
                        <textarea class="form-control" name="s_schema" rows="3">{{ isset($edit->s_schema) ? $edit->s_schema : '' }}</textarea>
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
            @error('name')
            <span class="help-block m-b-none text-danger">{{$message}}</span>
            @enderror
            @if(isset($edit->id))
            <input type="hidden" name="hidden_id" value="{{$edit->id}}">
            @endif
            <button class="sa-btn sa-btn-primary" type="submit">Save brand</button>
            @if(isset($edit->id))
            <a href="{{ route('admins.brand') }}" class="sa-btn sa-btn-secondary">Cancel</a>
            @endif
        </form>
    </div>

    <div class="sa-card" style="padding:0;overflow:hidden;">
        <div class="table-responsive">
            <table class="sa-table dataTables-example">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Brand</th>
                    <th>Created</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php $sr=1; @endphp
                @foreach ($categories as $item)
                    <tr>
                        <td>{{$sr++}}</td>
                        <td><strong>{{$item->name}}</strong></td>
                        <td>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
                        <td style="white-space:nowrap;">
                            <a href="{{route('admins.brand')}}/{{$item->id}}" class="sa-btn sa-btn-secondary">Edit</a>
                            <a href="javascript:void(0)" data-href="{{route('admins.brand')}}/{{$item->id}}/delete" class="sa-btn sa-btn-danger delete_record">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
