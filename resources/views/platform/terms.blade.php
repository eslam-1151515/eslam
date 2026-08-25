@extends('layouts.platform')

@section('title', 'شروط الخدمة | أوردر سيف (Order Saif) - التزامات وقواعد واضحة')

@section('meta_description', 'اقرأ شروط وأحكام استخدام منصة أوردر سيف لبناء المتاجر الإلكترونية، وقواعد الاستخدام المقبول وحقوق الملكية الفكرية والالتزامات المالية.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-12">
            <span class="px-4 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold border border-amber-200 inline-block uppercase tracking-wider mb-4">
                📄 شروط الاستخدام
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                اتفاقية شروط الخدمة والاستخدام
            </h1>
            <p class="text-slate-500 mt-3 font-medium text-sm">آخر تحديث: 2026</p>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-sm space-y-10 leading-relaxed text-slate-600">
            
            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    1. قبول الشروط والأحكام
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    باستخدامك لمنصة أوردر سيف (Order Saif) أو تسجيل حساب فيها، فإنك توافق على الالتزام الكامل بشروط الخدمة هذه وسياسة الخصوصية الخاصة بنا. إذا كنت لا توافق على أي من هذه الشروط، فيرجى التوقف فوراً عن استخدام خدماتنا.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    2. شروط إنشاء الحساب
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    عند إنشاء متجر أو حساب على منصتنا، يجب عليك تقديم معلومات دقيقة وكاملة وحديثة. أنت مسؤول مسؤولية كاملة عن الحفاظ على سرية بيانات اعتماد حسابك (كلمة المرور) وعن جميع الأنشطة التي تتم تحت حسابك. يجب إخطارنا فوراً بأي اختراق أمني أو استخدام غير مصرح به لمتجرك.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    3. الاستخدام المقبول والمحتوى المحظور
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    يُحظر استخدام المنصة لعرض أو بيع أو ترويج أي من المواد أو الخدمات التالية:
                </p>
                <ul class="space-y-2 list-inside list-disc pl-4 text-sm sm:text-base text-slate-700">
                    <li>المنتجات غير القانونية أو المقرصنة أو التي تنتهك حقوق الملكية الفكرية للآخرين.</li>
                    <li>المواد المخدرة، الأسلحة، والمواد الخطرة أو التي تتطلب تراخيص أمنية خاصة غير متوفرة.</li>
                    <li>أي محتوى يحتوي على تشهير، أو تهديد، أو إساءة، أو ترويج للعنف والتمييز.</li>
                    <li>استخدام المنصة لإرسال رسائل عشوائية (Spam) أو محاولة اختراق خوادم المنصة.</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    4. الاشتراكات والمدفوعات
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    تتوفر الخدمات عبر باقات اشتراك شهرية أو نظام شحن المحفظة (2ج لكل أوردر) أو التجربة المجانية الموضحة في صفحة الأسعار. يتم دفع رسوم الاشتراك أو شحن الرصيد مقدماً عبر وسائل الدفع المعتمدة (فودافون كاش، إنستاباي، والتحويل البنكي).
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    5. إخلاء المسؤولية وحدودها
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    تُقدم المنصة خدماتها بأعلى مستويات الاستقرار والأمان. أوردر سيف توفر البنية التحتية التقنية للمتاجر وتسهل استقبال الطلبات وإدارتها، ولكن التاجر يتحمل المسؤولية القانونية الكاملة عن جودة منتجاته وعمليات شحنها لعملائه النهائيين.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    6. الملكية الفكرية
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    جميع الحقوق والملكيات الفكرية الخاصة بالمنصة وتصميمها وشفرتها البرمجية وعلامتها التجارية تنتمي حصرياً لـ Order Saif. بالمقابل، يمتلك التاجر كامل الحقوق والملكية الفكرية للمحتوى والمنتجات والصور التي يرفعها على متجره الإلكتروني الخاص.
                </p>
            </div>

            <div class="space-y-3 pt-6 border-t border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">هل لديك استفسارات قانونية حول الاتفاقية؟</h3>
                <p class="text-sm text-slate-500">
                    يسعدنا تواصلك مع القسم القانوني أو الدعم الفني عبر البريد: <a href="mailto:support@ordersaif.com" class="text-brand-600 font-bold hover:underline font-mono">support@ordersaif.com</a> أو هاتفياً: <a href="tel:01066571999" class="text-slate-900 font-bold font-mono">01066571999</a>
                </p>
            </div>

        </div>

    </div>
</section>
@endsection
