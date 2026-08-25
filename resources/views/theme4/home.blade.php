@extends($layout)
@section('content')
@php
    $layoutNum = storefront_home_layout($setting ?? null);
@endphp
@include('theme4.homes.home-' . $layoutNum)
@endsection
