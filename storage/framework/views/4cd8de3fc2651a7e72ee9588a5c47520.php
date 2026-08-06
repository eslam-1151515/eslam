<?php $__env->startSection('content'); ?>
<h2>مرحباً <?php echo e($merchantName); ?>! 🎉</h2>
<p>يسعدنا انضمامك إلى منصة <strong>فاست أوردر</strong>. متجرك <strong><?php echo e($storeName); ?></strong> جاهز الآن ويمكنك البدء في إضافة منتجاتك.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">اسم المتجر</span>
        <span class="info-value"><?php echo e($storeName); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">رابط المتجر</span>
        <span class="info-value"><?php echo e($storeUrl); ?></span>
    </div>
</div>
<div style="text-align: center;">
    <a href="<?php echo e($storeUrl); ?>" class="btn">زيارة متجري الآن</a>
</div>
<p>إذا احتجت أي مساعدة، فريق الدعم موجود دائماً.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\merchant\welcome.blade.php ENDPATH**/ ?>