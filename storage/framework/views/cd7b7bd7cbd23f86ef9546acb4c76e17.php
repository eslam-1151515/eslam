<aside class="hidden sm:block">
    <?php
        $isRtl = app()->getLocale() == 'ar';
    ?>
    <div x-data="{ profileOpen: false }" class="fixed inset-y-0 <?php echo e($isRtl ? 'right-0 border-l' : 'left-0 border-r'); ?> w-64 bg-white shadow-xl z-40 p-5 flex flex-col border-gray-200">
        <!-- Big Logo + Name stacked -->
        <div class="flex flex-col items-center mb-6 select-none">
            <?php
                $logoSetting = \App\Models\Setting::get('logo');
                $logoPath = $logoSetting ? public_path('storage/' . $logoSetting) : public_path('images/logo.png');
                $logoUrl  = $logoSetting ? asset('storage/' . $logoSetting) : asset('images/logo.png') . '?v=202604031';
            ?>
            <?php if(file_exists($logoPath)): ?>
                <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($storeName ?? 'Store'); ?>" class="h-32 w-32 rounded-2xl border border-gray-200 object-cover" width="128" height="128" style="width:128px;height:128px;" />
            <?php else: ?>
                <img src="https://dummyimage.com/128x128/111827/ffffff&text=SM" alt="<?php echo e($storeName ?? 'Store'); ?>" class="h-32 w-32 rounded-2xl border border-gray-200 object-cover" width="128" height="128" style="width:128px;height:128px;" />
            <?php endif; ?>
            <div class="mt-2 text-2xl font-extrabold text-gray-900 leading-none" style="text-align: center;"><?php echo e($storeName ?? 'Store'); ?></div>
        </div>

        <!-- Navigation Links (better style) -->
        <nav class="flex flex-col gap-1" aria-label="<?php echo e(__('القائمة')); ?>">
            <a href="/admin/dashboard" <?php echo request()->is('admin/dashboard') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <!-- icon: home -->
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.47 3.84a1.5 1.5 0 011.06 0l7.5 3a1.5 1.5 0 01.97 1.4V19a2 2 0 01-2 2H5a2 2 0 01-2-2V8.24a1.5 1.5 0 01.97-1.4l7.5-3zM8 12h8v7H8v-7z"/></svg>
                <span><?php echo e(__('الرئيسية')); ?></span>
            </a>
            <a href="/admin/orders" <?php echo request()->is('admin/orders*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/orders*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <!-- icon: orders/list -->
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 6.75A.75.75 0 014.75 6h14.5a.75.75 0 010 1.5H4.75A.75.75 0 014 6.75zM4 12a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H4.75A.75.75 0 014 12zm0 5.25a.75.75 0 01.75-.75h9.5a.75.75 0 010 1.5h-9.5A.75.75 0 014 17.25z"/></svg>
                <span><?php echo e(__('الطلبات')); ?></span>
            </a>
            <a href="/admin/categories" <?php echo request()->is('admin/categories*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/categories*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <!-- icon: folders -->
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 7.75A1.75 1.75 0 013.75 6h4.836c.466 0 .909.184 1.237.512l.665.665c.328.328.771.512 1.237.512H20.25A1.75 1.75 0 0122 9.399V17A3 3 0 0119 20H5a3 3 0 01-3-3V7.75z"/></svg>
                <span><?php echo e(__('الأقسام')); ?></span>
            </a>
            <a href="/admin/products" <?php echo request()->is('admin/products*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/products*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <!-- icon: tag/product -->
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.586 13.414l-7.172 7.172a2 2 0 01-2.828 0l-7.07-7.07A2 2 0 013 12.101V5a2 2 0 012-2h7.1a2 2 0 011.415.586l7.07 7.07a2 2 0 010 2.828zM7.5 7A1.5 1.5 0 106 8.5 1.5 1.5 0 007.5 7z"/></svg>
                <span><?php echo e(__('المنتجات')); ?></span>
            </a>
            <a href="/admin/shipping" <?php echo request()->is('admin/shipping*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/shipping*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <!-- icon: truck/shipping -->
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a3 3 0 116 0h.75c1.035 0 1.875-.84 1.875-1.875V15zM8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/><path d="M15 13.5v-1.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V16.5a.75.75 0 01-.75.75h-1.5a3 3 0 00-6 0H15z"/></svg>
                <span><?php echo e(__('الشحن')); ?></span>
            </a>
            <a href="/admin/banners" <?php echo request()->is('admin/banners*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/banners*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                <span><?php echo e(__('البانرات')); ?></span>
            </a>
            <a href="/admin/settings" <?php echo request()->is('admin/settings*') ? 'aria-current="page"' : ''; ?> class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-indigo-50 <?php echo e(request()->is('admin/settings*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700'); ?>">
                <svg class="h-5 w-5 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.32.07-.64.07-.58 0-.54-.03-1.06-.07-1.56l2.28-1.79c.2-.16.26-.46.13-.67l-2.16-3.75c-.13-.22-.4-.3-.62-.22l-2.69 1.08c-.55-.42-1.15-.77-1.81-1.04L14.5 2.42c-.04-.25-.27-.42-.53-.42H9.03c-.26 0-.49.17-.53.42L8.13 5.05C7.47 5.32 6.87 5.67 6.32 6.09L3.63 5.01c-.22-.08-.49 0-.62.22L.85 8.98c-.13.21-.07.51.13.67l2.28 1.79C3.21 11.44 3.18 11.96 3.18 12.5c0 .54.03 1.06.08 1.56L1 15.85c-.2.16-.26.46-.13.67l2.16 3.75c.13.22.4.3.62.22l2.69-1.08c.55.42 1.15.77 1.81 1.04l.38 2.63c.04.25.27.42.53.42h4.32c.26 0 .49-.17.53-.42l.38-2.63c.66-.27 1.26-.62 1.81-1.04l2.69 1.08c.22.08.49 0 .62-.22l2.16-3.75c.13-.21.07-.51-.13-.67l-2.28-1.79z"/></svg>
                <span><?php echo e(__('الإعدادات')); ?></span>
            </a>
        </nav>

        <!-- Profile section with dropdown at bottom -->
        <div class="mt-auto pt-5">
            <!-- Language Switcher -->
            <div class="mb-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider"><i class="fa-solid fa-globe mr-1"></i><?php echo e(__('اللغة')); ?></span>
                <div class="flex items-center gap-1 bg-gray-100 p-0.5 rounded-lg">
                    <a href="<?php echo e(route('lang.switch', 'ar')); ?>" class="px-2.5 py-1 text-xs rounded-md transition-all <?php echo e($isRtl ? 'bg-white text-indigo-600 font-bold shadow-sm' : 'text-gray-600 hover:text-gray-900'); ?>">العربية</a>
                    <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="px-2.5 py-1 text-xs rounded-md transition-all <?php echo e(!$isRtl ? 'bg-white text-indigo-600 font-bold shadow-sm' : 'text-gray-600 hover:text-gray-900'); ?>">English</a>
                </div>
            </div>

            <div class="relative">
                <button @click="profileOpen = !profileOpen" aria-haspopup="true" :aria-expanded="profileOpen.toString()" aria-label="<?php echo e(__('قائمة الملف الشخصي')); ?>" class="w-full flex items-center justify-between px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                    <div class="flex items-center gap-2">
                        <?php if(file_exists($logoPath)): ?>
                            <img src="<?php echo e(asset('images/logo.png?v=202604031')); ?>" alt="<?php echo e(__('شعار المتجر')); ?>" class="h-6 w-6 rounded-full border border-gray-200 object-cover" />
                        <?php else: ?>
                            <img src="https://dummyimage.com/24x24/111827/ffffff&text=K" alt="<?php echo e(__('الصورة الشخصية الافتراضية')); ?>" class="h-6 w-6 rounded-full border border-gray-200 object-cover" />
                        <?php endif; ?>
                        <span class="text-sm text-gray-700"><?php echo e(__('الملف الشخصي')); ?></span>
                    </div>
                    <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>

                <div x-show="profileOpen" x-transition @click.outside="profileOpen=false" class="absolute bottom-12 right-3 left-3 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">
                    <a href="/admin/profile" class="block px-3 py-2 text-sm hover:bg-gray-50"><?php echo e(__('الملف الشخصي')); ?></a>
                    <form method="POST" action="/admin/logout">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-right px-3 py-2 text-sm text-red-600 hover:bg-red-50"><?php echo e(__('تسجيل الخروج')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\layouts\sidebar.blade.php ENDPATH**/ ?>