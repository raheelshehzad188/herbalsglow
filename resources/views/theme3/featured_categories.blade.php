@if(isset($featured_categories) && count($featured_categories) > 0)
    @foreach($featured_categories as $category)
    <section id="hp-module-{{ $category->id }}" class="t3-category-module">
        <div class="container-fluid">
            <div class="title" style="display:flex;justify-content:space-between;align-items:center;padding:24px 0 16px;">
                <h3 style="margin:0;font-size:24px;font-weight:700;">
                    <a href="{{ url('category/' . $category->slug) }}" style="color:#181b1f;text-decoration:none;">
                        {{ $category->name }}
                    </a>
                </h3>
                <a href="{{ url('category/' . $category->slug) }}" style="color:var(--primary-color,#458500);font-weight:600;text-decoration:none;font-size:14px;">
                    See All →
                </a>
            </div>
            @if(isset($category->products) && count($category->products) > 0)
            <div class="product-column">
                <div class="product-cells v2" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
                    @foreach($category->products as $product)
                        @php $v = $product; @endphp
                        @include('theme3/product_box_new')
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
    @endforeach
@endif
