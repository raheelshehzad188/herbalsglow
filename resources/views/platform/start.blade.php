@extends('platform.layout')
@section('title', 'Start free — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <h1>Start your 7-day free trial.</h1>
    <p class="sg-lead">No card needed. Super admin can later attach a domain, theme and apps to this store.</p>
    <form class="sg-form" method="post" action="{{ platform_url('start') }}">
        @csrf
        @if($errors->any())
            <div class="sg-card" style="background:#fff4f1;border-color:#f1b7ac">{{ $errors->first() }}</div>
        @endif
        <input type="text" name="store_name" placeholder="Store name" value="{{ old('store_name') }}" required>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password (min 6 characters)" required>
        @if(!empty($selectedPlan))
            <input type="hidden" name="plan" value="{{ $selectedPlan->slug }}">
            <p>Selected product: <strong>{{ $selectedPlan->name }}</strong> — {{ $selectedPlan->price_label }}</p>
        @endif
        <button class="sg-btn sg-btn-dark" type="submit">Create store</button>
    </form>
</section>
@endsection
