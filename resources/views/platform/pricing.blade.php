@extends('layouts.platform')

@section('title', 'الباقات والأسعار | Order Saif - خطط مرنة وتجربة مجانية 7 أيام')

@section('meta_description', 'اختر الباقة المناسبة لمتجرك الإلكتروني مع Order Saif. أسعار مرنة تبدأ من 2ج للأوردر، باقات شهرية وسنوية، 0% عمولة على المبيعات، وتجربة مجانية لمدة 7 أيام.')

@section('content')
<div class="overflow-hidden bg-slate-50" x-data="{ billing: 'yearly', activeFaq: null }">

    <!-- Hero Header -->
    <section class="py-16 md:py-24 relative overflow-hidden bg-white border-b border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-100 border border-brand-200 text-brand-700 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-sparkles text-amber-500"></i> 0% عمولة على مبيعاتك • تجربة مجانية 7 أيام
            </div>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight">
                باقات وأسعار مصممة <br class="hidden sm:block"> 
                <span class="text-brand-600">لنمو متجرك الإلكتروني بسرعة</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-600 font-medium max-w-2xl mx-auto leading-relaxed">
                اختر الباقة المناسبة لحجم تجارتك وابدأ البيع خلال دقائق. جميع الباقات تشمل تجربة مجانية بدون بطاقة ائتمانية وبدون أي رسوم خفية.
            </p>

            <!-- Monthly / Annual Toggle Button -->
            <div class="inline-flex items-center justify-center p-1.5 rounded-2xl bg-slate-100 border border-slate-200 shadow-xs max-w-md mx-auto">
                <button 
                    @click="billing = 'monthly'" 
                    :class="billing === 'monthly' ? 'bg-white text-brand-600 shadow-sm font-bold border border-slate-200' : 'text-slate-500 hover:text-slate-900 font-semibold'"
                    class="px-6 py-2.5 rounded-xl text-sm transition-all duration-200">
                    دفع شهري
                </button>
                <button 
                    @click="billing = 'yearly'" 
                    :class="billing === 'yearly' ? 'bg-brand-600 text-white shadow-sm font-bold' : 'text-slate-500 hover:text-slate-900 font-semibold'"
                    class="px-6 py-2.5 rounded-xl text-sm transition-all duration-200 flex items-center gap-2">
                    <span>دفع سنوي</span>
                    <span class="px-2 py-0.5 text-[10px] font-black bg-amber-400 text-slate-950 rounded-full">
                        وفر 17% 🎁
                    </span>
                </button>
            </div>
            <p class="text-xs text-slate-500 font-medium">
                * عند اختيار الدفع السنوي تحصل على شهرين مجاناً في جميع الباقات
            </p>
        </div>
    </section>

    <!-- Pricing Cards Section -->
    <section id="pricing-cards" class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10 w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @foreach($plans as $plan)
                @php
                    $isPopular = $plan['is_popular'] ?? false;
                    $name = $plan['name'] ?? '';
                    $description = $plan['description'] ?? '';
                    $priceMonthly = $plan['price_monthly'] ?? 0;
                    $priceYearly = $plan['price_yearly'] ?? 0;
                    $trialDays = $plan['trial_days'] ?? 7;
                    $limits = $plan['limits'] ?? [];
                    $features = $limits['features'] ?? [];
                    $maxProducts = $limits['max_products'] ?? null;
                    $maxOrders = $limits['max_orders'] ?? null;
                @endphp

                <div class="rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 relative {{ $isPopular ? 'bg-white border-2 border-brand-500 shadow-xl shadow-brand-500/10 scale-100 md:-translate-y-2' : 'bg-white border border-slate-200 shadow-xs hover:border-slate-300' }}">
                    
                    @if($isPopular)
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-brand-600 text-white text-xs font-black px-4 py-1 rounded-full shadow-md">
                            ⭐ الأكثر اختياراً وقيمة
                        </div>
                    @endif

                    <div>
                        <!-- Plan Header -->
                        <div class="mb-6">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $name }}</h3>
                            <p class="text-slate-500 text-xs leading-relaxed min-h-[36px]">{{ $description }}</p>
                        </div>

                        <!-- Price Box -->
                        <div class="mb-6 pb-6 border-b border-slate-100">
                            <!-- Monthly Price Display -->
                            <div x-show="billing === 'monthly'" class="flex items-baseline gap-1.5">
                                <span class="text-4xl sm:text-5xl font-black text-slate-900 font-mono">{{ number_format($priceMonthly) }}</span>
                                <span class="text-slate-500 text-sm font-bold">ج.م / شهرياً</span>
                            </div>

                            <!-- Yearly Price Display -->
                            <div x-show="billing === 'yearly'" style="display: none;" class="space-y-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-4xl sm:text-5xl font-black text-slate-900 font-mono">{{ number_format($priceYearly / 12, 0) }}</span>
                                    <span class="text-slate-500 text-sm font-bold">ج.م / شهرياً</span>
                                </div>
                                <div class="text-xs text-emerald-600 font-bold">
                                    تُدفع {{ number_format($priceYearly) }} ج.م سنوياً (خصم شهرين)
                                </div>
                            </div>

                            <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                <i class="fa-solid fa-gift text-emerald-500"></i>
                                <span>تجربة مجانية {{ $trialDays }} أيام + 100 ج.م رصيد هدية في المحفظة</span>
                            </div>
                        </div>

                        <!-- Plan Limits Highlights -->
                        <div class="grid grid-cols-2 gap-2 mb-6 text-center">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="text-[11px] text-slate-500 font-medium">المنتجات</div>
                                <div class="font-bold text-slate-900 text-sm mt-0.5">
                                    {{ $maxProducts && $maxProducts < 9999 ? $maxProducts . ' منتج' : 'غير محدود 🚀' }}
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="text-[11px] text-slate-500 font-medium">الطلبات</div>
                                <div class="font-bold text-slate-900 text-sm mt-0.5">
                                    {{ $maxOrders && $maxOrders < 9999 ? $maxOrders . ' أوردر/شهر' : 'غير محدود 🚀' }}
                                </div>
                            </div>
                        </div>

                        <!-- Plan Features List -->
                        <div class="space-y-3 mb-8">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">المميزات المشمولة:</div>
                            @foreach($features as $feature)
                                <div class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-700">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-4">
                        <a href="{{ Route::has('register') ? route('register') : url('/register') }}" 
                           class="w-full py-3.5 px-6 rounded-xl font-bold text-sm text-center flex items-center justify-center gap-2 transition-all {{ $isPopular ? 'bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/25' : 'bg-slate-100 hover:bg-slate-200 text-slate-800' }}">
                            <span>ابدأ التجربة المجانية ({{ $trialDays }} أيام)</span>
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </section>

    <!-- Detailed Feature Comparison Table Section -->
    @if(isset($comparisonCategories) && count($comparisonCategories) > 0)
    <section id="comparison" class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">مقارنة تفصيلية بين جميع الباقات</h2>
            <p class="text-slate-600 mt-3 font-medium text-sm">تعرف على كل التفاصيل والخصائص المتاحة في كل باقة بدقة وشفافية</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-5 font-black text-slate-900 text-base w-1/3">الميزة / الخاصية</th>
                            @foreach($plans as $plan)
                                <th class="p-5 font-black text-center text-base {{ ($plan['is_popular'] ?? false) ? 'text-brand-600 bg-brand-50/50' : 'text-slate-900' }}">
                                    {{ $plan['name'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($comparisonCategories as $categoryName => $catFeatures)
                            <!-- Category Header Row -->
                            <tr class="bg-slate-50/80">
                                <td colspan="{{ count($plans) + 1 }}" class="py-3 px-5 font-black text-xs text-slate-500 uppercase tracking-wider">
                                    {{ $categoryName }}
                                </td>
                            </tr>
                            
                            @foreach($catFeatures as $featureName => $featureAvailability)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 font-semibold text-slate-700 pr-8">
                                        {{ $featureName }}
                                    </td>
                                    @foreach($plans as $plan)
                                        @php
                                            $slug = $plan['slug'] ?? 'basic';
                                            $val = $featureAvailability[$slug] ?? false;
                                        @endphp
                                        <td class="p-4 text-center {{ ($plan['is_popular'] ?? false) ? 'bg-brand-50/20 font-bold' : '' }}">
                                            @if(is_bool($val))
                                                @if($val)
                                                    <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-xmark text-slate-300 text-base"></i>
                                                @endif
                                            @else
                                                <span class="font-bold text-slate-800 text-xs">{{ $val }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    <!-- FAQ Section -->
    <section id="faq" class="py-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">الأسئلة الشائعة حول الباقات</h2>
            <p class="text-slate-600 mt-2 font-medium text-sm">كل ما تحتاج لمعرفته قبل بدء الاشتراك</p>
        </div>

        <div class="space-y-4">
            @foreach($faqs ?? [] as $index => $faq)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button 
                        @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" 
                        class="w-full p-5 text-right flex items-center justify-between font-bold text-slate-900 hover:bg-slate-50 transition-all">
                        <span class="text-sm sm:text-base">{{ $faq['question'] }}</span>
                        <i class="fa-solid text-xs text-brand-600 transition-transform duration-300" :class="activeFaq === {{ $index }} ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="activeFaq === {{ $index }}" x-collapse class="p-5 border-t border-slate-100 text-slate-600 text-sm leading-relaxed bg-slate-50/50">
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Help Contact Box -->
        <div class="mt-12 p-8 rounded-3xl bg-white border border-slate-200 shadow-sm text-center relative overflow-hidden">
            <h3 class="text-xl font-bold text-slate-900 mb-2">هل لديك سؤال آخر لم تجد إجابته هنا؟</h3>
            <p class="text-slate-600 text-sm mb-6 max-w-xl mx-auto font-medium">
                فريق الدعم الفني لدينا متاح على مدار الساعة للإجابة على جميع استفساراتك ومساعدتك في اختيار الباقة الأنسب لمتجرك.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="https://wa.me/201066571999" target="_blank" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i> تواصل معنا عبر الواتساب (01066571999)
                </a>
                <a href="mailto:support@ordersaif.com" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm flex items-center gap-2 transition-all border border-slate-200">
                    <i class="fa-solid fa-envelope text-brand-600"></i> راسلنا عبر البريد
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
