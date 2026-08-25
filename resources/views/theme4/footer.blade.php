@php
    $site = $setting ?? DB::table('setting')->where('id', 1)->first();
    $footerMenus = $footer_menus ?? footer_menus();
    $t4 = rtrim($assets_url ?? asset('theme4') . '/', '/') . '/assets/images';
    $footerLogo = storefront_img($site->wlogo ?? '', '/theme4/assets/images/logos/footer-logo.webp');
    $footerBg = storefront_img('', '/theme4/assets/images/homepage-one/footer-bg.webp');
    $phone = $site->phone ?? '+ 00645 4568';
    $address = $site->footer_text ?? '4517 Washington Ave. Manchester, Kentucky 39495';
@endphp
@if(theme_setting('footer.enabled', true))
<section class="product footer" style="background-image:url('{{ $footerBg }}');background-size:cover;background-position:center top;background-repeat:no-repeat;background-color:#111;">
    <div class="container">
        <div class="footer-service-section">
            <div class="row gy-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="service-wrapper free-shipping">
                        <div class="service-content">
                            <h5 class="service-info service-title">Free Shipping</h5>
                            <p class="service-info service-details">When ordering over $100</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="service-wrapper free-shipping">
                        <div class="service-content">
                            <h5 class="service-info service-title">Free Return</h5>
                            <p class="service-info service-details">Get Return within 30 days</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="service-wrapper free-shipping">
                        <div class="service-content">
                            <h5 class="service-info service-title">Secure Payment</h5>
                            <p class="service-info service-details">100% Secure Online Payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="service-wrapper free-shipping">
                        <div class="service-content">
                            <h5 class="service-info service-title">Best Quality</h5>
                            <p class="service-info service-details">Original Product Guarenteed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-section">
            <div class="row gy-5">
                <div class="col-lg-3 col-sm-6">
                    <div class="footer-order">
                        <div class="logo">
                            <img src="{{ $footerLogo }}" alt="{{ $site->site_title ?? 'Shopus' }}">
                        </div>
                        <div class="footer-link order-link">
                            <ul>
                                <li><a href="{{ url('/track_order') }}">Track Order</a></li>
                                <li><a href="{{ url('/cart') }}">Delivery & Returns</a></li>
                                <li><a href="{{ url('/about') }}">Warranty</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="about-us">
                        <h4 class="footer-heading footer-title">About Us</h4>
                        <div class="footer-link about-link">
                            <ul>
                                @forelse(($footerMenus[0]['pages'] ?? []) as $page)
                                    <li><a href="{{ page_url($page) }}">{{ $page->name }}</a></li>
                                @empty
                                    <li><a href="{{ url('/about') }}">Rave’s Story</a></li>
                                    <li><a href="{{ url('/about') }}">Work With Us</a></li>
                                    <li><a href="{{ url('/about') }}">Coporate News</a></li>
                                    <li><a href="{{ url('/about') }}">Investors</a></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="links">
                        <h4 class="footer-heading footer-title">Useful Links</h4>
                        <div class="footer-link useful-link">
                            <ul>
                                <li><a href="{{ url('/shop') }}">Secure Payment</a></li>
                                <li><a href="{{ url('/about') }}">Privacy Policy</a></li>
                                <li><a href="{{ url('/about') }}">Terms of Use</a></li>
                                <li><a href="{{ url('/shop') }}">Archived Products</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="contact-info">
                        <h4 class="footer-heading footer-title">Contact Info</h4>
                        <div class="footer-link contact-link">
                            <div class="address">
                                <div class="details">
                                    <h4 class="footer-heading">Address:</h4>
                                    <p>{!! $address !!}</p>
                                </div>
                            </div>
                            <div class="phone address">
                                <div class="details">
                                    <h4 class="footer-heading">Phone:</h4>
                                    <p>{{ $phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</section>
@endif
