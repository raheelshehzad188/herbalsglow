<div id="mobileSearchModal" class="mobile-search-modal t3-mobile-search">
    <div class="mobile-search-content">
        <div class="mobile-search-header">
            <h3>Search Products</h3>
            <span id="closeMobileSearch" class="close-mobile-search">&times;</span>
        </div>
        <div class="mobile-search-body">
            <form action="{{ url('/search') }}" method="GET" id="mobileSearchForm">
                <div class="mobile-search-wrapper">
                    <input type="text" name="q" id="mobileSearchInput" placeholder="Search products..." autocomplete="off">
                    <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div id="mobileSearchResults" class="mobile-search-results"></div>
            </form>
        </div>
    </div>
    <div id="mobileSearchOverlay" class="mobile-search-overlay"></div>
</div>
