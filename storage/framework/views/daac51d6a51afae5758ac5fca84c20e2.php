<?php $__env->startSection('content'); ?>
<h2>تحديث على طلبك #<?php echo e($orderData['id']); ?></h2>
<p>تم تحديث حالة طلبك من متجر <strong><?php echo e($orderData['store_name']); ?></strong>.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#<?php echo e($orderData['id']); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">الحالة الجديدة</span>
        <span class="info-value"><span class="badge badge-success"><?php echo e($statusLabel); ?></span></span>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\orders\status-update.blade.php ENDPATH**/ ?>