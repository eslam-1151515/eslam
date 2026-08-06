<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'product' => null,
    'title' => null,
    'price' => null,
    'oldPrice' => null,
    'image' => null,
    'url' => '#',
    'category' => null,
    'badge' => null,
    'isDiscount' => false,
    'showActions' => true,
    'showCategory' => true,
    'customClass' => '',
    'addToCartUrl' => null,
    'productId' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'product' => null,
    'title' => null,
    'price' => null,
    'oldPrice' => null,
    'image' => null,
    'url' => '#',
    'category' => null,
    'badge' => null,
    'isDiscount' => false,
    'showActions' => true,
    'showCategory' => true,
    'customClass' => '',
    'addToCartUrl' => null,
    'productId' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Fallbacks if a product model or array is passed directly
    $pTitle = $title ?? data_get($product, 'name') ?? data_get($product, 'title', __('Product Name'));
    $pPrice = $price ?? data_get($product, 'price', '0.00');
    $pOldPrice = $oldPrice ?? data_get($product, 'old_price') ?? data_get($product, 'original_price');
    $pImage = $image ?? data_get($product, 'image_url') ?? data_get($product, 'image', asset('images/default-product.png'));
    $pUrl = $url !== '#' ? $url : (data_get($product, 'url') ?? (data_get($product, 'slug') ? route('shop.product', data_get($product, 'slug')) : '#'));
    $pCategory = $category ?? data_get($product, 'category.name') ?? data_get($product, 'category_name');
    $pBadge = $badge ?? data_get($product, 'badge');
    $pId = $productId ?? data_get($product, 'id', uniqid());
?>

<div <?php echo e($attributes->merge(['class' => 'fo-product-card ' . $customClass])); ?> data-product-id="<?php echo e($pId); ?>">
    <div class="fo-product-card__media">
        <a href="<?php echo e($pUrl); ?>">
            <img src="<?php echo e($pImage); ?>" alt="<?php echo e($pTitle); ?>" class="fo-product-card__image" loading="lazy" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
        </a>

        <?php if($pBadge || $pOldPrice): ?>
            <span class="fo-product-card__badge <?php echo e(($isDiscount || $pOldPrice) ? 'fo-product-card__badge--discount' : ''); ?>">
                <?php echo e($pBadge ?? __('Sale')); ?>

            </span>
        <?php endif; ?>

        <?php if($showActions): ?>
            <div class="fo-product-card__actions">
                <button type="button" class="fo-product-card__action-btn btn-quick-view" data-product-id="<?php echo e($pId); ?>" title="<?php echo e(__('Quick View')); ?>" aria-label="<?php echo e(__('Quick View')); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </button>
                <button type="button" class="fo-product-card__action-btn btn-add-wishlist" data-product-id="<?php echo e($pId); ?>" title="<?php echo e(__('Add to Wishlist')); ?>" aria-label="<?php echo e(__('Add to Wishlist')); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <div class="fo-product-card__body">
        <div>
            <?php if($showCategory && $pCategory): ?>
                <div class="fo-product-card__category"><?php echo e($pCategory); ?></div>
            <?php endif; ?>
            <h3 class="fo-product-card__title">
                <a href="<?php echo e($pUrl); ?>"><?php echo e($pTitle); ?></a>
            </h3>
        </div>

        <div class="fo-product-card__price-wrapper">
            <span class="fo-product-card__price"><?php echo e(is_numeric($pPrice) ? number_format($pPrice, 0) . ' ' . __('SAR') : $pPrice); ?></span>
            <?php if($pOldPrice): ?>
                <span class="fo-product-card__price--old"><?php echo e(is_numeric($pOldPrice) ? number_format($pOldPrice, 0) . ' ' . __('SAR') : $pOldPrice); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="fo-product-card__footer">
        <button type="button" class="fo-btn fo-btn--primary btn-add-to-cart" aria-label="<?php echo e(__('Add :product to Cart', ['product' => $pTitle])); ?>" data-product-id="<?php echo e($pId); ?>" data-url="<?php echo e($addToCartUrl ?? route('shop.cart.add', ['id' => $pId])); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <span><?php echo e(__('Add to Cart')); ?></span>
        </button>
    </div>
</div>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\shop\themes\components\product-card.blade.php ENDPATH**/ ?>