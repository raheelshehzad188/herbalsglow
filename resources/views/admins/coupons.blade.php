@extends('admins.master')

@section('title','Coupons')

@section('coupon_active','active')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Coupon Form</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" method="post">
                        @csrf
                        <div class="form-group"><label class="col-lg-3 control-label">Coupon Code</label>
                            <div class="col-lg-9"><input type="text" required name="code" value="{{isset($edit->code) ? $edit->code : ""}}"  class="form-control">
                                @error('code')
                                <span class="help-block m-b-none text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-3 control-label">Discount %</label>
                            <div class="col-lg-9"><input type="number" required name="discount" value="{{isset($edit->discount) ? $edit->discount : ""}}"  class="form-control">
                                @error('discount')
                                <span class="help-block m-b-none text-danger">{{$message}}</span>
                                @enderror
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
              <h5>Coupons List</h5>
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
              <th>Coupon Code</th>
              <th>Discount</th>
              <th>Creation Ago</th>
              <th>Action</th>
          </tr>
          </thead>
          <tbody>
            @php $sr=1; @endphp
            @foreach ($coupons as $item)
                <tr>
                  <td>{{$sr++}}</td>
                  <td>{{$item->code}}</td>
                  <td>{{$item->discount}} %</td>
                  <td>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
                  <td>
                    <a href="{{route('admins.coupon')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                    <a href="javascript:void(0)" data-href="{{route('admins.coupon')}}/{{$item->id}}/{{'delete'}}"  class="btn btn-danger delete_record">Delete</a>
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