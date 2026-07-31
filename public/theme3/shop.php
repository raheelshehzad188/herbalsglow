<?php include 'header.php'; ?>
<?php include 'shop-data.php'; ?>

<style>
.shop-page {
    padding: 24px 0 48px;
}
.shop-page__breadcrumbs {
    font-size: 13px;
    margin-bottom: 12px;
    color: #666;
}
.shop-page__breadcrumbs a {
    color: #458500;
    text-decoration: none;
}
.shop-page__breadcrumbs a:hover {
    text-decoration: underline;
}
.shop-page__title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 16px;
    color: #181b1f;
}
.shop-page__subnav {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}
.shop-page__subnav a {
    display: inline-block;
    padding: 8px 14px;
    border: 1px solid #ddd;
    border-radius: 999px;
    font-size: 13px;
    color: #333;
    text-decoration: none;
    background: #fff;
}
.shop-page__subnav a:hover {
    border-color: #458500;
    color: #458500;
}
.shop-page__count {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}
.shop-page__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}
.shop-page__grid-item {
    min-width: 0;
}

@media (min-width: 768px) {
    .shop-page__grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .shop-page__grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1200px) {
    .shop-page__grid {
        grid-template-columns: repeat(6, 1fr);
    }
}
</style>

<div class="shop-page">
    <div class="container-fluid">
        <div class="shop-page__breadcrumbs">
            <a href="c/categories.html">Categories</a> /
            <a href="c/supplements.html">Supplements</a> /
            <a href="c/herbs.html">Herbs</a> /
            <span><?php echo htmlspecialchars($shopTitle, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <h1 class="shop-page__title"><?php echo htmlspecialchars($shopTitle, ENT_QUOTES, 'UTF-8'); ?></h1>

        <div class="shop-page__subnav">
            <a href="c/ashwagandha.html">Ashwagandha</a>
            <a href="c/maca.html">Maca</a>
            <a href="c/elderberry-sambucus.html">Elderberry Sambucus</a>
            <a href="c/ginseng.html">Ginseng</a>
            <a href="c/rhodiola.html">Rhodiola</a>
            <a href="c/cordyceps.html">Cordyceps</a>
        </div>

        <div class="shop-page__count">
            <?php echo count($shopProducts); ?> products
        </div>

        <div class="shop-page__grid">
            <?php foreach ($shopProducts as $product): ?>
            <div class="shop-page__grid-item">
                <?php include __DIR__ . '/product_box.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
