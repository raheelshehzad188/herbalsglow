@php
    use Illuminate\Support\Str;

    $navCategories = $header_menu_categories ?? collect();
@endphp

@if($navCategories->count() > 0)
    @foreach($navCategories as $navCategory)
        @php
            $categoryUrl = url('/' . $navCategory->slug);
            $menuSlug = Str::slug($navCategory->slug ?: $navCategory->name, '-');
            $menuClass = Str::studly($menuSlug) . '-menu';
            $wrapperId = Str::studly($menuSlug);
            $subcategories = $navCategory->subcategories ?? collect();
            $hasDropdown = $subcategories->count() > 0;
            $subChunks = $subcategories->chunk(max(1, (int) ceil($subcategories->count() / 3)));
            $columnClass = $subcategories->count() > 8 ? 'menu-flex-5' : ($subcategories->count() > 4 ? 'menu-flex-3' : 'menu-flex-2');
        @endphp
        <div class="menu-dropdown header-v2-mega-menu {{ $menuClass }} server-side-menu"
             data-overflow-target
             data-has-dropdown="{{ $hasDropdown ? 'True' : 'False' }}"
             data-wrapper-id="{{ $wrapperId }}"
             style="display:flex !important;">
            <a href="{{ $categoryUrl }}"
               style="color:"
               data-ga-event-name="global_navigation"
               data-ga-navigation-group="header {{ Str::lower($navCategory->name) }}"
               data-ga-navigation-path="/{{ $navCategory->slug }}"
               class="link-bar-item">
                <div class="link-bar-item-spacing"></div>
                {{ $navCategory->name }}
            </a>
            @if($hasDropdown)
                <div class="mega-menu mega-menu-{{ $wrapperId }}" style="display: none;"
                     data-category-name="{{ $navCategory->name }}"
                     data-is-category-brands="False">
                    <div class="mega-menu-content">
                        <div class="menu-wrapper menu-types">
                            <div class="menu-flex">
                                @foreach($subChunks as $subChunk)
                                    <div class="menu-column {{ $columnClass }}">
                                        @foreach($subChunk as $subIndex => $subcategory)
                                            @php
                                                $subUrl = url('/' . $subcategory->slug);
                                            @endphp
                                            <div class="{{ $subIndex === 0 ? 'type-heading' : 'type-default' }}">
                                                <a class="menu-link"
                                                   href="{{ $subUrl }}"
                                                   role="{{ $subIndex === 0 ? 'presentation' : 'link' }}"
                                                   data-ga-event-name="global_navigation"
                                                   data-ga-navigation-group="header {{ Str::lower($navCategory->name) }}"
                                                   data-ga-navigation-path="/{{ $subcategory->slug }}">
                                                    <span>{{ $subcategory->name }}</span>
                                                    @if($subIndex === 0)
                                                        <span>
                                                            <svg class="icon icon-chevron-right-blue" width="17" height="16" viewBox="0 0 17 16">
                                                                <use xlink:href="#icon-chevron-right-blue" fill="#126CC5"/>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span></span>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endif
