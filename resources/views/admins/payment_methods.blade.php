@extends('admins.master')

@section('title','Payment Gateways')

@section('payment_methods','active')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Payment Gateway Form</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" method="post">
                        @csrf
                        @if(isset($edit->id))
                        <input type="hidden" name="hidden_id" value="{{ $edit->id }}">
                        @endif
                        <div class="form-group">
                            <label class="col-lg-12">Title:</label>
                            <div class="col-lg-12">
                                <input type="text" class="form-control" name="title" value="{{ isset($edit->title) ? $edit->title : '' }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12">Detail:</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="detail" rows="4">{{ isset($edit->detail) ? $edit->detail : '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12">Sort:</label>
                            <div class="col-lg-12">
                                <input type="number" class="form-control" name="sort" value="{{ isset($edit->sort) ? $edit->sort : 0 }}" min="0" />
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
                    <h5>Payment Gateways List</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Title</th>
                                    <th>Detail</th>
                                    <th>Sort</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sr = 1; @endphp
                                @foreach ($payment_methods as $item)
                                <tr>
                                    <td>{{ $sr++ }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->detail, 80) }}</td>
                                    <td>{{ $item->sort ?? 0 }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admins.payment_methods') }}/{{ $item->id }}" class="btn btn-warning">Edit</a>
                                        <a href="javascript:void(0)" data-href="{{ route('admins.payment_methods') }}/{{ $item->id }}/delete" class="btn btn-danger delete_record">Delete</a>
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
