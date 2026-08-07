@php
    $footerMenus = $footer_menus ?? footer_menus();
@endphp

<link rel="stylesheet" href="{{ asset('theme3/css/t3-footer-nav.css') }}">

@if(!empty($footerMenus))
    @foreach($footerMenus as $section)
        @if(!empty($section['pages']) && count($section['pages']))
            <div class="footer-section t3-footer-section">
                <ul>
                    <li class="title">{{ $section['title'] }}</li>
                    @foreach($section['pages'] as $page)
                        <li>
                            <a href="{{ page_url($page) }}">{{ $page->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
@endif
