@extends($layout)

@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;

    $pageTitle = Session::get('title') ?? ($title ?? 'Shop');
    $totalProducts = method_exists($products, 'total') ? $products->total() : count($products);
    $subnavItems = collect();

    if (!empty($pcategory)) {
        $subnavItems = DB::table('sub_categories')
            ->where('category_id', $pcategory->id)
            ->orderBy('name')
            ->get();
    } elseif (!empty($category_id) && empty($sub_cat)) {
        $subnavItems = DB::table('sub_categories')
            ->where('category_id', $category_id->id)
            ->orderBy('name')
            ->get();
    } elseif (!empty($category_id) && !empty($sub_cat) && isset($sub_cat->category_id)) {
        $subnavItems = DB::table('sub_categories')
            ->where('category_id', $sub_cat->category_id)
            ->orderBy('name')
            ->get();
    }

    $activeSubSlug = $sub_cat->slug ?? ($category_id->slug ?? null);
    $isSubcategoryList = !empty($sub_cat)
        && !empty($category_id)
        && isset($sub_cat->id, $category_id->id)
        && (int) $sub_cat->id === (int) $category_id->id;

    $loadCategoryId = null;
    $loadSubcategoryId = null;
    if (!empty($category_id)) {
        if ($isSubcategoryList) {
            $loadSubcategoryId = $category_id->id;
            $loadCategoryId = $pcategory->id ?? null;
        } else {
            $loadCategoryId = $category_id->id;
        }
    }
@endphp

<link rel="stylesheet" href="{{ asset('theme3/css/t3-product-box.css') }}">
<link rel="stylesheet" href="{{ asset('theme3/css/t3-shop-list.css') }}">

<div class="t3-shop-page">
    <div class="container-fluid">
        <nav class="t3-shop-page__breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            @if(!empty($tags))
                <span> / </span>
                <span class="last">{{ $pageTitle }}</span>
            @elseif(!empty($pcategory))
                <span> / </span>
                <a href="{{ url('/' . $pcategory->slug) }}">{{ $pcategory->name }}</a>
                <span> / </span>
                <span class="last">{{ $pageTitle }}</span>
            @elseif(!empty($category_id) && empty($tags))
                <span> / </span>
                <span class="last">{{ $pageTitle }}</span>
            @else
                <span> / </span>
                <span class="last">{{ $pageTitle }}</span>
            @endif
        </nav>

        <h1 class="t3-shop-page__title">{{ $pageTitle }}</h1>

        @if($subnavItems->count() > 0)
            <div class="t3-shop-page__subnav">
                @if(!empty($pcategory))
                    <a href="{{ url('/' . $pcategory->slug) }}"
                       class="{{ !$isSubcategoryList ? 'is-active' : '' }}">
                        All {{ $pcategory->name }}
                    </a>
                @endif
                @foreach($subnavItems as $subItem)
                    <a href="{{ url('/' . $subItem->slug) }}"
                       class="{{ ($activeSubSlug === $subItem->slug) ? 'is-active' : '' }}">
                        {{ $subItem->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="t3-shop-page__count">
            {{ number_format($totalProducts) }} {{ Str::plural('product', $totalProducts) }}
        </div>

        @if($totalProducts > 0)
            <div class="t3-shop-page__grid" id="products-container">
                @foreach($products as $k => $v)
                    @php
                        $productColClass = 't3-list-product';
                        $alwaysShowRating = true;
                    @endphp
                    @include('theme3.product_box_new')
                @endforeach
            </div>

            <div id="loading-indicator" class="t3-shop-page__loading" style="display: none;">
                Loading more products...
            </div>
        @else
            <div class="t3-shop-page__empty">
                No products found in this category.
            </div>
        @endif
    </div>
</div>

@if($totalProducts > 0 && method_exists($products, 'hasMorePages') && $products->hasMorePages())
<script>
(function () {
    let currentPage = {{ $products->currentPage() }};
    let lastPage = {{ $products->lastPage() }};
    let isLoading = false;
    const container = document.getElementById('products-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const tagSlug = @json($slug ?? null);
    const categoryId = @json($loadCategoryId);
    const subcategoryId = @json($loadSubcategoryId);
    const searchQuery = @json($search_query ?? null);

    function loadMoreProducts() {
        if (!container || isLoading || currentPage >= lastPage) {
            return;
        }

        isLoading = true;
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
        }
        currentPage++;

        let url = `{{ url('/load-more-products') }}?page=${currentPage}&theme=theme3`;
        if (tagSlug) {
            url += `&tag_slug=${encodeURIComponent(tagSlug)}`;
        }
        if (subcategoryId) {
            url += `&subcategory_id=${subcategoryId}`;
        } else if (categoryId) {
            url += `&category_id=${categoryId}`;
        }
        if (searchQuery) {
            url += `&search_query=${encodeURIComponent(searchQuery)}`;
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data.html) {
                container.insertAdjacentHTML('beforeend', data.html);
                lastPage = data.lastPage;
            }

            if (loadingIndicator) {
                if (!data.hasMore) {
                    loadingIndicator.innerHTML = 'No more products to load';
                    loadingIndicator.style.display = 'block';
                } else {
                    loadingIndicator.style.display = 'none';
                }
            }
            isLoading = false;
        })
        .catch(function (error) {
            console.error('Error loading products:', error);
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            isLoading = false;
            currentPage--;
        });
    }

    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
            loadMoreProducts();
        }
    });
})();
</script>
@endif
@endsection
