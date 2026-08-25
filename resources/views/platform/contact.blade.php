@extends('layouts.platform')

@section('title', 'اتصل بنا | Order Saif - دعم فني متواصل 24/7')

@section('meta_description', 'تواصل مع فريق الدعم الفني أو المبيعات في Order Saif. نحن هنا لمساعدتك في الإجابة على استفساراتك وتوسيع نطاق مبيعات متجرك.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="px-4 py-1.5 rounded-full bg-brand-100 text-brand-700 text-xs font-bold inline-block uppercase tracking-wider">
                📞 تواصل معنا
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                يسعدنا دائماً سماع <span class="text-brand-600">استفساراتك وتواصلك</span>
            </h1>
            <p class="text-slate-600 leading-relaxed font-medium text-base sm:text-lg">
                هل لديك استفسار عن الباقات؟ أو بحاجة لمساعدة تقنية أو استشارة لمتجرك؟ فريقنا متاح على مدار الساعة لخدمتك عبر القنوات المباشرة التالية:
            </p>
        </div>

        <!-- 3 Direct Contact Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            
            <!-- WhatsApp Card -->
            <div class="bg-white p-8 rounded-3xl border border-emerald-200 shadow-sm text-center flex flex-col justify-between hover:shadow-md hover:border-emerald-400 transition-all">
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl mb-5">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">واتساب مباشر</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">
                        أسرع طريقة للتواصل مع خدمة العملاء والدعم الفني الفوري.
                    </p>
                    <div class="text-lg font-black text-slate-900 font-mono mb-6">
                        01066571999
                    </div>
                </div>
                <a href="https://wa.me/201066571999" target="_blank" class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>محادثة واتساب الآن</span>
                </a>
            </div>

            <!-- Phone Call Card -->
            <div class="bg-white p-8 rounded-3xl border border-brand-200 shadow-sm text-center flex flex-col justify-between hover:shadow-md hover:border-brand-400 transition-all">
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center mx-auto text-3xl mb-5">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">اتصال هاتفي</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">
                        متاح للرد على المكالمات الهاتفية والاستفسارات السريعة.
                    </p>
                    <div class="text-lg font-black text-slate-900 font-mono mb-6">
                        01066571999
                    </div>
                </div>
                <a href="tel:01066571999" class="w-full py-3.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-phone text-sm"></i>
                    <span>اتصل الآن مباشرة</span>
                </a>
            </div>

            <!-- Email Card -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition-all">
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mx-auto text-3xl mb-5">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">البريد الإلكتروني</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">
                        للمراسلات الرسمية والشراكات واستفسارات المنظومة.
                    </p>
                    <div class="text-sm font-black text-slate-900 font-mono mb-6 truncate px-1">
                        support@ordersaif.com
                    </div>
                </div>
                <a href="mailto:support@ordersaif.com" class="w-full py-3.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-envelope text-sm"></i>
                    <span>إرسال بريد إلكتروني</span>
                </a>
            </div>

        </div>

        <!-- 24/7 Availability Note -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white border border-slate-200 shadow-xs text-xs font-bold text-slate-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>فريق الدعم الفني متواجد على مدار الساعة 24/7 للرد الفوري على طلباتكم</span>
            </div>
        </div>

    </div>
</section>
@endsection
