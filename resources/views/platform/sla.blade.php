@extends('layouts.platform')

@section('title', 'اتفاقية مستوى الخدمة (SLA) | فاست أوردر (Fast Order) - استقرار وضمان لأعمالك')

@section('meta_description', 'تعرف على التزامات منصة فاست أوردر بخصوص وقت تشغيل الخوادم (Uptime)، والتعويضات المستحقة، ومستويات الدعم الفني لضمان استمرارية متجرك الإلكتروني.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-grid-pattern">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-16">
            <span class="px-4 py-1.5 rounded-full bg-indigo-500/10 text-indigo-400 text-xs font-bold border border-indigo-500/20 inline-block uppercase tracking-wider mb-4">
                ⚡ اتفاقية الخدمة SLA
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                اتفاقية مستوى الخدمة وضمان وقت التشغيل
            </h1>
            <p class="text-gray-400 mt-4">آخر تحديث: 8 يوليو 2026</p>
        </div>

        <div class="glass-card p-8 md:p-12 rounded-3xl border-white/5 space-y-10 leading-relaxed text-gray-300">
            
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    1. ضمان وقت تشغيل الخدمة (Uptime Guarantee)
                </h2>
                <p class="text-sm sm:text-base">
                    تلتزم منصة فاست أوردر (Fast Order) بتوفير معدل وقت تشغيل خوادم وسيرفرات لا يقل عن <strong class="text-emerald-400 font-mono">99.9%</strong> على مدار الشهر الميلادي. يُقاس وقت التشغيل من خلال أنظمة المراقبة الخارجية المعتمدة لدينا، ولا يشمل فترات الصيانة المجدولة مسبقاً.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    2. أرصدة التعويض المالي (Service Credits)
                </h2>
                <p class="text-sm sm:text-base">
                    في حال انخفاض معدل التشغيل الفعلي خلال أي شهر عن النسبة المضمونة، يحق للتاجر المشترك في الباقات المدفوعة الحصول على رصيد خدمة مجاني يُخصم من فاتورة الشهر التالي وفق الجدول التالي:
                </p>
                <div class="overflow-x-auto pt-2">
                    <table class="w-full text-left text-sm text-gray-400 border border-white/10 rounded-xl overflow-hidden">
                        <thead class="bg-white/5 text-gray-200 text-right">
                            <tr>
                                <th class="p-3">معدل التشغيل الشهري الفعلي</th>
                                <th class="p-3">نسبة التعويض المالي (رصيد الخدمة)</th>
                            </tr>
                        </thead>
                        <tbody class="text-right">
                            <tr class="border-b border-white/5 hover:bg-white/5">
                                <td class="p-3 font-mono">99.0% إلى 99.89%</td>
                                <td class="p-3">10% من قيمة الاشتراك الشهري</td>
                            </tr>
                            <tr class="border-b border-white/5 hover:bg-white/5">
                                <td class="p-3 font-mono">95.0% إلى 98.99%</td>
                                <td class="p-3">25% من قيمة الاشتراك الشهري</td>
                            </tr>
                            <tr class="hover:bg-white/5">
                                <td class="p-3 font-mono">أقل من 95.0%</td>
                                <td class="p-3">50% من قيمة الاشتراك الشهري</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    3. مستويات وأوقات الاستجابة للدعم الفني
                </h2>
                <p class="text-sm sm:text-base">
                    نصنف طلبات الدعم الفني ونلتزم بأوقات استجابة محددة وفق الأولوية التالية:
                </p>
                <ul class="space-y-3 list-inside list-disc pl-4 text-sm sm:text-base">
                    <li><strong class="text-rose-400">أولوية قصوى (حالة تعطل المتجر بالكامل):</strong> الاستجابة خلال <strong class="text-white">ساعة واحدة</strong> والعمل فوراً على الإصلاح.</li>
                    <li><strong class="text-amber-400">أولوية متوسطة (مشكلة في خاصية فرعية):</strong> الاستجابة خلال <strong class="text-white">4 ساعات</strong> وحل المشكلة في نفس اليوم.</li>
                    <li><strong class="text-indigo-400">أولوية عادية (استفسارات عامة أو طلب مساعدة إعدادات):</strong> الاستجابة خلال <strong class="text-white">12 إلى 24 ساعة</strong>.</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    4. الصيانة المجدولة (Scheduled Maintenance)
                </h2>
                <p class="text-sm sm:text-base">
                    نقوم بإجراء صيانة دورية وتحديثات أمان للأنظمة والخوادم لضمان تقديم أفضل أداء. يتم التخطيط لهذه الصيانة مسبقاً وتُجرى في أوقات انخفاض حركة الطلبات (عادة بين الساعة 2 صباحاً إلى 5 صباحاً بتوقيت القاهرة). نلتزم بإرسال تنبيه للتجار قبل 24 ساعة على الأقل من بدء الصيانة.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    5. الاستثناءات من الضمان
                </h2>
                <p class="text-sm sm:text-base">
                    لا ينطبق ضمان وقت تشغيل الخدمة في حالات القوة القاهرة، والتي تشمل الهجمات الإلكترونية واسعة النطاق (DDoS) الخارجة عن الإرادة، أو انقطاع شبكة الإنترنت العالمية، أو المشاكل الناتجة عن إعدادات خاطئة قام بها التاجر نفسه (مثل التعديل الخاطئ لإعدادات DNS للنطاق المخصص).
                </p>
            </div>

            <div class="space-y-4 pt-6 border-t border-white/10">
                <h3 class="text-lg font-bold text-white">هل ترغب في طلب رصيد خدمة تعويضي؟</h3>
                <p class="text-sm text-gray-400">
                    يمكنك إرسال تذكرة دعم فني تحتوي على تفاصيل وقت تعطل الخدمة والأدلة الخاصة بك عبر البريد: <a href="mailto:sla@fastorder.test" class="text-brand-400 font-mono underline">sla@fastorder.test</a>
                </p>
            </div>

        </div>

    </div>
</section>
@endsection
