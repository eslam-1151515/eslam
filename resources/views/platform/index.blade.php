@extends('layouts.platform')

@section('title', 'أوردر سيف (Order Saif) | أسرع منصة لإنشاء المتاجر الإلكترونية في مصر')

@section('content')
<div class="overflow-hidden">

    <!-- ========================================== -->
    <!-- 1. HERO SECTION (Minimalist Clean Modern)  -->
    <!-- ========================================== -->
    <section class="relative pt-16 pb-20 md:pt-24 md:pb-28 overflow-hidden bg-white border-b border-slate-100">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            
            <!-- Top Minimalist Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 border border-slate-200 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-700">المنصة الأسرع لإنشاء متجرك الإلكتروني في مصر • عمولة 0%</span>
            </div>

            <!-- Clean Bold Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.2] tracking-tight max-w-4xl mx-auto">
                أنشئ متجرك الإلكتروني في دقائق<br>
                <span class="text-brand-600">وابدأ البيع فوراً بأعلى كفاءة</span>
            </h1>

            <!-- Minimalist Subtitle -->
            <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed max-w-2xl mx-auto">
                صفحات هبوط خارقة السرعة، ربط فوري وتلقائي للبيكسلز (فيسبوك، تيك توك، سناب)، ونظام شحن المحفظة المرن (2ج لكل أوردر) بدون أي اشتراكات معقدة.
            </p>

            <!-- Clean Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 pt-2">
                <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                   class="w-full sm:w-auto px-8 py-4 rounded-xl text-base font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket text-amber-300"></i>
                    <span>ابدأ تجربتك المجانية (7 أيام)</span>
                </a>
                <a href="#pricing" 
                   class="w-full sm:w-auto px-7 py-4 rounded-xl text-base font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-tags text-slate-400"></i>
                    <span>عرض الباقات والأسعار</span>
                </a>
            </div>

            <!-- Micro Trust Row -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-6 text-xs font-bold text-slate-500">
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> بدون بطاقة ائتمانية</div>
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> تفعيل فوري لمتجرك</div>
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-500"></i> دعم فني وواتساب 24/7</div>
            </div>

            <!-- Minimalist Clean Preview Frame -->
            <div class="pt-8 max-w-4xl mx-auto">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-2 sm:p-3 shadow-xl">
                    <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-6 text-right space-y-5">
                        
                        <!-- Mini Bar Header -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-store text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-extrabold text-slate-900">لوحة تحكم المتجر</div>
                                    <div class="text-[11px] text-slate-400 font-mono">ordersaif.com/admin</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold">
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    المتجر نشط وجاهز
                                </span>
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 font-mono">
                                    0% عمولة
                                </span>
                            </div>
                        </div>

                        <!-- 3 Stats Pills -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="text-xs text-slate-500 font-semibold mb-1">مبيعات اليوم</div>
                                <div class="text-xl font-black text-slate-900 font-mono">48,520 <span class="text-xs font-bold text-slate-500">ج.م</span></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="text-xs text-slate-500 font-semibold mb-1">الطلبات الجديدة</div>
                                <div class="text-xl font-black text-brand-600 font-mono">64 <span class="text-xs font-bold text-slate-500">أوردر</span></div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="text-xs text-slate-500 font-semibold mb-1">سرعة المتجر</div>
                                <div class="text-xl font-black text-emerald-600 font-mono">0.8s <span class="text-xs font-bold text-slate-500">⚡ فائق السرعة</span></div>
                            </div>
                        </div>

                        <!-- Recent Orders Simple List -->
                        <div class="space-y-2 pt-1">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100 text-xs">
                                <div class="flex items-center gap-2.5 font-bold text-slate-800">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>أحمد محمود - القاهرة (طقم كاجوال صيفي)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-900 font-mono">850 ج.م</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">تم الاستلام</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100 text-xs">
                                <div class="flex items-center gap-2.5 font-bold text-slate-800">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span>سارة خليل - الإسكندرية (فستان سواريه أنيق)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-900 font-mono">1,450 ج.م</span>
                                    <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 text-[10px] font-bold">جاري الشحن</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 2. PROOF & KEY NUMBERS TICKER              -->
    <!-- ========================================== -->
    <section class="py-10 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl sm:text-3xl font-black text-brand-600 font-mono">+1,500</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-600 mt-1">متجر إلكتروني نشط</div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl sm:text-3xl font-black text-indigo-600 font-mono">+50,000</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-600 mt-1">أوردر يتم معالجته يومياً</div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono">99.9%</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-600 mt-1">استقرار وسرعة السيرفرات</div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl sm:text-3xl font-black text-amber-500 font-mono">0%</div>
                    <div class="text-xs sm:text-sm font-bold text-slate-600 mt-1">عمولة على مبيعاتك بالكامل</div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 3. WHY ORDER SAIF? (Key Features)          -->
    <!-- ========================================== -->
    <section id="features" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Title -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                    <i class="fa-solid fa-sparkles"></i>
                    <span>مميزات مصممة خصيصاً لمضاعفة مبيعاتك</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    كل الأدوات التي تحتاجها للنجاح في التجارة الإلكترونية
                </h2>
                <p class="text-base text-slate-600 font-medium leading-relaxed">
                    وفرنا لك حلولاً متطورة تعالج كافة المشاكل التي تواجه التجار والمعلنين على المنصات الأخرى.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card 1: Speed -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">سرعة فائقة تمنع هروب الزوار</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        صفحات هبوط خفيفة وتخزين كاش ذكي يضمن تحميل المتجر في أقل من ثانية، مما يرفع معدل إتمام الطلبات (Conversion Rate) بنسبة تصل إلى 40%.
                    </p>
                </div>

                <!-- Card 2: Smart Pixels -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">ربط واستخراج ذكي للبيكسلز</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        الصق كود بيكسل Facebook أو TikTok أو Snapchat بالكامل، وسيقوم النظام باستخراج المعرف وحفظه تلقائياً بدون تعقيد، مع تتبع دقيق لأحداث الشراء.
                    </p>
                </div>

                <!-- Card 3: Pay Per Order -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">باقة الأوردر المرنة (2ج فقط)</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        بدون أي اشتراك شهري ثابت! اشحن محفظتك بـ فودافون كاش أو إنستاباي، وادفع 2 ج.م فقط عند فتح ومعاينة كل أوردر يصل لمتجرك.
                    </p>
                </div>

                <!-- Card 4: Themes -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">ثيمات احترافية متجاوبة 100%</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        مكتبة تصميمات مخصصة لمختلف المجالات (ملابس، أحذية، عطور، إلكترونيات) مع تعديل سهل وسلس لألوان وخطوط وهيكل المتجر بدون أي برمجة.
                    </p>
                </div>

                <!-- Card 5: Smart Shipping -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">إدارة شحن ومحافظات ذكية</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        حدد أسعار شحن مخصصة لكل محافظة في مصر، واطبع بوالص الشحن وفواتير العملاء بضغطة زر واحدة لتسليمها لشركات الشحن بسرعة.
                    </p>
                </div>

                <!-- Card 6: Multi-Payment & COD -->
                <div class="light-card rounded-3xl p-8 space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl shadow-inner">
                        <i class="fa-solid fa-comments-dollar"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">دفع متعدد وواتساب مباشر</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        دعم كامل للدفع عند الاستلام (COD)، تحويل فودافون كاش وإنستاباي مع رفع إيصال التحويل، وزر واتساب عائم للتواصل المباشر مع العميل.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 4. HOW IT WORKS (3 Simple Steps)           -->
    <!-- ========================================== -->
    <section id="how-it-works" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                    <i class="fa-solid fa-forward-step"></i>
                    <span>بسيطة وسهلة للغاية</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    كيف تبدأ وتطلق متجرك في 3 خطوات بسيطة؟
                </h2>
                <p class="text-base text-slate-600 font-medium">
                    لا تحتاج إلى أي خبرة تقنية، نظامنا صُمم ليكون بسيطاً ومباشراً لأي بائع.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                
                <!-- Step 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 relative text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-brand-500/30">
                        1
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900">سجل حسابك وحدد اسم متجرك</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        أنشئ حسابك المجاني في 30 ثانية بدون بطاقة ائتمان، واختر الرابط الخاص بمتجرك لتبدأ على الفور.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 relative text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-500/30">
                        2
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900">ارفع منتجاتك واختر الثيم</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        أضف صور وأسعار وتفاصيل منتجاتك بسهولة، واختر الثيم الأنسب لبراندك مع تخصيص الألوان واللوجو.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 relative text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/30">
                        3
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900">أطلق إعلاناتك واستقبل الأوردرات</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        اربط بيكسل الإعلانات وابدأ التسويق، واستقبل إشعارات الطلبات المباشرة على لوحة تحكمك لحظة بلحظة.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 5. PRICING & PLANS                         -->
    <!-- ========================================== -->
    <section id="pricing" class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                    <i class="fa-solid fa-badge-percent"></i>
                    <span>باقات مرنة تناسب كل تاجر</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    اختر الخطة المناسبة لحجم تجارتك
                </h2>
                <p class="text-base text-slate-600 font-medium">
                    بدون أي رسوم خفية أو عمولات، كل أرباح مبيعاتك تعود إليك بنسبة 100%.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                
                <!-- Plan 1: Free Trial -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                            🎁 100 ج.م رصيد هدية ترحيبية
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">الباقة التجريبية</h3>
                        <div class="flex items-baseline gap-1 font-mono">
                            <span class="text-4xl font-black text-slate-900">0</span>
                            <span class="text-sm font-bold text-slate-500">ج.م / 7 أيام</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            احصل على 100 ج.م رصيد هدية مجانية في محفظتك ترحيباً بك لتجربة واستقبال أول 50 أوردر مجاناً بدون أي بطاقة ائتمانية.
                        </p>

                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-sm text-slate-700 font-semibold">
                            <div class="flex items-center gap-2 text-emerald-700 font-bold"><i class="fa-solid fa-gift text-emerald-500"></i> 100 ج.م رصيد هدية في محفظتك فوراً</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> متجر إلكتروني سريع ومتكامل</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> لوحة تحكم عربية سهلة</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> تجربة فتح ومعاينة الطلبات مجاناً</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> 0% عمولة على المبيعات</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> دعم فني متواصل 24/7</div>
                        </div>
                    </div>

                    <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                       class="w-full py-3.5 rounded-xl text-center font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all">
                        ابدأ التجربة المجانية واحصل على 100ج
                    </a>
                </div>

                <!-- Plan 2: Pay Per Order (Featured) -->
                <div class="bg-white rounded-3xl p-8 border-2 border-brand-500 shadow-xl flex flex-col justify-between space-y-6 relative">
                    <div class="absolute -top-4 right-8 bg-gradient-to-r from-brand-600 to-indigo-600 text-white text-xs font-black px-4 py-1.5 rounded-full shadow-md">
                        🔥 الأكثر شعبية وتوفيراً
                    </div>

                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-bold">
                            شحن محفظة مرن
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">باقة الدفع لكل أوردر</h3>
                        <div class="flex items-baseline gap-1 font-mono">
                            <span class="text-4xl font-black text-brand-600">2</span>
                            <span class="text-sm font-bold text-slate-500">ج.م / لكل أوردر مفتوح</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            بدون أي اشتراك شهري ثابت! اشحن محفظتك بـ فودافون كاش أو إنستاباي وادفع فقط عند معاينة الأوردر.
                        </p>

                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-sm text-slate-700 font-semibold">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> 0ج اشتراك شهري ثابت</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> خصم 2 ج.م فقط عند فتح كل أوردر</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> شحن فوري بـ فودافون كاش وإنستاباي</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> منتجات وثيمات غير محدودة</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> ربط دومين وبيكسلات مجاناً</div>
                        </div>
                    </div>

                    <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                       class="w-full py-3.5 rounded-xl text-center font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/25 transition-all">
                        اختر باقة الـ 2ج للأوردر
                    </a>
                </div>

                <!-- Plan 3: Monthly Unlimited -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                            اشتراك شهري غير محدود
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">الاشتراك الشهري الشامل</h3>
                        <div class="flex items-baseline gap-2 font-mono">
                            <span class="text-4xl font-black text-slate-900">1,000</span>
                            <span class="text-sm font-bold text-slate-500">ج.م / شهرياً</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            فتح غير محدود لكافة الأوردرات بدون خصم 2ج مع سيرفرات فائقة السرعة ودعم VIP.
                        </p>

                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-sm text-slate-700 font-semibold">
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> فتح غير محدود لجميع الأوردرات</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> بدون خصم 2ج على الأوردر</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> 0% عمولة على المبيعات</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> فتح جميع الثيمات الاحترافية</div>
                            <div class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> مدير حساب مخصص ودعم VIP</div>
                        </div>
                    </div>

                    <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                       class="w-full py-3.5 rounded-xl text-center font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all">
                        اشترك في الباقة الشهرية (1,000ج)
                    </a>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 6. REAL MERCHANT REVIEWS (Testimonials)    -->
    <!-- ========================================== -->
    <section id="testimonials" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                    <i class="fa-solid fa-star"></i>
                    <span>تجارب حقيقية من شركاء النجاح</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    ماذا يقول عنا أصحاب المتاجر في مصر؟
                </h2>
                <p class="text-base text-slate-600 font-medium">
                    مئات البراندات والتجار يعتمدون على أوردر سيف يومياً لإدارة مبيعاتهم.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="light-card rounded-3xl p-8 space-y-5">
                    <div class="flex text-amber-400 text-sm gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        "سرعة تحميل صفحة الهبوط رفعت نسبة إتمام الشراء عندي بأكثر من 30% مقارنة بالمنصات السابقة. نظام شحن المحفظة بالأوردر ممتاز جداً ووفر علينا مصاريف اشتراك ثابتة."
                    </p>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 font-bold flex items-center justify-center text-sm">
                            أ.ش
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">أحمد الشريف</div>
                            <div class="text-xs text-slate-400">مؤسس براند ملابس كاجوال</div>
                        </div>
                    </div>
                </div>

                <div class="light-card rounded-3xl p-8 space-y-5">
                    <div class="flex text-amber-400 text-sm gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        "ربط بيكسل التيك توك والفيسبوك أسهل ما يكون، بمجرد وضع الكود النظام بيستخرج الـ ID في ثواني. وتتبع أحداث الشراء دقيق 100%."
                    </p>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-600 font-bold flex items-center justify-center text-sm">
                            م.ع
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">مريم عبد العزيز</div>
                            <div class="text-xs text-slate-400">مالكة متجر مستحضرات تجميل</div>
                        </div>
                    </div>
                </div>

                <div class="light-card rounded-3xl p-8 space-y-5">
                    <div class="flex text-amber-400 text-sm gap-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        "لوحة التحكم سريعة وسلسة جداً في متابعة الطلبات، وتخصيص أسعار الشحن لكل محافظة سهل علينا التعامل مع شركات الشحن وتأكيد الأوردرات."
                    </p>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center text-sm">
                            ح.ص
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">حسام الصاوي</div>
                            <div class="text-xs text-slate-400">مدير متجر إلكترونيات وإكسسوارات</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 7. FAQS (Interactive Accordion)            -->
    <!-- ========================================== -->
    <section id="faqs" class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-14 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>إجابات واضحة لجميع تساؤلاتك</span>
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">الأسئلة الأكثر شيوعاً</h2>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-5 text-right font-extrabold text-slate-900 flex items-center justify-between gap-4">
                        <span>هل أحتاج إلى أي خبرة برمجية لإنشاء متجري؟</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        لا على الإطلاق! منصة أوردر سيف مصممة بواجهات مرئية عربية فائقة البساطة. يمكنك رفع المنتجات، وتحديد الأسعار، وربط البيكسلات وإطلاق متجرك بالكامل في أقل من 5 دقائق بدون سطر كود واحد.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-5 text-right font-extrabold text-slate-900 flex items-center justify-between gap-4">
                        <span>كيف يعمل نظام الدفع (2 ج.م لكل أوردر)؟</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        في باقة الطلب المرنة، لا تدفع أي اشتراك شهري ثابت. تقوم فقط بشحن محفظتك داخل لوحة التحكم برصيد (عبر فودافون كاش أو إنستا باي)، وعندما يأتيك أوردر جديد وتقوم بفتحه ومعاينته يتم خصم 2 ج.م فقط من رصيدك.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-5 text-right font-extrabold text-slate-900 flex items-center justify-between gap-4">
                        <span>هل تأخذ المنصة أي عمولة من مبيعاتي؟</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        لا، نحن نطبق سياسة 0% عمولة على جميع المبيعات في كافة الباقات. جميع الأرباح التي تحققها تعود لك بالكامل بنسبة 100%.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full p-5 text-right font-extrabold text-slate-900 flex items-center justify-between gap-4">
                        <span>هل يمكنني ربط دومين مخصص (Custom Domain) بمتجري؟</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 4 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        نعم بالتأكيد! يمكنك ربط النطاق الخاص بك بسهولة، مع توفير وتفعيل شهادة أمان SSL مجانية لضمان حماية وسرعة متجرك.
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full p-5 text-right font-extrabold text-slate-900 flex items-center justify-between gap-4">
                        <span>ما هي طرق الدفع المتاحة لعملائي في المتجر؟</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="openFaq === 5 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        ندعم الدفع عند الاستلام (COD) وهو الأكثر طلباً في مصر، بالإضافة للتحويل اليدوي عبر فودافون كاش وإنستاباي مع إمكانية رفع صورة إيصال التحويل، والتواصل الفوري عبر الواتساب.
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 8. BOTTOM FINAL CTA BANNER                 -->
    <!-- ========================================== -->
    <section class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-r from-brand-600 via-indigo-600 to-indigo-700 p-8 sm:p-12 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 space-y-5 max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-4xl font-black tracking-tight">
                        جاهز لمضاعفة مبيعاتك وإطلاق متجرك اليوم؟
                    </h2>
                    <p class="text-sm sm:text-base text-indigo-100 font-medium leading-relaxed">
                        انضم الآن إلى أكثر من 1,500 تاجر ناجح في مصر وابدأ تجربتك المجانية لمدة 7 أيام بدون أي بطاقة ائتمان.
                    </p>
                    <div class="pt-2">
                        <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl text-base font-bold text-brand-700 bg-white hover:bg-slate-50 shadow-xl hover:shadow-2xl transition-all">
                            <i class="fa-solid fa-rocket text-amber-500"></i>
                            <span>أنشئ متجرك مجاناً في 30 ثانية</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
