@extends($layout)

@section('content')
<link rel="stylesheet" href="{{ asset('theme3/css/t3-info-page.css') }}">

<div class="t3-info-page">
    <div class="container-fluid">
        <nav class="t3-info-page__breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span> / </span>
            <span>{{ $title }}</span>
        </nav>

        <h1 class="t3-info-page__title">{{ $title }}</h1>

        <div class="t3-info-page__card">
            <div class="t3-info-page__content">
                {!! $pages[0]->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
