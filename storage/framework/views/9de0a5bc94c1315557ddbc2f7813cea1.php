<?php $__env->startSection('content'); ?>
<h2>⚠️ اشتراكك ينتهي قريباً</h2>
<p>عزيزي <strong><?php echo e($merchantName); ?></strong>،</p>
<p>اشتراكك في منصة فاست أوردر لمتجر <strong><?php echo e($storeName); ?></strong> سينتهي خلال <strong><?php echo e($daysRemaining); ?> أيام</strong> فقط.</p>
<p>لتجنب انقطاع الخدمة، يرجى تجديد اشتراكك الآن.</p>
<div style="text-align: center;">
    <a href="<?php echo e($renewUrl); ?>" class="btn">تجديد الاشتراك الآن</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\subscription\expiry-reminder.blade.php ENDPATH**/ ?>