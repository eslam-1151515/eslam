@extends('layouts.platform')

@section('title', 'من نحن | Order Saif - ثورة في التجارة الإلكترونية العربية')

@section('meta_description', 'تعرف على قصة أوردر سيف ورؤيتنا لتمكين ملايين التجار والشركات الناشئة في العالم العربي من إطلاق متاجرهم الإلكترونية الفائقة السرعة وبدون عمولات.')

@section('content')
<div class="overflow-hidden bg-slate-50">
    <!-- About Hero Section -->
    <section class="py-16 md:py-24 relative overflow-hidden bg-white border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <span class="px-4 py-1.5 rounded-full bg-brand-100 text-brand-700 text-xs font-bold border border-brand-200 inline-block uppercase tracking-wider">
                ✨ قصتنا ورؤيتنا
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                نمكّن التجارة الإلكترونية <br>
                <span class="text-brand-600">بسرعة خارقة وعمولة 0%</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-600 max-w-3xl mx-auto leading-relaxed font-medium">
                بدأت Order Saif كفكرة بسيطة: لماذا يجب على التاجر العربي أن يعاني من تعقيدات البرمجة وعمولات المنصات المرتفعة؟ لقد أعدنا تعريف التجارة الإلكترونية لنمنحك السيطرة الكاملة على عملك.
            </p>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="py-12 relative bg-slate-50 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 sm:p-8 rounded-2xl text-center border border-slate-200 shadow-xs">
                    <div class="text-3xl sm:text-4xl font-black text-brand-600 mb-2 font-mono">+1,500</div>
                    <div class="text-sm font-bold text-slate-600">متجر إلكتروني نشط</div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl text-center border border-slate-200 shadow-xs">
                    <div class="text-3xl sm:text-4xl font-black text-indigo-600 mb-2 font-mono">+50,000</div>
                    <div class="text-sm font-bold text-slate-600">طلب يومي معالج</div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl text-center border border-slate-200 shadow-xs">
                    <div class="text-3xl sm:text-4xl font-black text-emerald-600 mb-2 font-mono">99.9%</div>
                    <div class="text-sm font-bold text-slate-600">معدل تشغيل السيرفرات</div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl text-center border border-slate-200 shadow-xs">
                    <div class="text-3xl sm:text-4xl font-black text-amber-600 mb-2 font-mono">+15M EGP</div>
                    <div class="text-sm font-bold text-slate-600">مبيعات التجار الشهرية</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-16 md:py-24 relative bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">القيم التي تحركنا كل يوم</h2>
                <p class="text-slate-600 mt-4 font-medium">نحن لا نبني برمجيات فقط، بل نساعدك في بناء وتوسيع إمبراطوريتك التجارية.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-brand-500/50 hover:bg-white transition-all shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-bolt text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">السرعة الفائقة أولاً</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        نؤمن أن سرعة تحميل متجرك هي العامل الأهم لنجاحك. نستخدم كاش ذكي وتقنيات حديثة لنوفر أسرع تجربة تسوق لعملائك على الإطلاق.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-indigo-500/50 hover:bg-white transition-all shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-handshake-angle text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">شراكة حقيقية (0% عمولة)</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        أرباحك ملكك بالكامل. نحن لا نفرض أي نسب مقتطعة على مبيعاتك في أي باقة من باقاتنا. نجاحك المالي هو نجاحنا الأكبر.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-emerald-500/50 hover:bg-white transition-all shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-headset text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">دعم فني استباقي</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        فريق دعم فني متكامل متواجد على مدار الساعة لخدمتك ومساعدتك في تذليل أي صعوبات تقنية أو تسويقية تواجه عملك.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Technology Section -->
    <section class="py-16 md:py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl font-black text-slate-900">رؤيتنا للمستقبل</h2>
                    <p class="text-slate-600 leading-relaxed font-medium">
                        نسعى لأن نكون البنية التحتية الأساسية لأي نشاط تجاري في الوطن العربي. من خلال إزالة العوائق التقنية والمالية، نفتح الباب لكل صاحب فكرة أو علامة تجارية محلية للوصول لعملائها والنمو بمرونة وحرية تامة.
                    </p>
                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-slate-200">
                            <i class="fa-solid fa-circle-check text-brand-600 mt-1 text-lg"></i>
                            <div>
                                <h4 class="font-bold text-slate-900">تطوير مستمر للأدوات</h4>
                                <p class="text-xs text-slate-500 mt-0.5">إضافة مميزات وربط بيكسل وتطوير دوري مجاني وتحديثات فورية للتطبيقات.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-slate-200">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-1 text-lg"></i>
                            <div>
                                <h4 class="font-bold text-slate-900">توفير بيئة أمان متكاملة</h4>
                                <p class="text-xs text-slate-500 mt-0.5">تشفير وحماية البيانات بنسبة 100% مع نسخ احتياطي دوري لمتجرك.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative flex justify-center">
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 max-w-md w-full shadow-lg relative">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-brand-100 flex items-center justify-center text-brand-600 mx-auto mb-4 text-2xl">
                                <i class="fa-solid fa-cubes"></i>
                            </div>
                            <h4 class="font-black text-xl text-slate-900">منصة Order Saif السحابية</h4>
                            <p class="text-xs text-slate-500 mt-1">نظام تشغيل متكامل للتجارة الإلكترونية</p>
                        </div>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-600 font-semibold">أداء السيرفرات</span>
                                <span class="font-bold text-emerald-600 font-mono">متجاوب فائق (0.8s)</span>
                            </li>
                            <li class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-600 font-semibold">قواعد البيانات</span>
                                <span class="font-bold text-brand-600 font-mono">معزولة وآمنة</span>
                            </li>
                            <li class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-slate-600 font-semibold">نظام المتاجر</span>
                                <span class="font-bold text-indigo-600 font-mono">مستقل بالكامل</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 text-center bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
            <h2 class="text-3xl font-black text-slate-900">انضم إلينا وابدأ قصة نجاحك اليوم</h2>
            <p class="text-slate-600 max-w-2xl mx-auto font-medium">استمتع بـ 7 أيام كاملة من التجربة المجانية لتتعرف على أداء المنصة الممتاز.</p>
            <div class="pt-2">
                <a href="{{ Route::has('register') ? route('register') : url('/register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/25 transition-all">
                    <i class="fa-solid fa-rocket text-amber-300"></i>
                    <span>ابدأ تجربتك المجانية</span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
