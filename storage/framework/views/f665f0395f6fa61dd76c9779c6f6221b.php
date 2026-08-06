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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إعدادات المتجر
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('status')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">حدث خطأ!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-3">🖼️ شعار المتجر (Logo)</h3>
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">رفع شعار جديد</label>
                                <input type="file" name="logo" id="logoInput" accept="image/*" onchange="previewLogo(event)"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">الصيغ المسموح بها: JPG, PNG, WEBP, SVG - الحد الأقصى: 2MB</p>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <p class="text-sm text-gray-600 font-medium">الشعار الحالي:</p>
                                <?php if($settings['logo']): ?>
                                    <img id="logoPreview" src="<?php echo e(asset('storage/' . $settings['logo'])); ?>" alt="Logo"
                                        class="h-24 w-24 object-cover rounded-xl border border-gray-200 shadow">
                                <?php else: ?>
                                    <img id="logoPreview" src="/images/logo.png" alt="Logo"
                                        class="h-24 w-24 object-cover rounded-xl border border-gray-200 shadow"
                                        onerror="this.src='https://dummyimage.com/96x96/6366f1/ffffff&text=Logo'">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-3">🎨 مظهر المتجر (Theme Customization)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اللون الرئيسي (Primary Color)</label>
                                <div class="flex gap-3 items-center">
                                    <input type="color" name="primary_color" value="<?php echo e($settings['primary_color'] ?? '#4f46e5'); ?>"
                                        class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo e($settings['primary_color'] ?? '#4f46e5'); ?>" readonly
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50 font-mono text-center">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">يُستخدم في الأزرار النشطة والروابط والأسعار</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اللون الفرعي (Secondary Color)</label>
                                <div class="flex gap-3 items-center">
                                    <input type="color" name="secondary_color" value="<?php echo e($settings['secondary_color'] ?? '#64748b'); ?>"
                                        class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo e($settings['secondary_color'] ?? '#64748b'); ?>" readonly
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50 font-mono text-center">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">يُستخدم في النصوص المساعدة والعناصر غير النشطة</p>
                            </div>

                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">خط المتجر (Font Family)</label>
                                <select name="font_family" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Cairo" <?php echo e(($settings['font_family'] ?? '') === 'Cairo' ? 'selected' : ''); ?>>Cairo (الافتراضي)</option>
                                    <option value="Tajawal" <?php echo e(($settings['font_family'] ?? '') === 'Tajawal' ? 'selected' : ''); ?>>Tajawal</option>
                                    <option value="Inter" <?php echo e(($settings['font_family'] ?? '') === 'Inter' ? 'selected' : ''); ?>>Inter</option>
                                    <option value="Roboto" <?php echo e(($settings['font_family'] ?? '') === 'Roboto' ? 'selected' : ''); ?>>Roboto</option>
                                    <option value="Almarai" <?php echo e(($settings['font_family'] ?? '') === 'Almarai' ? 'selected' : ''); ?>>Almarai</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">سيتم تطبيق هذا الخط على جميع نصوص وعناصر المتجر</p>
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-6 items-start border-t pt-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">أيقونة الموقع (Favicon)</label>
                                <input type="file" name="favicon" id="faviconInput" accept="image/*" onchange="previewFavicon(event)"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">الصيغ المسموح بها: ICO, PNG, JPG, WEBP - الحد الأقصى: 1MB</p>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <p class="text-sm text-gray-600 font-medium">الأيقونة الحالية:</p>
                                <?php if(isset($settings['favicon']) && $settings['favicon']): ?>
                                    <img id="faviconPreview" src="<?php echo e(asset('storage/' . $settings['favicon'])); ?>" alt="Favicon"
                                        class="h-12 w-12 object-contain rounded-lg border border-gray-200 shadow p-1 bg-gray-50">
                                <?php else: ?>
                                    <img id="faviconPreview" src="/favicon.ico" alt="Favicon"
                                        class="h-12 w-12 object-contain rounded-lg border border-gray-200 shadow p-1 bg-gray-50"
                                        onerror="this.src='https://dummyimage.com/48x48/6366f1/ffffff&text=Fav'">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-3">📞 بيانات المتجر الأساسية</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم المتجر</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <span class="px-3 py-2 bg-gray-100 text-gray-500 text-sm border-l border-gray-300">
                                        <i class="fa fa-store"></i>
                                    </span>
                                    <input type="text" name="store_name" value="<?php echo e($settings['store_name']); ?>"
                                        placeholder="اسم المتجر"
                                        class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">يظهر اسم المتجر في جميع صفحات الموقع والفواتير</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف (للاتصال)</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <span class="px-3 py-2 bg-gray-100 text-gray-500 text-sm border-l border-gray-300">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                    <input type="text" name="phone" value="<?php echo e($settings['phone']); ?>"
                                        placeholder="01xxxxxxxxx"
                                        class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">يُستخدم في زر "اتصل الآن" في الموقع</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الواتساب</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <span class="px-3 py-2 bg-green-50 text-green-500 text-sm border-l border-gray-300">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </span>
                                    <input type="text" name="whatsapp" value="<?php echo e($settings['whatsapp']); ?>"
                                        placeholder="201xxxxxxxxx (بدون +)"
                                        class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">مثال: 201146520922 (بدون + في البداية)</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">رابط صفحة فيسبوك</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <span class="px-3 py-2 bg-blue-50 text-blue-500 text-sm border-l border-gray-300">
                                        <i class="fa-brands fa-facebook"></i>
                                    </span>
                                    <input type="url" name="facebook_page" value="<?php echo e($settings['facebook_page']); ?>"
                                        placeholder="https://www.facebook.com/yourpage"
                                        class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1 border-b pb-3">📊 Facebook Pixel</h3>
                        <p class="text-sm text-gray-500 mb-4">أضف معرف الـ Pixel وسيُضاف تلقائياً على جميع صفحات الموقع</p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">معرف الـ Pixel (Pixel ID)</label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <span class="px-3 py-2 bg-blue-50 text-blue-600 text-sm border-l border-gray-300 font-bold">
                                    <i class="fa-brands fa-meta"></i>
                                </span>
                                <input type="text" name="facebook_pixel_id"
                                    value="<?php echo e($settings['facebook_pixel_id']); ?>"
                                    placeholder="مثال: 1234567890123456"
                                    class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                ادخل الـ Pixel ID فقط (الأرقام) - ستجده في إعدادات الـ Events Manager في Meta Business Suite
                            </p>
                            <?php if($settings['facebook_pixel_id']): ?>
                                <div class="mt-2 flex items-center gap-2 text-green-700 text-sm bg-green-50 rounded-lg px-3 py-2">
                                    <i class="fa fa-circle-check"></i>
                                    <span>الـ Pixel مفعّل على الموقع - ID: <span class="font-mono font-bold"><?php echo e($settings['facebook_pixel_id']); ?></span></span>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 flex items-center gap-2 text-gray-500 text-sm bg-gray-50 rounded-lg px-3 py-2">
                                    <i class="fa fa-circle-xmark"></i>
                                    <span>الـ Pixel غير مفعّل - أضف الـ Pixel ID لتفعيله</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1 border-b pb-3">🎵 TikTok Pixel</h3>
                        <p class="text-sm text-gray-500 mb-4">أضف معرف الـ Pixel وسيُضاف تلقائياً على جميع صفحات الموقع</p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">معرف الـ Pixel (Pixel ID)</label>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <span class="px-3 py-2 bg-black text-white text-sm border-l border-gray-300 font-bold">
                                    <i class="fa-brands fa-tiktok"></i>
                                </span>
                                <input type="text" name="tiktok_pixel_id"
                                    value="<?php echo e($settings['tiktok_pixel_id'] ?? ''); ?>"
                                    placeholder="مثال: CD8Q..."
                                    class="flex-1 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                ادخل الـ Pixel ID المكون من حروف وأرقام
                            </p>
                            <?php if(isset($settings['tiktok_pixel_id']) && $settings['tiktok_pixel_id']): ?>
                                <div class="mt-2 flex items-center gap-2 text-green-700 text-sm bg-green-50 rounded-lg px-3 py-2">
                                    <i class="fa fa-circle-check"></i>
                                    <span>الـ Pixel مفعّل على الموقع - ID: <span class="font-mono font-bold"><?php echo e($settings['tiktok_pixel_id']); ?></span></span>
                                </div>
                            <?php else: ?>
                                <div class="mt-2 flex items-center gap-2 text-gray-500 text-sm bg-gray-50 rounded-lg px-3 py-2">
                                    <i class="fa fa-circle-xmark"></i>
                                    <span>الـ Pixel غير مفعّل - أضف الـ Pixel ID لتفعيله</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1 border-b pb-3">🗂️ الأقسام الرئيسية</h3>
                        <p class="text-sm text-gray-500 mb-4">أضف أو عدّل أو احذف الأقسام الرئيسية التي تظهر عند إنشاء/تعديل قسم</p>

                        <div id="mainCategoriesList" class="space-y-2 mb-3">
                            <?php $__currentLoopData = $settings['main_categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-2 cat-row">
                                    <span class="text-gray-400 cursor-move">☰</span>
                                    <input type="text" name="main_categories[]"
                                        value="<?php echo e($cat); ?>"
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none text-sm"
                                        placeholder="اسم القسم الرئيسي">
                                    <button type="button" onclick="removeCategory(this)"
                                        class="text-red-400 hover:text-red-600 transition p-1 rounded hover:bg-red-50"
                                        title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <button type="button" onclick="addCategory()"
                            class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium hover:bg-indigo-50 px-3 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            إضافة قسم رئيسي جديد
                        </button>
                    </div>
                </div>

                
                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
                        </svg>
                        حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function previewFavicon(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('faviconPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Sync text input with color pickers
        document.addEventListener('DOMContentLoaded', function() {
            const colorInputs = document.querySelectorAll('input[type="color"]');
            colorInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const textInput = this.nextElementSibling;
                    if (textInput) {
                        textInput.value = this.value;
                    }
                });
            });
        });

        function addCategory() {
            const list = document.getElementById('mainCategoriesList');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2 cat-row';
            row.innerHTML = `
                <span class="text-gray-400 cursor-move">☰</span>
                <input type="text" name="main_categories[]"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none text-sm"
                    placeholder="اسم القسم الرئيسي الجديد" autofocus>
                <button type="button" onclick="removeCategory(this)"
                    class="text-red-400 hover:text-red-600 transition p-1 rounded hover:bg-red-50" title="حذف">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            list.appendChild(row);
            row.querySelector('input').focus();
        }

        function removeCategory(btn) {
            const row = btn.closest('.cat-row');
            const list = document.getElementById('mainCategoriesList');
            if (list.querySelectorAll('.cat-row').length <= 1) {
                alert('يجب أن يكون هناك قسم رئيسي واحد على الأقل');
                return;
            }
            row.remove();
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
<?php /**PATH E:\programing\flutter project\fast order\resources\views\settings\index.blade.php ENDPATH**/ ?>