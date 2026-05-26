@extends($layout)
@section('content')
<?php 
use App\Models\Admins\Category;
?>
<style>
    svg{
        width:50px !important;
    }
</style>
<!-- page-title -->
        <div class="tf-page-title">
            <div class="container-full">
                <div class="heading text-center">{{ Session::get('title') }}</div>
            </div>
        </div>
        <!-- /page-title -->
          
        <div class="all-products">
            <div class="container">
                <div class="inside-all-products" id="products-container">
				
                @foreach ($products as  $k=>$v)
                    @include('theme2/product_box_new')
                    @endforeach
           
                </div><!--inside-new-arrivals-->
                <div id="loading-indicator" style="text-align: center; padding: 20px; display: none;">
                    <p>Loading more products...</p>
                </div>
            </div><!--container-->
        </div><!--new-arrivals-->

        <script>
        (function() {
            let currentPage = {{ $products->currentPage() }};
            let lastPage = {{ $products->lastPage() }};
            let isLoading = false;
            const container = document.getElementById('products-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            @if(isset($slug))
            const tagSlug = '{{ $slug }}';
            @else
            const tagSlug = null;
            @endif
            @if(isset($category_id))
            const categoryId = {{ $category_id->id }};
            @else
            const categoryId = null;
            @endif
            @if(isset($search_query))
            const searchQuery = '{{ $search_query }}';
            @else
            const searchQuery = null;
            @endif

            function loadMoreProducts() {
                if (isLoading || currentPage >= lastPage) {
                    return;
                }

                isLoading = true;
                loadingIndicator.style.display = 'block';
                currentPage++;

                let url = `{{ url('/load-more-products') }}?page=${currentPage}&theme=theme2`;
                if (tagSlug) {
                    url += `&tag_slug=${encodeURIComponent(tagSlug)}`;
                }
                if (categoryId) {
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        container.insertAdjacentHTML('beforeend', data.html);
                        lastPage = data.lastPage;
                    }
                    
                    if (!data.hasMore) {
                        loadingIndicator.style.display = 'none';
                        loadingIndicator.innerHTML = '<p>No more products to load</p>';
                    } else {
                        loadingIndicator.style.display = 'none';
                    }
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    loadingIndicator.style.display = 'none';
                    isLoading = false;
                    currentPage--; // Rollback on error
                });
            }

            // Infinite scroll
            window.addEventListener('scroll', function() {
                if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                    loadMoreProducts();
                }
            });
        })();
        </script>
                      
                     
@endsection
