<?php
if (empty($product) || !is_array($product)) {
    return;
}

$pid = $product['id'] ?? '';
$partNumber = $product['part_number'] ?? '';
$title = $product['title'] ?? '';
$href = $product['href'] ?? '#';
$image = $product['image'] ?? '';
$brand = $product['brand'] ?? '';
$discountPrice = $product['discount_price'] ?? '';
$listPrice = $product['list_price'] ?? $discountPrice;
$ratingTitle = $product['rating_title'] ?? '';
$ratingCount = $product['rating_count'] ?? '';
$reviewHref = $product['review_href'] ?? $href;
$boxClass = $productBoxClass ?? 'product-box';
$showAddToCart = $productBoxShowCart ?? true;

$hasDiscount = $listPrice !== '' && $discountPrice !== '' && (float) $listPrice > (float) $discountPrice;
$discountDisplay = $discountPrice !== '' ? '₨' . number_format((float) $discountPrice, 2) : '';
$listDisplay = $listPrice !== '' ? '₨' . number_format((float) $listPrice, 2) : '';

static $productBoxAssetsLoaded = false;
if (!$productBoxAssetsLoaded):
    $productBoxAssetsLoaded = true;
?>
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
.product-box__image-link {
    display: block;
    text-align: center;
}
.product-box__image-link img {
    width: 120px;
    height: 120px;
    object-fit: contain;
    margin: 0 auto;
}
.product-box__title {
    font-size: 14px;
    line-height: 1.35;
    color: #333;
    min-height: 38px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-decoration: none;
}
.product-box__title:hover {
    color: #458500;
}
.product-box__rating {
    font-size: 12px;
    color: #666;
}
.product-box__price {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: baseline;
    font-size: 14px;
}
.product-box__price .price {
    font-weight: 700;
    color: #333;
}
.product-box__price .price.discount-red {
    color: #c8232c;
}
.product-box__price .price-olp {
    color: #888;
    text-decoration: line-through;
    font-size: 12px;
}
.product-box__btn {
    margin-top: auto;
}
</style>
<?php endif; ?>

<div class="<?php echo htmlspecialchars($boxClass, ENT_QUOTES, 'UTF-8'); ?> product"
     data-pid="<?php echo htmlspecialchars($pid, ENT_QUOTES, 'UTF-8'); ?>"
     <?php if ($partNumber !== ''): ?>data-part-number="<?php echo htmlspecialchars($partNumber, ENT_QUOTES, 'UTF-8'); ?>"<?php endif; ?>>
    <a class="product-box__image-link" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>"
             alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>"
             width="120"
             height="120" />
    </a>
    <a class="product-box__title product-title" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </a>
    <?php if ($ratingCount !== '' || $ratingTitle !== ''): ?>
    <div class="product-box__rating">
        <a href="<?php echo htmlspecialchars($reviewHref, ENT_QUOTES, 'UTF-8'); ?>"
           title="<?php echo htmlspecialchars($ratingTitle, ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($ratingCount !== ''): ?>
                <?php echo htmlspecialchars($ratingCount, ENT_QUOTES, 'UTF-8'); ?> reviews
            <?php else: ?>
                <?php echo htmlspecialchars($ratingTitle, ENT_QUOTES, 'UTF-8'); ?>
            <?php endif; ?>
        </a>
    </div>
    <?php endif; ?>
    <div class="product-box__price product-price">
        <?php if ($hasDiscount): ?>
        <span class="price discount-red"><?php echo htmlspecialchars($discountDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="price-olp"><?php echo htmlspecialchars($listDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php else: ?>
        <span class="price"><?php echo htmlspecialchars($discountDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
    </div>
    <?php if ($showAddToCart): ?>
    <button type="button"
            class="btn btn-primary btn-add-to-cart product-box__btn gtm-add-to-cart"
            data-product-id="<?php echo htmlspecialchars($pid, ENT_QUOTES, 'UTF-8'); ?>"
            <?php if ($brand !== ''): ?>data-ga-brand-code="<?php echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>"<?php endif; ?>>
        Add to Cart
    </button>
    <?php endif; ?>
</div>
