@extends($layout)
@section('content')
<section class="product best-product t4-page footer-padding">
    <div class="container">
        <div class="section-title">
            <h5>{{ $title ?? Session::get('title') ?? 'Shop' }}</h5>
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
            <div id="shop-scroll-sentinel" style="height:1px;"></div>
        </div>
    </div>
</section>
@if(method_exists($products, 'currentPage'))
<script>
(function() {
    var currentPage = {{ (int) $products->currentPage() }};
    var lastPage = {{ (int) $products->lastPage() }};
    var isLoading = false;
    var container = document.getElementById('products-container');
    var loadingIndicator = document.getElementById('loading-indicator');
    var sentinel = document.getElementById('shop-scroll-sentinel');
    var tagSlug = @json($slug ?? null);
    var categoryId = @json(isset($category_id) ? (is_object($category_id) ? $category_id->id : $category_id) : null);
    var searchQuery = @json($search_query ?? null);
    var endpoint = @json(url('/load-more-products'));

    function nearBottom() {
        var doc = document.documentElement;
        var scrolled = window.pageYOffset || doc.scrollTop || 0;
        var visible = window.innerHeight || doc.clientHeight || 0;
        var height = Math.max(doc.scrollHeight, document.body ? document.body.scrollHeight : 0);
        return (scrolled + visible) >= (height - 600);
    }

    function refreshAos() {
        if (window.AOS && typeof window.AOS.refresh === 'function') {
            window.AOS.refresh();
        }
    }

    function sentinelInRange() {
        if (!sentinel) return nearBottom();
        var rect = sentinel.getBoundingClientRect();
        return rect.top < ((window.innerHeight || 0) + 500);
    }

    function loadMoreProducts() {
        if (isLoading || currentPage >= lastPage || !container) return;
        isLoading = true;
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        var nextPage = currentPage + 1;
        var url = endpoint + '?page=' + nextPage + '&theme=theme4';
        if (tagSlug) url += '&tag_slug=' + encodeURIComponent(tagSlug);
        if (categoryId) url += '&category_id=' + encodeURIComponent(categoryId);
        if (searchQuery) url += '&search_query=' + encodeURIComponent(searchQuery);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }})
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.html) {
                    container.insertAdjacentHTML('beforeend', data.html);
                    Array.prototype.forEach.call(container.querySelectorAll('[data-aos]'), function (el) {
                        el.removeAttribute('data-aos');
                        el.style.opacity = '1';
                        el.style.transform = 'none';
                    });
                    refreshAos();
                }
                if (data && data.currentPage) currentPage = data.currentPage;
                else currentPage = nextPage;
                if (data && data.lastPage) lastPage = data.lastPage;
                if (data && data.hasMore === false) lastPage = currentPage;
            })
            .catch(function () {})
            .finally(function () {
                isLoading = false;
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                if (currentPage < lastPage && sentinelInRange()) {
                    loadMoreProducts();
                }
            });
    }

    if (window.IntersectionObserver && sentinel) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) loadMoreProducts();
            });
        }, { root: null, rootMargin: '500px 0px', threshold: 0 });
        observer.observe(sentinel);
    }

    function onScroll() {
        if (nearBottom() || sentinelInRange()) loadMoreProducts();
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    document.addEventListener('scroll', onScroll, { passive: true, capture: true });
    if (document.documentElement) {
        document.documentElement.addEventListener('scroll', onScroll, { passive: true });
    }
    if (document.body) {
        document.body.addEventListener('scroll', onScroll, { passive: true });
    }

    var poll = setInterval(function () {
        if (currentPage >= lastPage) {
            clearInterval(poll);
            return;
        }
        if (nearBottom() || sentinelInRange()) loadMoreProducts();
    }, 400);

    if (document.readyState === 'complete') onScroll();
    else window.addEventListener('load', onScroll);
})();
</script>
@endif
@endsection
