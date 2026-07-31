@extends('layout.app')
@section('content')
 <!-- Bread Crumb STRAT -->
  <div class="banner inner-banner1 ">
    <div class="container">
      <section class="banner-detail center-xs">
        <h1 class="banner-title">>Blog Detail</h1>
        <div class="bread-crumb right-side float-none-xs">
          <ul>
            <li><a href="">Home</a>/</li>
            <li><a href="/blog">Blog</a>/</li>
            <li><span>>Blog Detail</span></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
  <!-- Bread Crumb END -->

  <!-- CONTAIN START -->
  @foreach($blog as $v)
  <section class="ptb-95">
    <div class="container">
      <div class="row">
        <div class="col-md-9 pb-xs-60">
          <div class="row">
            <div class="col-xs-12 mb-60">
              <div class="blog-media mb-30"> 
                <img src="{{asset($v->image)}}" alt="Electrro"> 
              </div>
              <div class="blog-detail ">
                <div class="post-info">
                  <ul>
                    <li><span class="post-date">{{date(" F d Y ",strtotime($v->created_at))}}</span></li>
                    <li><span></span><a href="#"> Posted by : admin / On : {{date(" F d Y ",strtotime($v->created_at))}}</a></li>
                  </ul>
                </div>
                <h3>{{$v->title_english}}</h3>
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
                    <li>
                      <div class="comment-user"> <img src="{{asset('')}}front/assets/images/comment-user.jpg" alt="Electrro"> </div>
                      <div class="comment-detail">
                        <div class="user-name">{{$c->name}}</div>
                        <div class="post-info">
                          <ul>
                            <li>{{date(" F d Y ",strtotime($c->created_at))}}</li>
                            <!--<li><a href="#"><i class="fa fa-reply"></i>Reply</a></li>-->
                          </ul>
                        </div>
                        <p>{{$c->comment}}</p>
                      </div>
                    </li>
                    @endforeach
                    @else
                        No Comment Yet!
                    @endif
                  </ul>
                </div>
                <div class="main-form mt-30">
                  <h4>Leave a comments</h4>
                  <div class="row mt-30">
                    <form action="/blod_comment" method="post">
                     @csrf
                     <input type="hidden" name="bid" value="{{$v->id}}">
                      <div class="col-sm-4 mb-30">
                        <input type="text" placeholder="Name" name="name" required>
                      </div>
                      <div class="col-xs-12 mb-30">
                        <textarea cols="30" rows="3" name="cum" placeholder="Message" required></textarea>
                      </div>
                      <div class="col-xs-12">
                        <button class="btn btn-color" name="submit" type="submit">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="sidebar-block">
            <div class="sidebar-box mb-40">
              <form>
                <div class="search-box">
                  <input type="text" placeholder="Search entire store here..." class="input-text">
                  <button class="search-btn"></button>
                </div>
              </form>
            </div>
            <div class="sidebar-box listing-box mb-40"> <span class="opener plus"></span>
              <div class="sidebar-title">
                <h3>Categories</h3><span></span>
              </div>
              <div class="sidebar-contant">
                <ul>
                    @foreach($bcate as $cate)
                  <li><a href="/blog_category/{{$cate->slug}}">{{$cate->title_english}}</a></li>
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
                    <div class="pro-media"> <a><img alt="T-shirt" src="{{asset($p->image)}}"></a> </div>
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
  @endforeach
  <!-- CONTAINER END --> 
@endsection
