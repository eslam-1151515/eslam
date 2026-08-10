<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fast Order — تعيين كلمة السر الجديدة']); ?>
    <div style="text-align: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 8px;">تعيين كلمة سر جديدة</h2>
        <p style="font-size: 13px; color: #94a3b8; line-height: 1.5;">أدخل كلمة المرور الجديدة للحساب</p>
    </div>

    <form method="POST" action="<?php echo e(route('password.store')); ?>" class="rtl">
        <?php echo csrf_field(); ?>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email">البريد الإلكتروني</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="<?php echo e(old('email', $request->email)); ?>" 
                   style="background: rgba(255,255,255,0.02); color: #94a3b8;"
                   required 
                   autofocus 
                   readonly />
            <?php if($errors->has('email')): ?>
                <p style="color: #f87171; font-size: 12px; font-weight: 600; margin-top: 6px; text-align: right;"><?php echo e($errors->first('email')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <label for="password">كلمة المرور الجديدة</label>
            <div style="position: relative;">
                <input id="password" 
                       style="padding-left: 40px;"
                       type="password" 
                       name="password" 
                       placeholder="أدخل كلمة المرور الجديدة" 
                       required />
                <button type="button" 
                        onclick="var p=document.getElementById('password'); p.type=p.type==='password'?'text':'password';"
                        style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #6b7280; padding: 0;"
                        title="إظهار / إخفاء">
                    <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <?php if($errors->has('password')): ?>
                <p style="color: #f87171; font-size: 12px; font-weight: 600; margin-top: 6px; text-align: right;"><?php echo e($errors->first('password')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div style="margin-bottom: 24px;">
            <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
            <div style="position: relative;">
                <input id="password_confirmation" 
                       style="padding-left: 40px;"
                       type="password" 
                       name="password_confirmation" 
                       placeholder="أعد كتابة كلمة المرور الجديدة" 
                       required />
                <button type="button" 
                        onclick="var p=document.getElementById('password_confirmation'); p.type=p.type==='password'?'text':'password';"
                        style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #6b7280; padding: 0;"
                        title="إظهار / إخفاء">
                    <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <?php if($errors->has('password_confirmation')): ?>
                <p style="color: #f87171; font-size: 12px; font-weight: 600; margin-top: 6px; text-align: right;"><?php echo e($errors->first('password_confirmation')); ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary">
            حفظ كلمة المرور الجديدة
        </button>
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
<?php /**PATH E:\programing\flutter project\fast order\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>