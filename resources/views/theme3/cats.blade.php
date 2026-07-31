@if(isset($categories) && count($categories) > 0)
<section class="t3-section t3-categories-section">
    <div class="container">
        <h2 class="t3-section-title">Shop by <span>Category</span></h2>
        <div class="t3-cat-scroll">
            @foreach($categories as $category)
            <a href="{{ url('category/' . $category->slug) }}" class="t3-cat-chip">
                <img src="{{ img_url($category->image) }}" alt="{{ $category->name }}"
                     onerror="this.src='{{ $assets_url }}img/solo.webp'">
                <span>{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
