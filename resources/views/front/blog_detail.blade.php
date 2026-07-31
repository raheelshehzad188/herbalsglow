
@extends('layout.app2')
@section('content')
 <!-- Bread Crumb STRAT -->
 <style>
     .main-form {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 5px;
}

.main-form h4 {
    margin-bottom: 20px;
}

.main-form .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.main-form .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.main-form input[type="text"],
.main-form textarea {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
}

.main-form input[type="text"]:focus,
.main-form textarea:focus {
    border-color: #007bff;
    outline: none;
}

 </style>
  
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    <a class="breadcrumb-item text-dark" href="/blog/{{$blog[0]->slug}}">Blog</a>
                    <span class="breadcrumb-item active">{{$blog[0]->title_english}}</span>
                </nav>
            </div>
        </div>
    </div>
  <!-- Bread Crumb END -->

  <!-- CONTAIN START -->
  @foreach($blog as $v)
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-md-12 pb-xs-60">
          <div class="row">
            <div class="col-sm-12 mb-60">
              <div class="blog-media mb-30"> 
                <img src="{{asset($v->image)}}" alt="Electrro"> 
              </div>
              <div class="blog-detail ">
                <!--<h3>{{$v->title_english}}</h3>-->
                <p><?php echo $v->description_english ?></p>
                <hr>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="comments-area-main">
                <div class="comments-area">
                  <h4>Comments<span>({{count($cum)}})</span></h4>
                  <ul class="comment-list mt-30">
                      @if(count($cum) > 0)
                      @foreach($cum as $c)
                    <!--<li>-->
                      <!--<div class="comment-user"> <img src="{{asset('')}}front/assets/images/comment-user.jpg" alt="Electrro"> </div>-->
                      <div class="comment-detail">
                        <!--<div class="user-name">{{$c->name}}</div>-->
                        <div class="post-info">
                          <ul>
                            <li>{{date(" F d Y ",strtotime($c->created_at))}}</li>
                            <!--<li><a href="#"><i class="fa fa-reply"></i>Reply</a></li>-->
                          </ul>
                        </div>
                        <p>{{$c->comment}}</p>
                      </div>
                    <!--</li>-->
                    @endforeach
                    @else
                        No Comment Yet!
                    @endif
                  </ul>
                </div>
<div class="main-form mt-30 float-right">
    <h4>Leave a Comment</h4>
    <div class="row mt-30">
        <form action="/blog_comment" method="post">
            @csrf
            <input type="hidden" name="bid" value="{{$v->id}}">
            <div class="col-sm-12 mb-3">
                <input type="text" class="form-control" placeholder="Your Name" name="name" required>
            </div>
            <div class="col-sm-12 mb-3">
                <textarea class="form-control" cols="30" rows="3" name="comment" placeholder="Your Comment" required></textarea>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-primary" name="submit" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>


              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>
  @endforeach
  <!-- CONTAINER END --> 
@endsection
