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
            سوق الثيمات والتصميمات
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6 font-sans" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success/Error Alerts -->
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-500/30 rounded-2xl shadow-sm flex items-center justify-between text-emerald-800 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🎉</span>
                        <span class="font-bold"><?php echo e(session('success')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-500/30 rounded-2xl shadow-sm flex items-center justify-between text-rose-800 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <span class="font-bold"><?php echo e(session('error')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Hero Header Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-xl mb-8 border border-white/10">
                <div class="absolute -right-10 -top-10 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-3 text-center md:text-right max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-gradient-to-r from-orange-500/20 to-amber-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold tracking-wide">
                            <span>✨ متجر التصميمات الاحترافية</span>
                        </div>
                        <h1 className="text-3xl md:text-4xl font-black tracking-tight leading-tight" style="font-size: 1.875rem; font-weight: 900;">
                            سوق ثيمات فاست أوردر
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                            اختر من بين مجموعة واجهات احترافية ومحسنة لتحويل الزوار إلى مشترين. جميع الثيمات متجاوبة بنسبة 100% مع الجوال ومحسنة لسرعة التحميل ومحركات البحث.
                        </p>
                    </div>

                    <!-- Summary Stats Cards -->
                    <div class="grid grid-cols-3 gap-3 w-full md:w-auto min-w-[300px]">
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                            <span class="block text-2xl md:text-3xl font-black text-orange-400"><?php echo e($stats['total']); ?></span>
                            <span class="text-xs text-slate-400 font-medium mt-1 block">إجمالي الثيمات</span>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                            <span class="block text-2xl md:text-3xl font-black text-emerald-400"><?php echo e($stats['free']); ?></span>
                            <span class="text-xs text-slate-400 font-medium mt-1 block">ثيمات مجانية</span>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                            <span class="block text-2xl md:text-3xl font-black text-amber-400"><?php echo e($stats['paid']); ?></span>
                            <span class="text-xs text-slate-400 font-medium mt-1 block">ثيمات مدفوعة</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Category Tabs -->
                <div class="flex items-center gap-2 w-full md:w-auto bg-slate-100 p-1 rounded-xl">
                    <button onclick="filterThemes('all')" id="btn-tab-all" class="tab-btn flex-1 md:flex-none px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 bg-white text-orange-600 shadow-sm">
                        <span>الكل</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-600">
                            <?php echo e($stats['total']); ?>

                        </span>
                    </button>
                    <button onclick="filterThemes('free')" id="btn-tab-free" class="tab-btn flex-1 md:flex-none px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900">
                        <span>المجانية</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-200 text-slate-500">
                            <?php echo e($stats['free']); ?>

                        </span>
                    </button>
                    <button onclick="filterThemes('paid')" id="btn-tab-paid" class="tab-btn flex-1 md:flex-none px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900">
                        <span>المدفوعة الاحترافية</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-200 text-slate-500">
                            <?php echo e($stats['paid']); ?>

                        </span>
                    </button>
                </div>

                <!-- Search & Sort Controls -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <input
                            type="text"
                            id="search-input"
                            placeholder="ابحث باسم الثيم..."
                            onkeyup="searchThemes()"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-800 placeholder-slate-400"
                        />
                        <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>
                    </div>

                    <select
                        id="sort-select"
                        onchange="sortThemes()"
                        class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                        <option value="default">🔥 الترتيب الافتراضي</option>
                        <option value="rating">⭐ الأعلى تقييماً</option>
                        <option value="reviews">💬 الأكثر مراجعة</option>
                    </select>
                </div>
            </div>

            <!-- Themes Grid -->
            <div id="themes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isCurrentActive = $theme->slug === $activeTheme;
                        $gradientClass = '';
                        switch ($theme->slug) {
                            case 'modern_minimalist':
                                $gradientClass = 'from-slate-900 via-indigo-950 to-blue-900';
                                break;
                            case 'bold':
                                $gradientClass = 'from-amber-600 via-orange-600 to-red-700';
                                break;
                            case 'dark_elegance':
                                $gradientClass = 'from-gray-950 via-purple-950 to-slate-900';
                                break;
                            case 'fresh_market':
                                $gradientClass = 'from-emerald-600 via-teal-700 to-green-800';
                                break;
                            case 'tech_store':
                                $gradientClass = 'from-cyan-600 via-blue-700 to-indigo-900';
                                break;
                            case 'starter':
                                $gradientClass = 'from-violet-600 via-purple-700 to-fuchsia-800';
                                break;
                            default:
                                $gradientClass = 'from-orange-500 via-amber-600 to-yellow-600';
                        }
                    ?>

                    <div
                        data-slug="<?php echo e($theme->slug); ?>"
                        data-type="<?php echo e($theme->type); ?>"
                        data-name="<?php echo e(strtolower($theme->name)); ?>"
                        data-rating="<?php echo e($theme->rating); ?>"
                        data-reviews="<?php echo e($theme->reviews_count); ?>"
                        data-order="<?php echo e($theme->sort_order); ?>"
                        class="theme-card group bg-white rounded-3xl overflow-hidden border transition-all duration-300 flex flex-col justify-between <?php echo e($isCurrentActive ? 'border-orange-500 shadow-lg ring-2 ring-orange-500/20' : 'border-slate-200 hover:shadow-xl hover:-translate-y-1 hover:border-slate-300'); ?>"
                    >
                        <div>
                            <!-- Theme Thumbnail Preview Box -->
                            <div class="relative h-48 bg-gradient-to-tr <?php echo e($gradientClass); ?> p-6 flex flex-col justify-between overflow-hidden">
                                <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px] group-hover:scale-105 transition-transform duration-500"></div>
                                
                                <!-- Top Badges -->
                                <div class="relative z-10 flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full text-xs font-black shadow-md <?php echo e($theme->type === 'free' ? 'bg-emerald-500 text-white' : 'bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950'); ?>">
                                        <?php echo e($theme->type === 'free' ? '🎁 مجاني' : '💎 ' . $theme->price . ' ' . ($theme->currency ?? 'ج.م')); ?>

                                    </span>

                                    <?php if($isCurrentActive): ?>
                                        <span class="px-3 py-1 rounded-full bg-white text-orange-600 text-xs font-black shadow-lg flex items-center gap-1.5 animate-pulse">
                                            <span>✔</span>
                                            <span>النشط حالياً</span>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Center Title Mockup -->
                                <div class="relative z-10 text-center">
                                    <h3 class="text-xl font-black text-white drop-shadow-md tracking-wide">
                                        <?php echo e($theme->name); ?>

                                    </h3>
                                    <p class="text-xs text-white/80 mt-1">بواسطة <?php echo e($theme->author ?? 'Fast Order Team'); ?></p>
                                </div>

                                <!-- Bottom Quick Overlay -->
                                <div class="relative z-10 flex items-center justify-between text-xs text-white/90 bg-black/20 backdrop-blur-md px-3 py-1.5 rounded-xl">
                                    <span class="flex items-center gap-1">
                                        <span class="text-amber-400 font-bold">★ <?php echo e($theme->rating ?? '5.0'); ?></span>
                                        <span class="text-white/70">(<?php echo e($theme->reviews_count ?? 0); ?>)</span>
                                    </span>
                                    <span>الإصدار <?php echo e($theme->version ?? '1.0.0'); ?></span>
                                </div>
                            </div>

                            <!-- Theme Body Content -->
                            <div class="p-6 space-y-4">
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2">
                                    <?php echo e($theme->description); ?>

                                </p>

                                <!-- Feature Highlights -->
                                <div class="space-y-2 pt-2 border-t border-slate-100">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">أبرز المميزات:</span>
                                    <ul class="space-y-1.5 text-xs text-slate-700">
                                        <?php if(is_array($theme->features)): ?>
                                            <?php $__currentLoopData = array_slice($theme->features, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="flex items-center gap-2">
                                                    <span class="text-emerald-500 font-bold">✓</span>
                                                    <span class="truncate"><?php echo e($feat); ?></span>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Footer -->
                        <div class="p-6 pt-0 flex flex-col gap-2.5">
                            <div class="grid grid-cols-2 gap-2">
                                <a
                                    href="<?php echo e(url('/merchant/themes/preview/' . $theme->slug)); ?>"
                                    target="_blank"
                                    class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                                >
                                    <span>👁️</span>
                                    <span>معاينة حية</span>
                                </a>
                                <a
                                    href="<?php echo e(url('/merchant/themes/marketplace/' . $theme->slug)); ?>"
                                    class="w-full py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                                >
                                    <span>ℹ️</span>
                                    <span>التفاصيل والمراجعات</span>
                                </a>
                            </div>

                            <?php if($isCurrentActive): ?>
                                <div class="w-full py-3 bg-emerald-50 text-emerald-700 border border-emerald-500/30 font-black text-sm rounded-xl text-center">
                                    ✔ الثيم المفعل لمتجرك حالياً
                                </div>
                            <?php else: ?>
                                <form action="<?php echo e(url('/merchant/themes/marketplace/' . $theme->slug . '/install')); ?>" method="POST" onsubmit="showLoader(this)">
                                    <?php echo csrf_field(); ?>
                                    <button
                                        type="submit"
                                        class="install-btn w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-black text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2"
                                    >
                                        <span>⚡</span>
                                        <span><?php echo e($theme->type === 'paid' ? 'شراء وتفعيل (' . $theme->price . ' ج.م)' : 'تفعيل الثيم لمتجري'); ?></span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto">
                <div class="text-5xl mb-4">🎨</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">لا توجد ثيمات مطابقة لبحثك</h3>
                <p class="text-slate-500 text-sm mb-6">حاول تغيير كلمات البحث أو إعادة ضبط فلتر التصنيف لعرض جميع الثيمات المتاحة.</p>
                <button
                    onclick="resetFilters()"
                    class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition"
                >
                    عرض جميع الثيمات
                </button>
            </div>
        </div>
    </div>

    <!-- Inline script for interactive filtering and loading in Blade -->
    <script>
        let currentCategory = 'all';

        function filterThemes(category) {
            currentCategory = category;
            
            // Toggle active classes on tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-orange-600', 'shadow-sm');
                btn.classList.add('text-slate-600', 'hover:text-slate-900');
                const badge = btn.querySelector('.text-xs');
                if (badge) {
                    badge.classList.remove('bg-orange-100', 'text-orange-600');
                    badge.classList.add('bg-slate-200', 'text-slate-500');
                }
            });

            const activeBtn = document.getElementById('btn-tab-' + category);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-600', 'hover:text-slate-900');
                activeBtn.classList.add('bg-white', 'text-orange-600', 'shadow-sm');
                const badge = activeBtn.querySelector('.text-xs');
                if (badge) {
                    badge.classList.remove('bg-slate-200', 'text-slate-500');
                    badge.classList.add('bg-orange-100', 'text-orange-600');
                }
            }

            applyFilters();
        }

        function searchThemes() {
            applyFilters();
        }

        function applyFilters() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.theme-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const type = card.dataset.type;
                const name = card.dataset.name;
                const description = card.querySelector('p').textContent.toLowerCase();

                const matchesCategory = (currentCategory === 'all' || type === currentCategory);
                const matchesSearch = (query === '' || name.includes(query) || description.includes(query));

                if (matchesCategory && matchesSearch) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function resetFilters() {
            document.getElementById('search-input').value = '';
            filterThemes('all');
        }

        function sortThemes() {
            const sortBy = document.getElementById('sort-select').value;
            const grid = document.getElementById('themes-grid');
            const cards = Array.from(grid.getElementsByClassName('theme-card'));

            cards.sort((a, b) => {
                if (sortBy === 'rating') {
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                } else if (sortBy === 'reviews') {
                    return parseInt(b.dataset.reviews) - parseInt(a.dataset.reviews);
                } else {
                    // default order
                    return parseInt(a.dataset.order) - parseInt(b.dataset.order);
                }
            });

            grid.innerHTML = '';
            cards.forEach(card => grid.appendChild(card));
        }

        function showLoader(form) {
            const btn = form.querySelector('.install-btn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جاري تفعيل الثيم...
            `;
        }
    </script>
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
<?php /**PATH E:\programing\flutter project\fast order\resources\views\merchant\themes\marketplace.blade.php ENDPATH**/ ?>