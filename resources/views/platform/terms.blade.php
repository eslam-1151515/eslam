@extends('layouts.platform')

@section('title', 'شروط الخدمة | فاست أوردر (Order Saif) - التزامات وقواعد واضحة')

@section('meta_description', 'اقرأ شروط وأحكام استخدام منصة فاست أوردر لبناء المتاجر الإلكترونية، وقواعد الاستخدام المقبول وحقوق الملكية الفكرية والالتزامات المالية.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-grid-pattern">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-16">
            <span class="px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold border border-amber-500/20 inline-block uppercase tracking-wider mb-4">
                📄 شروط الاستخدام
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                اتفاقية شروط الخدمة والاستخدام
            </h1>
            <p class="text-gray-400 mt-4">آخر تحديث: 8 يوليو 2026</p>
        </div>

        <div class="glass-card p-8 md:p-12 rounded-3xl border-white/5 space-y-10 leading-relaxed text-gray-300">
            
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    1. قبول الشروط والأحكام
                </h2>
                <p class="text-sm sm:text-base">
                    باستخدامك لمنصة فاست أوردر (Order Saif) أو تسجيل حساب فيها، فإنك توافق على الالتزام الكامل بشروط الخدمة هذه وسياسة الخصوصية الخاصة بنا. إذا كنت لا توافق على أي من هذه الشروط، فيرجى التوقف فوراً عن استخدام خدماتنا.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    2. شروط إنشاء الحساب
                </h2>
                <p class="text-sm sm:text-base">
                    عند إنشاء متجر أو حساب على منصتنا، يجب عليك تقديم معلومات دقيقة وكاملة وحديثة. أنت مسؤول مسؤولية كاملة عن الحفاظ على سرية بيانات اعتماد حسابك (كلمة المرور) وعن جميع الأنشطة التي تتم تحت حسابك. يجب إخطارنا فوراً بأي اختراق أمني أو استخدام غير مصرح به لمتجرك.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    3. الاستخدام المقبول والمحتوى المحظور
                </h2>
                <p class="text-sm sm:text-base">
                    يُحظر استخدام المنصة لعرض أو بيع أو ترويج أي من المواد أو الخدمات التالية:
                </p>
                <ul class="space-y-2 list-inside list-disc pl-4 text-sm sm:text-base">
                    <li>المنتجات غير القانونية أو المقرصنة أو التي تنتهك حقوق الملكية الفكرية للآخرين.</li>
                    <li>المواد المخدرة، الأسلحة، والمواد الخطرة أو التي تتطلب تراخيص أمنية خاصة غير متوفرة.</li>
                    <li>أي محتوى يحتوي على تشهير، أو تهديد، أو إساءة، أو ترويج للعنف والتمييز.</li>
                    <li>استخدام المنصة لإرسال رسائل عشوائية (Spam) أو محاولة اختراق خوادم المنصة.</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    4. الاشتراكات والمدفوعات
                </h2>
                <p class="text-sm sm:text-base">
                    تتوفر الخدمات عبر باقات اشتراك شهرية أو سنوية موضحة في صفحة الأسعار. يتم دفع رسوم الاشتراك مقدماً وبشكل دوري. يحق لك إلغاء اشتراكك في أي وقت، ولن يتم فرض أي شروط جزائية، ولكن لا يتم استرداد المبالغ المدفوعة عن الفترة المتبقية من دورة الفوترة الجارية.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    5. إخلاء المسؤولية وحدودها
                </h2>
                <p class="text-sm sm:text-base">
                    تُقدم المنصة "كما هي" وبدون أي ضمانات صريحة أو ضمنية بخصوص ملاءمتها لغرض معين. فاست أوردر غير مسؤولة عن أي خسائر مالية مباشرة أو غير مباشرة، أو فقدان بيانات، أو تعطل أعمال يواجهه التاجر أو عملائه نتيجة استخدام أو عدم القدرة على استخدام المنصة.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-500"></span>
                    6. الملكية الفكرية
                </h2>
                <p class="text-sm sm:text-base">
                    جميع الحقوق والملكيات الفكرية الخاصة بالمنصة وتصميمها وشفرتها البرمجية وعلامتها التجارية تنتمي حصرياً لشركة فاست أوردر. بالمقابل، يمتلك التاجر كامل الحقوق والملكية الفكرية للمحتوى والمنتجات والصور التي يرفعها على متجره الإلكتروني الخاص.
                </p>
            </div>

            <div class="space-y-4 pt-6 border-t border-white/10">
                <h3 class="text-lg font-bold text-white">هل لديك استفسارات قانونية حول الاتفاقية؟</h3>
                <p class="text-sm text-gray-400">
                    يسعدنا تواصلك مع القسم القانوني عبر البريد الإلكتروني: <a href="mailto:legal@OrderSaif.test" class="text-brand-400 font-mono underline">legal@OrderSaif.test</a>
                </p>
            </div>

        </div>

    </div>
</section>
@endsection
