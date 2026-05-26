
@extends('layout.app')
@section('content')
 <!-- Bread Crumb STRAT -->
  <div class="banner inner-banner1 ">
    <div class="container">
      <section class="banner-detail center-xs">
        <h1 class="banner-title">Blog</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="/">Home</a>/</li>
            <li><span>{{$category_id->title_english}}</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  <!-- Bread Crumb END -->
  
  <!-- CONTAIN START -->
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 col-md-8  content-col">
          <div class="blog-listing">
            <div class="row">
                @foreach($post as $v)
                    <div class="col-lg-6 col-xs-12">
                <div class="blog-item">
                  <div class="blog-media mb-30">
                    <img src="{{asset($v->image)}}" alt="Electrro">
                    <a href="/blog/{{$v->slug}}" title="Click For Read More" class="read">&nbsp;</a> 
                  </div>
                  <div class="blog-detail">
                    <span class="post-date">{{date(" F d Y ",strtotime($v->created_at))}}</span>
                    <h3><a href="/blog/{{$v->slug}}">{{$v->title_english}}</a></h3>
                    <hr>
                    <div class="post-info">
                      <ul>
                        <li><span>By</span><a href="#"> Admin</a></li>
                        <!--<li><a href="#">(5) comments</a></li>-->
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
                @endforeach
            </div>
            
          </div>
        </div>
        <div class="col-lg-2 col-md-4 sidebar-col">
          <div class="sidebar-block">
            
            <div class="sidebar-box listing-box mb-40"> <span class="opener plus"></span>
              <div class="sidebar-title">
                <h3>Categories</h3><span></span>
              </div>
              <div class="sidebar-contant">
                <ul>
                    @foreach($cate as $v)
                  <li><a href="/blog_category/{{$v->slug}}">{{$v->title_english}}</a></li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="sidebar-box sidebar-item sidebar-item-wide"> <span class="opener plus"></span>
              <div class="sidebar-title">
                <h3>Recent Post</h3><span></span>
              </div>
              <div class="sidebar-contant">
                <ul>
                    <?php 
                        $pro = DB::table('posts')->limit('4')->orderBy('id','DESC')->get();
                    ?>
                    @foreach($pro as $p)
                  <li>
                    <div class="pro-media"> <a href="/blog/{{$p->slug}}"><img alt="T-shirt" src="{{asset($p->image)}}"></a> </div>
                    <div class="pro-detail-info"> <a href="/blog/{{$p->slug}}">{{$p->title_english}}</a>
                      <div class="post-info">{{date(" F d Y ",strtotime($p->created_at))}}</div>
                    </div>
                  </li>
                   @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- CONTAINER END --> 
  @endsection
