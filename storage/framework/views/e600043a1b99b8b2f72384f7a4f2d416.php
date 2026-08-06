<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'category' => null,
    'title' => null,
    'url' => '#',
    'icon' => null,
    'image' => null,
    'count' => null,
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
    'category' => null,
    'title' => null,
    'url' => '#',
    'icon' => null,
    'image' => null,
    'count' => null,
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
    $cTitle = $title ?? data_get($category, 'name') ?? data_get($category, 'title', __('Category Name'));
    $cUrl = $url !== '#' ? $url : (data_get($category, 'url') ?? (data_get($category, 'slug') ? route('shop.category', data_get($category, 'slug')) : '#'));
    $cIcon = $icon ?? data_get($category, 'icon');
    $cImage = $image ?? data_get($category, 'image_url') ?? data_get($category, 'image');
    $cCount = $count ?? data_get($category, 'products_count') ?? data_get($category, 'count');
?>

<a href="<?php echo e($cUrl); ?>" <?php echo e($attributes->merge(['class' => 'fo-category-card ' . $customClass])); ?>>
    <div class="fo-category-card__icon">
        <?php if($cImage): ?>
            <img src="<?php echo e($cImage); ?>" alt="<?php echo e($cTitle); ?>" class="fo-category-card__image" loading="lazy" onerror="this.style.display='none'">
        <?php elseif($cIcon): ?>
            <?php echo $cIcon; ?>

        <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
            </svg>
        <?php endif; ?>
    </div>

    <h3 class="fo-category-card__title"><?php echo e($cTitle); ?></h3>

    <?php if(!is_null($cCount)): ?>
        <span class="fo-category-card__count"><?php echo e($cCount); ?> <?php echo e(__('Products')); ?></span>
    <?php endif; ?>
</a>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\category-card.blade.php ENDPATH**/ ?>