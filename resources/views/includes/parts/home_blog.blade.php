<!--Feater start-->
<style>
    .section-title {
        color: #333;
        text-align: left;
        margin-bottom: 30px;
    }

    .bg-secondary {
        background-color: #007bff;
        color: #000;
        padding: 5px 15px;
        border-radius: 5px;
    }

    .post-wrap {
        background-color: #fff;
        border-radius: 3px;
        overflow: hidden;
        border:1px solid #ebebeb;
        transition: all 0.3s ease;
    }

    .post-wrap:hover {
            box-shadow: 0 4px 6px rgb(0 0 0 / 6%)
    }

    .post-image {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    .post-body {
        padding: 20px;
    }

    .post-meta {
        margin-bottom: 10px;
        color: #777;
    }

    .post-title {
        margin-bottom: 10px;
    }

    .post-title h2 {
        font-size: 20px;
        color: #333;
        margin: 0;
    }

    .post-text p {
        color: #555;
        line-height: 1.6;
    }

    .post-link {
        text-decoration: none;
        color: inherit;
    }
</style>

<div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Blogs</span></h2>
        <div class="row px-xl-5">
            @foreach ($posts as  $k=>$v)
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
            <p>{{substr(strip_tags($v->description_english),0,100)}}...</p>
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