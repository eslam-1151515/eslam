<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل ثيم {{ $theme->name }}
        </h2>
    </x-slot>

    @php
        $reviews = $theme->reviews ?? [];
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($reviews as $rev) {
            $rounded = (int) round($rev['rating'] ?? 5);
            if (isset($distribution[$rounded])) {
                $distribution[$rounded]++;
            }
        }
        $totalReviewsCount = count($reviews);
        $totalReviewsDivider = $totalReviewsCount ?: 1;
        $percentages = [];
        foreach ($distribution as $stars => $count) {
            $percentages[$stars] = round(($count / $totalReviewsDivider) * 100);
        }

        $isCurrentActive = $theme->slug === $activeTheme;

        // Gradient theme configuration
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
    @endphp

    <div class="py-6 font-sans text-right" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <div class="mb-6 flex items-center justify-between">
                <a
                    href="{{ url('/merchant/themes/marketplace') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-orange-600 transition"
                >
                    <span>&rarr;</span>
                    <span>العودة لسوق الثيمات</span>
                </a>
                <div class="text-xs text-slate-400">
                    سوق الثيمات / تفاصيل الثيم / {{ $theme->name }}
                </div>
            </div>

            <!-- Success/Error Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-500/30 rounded-2xl shadow-sm flex items-center justify-between text-emerald-800 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🎉</span>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-500/30 rounded-2xl shadow-sm flex items-center justify-between text-rose-800 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-500/30 rounded-2xl shadow-sm text-rose-800">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Showcase Section -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-2xl mb-8 border border-white/10">
                <div class="absolute -right-10 -top-10 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 p-6 md:p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="space-y-4 text-right max-w-3xl flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-black shadow-md {{ $theme->type === 'free' ? 'bg-emerald-500 text-white' : 'bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950' }}">
                                {{ $theme->type === 'free' ? '🎁 مجاني تماماً' : '💎 ثيم بريميوم' }}
                            </span>
                            @if($isCurrentActive)
                                <span class="px-3 py-1 rounded-full bg-orange-500 text-white text-xs font-black shadow-md">
                                    ✔ الثيم النشط حالياً
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-slate-300 text-xs font-medium">
                                الإصدار {{ $theme->version }}
                            </span>
                        </div>
                        <h1 class="text-2xl md:text-4xl font-black leading-tight text-white drop-shadow-md" style="font-size: 2rem; font-weight: 900;">
                            {{ $theme->name }}
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                            {{ $theme->description }}
                        </p>
                        <div class="flex items-center gap-4 text-xs md:text-sm text-slate-400">
                            <span>المطور: <strong class="text-white">{{ $theme->author }}</strong></span>
                            <span class="w-1.5 h-1.5 bg-slate-600 rounded-full"></span>
                            <span class="flex items-center gap-1">
                                <span class="text-amber-400 font-bold">★ {{ $theme->rating ?? '5.0' }}</span>
                                <span>({{ $theme->reviews_count ?? 0 }} تقييم)</span>
                            </span>
                        </div>
                    </div>

                    <!-- Side card block -->
                    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 text-center w-full lg:w-80 space-y-4 shrink-0 shadow-lg">
                        <div class="space-y-1">
                            <span class="text-xs text-slate-400 block font-medium">سعر الترخيص</span>
                            <div class="text-3xl font-black text-white">
                                @if($theme->type === 'free')
                                    <span class="text-emerald-400">مجاني بالكامل</span>
                                @else
                                    <span>{{ $theme->price }} <span class="text-lg font-normal text-slate-300">{{ $theme->currency ?? 'ج.م' }}</span></span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 space-y-3">
                            @if($isCurrentActive)
                                <div class="w-full py-3 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-black text-sm rounded-xl text-center flex items-center justify-center gap-2">
                                    <span>✔</span>
                                    <span>هذا هو الثيم النشط لمتجرك</span>
                                </div>
                            @else
                                <form action="{{ url('/merchant/themes/marketplace/' . $theme->slug . '/install') }}" method="POST" onsubmit="showInstallLoader(this)">
                                    @csrf
                                    <button
                                        type="submit"
                                        id="install-btn-submit"
                                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-black text-sm rounded-xl shadow-lg transition flex items-center justify-center gap-2"
                                    >
                                        <span>⚡</span>
                                        <span>{{ $theme->type === 'paid' ? 'شراء وتفعيل الثيم' : 'تفعيل الثيم الآن' }}</span>
                                    </button>
                                </form>
                            @endif

                            <a
                                href="{{ url('/merchant/themes/preview/' . $theme->slug) }}"
                                target="_blank"
                                class="w-full py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/10 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2"
                            >
                                <span>👁️ معاينة تفاعلية حية</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs selector (CSS controlled / Toggle visible columns) -->
            <div class="flex border-b border-slate-200 mb-8 bg-white p-1.5 rounded-2xl shadow-sm">
                <button onclick="switchTab('overview')" id="tab-btn-overview" class="section-tab-btn flex-1 py-3 text-center text-sm font-black rounded-xl transition-all duration-200 bg-slate-100 text-orange-600 shadow-sm">
                    نظرة عامة والمعرض
                </button>
                <button onclick="switchTab('features')" id="tab-btn-features" class="section-tab-btn flex-1 py-3 text-center text-sm font-black rounded-xl transition-all duration-200 text-slate-500 hover:text-slate-800">
                    المميزات والتوافق الفني
                </button>
                <button onclick="switchTab('reviews')" id="tab-btn-reviews" class="section-tab-btn flex-1 py-3 text-center text-sm font-black rounded-xl transition-all duration-200 text-slate-500 hover:text-slate-800">
                    التقييمات والتعليقات ({{ $theme->reviews_count ?? 0 }})
                </button>
            </div>

            <!-- Main Content Body -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left/Main Column -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Tab Overview Section -->
                    <div id="section-overview" class="section-content space-y-6">
                        <!-- Theme Mockup / Preview Showcase Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span>🎨</span>
                                <span>معاينة واجهة الثيم الرقمية</span>
                            </h2>

                            <!-- Simulated Desktop Preview Box -->
                            <div class="rounded-2xl border-4 border-slate-800 overflow-hidden shadow-xl relative aspect-video bg-slate-900">
                                <!-- Browser Header Simulator -->
                                <div class="bg-slate-800 py-2 px-4 text-center text-[10px] font-mono flex items-center justify-between shrink-0 text-slate-450 text-slate-400">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                    </div>
                                    <span class="text-slate-300">OrderSaif.eg/shop/preview/{{ $theme->slug }}</span>
                                    <span>معاينة سريعة</span>
                                </div>

                                <!-- Simulated Preview Body -->
                                <div class="w-full h-full bg-gradient-to-tr {{ $gradientClass }} flex flex-col items-center justify-center p-8 text-center text-white relative">
                                    <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px]"></div>
                                    <div class="relative z-10 space-y-3 max-w-md">
                                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl mx-auto border border-white/20 shadow-lg animate-bounce">
                                            💻
                                        </div>
                                        <h3 class="text-xl md:text-2xl font-black tracking-wide">{{ $theme->name }}</h3>
                                        <p class="text-xs text-white/80 line-clamp-2">
                                            قم بتشغيل المعاينة الحية لتجربة الثيم وتغيير الألوان والخطوط والتفاعل مع سلة المشتريات وإتمام الطلب.
                                        </p>
                                        <div class="pt-2">
                                            <a
                                                href="{{ url('/merchant/themes/preview/' . $theme->slug) }}"
                                                target="_blank"
                                                class="px-6 py-2 bg-white text-slate-900 hover:bg-slate-100 text-xs font-black rounded-xl transition shadow-md inline-flex items-center gap-1.5"
                                            >
                                                <span>👁️ تشغيل المعاينة التفاعلية الآن</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span>📄</span>
                                <span>تفاصيل وتصميم الثيم</span>
                            </h2>
                            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50 p-5 rounded-2xl border border-slate-200/60">
                                {{ $theme->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Tab Features Section -->
                    <div id="section-features" class="section-content hidden space-y-6">
                        <!-- Features block -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span>🔥</span>
                                <span>أبرز المميزات والخصائص للواجهة:</span>
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(is_array($theme->features))
                                    @foreach($theme->features as $feat)
                                        <div class="flex items-start gap-3 p-4 rounded-2xl bg-emerald-50/50 border border-emerald-500/20 shadow-sm">
                                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                                            <span class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $feat }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Compatibility block -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span>🛡️</span>
                                <span>التوافقية والبيئة التشغيلية:</span>
                            </h2>
                            <div class="space-y-3">
                                @if(is_array($theme->compatibility))
                                    @foreach($theme->compatibility as $comp)
                                        <div class="flex items-center gap-3 text-sm text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-200/60">
                                            <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center shrink-0 text-xs">⚡</span>
                                            <span class="font-medium">{{ $comp }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tab Reviews Section -->
                    <div id="section-reviews" class="section-content hidden space-y-6">
                        <!-- Rating Distribution -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-4 mb-6">
                                <span>📊</span>
                                <span>ملخص تقييمات التجار</span>
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                <!-- Big score -->
                                <div class="text-center md:border-l border-slate-200 py-4">
                                    <div class="text-5xl font-black text-amber-500">{{ $theme->rating ?? '5.0' }}</div>
                                    <div class="flex justify-center text-amber-400 text-lg my-2">
                                        ★ ★ ★ ★ ★
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        إجمالي التقييمات: {{ $theme->reviews_count ?? 0 }} مراجعة
                                    </div>
                                </div>

                                <!-- Bars -->
                                <div class="md:col-span-2 space-y-2.5">
                                    @foreach([5, 4, 3, 2, 1] as $stars)
                                        @php
                                            $pct = $percentages[$stars] ?? 0;
                                            $count = $distribution[$stars] ?? 0;
                                        @endphp
                                        <div class="flex items-center gap-3 text-xs font-semibold">
                                            <span class="w-12 text-slate-500 flex items-center gap-1 justify-end">
                                                <span>{{ $stars }} نجوم</span>
                                            </span>
                                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div
                                                    style="width: {{ $pct }}%"
                                                    class="h-full bg-amber-500 rounded-full"
                                                ></div>
                                            </div>
                                            <span class="w-14 text-slate-400 text-left">
                                                {{ $pct }}% ({{ $count }})
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Add Review form -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                                <span>✍️</span>
                                <span>أضف مراجعتك وتقييمك الشخصي للثيم:</span>
                            </h2>

                            <form action="{{ url('/merchant/themes/marketplace/' . $theme->slug . '/review') }}" method="POST" class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">التقييم بالنجوم:</label>
                                        <select
                                            name="rating"
                                            required
                                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800"
                                        >
                                            <option value="5">⭐⭐⭐⭐⭐ 5 نجوم (ممتاز)</option>
                                            <option value="4">⭐⭐⭐⭐ 4 نجوم (جيد جداً)</option>
                                            <option value="3">⭐⭐⭐ 3 نجوم (جيد)</option>
                                            <option value="2">⭐⭐ 2 نجوم (مقبول)</option>
                                            <option value="1">⭐ 1 نجمة (سيء)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">اسمك أو اسم متجرك (اختياري):</label>
                                        <input
                                            type="text"
                                            name="reviewer"
                                            placeholder="مثال: متجر الأناقة الحديثة"
                                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">تعليقك أو مراجعتك التفصيلية:</label>
                                    <textarea
                                        rows="4"
                                        name="comment"
                                        required
                                        placeholder="اكتب هنا رأيك بخصوص سرعة تحميل الصفحة، ومظهره على الجوال، وسهولة تخصيصه..."
                                        class="w-full bg-white border border-slate-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800"
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2"
                                >
                                    <span>📨</span>
                                    <span>إرسال ونشر المراجعة الآن</span>
                                </button>
                            </form>
                        </div>

                        <!-- Review list -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <h2 class="text-lg font-black text-slate-800 pb-3 border-b border-slate-100">
                                💬 آراء ومراجعات التجار الفعليين ({{ count($reviews) }})
                            </h2>

                            @if(count($reviews) === 0)
                                <div class="text-center py-12 text-slate-400">
                                    <span class="text-4xl block mb-2">⭐</span>
                                    <span>لا توجد تقييمات لهذا الثيم حتى الآن. كن أول من يضيف تقييمه!</span>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($reviews as $rev)
                                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black text-sm flex items-center justify-center shadow-inner">
                                                        {{ mb_substr($rev['reviewer'] ?? 'ت', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <span class="text-sm font-bold text-slate-800 block">{{ $rev['reviewer'] ?? 'تاجر فاست أوردر' }}</span>
                                                        <span class="text-[10px] text-slate-400">{{ $rev['date'] ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center text-amber-400 font-black text-sm text-xs">
                                                    @php $starsCount = (int) ($rev['rating'] ?? 5); @endphp
                                                    {!! str_repeat('★', $starsCount) !!}
                                                    {!! str_repeat('☆', 5 - $starsCount) !!}
                                                </div>
                                            </div>
                                            <p class="text-sm text-slate-600 leading-relaxed pr-12">
                                                {{ $rev['comment'] ?? '' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Specs -->
                <div class="space-y-6">
                    <!-- Technical specifications card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h3 class="text-base font-black text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <span>📋</span>
                            <span>المواصفات الفنية للثيم</span>
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">اسم المطور:</span>
                                <span class="font-bold text-slate-800">{{ $theme->author }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">نوع الترخيص:</span>
                                <span class="px-2 py-0.5 rounded text-xs font-bold {{ $theme->type === 'free' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                    {{ $theme->type === 'free' ? 'مجاني' : 'مدفوع بريميوم' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">سعر الثيم:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $theme->type === 'free' ? '0.00 EGP' : $theme->price . ' ' . ($theme->currency ?? 'EGP') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">الإصدار الحالي:</span>
                                <span class="font-bold text-slate-800">{{ $theme->version }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">متوسط التقييمات:</span>
                                <span class="font-bold text-amber-500">★ {{ $theme->rating ?? '5.0' }} / 5.0</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-slate-500 font-medium">عدد المراجعات:</span>
                                <span class="font-bold text-slate-800">{{ $theme->reviews_count ?? 0 }} مراجعة</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Saif Guarantee -->
                    <div class="bg-gradient-to-r from-orange-500/10 to-amber-500/10 border border-orange-500/20 rounded-3xl p-6 text-right space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange-500 text-white flex items-center justify-center text-xl shadow-md">
                            🛡️
                        </div>
                        <h4 class="text-sm font-black text-orange-600">ضمان جودة وأمان فاست أوردر</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            جميع الثيمات المعروضة في سوقنا يتم مراجعتها برمجياً وفحصها للتأكد من خلوها من أي مشاكل برمجية أو أكواد ضارة، لضمان أعلى مستويات الأداء والأمان لمتجرك وسرعة تحميل لا تضاهى.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to toggle tab contents -->
    <script>
        function switchTab(tabId) {
            // Hide all tab sections
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.add('hidden');
            });

            // Show current active tab section
            const targetSection = document.getElementById('section-' + tabId);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }

            // Update active styling on tab buttons
            document.querySelectorAll('.section-tab-btn').forEach(btn => {
                btn.classList.remove('bg-slate-100', 'text-orange-600', 'shadow-sm');
                btn.classList.add('text-slate-500', 'hover:text-slate-800');
            });

            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-500', 'hover:text-slate-800');
                activeBtn.classList.add('bg-slate-100', 'text-orange-600', 'shadow-sm');
            }
        }

        function showInstallLoader(form) {
            const btn = document.getElementById('install-btn-submit');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                جاري تفعيل الثيم...
            `;
        }
    </script>
</x-app-layout>
