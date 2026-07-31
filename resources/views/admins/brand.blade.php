@extends('admins.master')

@section('title','Brand')

@section('category_active','active')

@section('category_child_1_active','active')

@section('category_active_c1','collapse in')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
          <div class="ibox-title">
              <h5>Brand Form</h5>
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
                          <label for="exampleInputEmail2">Brand</label>
                          <input type="text" required name="name" value="{{isset($edit->name) ? $edit->name : ""}}" id="exampleInputEmail2" class="form-control">
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
              <h5>Brand List</h5>
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
              <th>Brand</th>
              <th>Creation Ago</th>
              <th>Action</th>
          </tr>
          </thead>
          <tbody>
            @php $sr=1; @endphp
            @foreach ($categories as $item)
                <tr>
                  <td>{{$sr++}}</td>
                  <td>{{$item->name}}</td>
                  <td>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>
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