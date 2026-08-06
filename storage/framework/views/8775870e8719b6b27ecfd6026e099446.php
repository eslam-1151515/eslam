<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator' => null,
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
    'paginator' => null,
    'customClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(isset($paginator) && is_object($paginator) && method_exists($paginator, 'hasPages') && $paginator->hasPages()): ?>
    <nav aria-label="<?php echo e(__('Page Navigation')); ?>" <?php echo e($attributes->merge(['class' => 'fo-pagination ' . $customClass])); ?>>
        <ul class="fo-pagination__list">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="fo-pagination__item">
                    <span class="fo-pagination__link is-disabled" aria-disabled="true" aria-label="<?php echo e(__('Previous')); ?>">
                        &lsaquo;
                    </span>
                </li>
            <?php else: ?>
                <li class="fo-pagination__item">
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="fo-pagination__link" rel="prev" aria-label="<?php echo e(__('Previous')); ?>">
                        &lsaquo;
                    </a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $paginator->currentPage()): ?>
                    <li class="fo-pagination__item">
                        <span class="fo-pagination__link is-active" aria-current="page" aria-label="<?php echo e(__('Page :page', ['page' => $page])); ?>"><?php echo e($page); ?></span>
                    </li>
                <?php else: ?>
                    <li class="fo-pagination__item">
                        <a href="<?php echo e($url); ?>" class="fo-pagination__link" aria-label="<?php echo e(__('Page :page', ['page' => $page])); ?>"><?php echo e($page); ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="fo-pagination__item">
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="fo-pagination__link" rel="next" aria-label="<?php echo e(__('Next Page')); ?>">
                        &rsaquo;
                    </a>
                </li>
            <?php else: ?>
                <li class="fo-pagination__item">
                    <span class="fo-pagination__link is-disabled" aria-disabled="true" aria-label="<?php echo e(__('Next Page')); ?>">
                        &rsaquo;
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php elseif(isset($paginator) && is_array($paginator) && !empty($paginator['pages'])): ?>
    
    <nav aria-label="<?php echo e(__('Page Navigation')); ?>" <?php echo e($attributes->merge(['class' => 'fo-pagination ' . $customClass])); ?>>
        <ul class="fo-pagination__list">
            <?php $__currentLoopData = $paginator['pages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="fo-pagination__item">
                    <?php if(data_get($p, 'active')): ?>
                        <span class="fo-pagination__link is-active" aria-current="page"><?php echo e(data_get($p, 'label')); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e(data_get($p, 'url', '#')); ?>" class="fo-pagination__link <?php echo e(data_get($p, 'disabled') ? 'is-disabled' : ''); ?>" aria-label="<?php echo e(strip_tags(data_get($p, 'label'))); ?>"><?php echo data_get($p, 'label'); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\shop\themes\components\pagination.blade.php ENDPATH**/ ?>