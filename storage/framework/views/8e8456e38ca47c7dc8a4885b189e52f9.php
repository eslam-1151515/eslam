<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'position' => 'bottom-right',
    'autoDismiss' => 4000,
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
    'position' => 'bottom-right',
    'autoDismiss' => 4000,
    'customClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div id="foToastContainer" class="fo-toast-container <?php echo e($customClass); ?>" role="region" aria-live="polite" aria-label="<?php echo e(__('Notifications')); ?>">
    
    <?php if(session()->has('success')): ?>
        <div class="fo-toast fo-toast--success is-active" data-auto-dismiss="<?php echo e($autoDismiss); ?>">
            <span class="fo-toast__icon">✓</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title"><?php echo e(__('Success')); ?></h4>
                <p class="fo-toast__message"><?php echo e(session('success')); ?></p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div class="fo-toast fo-toast--error is-active" data-auto-dismiss="<?php echo e($autoDismiss); ?>">
            <span class="fo-toast__icon">✕</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title"><?php echo e(__('Error')); ?></h4>
                <p class="fo-toast__message"><?php echo e(session('error')); ?></p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('warning')): ?>
        <div class="fo-toast fo-toast--warning is-active" data-auto-dismiss="<?php echo e($autoDismiss); ?>">
            <span class="fo-toast__icon">⚠</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title"><?php echo e(__('Warning')); ?></h4>
                <p class="fo-toast__message"><?php echo e(session('warning')); ?></p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('info')): ?>
        <div class="fo-toast fo-toast--info is-active" data-auto-dismiss="<?php echo e($autoDismiss); ?>">
            <span class="fo-toast__icon">ℹ</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title"><?php echo e(__('Information')); ?></h4>
                <p class="fo-toast__message"><?php echo e(session('info')); ?></p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
        </div>
    <?php endif; ?>
</div>

<script>
    if (typeof window.showFoToast !== 'function') {
        window.showFoToast = function(message, type = 'success', title = null, duration = <?php echo e($autoDismiss); ?>) {
            const container = document.getElementById('foToastContainer');
            if (!container) return;

            const iconMap = {
                'success': '✓',
                'error': '✕',
                'warning': '⚠',
                'info': 'ℹ'
            };

            const titleMap = {
                'success': '<?php echo e(__("Success")); ?>',
                'error': '<?php echo e(__("Error")); ?>',
                'warning': '<?php echo e(__("Warning")); ?>',
                'info': '<?php echo e(__("Information")); ?>'
            };

            const displayTitle = title || titleMap[type] || '<?php echo e(__("Notification")); ?>';
            const icon = iconMap[type] || 'ℹ';

            const toast = document.createElement('div');
            toast.className = `fo-toast fo-toast--${type}`;
            toast.innerHTML = `
                <span class="fo-toast__icon">${icon}</span>
                <div class="fo-toast__content">
                    <h4 class="fo-toast__title">${displayTitle}</h4>
                    <p class="fo-toast__message">${message}</p>
                </div>
                <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
            `;

            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('is-active');
            }, 50);

            if (duration > 0) {
                setTimeout(() => {
                    toast.classList.remove('is-active');
                    setTimeout(() => toast.remove(), 350);
                }, duration);
            }
        };

        // Auto dismiss session toasts
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fo-toast[data-auto-dismiss]').forEach(toast => {
                const duration = parseInt(toast.getAttribute('data-auto-dismiss')) || 4000;
                if (duration > 0) {
                    setTimeout(() => {
                        toast.classList.remove('is-active');
                        setTimeout(() => toast.remove(), 350);
                    }, duration);
                }
            });
        });
    }
</script>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\components\shop\themes\components\toast.blade.php ENDPATH**/ ?>