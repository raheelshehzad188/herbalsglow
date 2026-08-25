@extends('platform.layout')
@section('title', 'Apps — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <h1>{{ $settings['apps_heading'] ?? 'Add what your business needs next.' }}</h1>
    <p class="sg-lead">{{ $settings['apps_sub'] ?? '' }}</p>
    <form class="sg-filters" method="get">
        <a class="sg-chip {{ $activeCat === 'All' ? 'is-on' : '' }}" href="{{ platform_url('apps') }}">All</a>
        @foreach($appCategories as $cat)
            <a class="sg-chip {{ $activeCat === $cat ? 'is-on' : '' }}" href="{{ platform_url('apps?cat='.urlencode($cat)) }}">{{ $cat }}</a>
        @endforeach
        <div class="sg-search">
            <input type="hidden" name="cat" value="{{ $activeCat }}">
            <input type="search" name="q" value="{{ $appSearch }}" placeholder="Search apps">
        </div>
    </form>
    <div class="sg-grid-3">
        @forelse($apps as $app)
            <article class="sg-card">
                <div class="sg-app-icon" style="background: {{ $app->color ?: '#111' }}">{{ $app->icon ?: strtoupper(substr($app->name,0,1)) }}</div>
                <div class="cat" style="font-size:12px;color:#5c5c5c;margin-bottom:6px">{{ $app->category }}</div>
                <h3>{{ $app->name }}</h3>
                <p>{{ $app->description }}</p>
            </article>
        @empty
            <p>No apps yet. Add them from Super Admin → Apps.</p>
        @endforelse
    </div>
</section>
@endsection
