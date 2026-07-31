(function () {
    var searchInput = document.getElementById('txtSearch');
    var searchForm = document.getElementById('searchForm');
    var clearBtn = document.getElementById('clearSearchBtn');
    var suggestionsBox = document.getElementById('sug-results');
    var suggestionsInner = suggestionsBox ? suggestionsBox.querySelector('.t3-search-suggestions__inner') : null;
    var searchTimeout = null;
    var searchConfig = window.t3SearchConfig || {};
    var fallbackImage = searchConfig.fallbackImage || ((window.location.origin || '') + '/theme3/img/solo.webp');
    var currency = searchConfig.currency || 'Rs.';

    if (!searchInput || !searchForm || !suggestionsBox || !suggestionsInner) {
        return;
    }

    function toggleClearButton() {
        if (!clearBtn) {
            return;
        }
        if (searchInput.value.trim()) {
            clearBtn.classList.remove('hide');
        } else {
            clearBtn.classList.add('hide');
        }
    }

    function hideSuggestions() {
        suggestionsBox.style.display = 'none';
        suggestionsInner.innerHTML = '';
    }

    function showSuggestions() {
        suggestionsBox.style.display = 'block';
    }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatPrice(price) {
        var amount = Number(price || 0);
        return currency + ' ' + amount.toLocaleString();
    }

    function renderSuggestions(products, query) {
        if (!products || !products.length) {
            suggestionsInner.innerHTML = '<div class="t3-search-suggestions__empty">No products found</div>';
            showSuggestions();
            return;
        }

        var html = '<div class="t3-search-suggestions__title">Suggested products</div>';
        html += '<ul class="t3-search-suggestions__list">';

        products.forEach(function (product) {
            var imageUrl = product.image || fallbackImage;
            if (imageUrl && imageUrl.indexOf('http') !== 0 && imageUrl.indexOf('/') !== 0) {
                imageUrl = '/' + imageUrl;
            }

            html += '<li class="t3-search-suggestions__item">';
            html += '<a href="' + escapeHtml(product.url) + '">';
            html += '<img class="t3-search-suggestions__thumb" src="' + escapeHtml(imageUrl) + '" alt="" onerror="this.src=\'' + fallbackImage + '\'">';
            html += '<span class="t3-search-suggestions__info">';
            html += '<span class="t3-search-suggestions__name">' + escapeHtml(product.name) + '</span>';
            html += '<span class="t3-search-suggestions__price">' + escapeHtml(formatPrice(product.price)) + '</span>';
            html += '</span></a></li>';
        });

        html += '</ul>';
        html += '<a class="t3-search-suggestions__view-all" href="' + escapeHtml(searchForm.action + '?q=' + encodeURIComponent(query)) + '">View all results</a>';

        suggestionsInner.innerHTML = html;
        showSuggestions();
    }

    function performLiveSearch(query) {
        suggestionsInner.innerHTML = '<div class="t3-search-suggestions__loading">Searching...</div>';
        showSuggestions();

        fetch('/api/live-search?q=' + encodeURIComponent(query), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                renderSuggestions(data.products || [], query);
            })
            .catch(function () {
                suggestionsInner.innerHTML = '<div class="t3-search-suggestions__empty">Search unavailable. Press Enter to search.</div>';
                showSuggestions();
            });
    }

    searchInput.addEventListener('input', function () {
        toggleClearButton();
        var query = searchInput.value.trim();

        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        if (query.length < 2) {
            hideSuggestions();
            return;
        }

        searchTimeout = setTimeout(function () {
            performLiveSearch(query);
        }, 300);
    });

    searchInput.addEventListener('focus', function () {
        var query = searchInput.value.trim();
        if (query.length >= 2 && suggestionsInner.innerHTML) {
            showSuggestions();
        }
    });

    searchForm.addEventListener('submit', function (event) {
        var query = searchInput.value.trim();
        if (!query) {
            event.preventDefault();
            hideSuggestions();
            return;
        }
        hideSuggestions();
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            toggleClearButton();
            hideSuggestions();
            searchInput.focus();
        });
    }

    document.addEventListener('click', function (event) {
        if (!searchForm.contains(event.target)) {
            hideSuggestions();
        }
    });

    toggleClearButton();
})();
