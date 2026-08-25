@extends('layouts.platform')

@section('title', 'اتفاقية مستوى الخدمة (SLA) | أوردر سيف (Order Saif) - استقرار وضمان لأعمالك')

@section('meta_description', 'تعرف على التزامات منصة أوردر سيف بخصوص وقت تشغيل الخوادم (Uptime)، والتعويضات المستحقة، ومستويات الدعم الفني لضمان استمرارية متجرك الإلكتروني.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-12">
            <span class="px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold border border-indigo-200 inline-block uppercase tracking-wider mb-4">
                ⚡ اتفاقية الخدمة SLA
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                اتفاقية مستوى الخدمة وضمان وقت التشغيل
            </h1>
            <p class="text-slate-500 mt-3 font-medium text-sm">آخر تحديث: 2026</p>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-sm space-y-10 leading-relaxed text-slate-600">
            
            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    1. ضمان وقت تشغيل الخدمة (Uptime Guarantee)
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    تلتزم منصة أوردر سيف (Order Saif) بتوفير معدل وقت تشغيل خوادم وسيرفرات لا يقل عن <strong class="text-emerald-600 font-mono font-bold">99.9%</strong> على مدار الشهر الميلادي. يُقاس وقت التشغيل من خلال أنظمة المراقبة الخارجية المعتمدة لدينا، لضمان فتح صفحات متجرك لعملائك في أي وقت بدون انقطاع.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    2. سرعة الاستجابة وأداء الخوادم
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    نعتمد بنية تحتية سحابية متقدمة داخل جمهورية مصر العربية والشرق الأوسط، مما يضمن تحميل صفحات المنتجات والشراء في زمن قياسي يقل عن <strong class="text-brand-600 font-bold">ثانية واحدة</strong> في المتوسط لشبكات الجوال.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    3. مستويات الدعم الفني وسرعة الرد
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    فريق الدعم الفني متواجد عبر الواتساب والمحادثة المباشرة على مدار الساعة 24/7 للتعامل مع أي بلاغات تقنية أو استفسارات حول ضبط المتاجر والبيكسلز.
                </p>
            </div>

            <div class="space-y-3 pt-6 border-t border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">هل لديك استفسار حول اتفاقية مستوى الخدمة؟</h3>
                <p class="text-sm text-slate-500">
                    تواصل مباشرة مع فريقنا عبر البريد: <a href="mailto:support@ordersaif.com" class="text-brand-600 font-bold hover:underline font-mono">support@ordersaif.com</a> أو عبر الواتساب: <a href="https://wa.me/201066571999" target="_blank" class="text-emerald-600 font-bold font-mono">01066571999</a>
                </p>
            </div>

        </div>

    </div>
</section>
@endsection
