(function () {
    var config = window.t3CartConfig || {};
    var cartAddUrl = config.addUrl || '/cart/add';
    var cartPageUrl = config.cartUrl || '/cart';

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function updateCartCount(qty) {
        var count = parseInt(qty, 10) || 0;
        document.querySelectorAll('#cart-qty, .cart-qty, .cart-count, .toolbar-count').forEach(function (el) {
            el.textContent = count > 0 ? String(count) : '';
            if (count > 0) {
                el.classList.remove('cart-hide');
            } else {
                el.classList.add('cart-hide');
            }
        });
    }

    function showToast(message, type) {
        var toast = document.getElementById('t3-cart-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 't3-cart-toast';
            toast.className = 't3-cart-toast';
            document.body.appendChild(toast);
        }

        toast.className = 't3-cart-toast t3-cart-toast--' + (type || 'success');
        toast.innerHTML = '<span>' + message + '</span><a href="' + cartPageUrl + '">View cart</a>';
        toast.classList.add('is-visible');

        clearTimeout(showToast._timer);
        showToast._timer = setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 3500);
    }

    function storeButtonLabel(btn) {
        if (!btn.getAttribute('data-original-label')) {
            btn.setAttribute('data-original-label', btn.textContent.trim());
        }
    }

    function setButtonLoading(btn, loading) {
        if (!btn) {
            return;
        }

        storeButtonLabel(btn);

        if (loading) {
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.textContent = 'Adding...';
            return;
        }

        btn.disabled = false;
        btn.classList.remove('is-loading');
        btn.textContent = btn.getAttribute('data-original-label') || 'Add to Cart';
    }

    function resolveQuantity(btn) {
        var qty = parseInt(btn.getAttribute('data-quantity') || '1', 10);
        if (isNaN(qty) || qty < 1) {
            qty = 1;
        }

        var qtyInput = document.getElementById('t3-pdp-qty');
        if (qtyInput && btn.closest('.t3-pdp-page')) {
            qty = parseInt(qtyInput.value, 10) || qty;
        }

        return qty;
    }

    function addToCart(productId, quantity, buyNow, buttonElement) {
        if (!productId) {
            return Promise.resolve();
        }

        var btn = buttonElement || null;
        setButtonLoading(btn, true);

        return fetch(cartAddUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                id: productId,
                qty: quantity || 1
            })
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.msg_type === 'success' || data.success) {
                    updateCartCount(data.qty || 0);
                    showToast(data.msg || 'Product added to cart', 'success');

                    if (parseInt(buyNow, 10) === 1) {
                        window.location.href = config.checkoutUrl || '/checkout';
                        return;
                    }
                    return;
                }

                showToast(data.msg || 'Could not add product to cart', 'error');
            })
            .catch(function () {
                showToast('Could not add product to cart', 'error');
            })
            .finally(function () {
                setButtonLoading(btn, false);
            });
    }

    function bindAddToCartButtons() {
        document.addEventListener('click', function (event) {
            var btn = event.target.closest('.btn-add-to-cart, .gtm-add-to-cart, .t3-add-to-cart');
            if (!btn || btn.disabled) {
                return;
            }

            var productId = btn.getAttribute('data-product-id');
            if (!productId) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            var qty = resolveQuantity(btn);
            var buyNow = btn.getAttribute('data-buy-now') || 0;
            addToCart(productId, qty, buyNow, btn);
        });
    }

    function refreshCartCount() {
        fetch('/cart/data', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.cart && typeof data.cart.qty !== 'undefined') {
                    updateCartCount(data.cart.qty);
                }
            })
            .catch(function () {});
    }

    window.addToCart = addToCart;
    window.updateCartCount = updateCartCount;
    window.showT3CartToast = showToast;

    function init() {
        bindAddToCartButtons();
        if (typeof config.initialQty !== 'undefined') {
            updateCartCount(config.initialQty);
        } else {
            refreshCartCount();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
