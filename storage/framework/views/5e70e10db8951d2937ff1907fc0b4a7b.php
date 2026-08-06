<?php $__env->startSection('content'); ?>
<h2>طلب جديد! 🛍️ #<?php echo e($orderData['id']); ?></h2>
<p>لديك طلب جديد في متجرك <strong><?php echo e($orderData['store_name']); ?></strong>.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#<?php echo e($orderData['id']); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">العميل</span>
        <span class="info-value"><?php echo e($orderData['customer_name'] ?? 'غير محدد'); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">إجمالي الطلب</span>
        <span class="info-value"><?php echo e($orderData['total'] ?? ''); ?> <?php echo e($orderData['currency'] ?? 'ج.م'); ?></span>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\orders\new-order-merchant.blade.php ENDPATH**/ ?>