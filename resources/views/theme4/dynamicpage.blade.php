@extends($layout)
@section('content')
<section class="t4-page">
    <div class="container">
        <h1>{{ $title ?? '' }}</h1>
        <div>{!! $pages[0]->content ?? '' !!}</div>
    </div>
</section>
@endsection
