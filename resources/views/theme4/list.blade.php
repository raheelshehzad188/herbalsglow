@extends($layout)
@section('content')
<section class="product best-product t4-page footer-padding">
    <div class="container">
        <div class="section-title">
            <h5>{{ Session::get('title') ?? ($title ?? 'Shop') }}</h5>
        </div>
        <div class="best-product-section">
            <div class="row g-4" id="products-container">
                @forelse($products as $v)
                <div class="col-xl-2 col-md-4">
                    @include('theme4.product_box')
                </div>
                @empty
                <p>No products found.</p>
                @endforelse
            </div>
            <div id="loading-indicator" style="text-align:center;padding:20px;display:none;">Loading more products...</div>
            @if(method_exists($products, 'links'))
                <div class="mt-4">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</section>
@if(method_exists($products, 'currentPage'))
<script>
(function() {
    let currentPage = {{ $products->currentPage() }};
    let lastPage = {{ $products->lastPage() }};
    let isLoading = false;
    const container = document.getElementById('products-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const tagSlug = @json($slug ?? null);
    const categoryId = @json(isset($category_id) ? (is_object($category_id) ? $category_id->id : $category_id) : null);
    const searchQuery = @json($search_query ?? null);

    function loadMoreProducts() {
        if (isLoading || currentPage >= lastPage) return;
        isLoading = true;
        loadingIndicator.style.display = 'block';
        currentPage++;
        let url = `{{ url('/load-more-products') }}?page=${currentPage}&theme=theme4`;
        if (tagSlug) url += `&tag_slug=${encodeURIComponent(tagSlug)}`;
        if (categoryId) url += `&category_id=${categoryId}`;
        if (searchQuery) url += `&search_query=${encodeURIComponent(searchQuery)}`;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then(r => r.json())
            .then(data => {
                if (data.html) {
                    const wrap = document.createElement('div');
                    wrap.innerHTML = data.html;
                    Array.from(wrap.children).forEach(function (child) {
                        const col = document.createElement('div');
                        col.className = 'col-xl-2 col-md-4';
                        col.appendChild(child);
                        container.appendChild(col);
                    });
                }
            })
            .finally(function () {
                isLoading = false;
                loadingIndicator.style.display = 'none';
            });
    }
    window.addEventListener('scroll', function () {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 400) {
            loadMoreProducts();
        }
    });
})();
</script>
@endif
@endsection
