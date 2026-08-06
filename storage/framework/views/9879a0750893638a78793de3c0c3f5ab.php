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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                تفاصيل الطلب <?php echo e($order->reference_number); ?>

            </h2>
            <a href="<?php echo e(route('orders.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                <i class="fas fa-arrow-right mr-2"></i>العودة للقائمة
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Order Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold">طلب رقم: <?php echo e($order->reference_number); ?></h1>
                                <p class="text-blue-100 mt-2">تاريخ الطلب: <?php echo e($order->created_at->format('Y/m/d h:i A')); ?></p>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-blue-100 mb-1">حالة الطلب</div>
                                <span class="px-4 py-2 bg-white text-blue-600 rounded-full font-bold">
                                    <?php switch($order->status):
                                        case ('pending'): ?> قيد الانتظار <?php break; ?>
                                        <?php case ('confirmed'): ?> مؤكد <?php break; ?>
                                        <?php case ('shipped'): ?> في مرحلة التوصيل <?php break; ?>
                                        <?php case ('delivered'): ?> تم التسليم <?php break; ?>
                                        <?php case ('cancelled'): ?> ملغي <?php break; ?>
                                        <?php default: ?> <?php echo e($order->status); ?>

                                    <?php endswitch; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Order Info -->
                        <div class="bg-blue-50 p-6 rounded-xl">
                            <h3 class="text-lg font-bold text-blue-900 mb-4">
                                <i class="fas fa-info-circle mr-2"></i>معلومات الطلب
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الرقم المرجعي:</span>
                                    <span class="font-bold text-blue-600"><?php echo e($order->reference_number); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">التاريخ:</span>
                                    <span class="font-medium"><?php echo e($order->created_at->format('Y/m/d h:i A')); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">إجمالي المنتجات:</span>
                                    <span class="font-bold text-green-600"><?php echo e(number_format($order->total_amount - $order->shipping_cost, 0)); ?> جنيه</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">تكلفة الشحن:</span>
                                    <span class="font-bold text-blue-600">
                                        <?php if($order->shipping_cost > 0): ?>
                                            <?php echo e(number_format($order->shipping_cost, 0)); ?> جنيه
                                        <?php else: ?>
                                            مجاني
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between text-lg">
                                    <span class="font-bold text-gray-800">المجموع الكلي:</span>
                                    <span class="font-bold text-red-600"><?php echo e(number_format($order->total_amount, 0)); ?> جنيه</span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-green-50 p-6 rounded-xl">
                            <h3 class="text-lg font-bold text-green-900 mb-4">
                                <i class="fas fa-user mr-2"></i>بيانات العميل
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الاسم:</span>
                                    <span class="font-bold"><?php echo e($order->customer_name); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">الهاتف:</span>
                                    <span class="font-medium"><?php echo e($order->customer_phone); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">العنوان:</span>
                                    <span class="font-medium"><?php echo e($order->customer_address); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">المحافظة:</span>
                                    <span class="font-medium"><?php echo e($order->governorate); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-shopping-cart mr-2"></i>المنتجات المطلوبة
                        </h3>
                        <div class="bg-gray-50 rounded-xl overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المجموع</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = json_decode($order->items); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if(isset($item->image) && $item->image): ?>
                                                    <img class="h-16 w-16 rounded-lg object-cover mr-4" src="<?php echo e($item->image); ?>" alt="<?php echo e($item->name); ?>">
                                                <?php else: ?>
                                                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($item->name); ?></div>
                                                    <?php if(isset($item->description) && $item->description): ?>
                                                        <div class="text-sm text-gray-500"><?php echo e(Str::limit($item->description, 50)); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($item->quantity); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                            <?php echo e(number_format($item->price, 0)); ?> جنيه
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-green-600">
                                            <?php echo e(number_format($item->price * $item->quantity, 0)); ?> جنيه
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="<?php echo e(route('orders.invoice', $order)); ?>" 
                           class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-200 flex items-center">
                            <i class="fas fa-file-invoice mr-2"></i>عرض الفاتورة
                        </a>
                        
                        <a href="<?php echo e(route('orders.downloadInvoice', $order)); ?>" 
                           class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition duration-200 flex items-center">
                            <i class="fas fa-download mr-2"></i>تحميل الفاتورة
                        </a>
                        
                        <form action="<?php echo e(route('orders.destroy', $order)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')"
                                    class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition duration-200 flex items-center">
                                <i class="fas fa-trash mr-2"></i>حذف الطلب
                            </button>
                        </form>
                    </div>
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
<?php endif; ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\orders\show.blade.php ENDPATH**/ ?>