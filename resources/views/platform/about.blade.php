@extends('layouts.platform')

@section('title', 'من نحن | فاست أوردر (Fast Order) - ثورة في التجارة الإلكترونية العربية')

@section('meta_description', 'تعرف على قصة فاست أوردر ورؤيتنا لتمكين ملايين التجار والشركات الناشئة في العالم العربي من إطلاق متاجرهم الإلكترونية الفائقة السرعة وبدون عمولات.')

@section('content')
<!-- About Hero Section -->
<section class="py-16 md:py-24 relative overflow-hidden bg-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="px-4 py-1.5 rounded-full bg-brand-500/10 text-brand-400 text-xs font-bold border border-brand-500/20 inline-block uppercase tracking-wider mb-6">
            ✨ قصتنا ورؤيتنا
        </span>
        <h1 class="text-4xl md:text-6xl font-black tracking-tight text-white mb-6">
            نمكّن التجارة الإلكترونية <br>
            <span class="text-gradient-primary">بسرعة خارقة وعمولة 0%</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
            بدأت فاست أوردر كفكرة بسيطة: لماذا يجب على التاجر العربي أن يعاني من تعقيدات البرمجة وعمولات المنصات المرتفعة؟ لقد أعدنا تعريف التجارة الإلكترونية لنمنحك السيطرة الكاملة على عملك.
        </p>
    </div>
</section>

<!-- Stats Grid -->
<section class="py-12 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass-card p-6 sm:p-8 rounded-2xl text-center border-white/5">
                <div class="text-3xl sm:text-4xl font-extrabold text-brand-400 mb-2 font-mono">+1,500</div>
                <div class="text-sm font-semibold text-gray-400">متجر إلكتروني نشط</div>
            </div>
            <div class="glass-card p-6 sm:p-8 rounded-2xl text-center border-white/5">
                <div class="text-3xl sm:text-4xl font-extrabold text-pink-400 mb-2 font-mono">+50,000</div>
                <div class="text-sm font-semibold text-gray-400">طلب يومي معالج</div>
            </div>
            <div class="glass-card p-6 sm:p-8 rounded-2xl text-center border-white/5">
                <div class="text-3xl sm:text-4xl font-extrabold text-emerald-400 mb-2 font-mono">99.9%</div>
                <div class="text-sm font-semibold text-gray-400">معدل تشغيل السيرفرات</div>
            </div>
            <div class="glass-card p-6 sm:p-8 rounded-2xl text-center border-white/5">
                <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-2 font-mono">+15M EGP</div>
                <div class="text-sm font-semibold text-gray-400">مبيعات التجار الشهرية</div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="py-16 md:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white">القيم التي تحركنا كل يوم</h2>
            <p class="text-gray-400 mt-4">نحن لا نبني برمجيات فقط، بل نساعدك في بناء وتوسيع إمبراطوريتك التجارية.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="glass-card p-8 rounded-2xl border-white/5 relative overflow-hidden group hover:border-brand-500/30 transition-all">
                <div class="w-12 h-12 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-bolt text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">السرعة الفائقة أولاً</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    نؤمن أن سرعة تحميل متجرك هي العامل الأهم لنجاحك. نستخدم تقنيات حديثة وكاش ذكي لنوفر أسرع تجربة تسوق لعملائك على الإطلاق.
                </p>
            </div>

            <!-- Value 2 -->
            <div class="glass-card p-8 rounded-2xl border-white/5 relative overflow-hidden group hover:border-pink-500/30 transition-all">
                <div class="w-12 h-12 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-400 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-handshake-angle text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">شراكة حقيقية (0% عمولة)</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    أرباحك ملكك بالكامل. نحن لا نفرض أي نسب مقتطعة على مبيعاتك في أي باقة من باقاتنا. نجاحك المالي هو نجاحنا الأكبر.
                </p>
            </div>

            <!-- Value 3 -->
            <div class="glass-card p-8 rounded-2xl border-white/5 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-6">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">دعم فني استباقي</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    فريق دعم فني متكامل متواجد على مدار الساعة لخدمتك ومساعدتك في تذليل أي صعوبات تقنية أو تسويقية تواجه عملك.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-16 bg-white/5 border-y border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-black text-white">رؤيتنا للمستقبل</h2>
                <p class="text-gray-300 leading-relaxed">
                    نسعى لأن نكون البنية التحتية الأساسية لأي نشاط تجاري في الوطن العربي. من خلال إزالة العوائق التقنية والمالية، نفتح الباب لكل صاحب فكرة أو علامة تجارية محلية للوصول لعملائها والنمو بمرونة وحرية تامة.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-check-double text-brand-400 mt-1"></i>
                        <div>
                            <h4 class="font-bold text-white">تطوير مستمر للأدوات</h4>
                            <p class="text-xs text-gray-400">إضافة مميزات وربط بكسل وتطوير دوري مجاني وتحديثات فورية للتطبيقات.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-check-double text-pink-400 mt-1"></i>
                        <div>
                            <h4 class="font-bold text-white">توفير بيئة أمان متكاملة</h4>
                            <p class="text-xs text-gray-400">تشفير وحماية البيانات بنسبة 100% مع نسخ احتياطي دوري لمتجرك.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative flex justify-center">
                <!-- Visual Block Representing Technology Stack -->
                <div class="glass-card p-8 rounded-3xl border-brand-500/20 max-w-md w-full shadow-2xl relative">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-brand-600 flex items-center justify-center text-white mx-auto shadow-lg shadow-brand-500/30 mb-4">
                            <i class="fa-solid fa-cubes text-2xl"></i>
                        </div>
                        <h4 class="font-black text-lg text-white">منصة فاست أوردر السحابية</h4>
                        <p class="text-xs text-gray-400 mt-1">نظام تشغيل متكامل للتجارة الإلكترونية</p>
                    </div>
                    <ul class="space-y-3.5 text-sm">
                        <li class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                            <span class="text-gray-300">أداء السيرفرات</span>
                            <span class="font-bold text-emerald-400 font-mono">متجاوب فائق</span>
                        </li>
                        <li class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                            <span class="text-gray-300">قواعد البيانات</span>
                            <span class="font-bold text-brand-400 font-mono">معزولة وآمنة</span>
                        </li>
                        <li class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                            <span class="text-gray-300">نظام Tenancy</span>
                            <span class="font-bold text-pink-400 font-mono">مستقل بالكامل</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 text-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h2 class="text-3xl font-black text-white mb-6">انضم إلينا وابدأ قصة نجاحك اليوم</h2>
        <p class="text-gray-400 max-w-2xl mx-auto mb-8">استمتع بـ 14 يوماً كاملة من التجربة المجانية لتتعرف على أداء المنصة الممتاز.</p>
        <a href="{{ route('main.home') }}#pricing" class="px-8 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5 transition-all">
            <i class="fa-solid fa-rocket ml-2"></i> ابدأ تجربتك المجانية
        </a>
    </div>
</section>
@endsection
