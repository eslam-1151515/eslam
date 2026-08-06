<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'foModalDefault',
    'title' => null,
    'size' => 'md', // sm, md, lg
    'showClose' => true,
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
    'id' => 'foModalDefault',
    'title' => null,
    'size' => 'md', // sm, md, lg
    'showClose' => true,
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
    $sizeClass = match($size) {
        'sm' => 'fo-modal--sm',
        'lg' => 'fo-modal--lg',
        default => '',
    };
?>

<div id="<?php echo e($id); ?>" class="fo-modal-overlay <?php echo e($customClass); ?>" onclick="handleModalOverlayClick(event, '<?php echo e($id); ?>')" role="dialog" aria-modal="true" aria-labelledby="<?php echo e($id); ?>Title" style="display: none;">
    <div class="fo-modal <?php echo e($sizeClass); ?>">
        <?php if($title || $showClose || isset($header)): ?>
            <div class="fo-modal__header">
                <?php if(isset($header)): ?>
                    <?php echo e($header); ?>

                <?php else: ?>
                    <h3 id="<?php echo e($id); ?>Title" class="fo-modal__title"><?php echo e($title); ?></h3>
                <?php endif; ?>

                <?php if($showClose): ?>
                    <button type="button" class="fo-modal__close" onclick="closeFoModal('<?php echo e($id); ?>')" aria-label="<?php echo e(__('Close')); ?>">
                        &times;
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="fo-modal__body">
            <?php echo e($slot); ?>

        </div>

        <?php if(isset($footer)): ?>
            <div class="fo-modal__footer">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    if (typeof window.openFoModal !== 'function') {
        window.openFoModal = function(modalId) {
            const overlay = document.getElementById(modalId);
            if (!overlay) return;
            overlay.style.display = 'flex';
            // Trigger reflow for transition
            void overlay.offsetWidth;
            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        };

        window.closeFoModal = function(modalId) {
            const overlay = document.getElementById(modalId);
            if (!overlay) return;
            overlay.classList.remove('is-active');
            setTimeout(() => {
                if (!overlay.classList.contains('is-active')) {
                    overlay.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }, 300);
        };

        window.handleModalOverlayClick = function(event, modalId) {
            if (event.target.id === modalId) {
                closeFoModal(modalId);
            }
        };

        // Close on Esc key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fo-modal-overlay.is-active').forEach(modal => {
                    closeFoModal(modal.id);
                });
            }
        });
    }
</script>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\modal.blade.php ENDPATH**/ ?>