(function () {
    function initThumbnails() {
        var mainImg = document.getElementById('iherb-product-image');
        if (!mainImg) {
            return;
        }

        document.querySelectorAll('.t3-pdp-page .thumbnail-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var thumb = btn.querySelector('img');
                if (!thumb) {
                    return;
                }

                var large = thumb.getAttribute('data-large-img') || thumb.src;
                mainImg.src = large;

                document.querySelectorAll('.t3-pdp-page .thumbnail-item').forEach(function (item) {
                    item.classList.remove('selected');
                    item.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('selected');
                btn.setAttribute('aria-pressed', 'true');
            });
        });
    }

    function initQty() {
        var qtyInput = document.getElementById('t3-pdp-qty');
        var addBtn = document.querySelector('.t3-pdp-page .btn-add-to-cart');
        if (!qtyInput) {
            return;
        }

        var min = parseInt(qtyInput.getAttribute('min') || '1', 10);
        var max = parseInt(qtyInput.getAttribute('max') || '10', 10);

        function syncQty() {
            var val = parseInt(qtyInput.value, 10);
            if (isNaN(val) || val < min) {
                val = min;
            }
            if (val > max) {
                val = max;
            }
            qtyInput.value = val;
            if (addBtn) {
                addBtn.setAttribute('data-quantity', String(val));
            }
        }

        document.querySelectorAll('[data-qty-minus]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                qtyInput.value = Math.max(min, parseInt(qtyInput.value, 10) - 1 || min);
                syncQty();
            });
        });

        document.querySelectorAll('[data-qty-plus]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                qtyInput.value = Math.min(max, parseInt(qtyInput.value, 10) + 1 || min);
                syncQty();
            });
        });

        qtyInput.addEventListener('change', syncQty);
        syncQty();
    }

    function initTabs() {
        var root = document.querySelector('.t3-pdp-tabs');
        if (!root) {
            return;
        }

        var buttons = root.querySelectorAll('.t3-pdp-tabs__btn');
        var panels = root.querySelectorAll('.t3-pdp-tabs__panel');

        function activate(tabId) {
            buttons.forEach(function (btn) {
                var active = btn.getAttribute('data-tab') === tabId;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                var active = panel.getAttribute('data-panel') === tabId;
                panel.classList.toggle('is-active', active);
                panel.hidden = !active;
            });
        }

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activate(btn.getAttribute('data-tab'));
            });
        });

        if (window.location.hash === '#product-reviews' || window.location.hash === '#tab-reviews') {
            activate('reviews');
        }

        window.addEventListener('hashchange', function () {
            if (window.location.hash === '#product-reviews' || window.location.hash === '#tab-reviews') {
                activate('reviews');
                var tabs = document.querySelector('.t3-pdp-tabs');
                if (tabs) {
                    tabs.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    }

    function init() {
        initThumbnails();
        initQty();
        initTabs();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
