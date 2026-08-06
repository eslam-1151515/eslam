<?php $__env->startSection('title', ($title ?? 'عروض جديدة') . ' - ' . ($storeName ?? 'فاست أوردر')); ?>

<?php $__env->startSection('content'); ?>
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 54px; margin-bottom: 10px;">🏷️</div>
    <h2 style="color: #667eea; font-size: 26px;"><?php echo e($title ?? 'عروض حصرية وخصومات رائعة!'); ?></h2>
    <p style="color: #555; font-size: 16px; line-height: 1.8;"><?php echo e($offer['description'] ?? 'لا تفوت أحدث عروضنا وخصوماتنا الحصرية لفترة محدودة على منتجاتنا المميزة.'); ?></p>
</div>

<?php if(!empty($offer['image'])): ?>
<div style="text-align: center; margin: 20px 0;">
    <img src="<?php echo e($offer['image']); ?>" alt="صورة العرض" style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
</div>
<?php endif; ?>

<div class="info-box" style="text-align: center; background: #fffaf0; border: 2px dashed #f6ad55; border-radius: 12px; padding: 25px;">
    <?php if(!empty($offer['discount'])): ?>
    <div style="font-size: 32px; font-weight: bold; color: #dd6b20; margin-bottom: 10px;">
        خصم <?php echo e($offer['discount']); ?>% 💥
    </div>
    <?php endif; ?>

    <?php if(!empty($offer['coupon_code'])): ?>
    <p style="color: #718096; font-size: 15px; margin-bottom: 8px;">استخدم كود الخصم الحصري عند إتمام الطلب:</p>
    <div style="display: inline-block; background: #2d3748; color: #fff; font-family: monospace; font-size: 22px; font-weight: bold; padding: 10px 25px; border-radius: 8px; letter-spacing: 2px;">
        <?php echo e($offer['coupon_code']); ?>

    </div>
    <?php endif; ?>
</div>

<div style="text-align: center; margin: 35px 0;">
    <a href="<?php echo e($actionUrl ?? url('/products')); ?>" class="btn" style="background: linear-gradient(135deg, #ff6584, #ff3b6a); color: white; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-weight: bold; font-size: 18px; display: inline-block; box-shadow: 0 4px 15px rgba(255,101,132,0.4);">تسوق العرض الآن 🚀</a>
</div>

<p style="font-size: 13px; color: #999; text-align: center;">العروض سارية لفترة محدودة أو حتى نفاد المخزون. تطبق الشروط والأحكام.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\customer\new-offers.blade.php ENDPATH**/ ?>