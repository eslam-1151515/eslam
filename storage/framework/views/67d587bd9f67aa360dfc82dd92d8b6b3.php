<?php $__env->startSection('title', 'فشل فحص صحة النظام'); ?>

<?php $__env->startSection('content'); ?>
<h2>تنبيه: فشل فحص صحة النظام للمنصة ⚠️</h2>
<p>تم إجراء فحص صحة تلقائي للمنصة وتم رصد مشاكل أو فشل في بعض الخدمات الحيوية. يرجى مراجعة التفاصيل أدناه فوراً واتخاذ الإجراءات اللازمة.</p>

<div class="info-box" style="border-right-color: #e3342f;">
    <?php $__currentLoopData = $checks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="info-row" style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee;">
            <span class="info-label" style="font-weight: bold; color: #1a1a2e;"><?php echo e($name); ?></span>
            <span class="info-value">
                <?php if($check['ok']): ?>
                    <span class="badge badge-success">سليم</span>
                <?php else: ?>
                    <span class="badge badge-danger">فشل</span>
                <?php endif; ?>
            </span>
        </div>
        <?php if(!$check['ok']): ?>
            <div style="padding: 8px 10px; background: #fff5f5; border-radius: 4px; color: #e3342f; font-size: 13px; margin-bottom: 15px; border: 1px solid #fed7d7;">
                <strong>سبب الفشل:</strong> <?php echo e($check['message']); ?>

            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<p>تاريخ ووقت الفحص: <strong><?php echo e(now()->toDateTimeString()); ?></strong></p>
<p>تم تسجيل تفاصيل الأخطاء في ملف السجل المخصص: <code>storage/logs/fastorder-errors.log</code></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\system\health_failed.blade.php ENDPATH**/ ?>