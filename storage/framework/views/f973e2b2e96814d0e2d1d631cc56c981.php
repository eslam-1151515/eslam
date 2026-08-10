<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fast Order — استعادة كلمة السر']); ?>
    <div style="text-align: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 8px;">استعادة كلمة السر</h2>
        <p style="font-size: 13px; color: #94a3b8; line-height: 1.5;">أدخل البريد الإلكتروني وسنرسل لك رابطاً لإعادة التعيين</p>
    </div>

    <?php if(session('status')): ?>
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; text-align: center; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <span>✓</span> <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.email')); ?>" class="rtl">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom: 24px;">
            <label for="email">البريد الإلكتروني</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="<?php echo e(old('email')); ?>" 
                   placeholder="أدخل البريد الإلكتروني المسجل" 
                   required 
                   autofocus />
            <?php if($errors->has('email')): ?>
                <p style="color: #f87171; font-size: 12px; font-weight: 600; margin-top: 6px; text-align: right;"><?php echo e($errors->first('email')); ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary">
            إرسال رابط استعادة كلمة السر
        </button>

        <div style="margin-top: 24px; text-align: center; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px;">
            <a href="<?php echo e(route('login')); ?>" style="color: #a5b4fc; text-decoration: none; font-size: 13px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#c7d2fe'" onmouseout="this.style.color='#a5b4fc'">
                العودة لصفحة تسجيل الدخول
            </a>
        </div>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH E:\programing\flutter project\fast order\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>