@extends('layouts.platform')

@section('title', 'سياسة الاسترجاع والإلغاء | أوردر سيف (Order Saif) - ضوابط واضحة وشفافة')

@section('meta_description', 'تعرف على سياسة الاسترجاع والإلغاء لمنصة أوردر سيف (Order Saif) بخصوص شحن محفظة المتاجر وباقات الاشتراك الرقمية، وضوابط استمرارية الخدمة وحالات المعالجة التقنية.')

@section('content')
<section class="py-16 md:py-24 relative overflow-hidden bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-12">
            <span class="px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 inline-block uppercase tracking-wider mb-4">
                🔄 سياسة الاسترجاع والإلغاء
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">
                سياسة الاسترجاع والإلغاء والخدمة الرقمية
            </h1>
            <p class="text-slate-500 mt-3 font-medium text-sm">آخر تحديث: نوفمبر 2026 • العملة المعتمدة: الجنيه المصري (EGP)</p>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-sm space-y-10 leading-relaxed text-slate-600">

            <!-- Highlight Summary Box -->
            <div class="p-6 rounded-2xl bg-brand-50/70 border border-brand-200 space-y-2">
                <div class="flex items-center gap-2 text-brand-900 font-extrabold text-base">
                    <i class="fa-solid fa-circle-info text-brand-600"></i>
                    <span>ملخص السياسة بوضوح</span>
                </div>
                <p class="text-sm text-brand-900/90 leading-relaxed font-medium">
                    تقدم منصة <strong>أوردر سيف (Order Saif)</strong> خدمات برمجية وسحابية رقمية (SaaS) لإنشاء وإدارة المتاجر الإلكترونية. نظراً لطبيعة الخدمة الفورية، فإن مبالغ <strong>شحن محفظة المتجر</strong> و<strong>الاشتراكات المفعّلة</strong> غير قابلة للاسترجاع النقدي بعد إضافتها، ولكن <strong>يظل متجرك نشطاً وشغالاً معك ويستمر رصيدك المتاح في المحفظة دون انتهاء صلاحية لاستخدامه وفق الباقة المختارة</strong>، مع الالتزام الكامل برد أي مبالغ تخصم نتيجة خطأ تقني أو خصم مزدوج عبر نفس وسيلة الدفع الأصلية.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    1. طبيعة الخدمات المقدمة
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    منصة أوردر سيف (Order Saif) هي منصة سحابية رقمية (Software as a Service) تتيح للتجار إنشاء وإدارة متاجرهم الإلكترونية، وتوفر باقات اشتراك وخدمات شحن رصيد المحفظة الإلكترونية داخل لوحة تحكم التاجر (يُستخدم الرصيد في فتح ومعاينة الطلبات بتكلفة 2 ج.م للطلب، أو في خدمات الإشعارات والتأكيد التلقائي عبر الواتساب بتكلفة 1 ج.م للرسالة، أو لتفعيل الباقة الشهرية/السنوية المختارة).
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    2. سياسة شحن رصيد المحفظة (عدم الاسترجاع واستمرارية التشغيل)
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    عند قيام التاجر بشحن محفظة متجره عبر بوابة الدفع الإلكتروني (البطاقات البنكية أو المحافظ الإلكترونية) أو التحويل المعتمد، تنطبق القواعد التالية:
                </p>
                <ul class="space-y-3 list-inside list-disc pl-4 text-sm sm:text-base text-slate-700">
                    <li><strong class="text-slate-900 font-bold">عدم الاسترجاع النقدي بعد الشحن:</strong> بمجرد إتمام الدفع وإضافة الرصيد إلكترونياً إلى محفظة المتجر، لا يكون المبلغ المشحون قابلاً للاسترداد أو السحب النقدي، لكونه رصيد خدمة رقمية فورية مخصصة لتشغيل وإدارة خدمات المتجر على المنصة.</li>
                    <li><strong class="text-slate-900 font-bold">بقاء المتجر شغالاً والرصيد فعالاً:</strong> لا يتوقف المتجر ولا يسقط حق التاجر في الرصيد المشحون؛ حيث يظل المتجر متاحاً وشغالاً مع العميل، ويبقى الرصيد في المحفظة متاحاً دون تاريخ انتهاء صلاحية ليُستخدم في تشغيل المتجر وفتح الطلبات والخدمات وفق الباقة المختارة.</li>
                    <li><strong class="text-slate-900 font-bold">التجربة المجانية قبل الدفع:</strong> تمنح المنصة للتجار الجدد رصيداً ترحيبياً مجانياً (100 ج.م) لتجربة كامل مميزات النظام ولوحة التحكم والتأكد من ملاءمة الخدمة لاحتياجاتهم قبل إجراء أي عملية شحن مدفوعة.</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    3. سياسة إلغاء الباقات وتبديل الخطط
                </h2>
                <ul class="space-y-3 list-inside list-disc pl-4 text-sm sm:text-base text-slate-700">
                    <li><strong class="text-slate-900 font-bold">الاشتراكات الشهرية والسنوية:</strong> بمجرد تفعيل باقة الاشتراك الشهرية أو السنوية وبدء الاستفادة من مواردها الرقمية، لا تُرد قيمة المدة المفعّلة بالفعل، ويستمر المتجر في العمل بكامل مميزات الباقة حتى نهاية الفترة المدفوعة.</li>
                    <li><strong class="text-slate-900 font-bold">حرية الإلغاء أو التعديل:</strong> يحق للتاجر إلغاء تجديد الاشتراك أو الانتقال بين الباقات (مثل الانتقال من الباقة الشهرية إلى باقة الدفع لكل أوردر 2 ج.م أو العكس) في أي وقت دون أي غرامات أو شروط جزائية، ويظل متجره يعمل وفق الباقة المختارة والرصيد المتاح في محفظته.</li>
                </ul>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    4. الاستثناءات التقنية والأخطاء البنكية (ضمان حقوق العميل)
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    حرصاً على حماية عملائنا وضمان حقوقهم المالية الكاملة عند استخدام بوابات الدفع الإلكتروني (Paymob)، تُستثنى الحالات التقنية الموثقة التالية وتتم معالجتها فوراً:
                </p>
                <ul class="space-y-3 list-inside list-disc pl-4 text-sm sm:text-base text-slate-700">
                    <li><strong class="text-slate-900 font-bold">الخصم المكرر (Duplicate Transaction):</strong> في حال خصم قيمة عملية الشحن أو الاشتراك أكثر من مرة عن طريق الخطأ لنفس العملية بسبب عطل تقني في الشبكة أو بوابة الدفع.</li>
                    <li><strong class="text-slate-900 font-bold">عدم إضافة الرصيد رغم نجاح الخصم:</strong> إذا تم خصم المبلغ من البطاقة البنكية أو المحفظة الإلكترونية ولم ينعكس الرصيد في محفظة المتجر خلال 24 ساعة عمل رغم تأكيد البنك لنجاح العملية، ولم يتم التمكن من إضافته.</li>
                    <li><strong class="text-slate-900 font-bold">عمليات غير مصرح بها موثقة:</strong> أي معاملة يُثبت رسمياً عبر البنك المُصدر حدوثها نتيجة خلل تقني غير مصرح به قبل استخدام الرصيد.</li>
                </ul>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-700 space-y-2 mt-2">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-brand-600"></i>
                        <span>آلية ومدة رد الأموال في الحالات الاستثنائية:</span>
                    </p>
                    <p>
                        بعد مراجعة الطلب والتحقق من سجل المعاملة خلال <strong>2 إلى 5 أيام عمل</strong>، يختار العميل إما إضافة الرصيد المستحق لحسابه فوراً، أو استرداد المبلغ المخصوم بالخطأ <strong>حصراً إلى نفس وسيلة الدفع الأصلية</strong> المستخدمة عند الشراء (البطاقة البنكية: Visa / Mastercard / ميزة، أو المحفظة الإلكترونية عبر بوابة <strong>Paymob</strong>)، ولا يتم رد أي مبالغ نقداً (كاش). تستغرق عملية عكس المبلغ في حساب العميل عادةً من <strong>7 إلى 14 يوم عمل</strong> وفقاً لإجراءات البنك المُصدر للبطاقة أو مزود المحفظة.
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    5. نطاق المشتريات داخل متاجر التجار
                </h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    منصة أوردر سيف (Order Saif) هي مزود البنية التحتية البرمجية للمتاجر المستضافة عليها. أما عمليات بيع السلع والمنتجات بين المستهلك النهائي وأي متجر فرعي على المنصة فتخضع مباشرة لسياسة الاستبدال والاسترجاع المعلنة في صفحة كل تاجر ووفقاً لأحكام قانون حماية المستهلك في جمهورية مصر العربية.
                </p>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full bg-brand-600"></span>
                    6. روابط السياسات ذات الصلة
                </h2>
                <div class="flex flex-wrap gap-3 text-sm font-bold">
                    <a href="{{ route('main.terms') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-brand-50 text-slate-700 hover:text-brand-700 border border-slate-200 transition-colors">
                        <i class="fa-solid fa-file-contract ml-1 text-amber-500"></i> الشروط والأحكام
                    </a>
                    <a href="{{ route('main.privacy') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-brand-50 text-slate-700 hover:text-brand-700 border border-slate-200 transition-colors">
                        <i class="fa-solid fa-shield-halved ml-1 text-rose-500"></i> سياسة الخصوصية
                    </a>
                    <a href="{{ route('main.contact') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-brand-50 text-slate-700 hover:text-brand-700 border border-slate-200 transition-colors">
                        <i class="fa-solid fa-headset ml-1 text-emerald-500"></i> اتصل بنا
                    </a>
                </div>
            </div>

            <div class="space-y-3 pt-6 border-t border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">تقديم طلب مراجعة مالية أو استفسار</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    في حال واجهتك أي مشكلة تقنية أثناء الدفع أو الخصم المكرر، يرجى التواصل معنا مباشرة مع إرفاق رقم المرجع أو لقطة المعاملة عبر البريد الإلكتروني:
                    <a href="mailto:support@ordersaif.com" class="text-brand-600 font-bold hover:underline font-mono">support@ordersaif.com</a>
                    أو عبر الهاتف والواتساب:
                    <a href="https://wa.me/201066571999" target="_blank" class="text-emerald-600 font-bold font-mono">01066571999</a>
                    (جمهورية مصر العربية).
                </p>
            </div>

        </div>

    </div>
</section>
@endsection
