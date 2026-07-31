@extends('admins.master')

@section('title','Boxes')

@section('category_active','active')

@section('category_child_1_active','active')

@section('category_active_c1','collapse in')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <h5>Box Form</h5>
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
                            <label>Box icon:</label>
                            <input type="text"  class="form-control" required value="<?php echo isset($edit->icon) ? htmlspecialchars($edit->icon) : null; ?>" name="icon" >
                        </div>
                        
                        <div class="form-group" style="display: flex;flex-direction: column;">
                            <label>Box Text:</label>
                            <input type="text"  class="form-control" required value="<?php echo isset($edit->txt) ? htmlspecialchars($edit->txt) : null; ?>" name="txt" >
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
              <h5>Boxes List</h5>
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
              <th>Icon</th>
              <th>Title</th>
              <th>Creation Ago</th>
              <th>Action</th>
          </tr>
          </thead>
          <tbody>
            @php $sr=1; @endphp
            @foreach ($categories as $item)
                <tr>
                  <td>{{$sr++}}</td>
                 <td><i class="fa fa-{{$item->icon}}"></i></td>
                 <td>{{$item->txt}}</td>
                  <td>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
                  <td>
                    <a href="{{route('admins.boxs')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                    <a href="javascript:void(0)" data-href="{{route('admins.boxs')}}/{{$item->id}}/{{'delete'}}"  class="btn btn-danger delete_record">Delete</a>
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