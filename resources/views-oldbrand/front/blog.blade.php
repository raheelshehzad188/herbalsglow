@extends('layout.app2')
@section('content')
 <!-- Bread Crumb STRAT -->
  
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    <span class="breadcrumb-item active">Blog</span>
                </nav>
            </div>
        </div>
    </div>
  <!-- Bread Crumb END -->
  
  <!-- CONTAIN START -->
  
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12  content-col">
          <div class="blog-listing">
            <div class="row">
                
                @foreach($post as $v)
                    <div class="col-lg-4 col-xs-12">
                <div class="blog-item">
                  <div class="blog-media mb-30">
                    <img class="blog-item-img" src="{{asset($v->image)}}" alt="Electrro">
                    <a href="/blog/{{$v->slug}}" title="Click For Read More" class="read">&nbsp;</a> 
                  </div>
                  <div class="blog-detail">
                    <h3><a href="/blog/{{$v->slug}}">{{$v->title_english}}</a></h3>
                    <hr>
                  </div>
                </div>
              </div>
                @endforeach
            </div>
            
          </div>
        </div>
        
      </div>
    </div>
  </section>
  <!-- CONTAINER END --> 
  @endsection