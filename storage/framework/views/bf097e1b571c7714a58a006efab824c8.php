<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'storeName' => null,
    'logoUrl' => null,
    'navLinks' => [],
    'cartCount' => 0,
    'wishlistCount' => 0,
    'showSearch' => true,
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
    'logoUrl' => null,
    'navLinks' => [],
    'cartCount' => 0,
    'wishlistCount' => 0,
    'showSearch' => true,
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
    $nStoreName = $storeName ?? (isset($store) ? data_get($store, 'name') : config('app.name', 'Fast Order'));
    $nLogoUrl = $logoUrl ?? (isset($store) ? data_get($store, 'logo_url') : null);
    
    // Default navigation links if empty
    $nLinks = !empty($navLinks) ? $navLinks : [
        ['title' => __('Home'), 'url' => route('shop.home'), 'active' => request()->routeIs('shop.home')],
        ['title' => __('Products'), 'url' => route('shop.products'), 'active' => request()->routeIs('shop.products*')],
        ['title' => __('Categories'), 'url' => route('shop.categories'), 'active' => request()->routeIs('shop.categories*')],
        ['title' => __('Contact Us'), 'url' => route('shop.contact'), 'active' => request()->routeIs('shop.contact')],
    ];
?>

<header <?php echo e($attributes->merge(['class' => 'fo-navbar ' . $customClass])); ?>>
    <div class="fo-navbar__container">
        <!-- Brand Logo / Name -->
        <a href="<?php echo e(route('shop.home')); ?>" class="fo-navbar__brand">
            <?php if($nLogoUrl): ?>
                <img src="<?php echo e($nLogoUrl); ?>" alt="<?php echo e($nStoreName); ?>" class="fo-navbar__brand-logo">
            <?php else: ?>
                <span><?php echo e($nStoreName); ?></span>
            <?php endif; ?>
        </a>

        <!-- Main Navigation -->
        <nav aria-label="<?php echo e(__('Main Navigation')); ?>">
            <ul class="fo-navbar__nav">
                <?php $__currentLoopData = $nLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(data_get($link, 'url', '#')); ?>" class="fo-navbar__link <?php echo e(data_get($link, 'active') ? 'is-active' : ''); ?>">
                            <?php echo e(data_get($link, 'title')); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </nav>

        <!-- Search Bar (Optional inline or mobile trigger) -->
        <?php if($showSearch): ?>
            <div style="flex-grow: 1; max-width: 400px; display: none; @media(min-width: 992px) { display: block; }">
                <?php if (isset($component)) { $__componentOriginalb1ec2a0c9753a45d389c492c2fc64dd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1ec2a0c9753a45d389c492c2fc64dd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.shop.themes.components.search-bar','data' => ['placeholder' => __('Search products...')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('shop.themes.components.search-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Search products...'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1ec2a0c9753a45d389c492c2fc64dd1)): ?>
<?php $attributes = $__attributesOriginalb1ec2a0c9753a45d389c492c2fc64dd1; ?>
<?php unset($__attributesOriginalb1ec2a0c9753a45d389c492c2fc64dd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1ec2a0c9753a45d389c492c2fc64dd1)): ?>
<?php $component = $__componentOriginalb1ec2a0c9753a45d389c492c2fc64dd1; ?>
<?php unset($__componentOriginalb1ec2a0c9753a45d389c492c2fc64dd1); ?>
<?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Navbar Actions (Cart, Wishlist, Account, Language Switcher) -->
        <div class="fo-navbar__actions">
            <!-- Language Switcher (AR / EN) -->
            <?php
                $currentLang = app()->getLocale();
                $switchLang = $currentLang === 'ar' ? 'en' : 'ar';
            ?>
            <a href="<?php echo e(route('locale.switch', $switchLang)); ?>" class="fo-navbar__icon-btn" title="<?php echo e($switchLang === 'ar' ? 'العربية' : 'English'); ?>" aria-label="<?php echo e($switchLang === 'ar' ? 'تغيير اللغة إلى العربية' : 'Switch language to English'); ?>" style="font-weight: 700; font-size: 0.75rem;">
                <?php echo e(strtoupper($switchLang)); ?>

            </a>

            <!-- Wishlist Button -->
            <a href="<?php echo e(route('shop.wishlist')); ?>" class="fo-navbar__icon-btn" title="<?php echo e(__('Wishlist')); ?>" aria-label="<?php echo e(__('Wishlist')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                </svg>
                <?php if($wishlistCount > 0): ?>
                    <span class="fo-navbar__badge id-wishlist-badge"><?php echo e($wishlistCount); ?></span>
                <?php endif; ?>
            </a>

            <!-- Cart Trigger Button -->
            <button type="button" class="fo-navbar__icon-btn" onclick="toggleCartDrawer(true)" title="<?php echo e(__('Cart')); ?>" aria-label="<?php echo e(__('Cart')); ?>" aria-expanded="false" aria-controls="foCartDrawer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <span class="fo-navbar__badge id-cart-badge" id="foCartCountBadge" <?php if($cartCount === 0): ?> style="display: none;" <?php endif; ?>><?php echo e($cartCount); ?></span>
            </button>

            <!-- Account / Profile Button -->
            <a href="<?php echo e(auth()->guard('customer')->check() ? route('shop.account') : route('shop.login')); ?>" class="fo-navbar__icon-btn" title="<?php echo e(__('My Account')); ?>" aria-label="<?php echo e(__('My Account')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
            </a>
        </div>
    </div>
</header>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\navbar.blade.php ENDPATH**/ ?>