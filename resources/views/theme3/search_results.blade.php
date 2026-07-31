
@extends('theme3.layout')

@section('title', 'Search Results')

@section('content')
<!-- Search Results Section Start -->
<div class="search-results-section">
    <div class="container">
        <div class="search-header">
            <h2>Search Results</h2>
            <p>Found {{ $rproducts->count() }} product(s) for "<strong>{{ $search_query }}</strong>"</p>
            <small class="search-info">Searching in product names, descriptions, tags, codes, brand names, and category names</small>
            <div class="search-actions">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
        
        @if($rproducts->count() > 0)
        <div class="search-results-grid">
            <div class="row">
                @foreach($rproducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ url('/product/' . $product->slug) }}">
                                <img src="{{ img_url($product->image_one) }}" alt="{{ $product->product_name }}" class="img-fluid">
                            </a>
                            @if($product->discount_price < $product->selling_price)
                            <div class="discount-badge">
                                @php
                                    $discount = (($product->selling_price - $product->discount_price) / $product->selling_price) * 100;
                                @endphp
                                {{ round($discount) }}% OFF
                            </div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h5 class="product-title">
                                <a href="{{ url('/product/' . $product->slug) }}">{{ $product->product_name }}</a>
                            </h5>
                            @if($product->brand)
                            <div class="product-brand">
                                <small class="brand-name">Brand: <strong>{{ $product->brand->name }}</strong></small>
                            </div>
                            @endif
                            @if($product->category)
                            <div class="product-category">
                                <small class="category-name">Category: <strong>{{ $product->category->name }}</strong></small>
                            </div>
                            @endif
                            <div class="product-price">
                                @if($product->discount_price < $product->selling_price)
                                    <span class="current-price">Rs {{ number_format($product->discount_price) }}</span>
                                    <span class="original-price">Rs {{ number_format($product->selling_price) }}</span>
                                @else
                                    <span class="current-price">Rs {{ number_format($product->selling_price) }}</span>
                                @endif
                            </div>
                            <div class="product-actions">
                                <a href="{{ url('/product/' . $product->slug) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="no-results">
            <div class="no-results-content">
                <i class="fa-solid fa-search"></i>
                <h3>No Products Found</h3>
                <p>Sorry, we couldn't find any products matching "{{ $search_query }}"</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Search Results Section End -->

<style>
.search-results-section {
    padding: 40px 0;
    min-height: 60vh;
}

.search-header {
    text-align: center;
    margin-bottom: 40px;
}

.search-header h2 {
    color: #333;
    margin-bottom: 10px;
}

.search-header p {
    color: #666;
    font-size: 16px;
}

.search-info {
    color: #888;
    font-size: 14px;
    font-style: italic;
    display: block;
    margin-top: 5px;
}

.search-actions {
    margin-top: 20px;
}

.search-actions .btn {
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.search-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.product-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.discount-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.product-info {
    padding: 20px;
}

.product-title {
    margin-bottom: 15px;
}

.product-title a {
    color: #333;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
}

.product-title a:hover {
    color: #007bff;
}

.product-brand, .product-category {
    margin-bottom: 8px;
}

.brand-name, .category-name {
    color: #666;
    font-size: 12px;
}

.brand-name strong, .category-name strong {
    color: #333;
    font-weight: 600;
}

.product-price {
    margin-bottom: 15px;
}

.current-price {
    color: #e74c3c;
    font-size: 18px;
    font-weight: bold;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    margin-left: 10px;
    font-size: 14px;
}

.product-actions .btn {
    width: 100%;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.no-results {
    text-align: center;
    padding: 60px 0;
}

.no-results-content i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.no-results-content h3 {
    color: #333;
    margin-bottom: 15px;
}

.no-results-content p {
    color: #666;
    margin-bottom: 30px;
    font-size: 16px;
}

@media (max-width: 768px) {
    .search-results-grid .col-lg-3 {
        margin-bottom: 20px;
    }
    
    .product-image {
        height: 150px;
    }
    
    .product-info {
        padding: 15px;
    }
}
</style>
@endsection
