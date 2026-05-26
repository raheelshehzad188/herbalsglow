@extends('admins.master')

@section('title','Faq')

@section('faq','active')


@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>FAQ Form</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="faq" method="post" action="/admin/faq" enctype="multipart/form-data">
                        @csrf
                        @if(isset($edit->id) && $edit->id)
                        <input type="hidden" name="id" value="{{(isset($edit->id)?$edit->id:'')}}">
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 ">Question:</label>
                                    <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->question) ? htmlspecialchars($edit->question) : null; ?>" required class="form-control" name="question"></div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-sm-12">
                                <div class="form-group"><label class="col-sm-12 ">Answer:</label>
                                <div class="col-sm-12"><textarea class="summernote" name="answer" id="answer" style="height:500px">
                                            <?php echo isset($edit->answer) ? htmlspecialchars($edit->answer) : null; ?>
        
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        @if(isset($edit->id) && $edit->id)
                        <div class="row">
                           <input type="hidden" name="hidden_id" value="{{isset($edit->id)?$edit->id:'';}}">
                        </div>
                        @endif
                       
                       
                        
                        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                        <div class="form-group">
                            <div class="col-sm-10"><button class="btn btn-md btn-primary" type="submit"><strong>Save</strong></button>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <div>
                    
                </div>
            </div>
        </div>
      </div>
    <div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Faq's List</h5>
            </div>
            <div class="ibox-content">
  
                <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th>Sr.No</th>
                <th>Questions</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
              @php $sr=1; @endphp
              @if(isset($sliders))
              @php
              @endphp
              @foreach ($sliders as $item)
                  <tr>
                    <td>{{$sr++}}</td>
                    <td>{{$item->question}}</td>
            
                    <td>
                        <a href="javascript:void(0)" data-href="{{route('admins.faq')}}/{{$item->id}}/{{'delete'}}"  class="btn btn-danger delete_record">Delete</a>
                        <a href="{{route('admins.faq')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                    </td>
                  </tr>
              @endforeach
              @endif
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
