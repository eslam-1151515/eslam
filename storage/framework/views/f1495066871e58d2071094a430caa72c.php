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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة قسم</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="<?php echo e(route('categories.store')); ?>" enctype="multipart/form-data" class="text-right" id="categoryForm">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">اسم القسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" value="<?php echo e(old('name_ar')); ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-200" required>
                    <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">القسم الرئيسي <span class="text-red-500">*</span></label>
                    <div class="flex gap-2 items-center">
                        <div class="flex-1 relative">
                            <select name="main_category" class="w-full border rounded-lg p-2 pr-8 focus:ring-2 focus:ring-indigo-200" required style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;">
                                <option value="">اختر القسم الرئيسي</option>
                                <?php $__currentLoopData = \App\Models\Category::getMainCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mainCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($mainCat); ?>" <?php echo e(old('main_category') == $mainCat ? 'selected' : ''); ?>>
                                        <?php echo e($mainCat); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <!-- Custom dropdown arrow pointing down on the right -->
                            <div class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="button" onclick="editSelectedCategory()" class="flex-shrink-0 px-3 py-2 border border-gray-300 rounded-lg text-gray-600 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 flex items-center gap-1" title="تعديل القسم المحدد">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                            </svg>
                            <span class="text-xs font-medium">تعديل</span>
                        </button>
                    </div>
                    <?php $__errorArgs = ['main_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">الوصف</label>
                    <textarea name="description" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-200" rows="4"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">صورة القسم (اختياري)</label>
                    <input type="file" name="image" accept="image/*" class="w-full border rounded-lg p-2" onchange="previewImage(event)">
                    <div class="mt-2">
                        <img id="imagePreview" class="hidden h-24 w-24 object-cover rounded border" alt="معاينة الصورة">
                    </div>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-red-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mt-6 flex items-center justify-between">
                    <a href="<?php echo e(route('categories.index')); ?>" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">رجوع</a>
                    <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 min-w-[160px] justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <!-- plus icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M12 4.5a.75.75 0 01.75.75V11h5.75a.75.75 0 010 1.5H12.75v5.75a.75.75 0 01-1.5 0V12.5H5.5a.75.75 0 010-1.5h5.75V5.25A.75.75 0 0112 4.5z"/></svg>
                        <span>إضافة القسم</span>
                    </button>
                </div>
            </form>
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

<script>
function previewImage(evt){
  const file = evt.target.files && evt.target.files[0];
  const img = document.getElementById('imagePreview');
  if(!file){ img.classList.add('hidden'); return; }
  img.src = URL.createObjectURL(file);
  img.classList.remove('hidden');
}

function editSelectedCategory(){
  try {
    const select = document.querySelector('select[name="main_category"]');
    if (!select) {
      alert('خطأ: لم يتم العثور على قائمة الأقسام الرئيسية');
      return;
    }
    
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
      alert('يرجى اختيار قسم رئيسي أولاً لتعديله');
      return;
    }
    
    const currentName = selectedOption.value;
    const newName = prompt('تعديل اسم القسم الرئيسي:\n\nالاسم الحالي: ' + currentName, currentName);
    
    if (newName !== null && newName.trim() !== '' && newName.trim() !== currentName) {
      // تحديث النص المعروض والقيمة
      selectedOption.value = newName.trim();
      selectedOption.textContent = newName.trim();
      
      alert('تم تعديل اسم القسم من "' + currentName + '" إلى "' + newName.trim() + '".\nملاحظة: هذا التغيير للجلسة الحالية فقط.');
    }
  } catch (error) {
    console.error('خطأ في تعديل القسم:', error);
    alert('حدث خطأ أثناء تعديل القسم. يرجى المحاولة مرة أخرى.');
  }
}
</script>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\categories\create.blade.php ENDPATH**/ ?>