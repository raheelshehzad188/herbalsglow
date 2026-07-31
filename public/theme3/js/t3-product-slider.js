(function () {
    function getPerView() {
        if (window.matchMedia('(min-width: 992px)').matches) {
            return 6;
        }
        if (window.matchMedia('(min-width: 768px)').matches) {
            return 2;
        }
        return 1;
    }

    function getProducts(inner) {
        return Array.prototype.filter.call(inner.children, function (node) {
            return node.classList && node.classList.contains('product');
        });
    }

    function initCarousel(carousel) {
        if (carousel.dataset.t3Initialized === '1') {
            return;
        }

        var inner = carousel.querySelector('.carousel-inner.product-carousels');
        var products = inner ? getProducts(inner) : [];
        var prevBtn = carousel.querySelector('[data-slider-prev]');
        var nextBtn = carousel.querySelector('[data-slider-next]');

        if (!inner || products.length <= getPerView()) {
            if (prevBtn) {
                prevBtn.classList.add('disabled');
            }
            if (nextBtn) {
                nextBtn.classList.add('disabled');
            }
            return;
        }

        carousel.dataset.t3Initialized = '1';

        var index = 0;

        function stepWidth() {
            return carousel.clientWidth / getPerView();
        }

        function maxIndex() {
            return Math.max(0, products.length - getPerView());
        }

        function update() {
            var offset = index * stepWidth();
            inner.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';

            if (prevBtn) {
                prevBtn.classList.toggle('disabled', index <= 0);
            }
            if (nextBtn) {
                nextBtn.classList.toggle('disabled', index >= maxIndex());
            }
        }

        function goPrev(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            if (index > 0) {
                index -= 1;
                update();
            }
        }

        function goNext(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            if (index < maxIndex()) {
                index += 1;
                update();
            }
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', goPrev, true);
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', goNext, true);
        }

        window.addEventListener('resize', function () {
            if (index > maxIndex()) {
                index = maxIndex();
            }
            update();
        });

        update();
    }

    function initAll() {
        document.querySelectorAll('.t3-product-carousel').forEach(initCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    window.addEventListener('load', initAll);
})();
