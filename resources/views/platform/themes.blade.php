@extends('platform.layout')
@section('title', 'Themes — '.$brand)
@section('content')
<section class="sg-wrap sg-hero">
    <h1>{{ $settings['themes_heading'] ?? 'Storefronts with a point of view.' }}</h1>
    <p class="sg-lead">{{ $settings['themes_sub'] ?? '' }}</p>
    <form class="sg-filters" method="get">
        <a class="sg-chip {{ $activeCat === 'All' ? 'is-on' : '' }}" href="{{ platform_url('themes') }}">All</a>
        @foreach($themeCategories as $cat)
            <a class="sg-chip {{ $activeCat === $cat ? 'is-on' : '' }}" href="{{ platform_url('themes?cat='.urlencode($cat)) }}">{{ $cat }}</a>
        @endforeach
        <div class="sg-search">
            <input type="hidden" name="cat" value="{{ $activeCat }}">
            <input type="search" name="q" value="{{ $themeSearch }}" placeholder="Search themes...">
        </div>
    </form>
    <div class="sg-grid-3">
        @forelse($themes as $theme)
            <article class="sg-card sg-theme-card">
                <img src="{{ $theme->image }}" alt="{{ $theme->name }}">
                <div class="cat">{{ $theme->category }}</div>
                <h3>{{ $theme->name }}</h3>
                <p>{{ $theme->description }}</p>
                <div class="sg-theme-actions">
                    <a href="{{ $theme->demo_url ?: url('/') }}">View demo</a>
                    <a href="{{ platform_url('start') }}">Choose theme</a>
                </div>
            </article>
        @empty
            <p>No themes yet. Add them from Super Admin → Themes.</p>
        @endforelse
    </div>
</section>
@endsection
