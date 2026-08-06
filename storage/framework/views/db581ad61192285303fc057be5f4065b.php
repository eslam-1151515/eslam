<?php $__env->startSection('title', 'تحديث حالة الطلب #' . ($orderNumber ?? '') . ' - ' . ($storeName ?? 'فاست أوردر')); ?>

<?php $__env->startSection('content'); ?>
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 48px; margin-bottom: 10px;"><?php echo e($statusIcon ?? '🔔'); ?></div>
    <h2>تحديث مهم بخصوص طلبك</h2>
    <p style="color: #666; font-size: 16px;">تم تغيير حالة طلبك رقم #<?php echo e($orderNumber ?? ''); ?> من <?php echo e($storeName ?? 'فاست أوردر'); ?>.</p>
</div>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#<?php echo e($orderNumber ?? ''); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">الحالة السابقة</span>
        <span class="info-value" style="color: #888;"><?php echo e($oldStatus ?: 'قيد المراجعة'); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">الحالة الجديدة</span>
        <span class="info-value">
            <?php if(($newStatus ?? '') === 'delivered'): ?>
                <span class="badge badge-success" style="font-size: 14px;"><?php echo e($statusLabel ?? 'تم التوصيل'); ?> ✅</span>
            <?php elseif(($newStatus ?? '') === 'cancelled'): ?>
                <span class="badge badge-danger" style="font-size: 14px;"><?php echo e($statusLabel ?? 'ملغي'); ?> ❌</span>
            <?php else: ?>
                <span class="badge badge-warning" style="font-size: 14px;"><?php echo e($statusLabel ?? 'جاري التجهيز'); ?> ⏳</span>
            <?php endif; ?>
        </span>
    </div>
    <?php if(isset($order) && is_object($order)): ?>
    <div class="info-row">
        <span class="info-label">إجمالي الطلب</span>
        <span class="info-value" style="font-weight: bold;"><?php echo e(round((float)($order->total ?? 0), 2)); ?> ج.م</span>
    </div>
    <?php endif; ?>
</div>

<?php if(($newStatus ?? '') === 'shipped'): ?>
<div style="background: #e0f2fe; border-right: 4px solid #0284c7; padding: 15px; border-radius: 8px; margin: 20px 0; color: #0369a1; font-size: 14px;">
    🚚 طلبك في الطريق إليك الآن! يرجى التأكد من التواجد في عنوان الشحن وجاهزية المبلغ في حالة الدفع عند الاستلام.
</div>
<?php elseif(($newStatus ?? '') === 'delivered'): ?>
<div style="background: #dcfce7; border-right: 4px solid #16a34a; padding: 15px; border-radius: 8px; margin: 20px 0; color: #15803d; font-size: 14px;">
    🎉 نتمنى أن تنال المنتجات إعجابك! نسعد دوماً بتقييمك للمنتجات وتجربتك معنا في المتجر.
</div>
<?php endif; ?>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo e($actionUrl ?? url('/account')); ?>" class="btn" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; padding: 14px 35px; border-radius: 30px; font-weight: bold; display: inline-block;">عرض تفاصيل الطلب 📄</a>
</div>

<p style="font-size: 14px; color: #888; text-align: center;">شكراً لتسوقك من <?php echo e($storeName ?? 'فاست أوردر'); ?>. نتمنى لك يوماً سعيداً!</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\customer\order-status-update.blade.php ENDPATH**/ ?>