<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'storeName' => null,
    'description' => null,
    'links' => [],
    'socialLinks' => [],
    'copyright' => null,
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
    'storeName' => null,
    'description' => null,
    'links' => [],
    'socialLinks' => [],
    'copyright' => null,
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
    $fStoreName = $storeName ?? (isset($store) ? data_get($store, 'name') : config('app.name', 'Fast Order'));
    $fDescription = $description ?? __('Your premium destination for seamless online shopping with fast and reliable delivery.');
    $fCopyright = $copyright ?? __('All Rights Reserved.');
    
    // Default Footer Columns if empty
    $fColumns = !empty($links) ? $links : [
        [
            'title' => __('Quick Links'),
            'items' => [
                ['title' => __('Home'), 'url' => route('shop.home')],
                ['title' => __('All Products'), 'url' => route('shop.products')],
                ['title' => __('Categories'), 'url' => route('shop.categories')],
                ['title' => __('Special Offers'), 'url' => route('shop.products', ['discount' => 1])],
            ]
        ],
        [
            'title' => __('Customer Service'),
            'items' => [
                ['title' => __('My Account'), 'url' => route('shop.account')],
                ['title' => __('Order History'), 'url' => route('shop.orders')],
                ['title' => __('Wishlist'), 'url' => route('shop.wishlist')],
                ['title' => __('Contact Us'), 'url' => route('shop.contact')],
            ]
        ],
        [
            'title' => __('Information'),
            'items' => [
                ['title' => __('About Us'), 'url' => '#'],
                ['title' => __('Privacy Policy'), 'url' => '#'],
                ['title' => __('Terms & Conditions'), 'url' => '#'],
                ['title' => __('Shipping & Returns'), 'url' => '#'],
            ]
        ],
    ];
?>

<footer <?php echo e($attributes->merge(['class' => 'fo-footer ' . $customClass])); ?>>
    <div class="fo-footer__container">
        <div class="fo-footer__grid">
            <!-- Store Info Column -->
            <div>
                <h3 class="fo-footer__col-title"><?php echo e($fStoreName); ?></h3>
                <p style="line-height: 1.6; margin-bottom: 1.25rem;">
                    <?php echo e($fDescription); ?>

                </p>
                <?php if(!empty($socialLinks)): ?>
                    <div style="display: flex; gap: 0.75rem;">
                        <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; font-size: 1.25rem; transition: var(--theme-transition);" title="<?php echo e(ucfirst($platform)); ?>">
                                <span>🌐</span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dynamic Link Columns -->
            <?php $__currentLoopData = $fColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <h4 class="fo-footer__col-title"><?php echo e(data_get($col, 'title')); ?></h4>
                    <ul class="fo-footer__links">
                        <?php $__currentLoopData = data_get($col, 'items', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(data_get($item, 'url', '#')); ?>" class="fo-footer__link">
                                    <?php echo e(data_get($item, 'title')); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Footer Bottom -->
        <div class="fo-footer__bottom">
            <div>
                &copy; <?php echo e(date('Y')); ?> <?php echo e($fStoreName); ?>. <?php echo e($fCopyright); ?>

            </div>
            <div style="display: flex; gap: 1rem; align-items: center; color: var(--theme-text-muted);">
                <span><?php echo e(__('Powered by Fast Order')); ?></span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\shop\themes\components\footer.blade.php ENDPATH**/ ?>