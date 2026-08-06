<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تفاصيل منتج</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-semibold mb-3"><?php echo e($product->name); ?></h2>
            <div class="mb-2"><strong>القسم:</strong> <?php echo e(optional($product->category)->name); ?></div>
            <div class="mb-2"><strong>السعر:</strong> <?php echo e(number_format($product->price, 0)); ?></div>
            <div class="mb-2"><strong>الكمية:</strong> <?php echo e($product->stock); ?></div>
            <div class="mb-2"><strong>الوصف:</strong> <?php echo e($product->description); ?></div>
            <?php if($product->image_url): ?>
                <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="mt-4 max-h-64">
            <?php endif; ?>
            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('products.index')); ?>" class="px-4 py-2 border rounded">رجوع</a>
            </div>
        </div>
    </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\products\show.blade.php ENDPATH**/ ?>