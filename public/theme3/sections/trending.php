<?php
if (!isset($trendingSection) || empty($trendingSection['products'])) {
    return;
}

$sectionIndex = $trendingSectionIndex ?? 0;
$sectionId = 'hp-module-trending-' . $sectionIndex;
$sliderId = 'trending-slider-' . $sectionIndex;
$viewAllId = 'trending-view-all-' . $sectionIndex;
$sectionTitle = $trendingSection['title'] ?? 'Trending now';
$viewAllHref = $trendingSection['view_all'] ?? 'index881d.html?type=0';
$products = $trendingSection['products'];

static $trendingSliderAssetsLoaded = false;
if (!$trendingSliderAssetsLoaded):
    $trendingSliderAssetsLoaded = true;
?>
<style>
.trending-slider {
    position: relative;
    padding: 0 40px;
}
.trending-slider__viewport {
    overflow: hidden;
}
.trending-slider__track {
    display: flex;
    transition: transform 0.35s ease;
    will-change: transform;
}
.trending-slider__slide {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0 8px;
    box-sizing: border-box;
}
.trending-slider__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    border-radius: 50%;
    background: #fff;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.trending-slider__nav:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.trending-slider__nav--prev { left: 0; }
.trending-slider__nav--next { right: 0; }

@media (min-width: 768px) {
    .trending-slider__slide {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (min-width: 992px) {
    .trending-slider__slide {
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }
}
</style>
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
            if (index > 0) {
                index -= 1;
                updateSlider();
            }
        });

        nextBtn.addEventListener('click', function () {
            var maxIndex = Math.max(0, slides.length - getPerView());
            if (index < maxIndex) {
                index += 1;
                updateSlider();
            }
        });

        window.addEventListener('resize', updateSlider);
        updateSlider();
    });
});
</script>
<?php endif; ?>

<div id="<?php echo htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8'); ?>" class="hp-module-trending">
    <div class="hp-module-background">
        <div class="container-fluid">
            <div class="hp-module-title">
                <div class="title-wrapper">
                    <b><?php echo htmlspecialchars($sectionTitle, ENT_QUOTES, 'UTF-8'); ?></b>
                </div>
                <a class="view-all btn"
                   id="<?php echo htmlspecialchars($viewAllId, ENT_QUOTES, 'UTF-8'); ?>"
                   href="<?php echo htmlspecialchars($viewAllHref, ENT_QUOTES, 'UTF-8'); ?>">
                    <span>View all</span>
                </a>
            </div>

            <div id="<?php echo htmlspecialchars($sliderId, ENT_QUOTES, 'UTF-8'); ?>" class="trending-slider">
                <button type="button" class="trending-slider__nav trending-slider__nav--prev" aria-label="Previous products">&#8249;</button>
                <div class="trending-slider__viewport">
                    <div class="trending-slider__track">
                        <?php foreach ($products as $product): ?>
                        <div class="trending-slider__slide">
                            <?php include __DIR__ . '/../product_box.php'; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="button" class="trending-slider__nav trending-slider__nav--next" aria-label="Next products">&#8250;</button>
            </div>
        </div>
    </div>
</div>
