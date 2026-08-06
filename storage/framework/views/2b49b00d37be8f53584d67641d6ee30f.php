<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'cartItems' => [],
    'total' => '0.00',
    'checkoutUrl' => null,
    'cartUrl' => null,
    'customClass' => '',
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
    'cartItems' => [],
    'total' => '0.00',
    'checkoutUrl' => null,
    'cartUrl' => null,
    'customClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cCheckoutUrl = $checkoutUrl ?? route('shop.checkout');
    $cCartUrl = $cartUrl ?? route('shop.cart');
?>

<div id="foCartDrawerOverlay" class="fo-cart-drawer-overlay <?php echo e($customClass); ?>" onclick="toggleCartDrawer(false)"></div>

<aside id="foCartDrawer" class="fo-cart-drawer <?php echo e($customClass); ?>" aria-labelledby="foCartDrawerTitle" role="dialog" aria-modal="true">
    <div class="fo-cart-drawer__header">
        <h2 id="foCartDrawerTitle" class="fo-cart-drawer__title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <span><?php echo e(__('Shopping Cart')); ?></span>
        </h2>
        <button type="button" class="fo-cart-drawer__close" onclick="toggleCartDrawer(false)" aria-label="<?php echo e(__('Close Cart')); ?>">
            &times;
        </button>
    </div>

    <div class="fo-cart-drawer__body" id="foCartDrawerBody">
        <?php if(empty($cartItems) || (is_countable($cartItems) && count($cartItems) === 0)): ?>
            <div style="text-align: center; padding: 3rem 1rem; color: var(--theme-text-muted);">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="margin: 0 auto 1rem auto; opacity: 0.5;" aria-hidden="true">
                    <path d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 8l-1.147 1.646a.5.5 0 0 0 .708.708l1.5-1.5a.5.5 0 0 0 0-.708l-1.5-1.5z"/>
                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.596l-1.313 7h-8.17z"/>
                </svg>
                <p style="margin: 0; font-weight: 600; font-size: 1rem;"><?php echo e(__('Your cart is empty')); ?></p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;"><?php echo e(__('Start adding some products to checkout.')); ?></p>
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $iName = data_get($item, 'name') ?? data_get($item, 'product.name', __('Product'));
                    $iPrice = data_get($item, 'price', '0.00');
                    $iQty = data_get($item, 'quantity', 1);
                    $iImage = data_get($item, 'image_url') ?? data_get($item, 'product.image_url', asset('images/default-product.png'));
                    $iId = data_get($item, 'id', uniqid());
                ?>
                <div class="fo-cart-item" data-cart-item-id="<?php echo e($iId); ?>">
                    <img src="<?php echo e($iImage); ?>" alt="<?php echo e($iName); ?>" class="fo-cart-item__image" onerror="this.src='https://via.placeholder.com/100x100?text=No+Image'">
                    <div class="fo-cart-item__details">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                            <h4 class="fo-cart-item__name"><?php echo e($iName); ?></h4>
                            <button type="button" class="fo-cart-drawer__close btn-remove-item" data-item-id="<?php echo e($iId); ?>" style="font-size: 1.125rem; padding: 0;" title="<?php echo e(__('Remove')); ?>" aria-label="<?php echo e(__('Remove item')); ?>">&times;</button>
                        </div>
                        <span class="fo-cart-item__price"><?php echo e(is_numeric($iPrice) ? number_format($iPrice, 0) . ' ' . __('SAR') : $iPrice); ?></span>
                        
                        <div class="fo-cart-item__actions">
                            <div class="fo-quantity-ctrl">
                                <button type="button" class="fo-quantity-ctrl__btn btn-qty-dec" data-item-id="<?php echo e($iId); ?>" aria-label="<?php echo e(__('Decrease quantity')); ?>">-</button>
                                <input type="number" class="fo-quantity-ctrl__input" value="<?php echo e($iQty); ?>" min="1" readonly aria-label="<?php echo e(__('Quantity')); ?>">
                                <button type="button" class="fo-quantity-ctrl__btn btn-qty-inc" data-item-id="<?php echo e($iId); ?>" aria-label="<?php echo e(__('Increase quantity')); ?>">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

    <div class="fo-cart-drawer__footer">
        <div class="fo-cart-drawer__summary-row">
            <span><?php echo e(__('Subtotal')); ?></span>
            <span id="foCartSubtotal"><?php echo e(is_numeric($total) ? number_format($total, 0) . ' ' . __('SAR') : $total); ?></span>
        </div>
        <div class="fo-cart-drawer__summary-row">
            <span><?php echo e(__('Shipping')); ?></span>
            <span><?php echo e(__('Calculated at checkout')); ?></span>
        </div>
        <div class="fo-cart-drawer__summary-row fo-cart-drawer__summary-row--total">
            <span><?php echo e(__('Total')); ?></span>
            <span id="foCartTotal"><?php echo e(is_numeric($total) ? number_format($total, 0) . ' ' . __('SAR') : $total); ?></span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="<?php echo e($cCheckoutUrl); ?>" class="fo-btn fo-btn--primary" style="text-align: center;">
                <?php echo e(__('Proceed to Checkout')); ?>

            </a>
            <a href="<?php echo e($cCartUrl); ?>" class="fo-btn fo-btn--outline" style="text-align: center;">
                <?php echo e(__('View Cart')); ?>

            </a>
        </div>
    </div>
</aside>

<script>
    function toggleCartDrawer(show) {
        const drawer = document.getElementById('foCartDrawer');
        const overlay = document.getElementById('foCartDrawerOverlay');
        if (!drawer || !overlay) return;
        
        if (show === undefined) {
            show = !drawer.classList.contains('is-active');
        }
        
        if (show) {
            drawer.classList.add('is-active');
            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        } else {
            drawer.classList.remove('is-active');
            overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }
    }
</script>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\cart-drawer.blade.php ENDPATH**/ ?>