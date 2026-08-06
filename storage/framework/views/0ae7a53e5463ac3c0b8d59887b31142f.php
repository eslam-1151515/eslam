<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
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
    'items' => [],
    'customClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(!empty($items) && count($items) > 0): ?>
<nav aria-label="<?php echo e(__('Breadcrumb')); ?>" <?php echo e($attributes->merge(['class' => 'fo-breadcrumb ' . $customClass])); ?>>
    <ol class="fo-breadcrumb__list">
        <!-- Home item always first if not explicitly provided -->
        <?php if(!isset($items[0]['is_home'])): ?>
            <li class="fo-breadcrumb__item">
                <a href="<?php echo e(route('shop.home')); ?>" class="fo-breadcrumb__link" title="<?php echo e(__('Home')); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146z"/>
                    </svg>
                    <span><?php echo e(__('Home')); ?></span>
                </a>
                <span class="fo-breadcrumb__separator">&rsaquo;</span>
            </li>
        <?php endif; ?>

        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isLast = $index === count($items) - 1 || data_get($item, 'active');
                $title = data_get($item, 'title', data_get($item, 'name', ''));
                $url = data_get($item, 'url', '#');
            ?>

            <li class="fo-breadcrumb__item <?php echo e($isLast ? 'is-active' : ''); ?>" <?php if($isLast): ?> aria-current="page" <?php endif; ?>>
                <?php if(!$isLast && $url !== '#'): ?>
                    <a href="<?php echo e($url); ?>" class="fo-breadcrumb__link"><?php echo e($title); ?></a>
                    <span class="fo-breadcrumb__separator">&rsaquo;</span>
                <?php else: ?>
                    <span><?php echo e($title); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php endif; ?>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\breadcrumb.blade.php ENDPATH**/ ?>