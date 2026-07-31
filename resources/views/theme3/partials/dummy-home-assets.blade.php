@once
@push('styles')
<style>
.product-box {
    height: 100%;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 12px;
    background: #fff;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.product-box__image-link { display: block; text-align: center; }
.product-box__image-link img { width: 120px; height: 120px; object-fit: contain; margin: 0 auto; }
.product-box__title {
    font-size: 14px; line-height: 1.35; color: #333; min-height: 38px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; text-decoration: none;
}
.product-box__title:hover { color: #458500; }
.product-box__rating { font-size: 12px; color: #666; }
.product-box__price { display: flex; flex-wrap: wrap; gap: 6px; align-items: baseline; font-size: 14px; }
.product-box__price .price { font-weight: 700; color: #333; }
.product-box__price .price.discount-red { color: #c8232c; }
.product-box__price .price-olp { color: #888; text-decoration: line-through; font-size: 12px; }

.hp-quality-promise { display: block !important; margin: 8px 0 24px; }
.hp-module-trending { margin-bottom: 32px; }
.hp-module-trending .hp-module-title {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0 0 16px;
}
.hp-module-trending .title-wrapper b { font-size: 22px; color: #181b1f; }
.hp-module-trending .view-all {
    color: var(--primary-color, #458500); font-weight: 600; text-decoration: none; font-size: 14px;
    background: none; border: none; padding: 0;
}

.trending-slider { position: relative; padding: 0 40px; }
.trending-slider__viewport { overflow: hidden; }
.trending-slider__track { display: flex; transition: transform 0.35s ease; will-change: transform; }
.trending-slider__slide { flex: 0 0 100%; max-width: 100%; padding: 0 8px; box-sizing: border-box; }
.trending-slider__nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 50%;
    background: #fff; color: #333; cursor: pointer;
    display: flex; align-items: center; justify-content: center; z-index: 2;
}
.trending-slider__nav:disabled { opacity: 0.35; cursor: not-allowed; }
.trending-slider__nav--prev { left: 0; }
.trending-slider__nav--next { right: 0; }
@media (min-width: 768px) {
    .trending-slider__slide { flex: 0 0 50%; max-width: 50%; }
}
@media (min-width: 992px) {
    .trending-slider__slide { flex: 0 0 16.666667%; max-width: 16.666667%; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.trending-slider').forEach(function (slider) {
        var track = slider.querySelector('.trending-slider__track');
        var slides = slider.querySelectorAll('.trending-slider__slide');
        var prevBtn = slider.querySelector('.trending-slider__nav--prev');
        var nextBtn = slider.querySelector('.trending-slider__nav--next');
        var viewport = slider.querySelector('.trending-slider__viewport');
        var index = 0;

        function getPerView() {
            if (window.innerWidth >= 992) return 6;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }

        function updateSlider() {
            var perView = getPerView();
            var maxIndex = Math.max(0, slides.length - perView);
            if (index > maxIndex) index = maxIndex;
            var slideWidth = viewport.offsetWidth / perView;
            track.style.transform = 'translateX(-' + (index * slideWidth) + 'px)';
            prevBtn.disabled = index <= 0;
            nextBtn.disabled = index >= maxIndex;
        }

        prevBtn.addEventListener('click', function () {
            if (index > 0) { index -= 1; updateSlider(); }
        });
        nextBtn.addEventListener('click', function () {
            var maxIndex = Math.max(0, slides.length - getPerView());
            if (index < maxIndex) { index += 1; updateSlider(); }
        });
        window.addEventListener('resize', updateSlider);
        updateSlider();
    });
});
</script>
@endpush
@endonce
