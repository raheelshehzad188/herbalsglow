<!--Feater start-->
<div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Blogs</span></h2>
        <div class="row px-xl-5">
            @foreach ($homeposts as  $k=>$v)
            @if($k <= 2)
            <div class="col-lg-4 col-md-4 col-sm-6 pb-1">
                <a class="post-link" href="/blog/{{$v->slug}}">
    <div class="post-wrap">

      <div class="post-image" style="background-image: url('{{url($v->image)}}')">
      </div>

      <div class="post-body">
        <div class="post-body-primary">
          <div class="post-meta">
            <p>{{ date("F d, Y", strtotime($v->updated_at)) }}</p>
          </div>

          <div class="post-title">
            <h2>{{$v->title_english}}</h2>
          </div>

          <div class="post-text">
            <p>{{substr(strip_tags($v->description_english),0,100)}}</p>
          </div>
        </div>

      </div>

    </div>
  </a>
            </div>
            @endif
            @endforeach
        </div>
    </div>
<!--Feater end-->