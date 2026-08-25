@extends('layouts.platform')

@section('title', 'مركز المساعدة والشروحات | Order Saif - دليلك للنجاح')

@section('meta_description', 'تصفح شروحات ودليل استخدام منصة Order Saif. تعلم كيفية إنشاء متجرك، وإضافة منتجاتك، وتفعيل إعدادات الدفع والشحن، وربط دومين مخصص بسهولة.')

@section('content')
<div class="overflow-hidden bg-slate-50">
    <!-- Hero Search Section -->
    <section class="py-16 relative overflow-hidden bg-white border-b border-slate-200/80">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
            <span class="px-4 py-1.5 rounded-full bg-brand-100 text-brand-700 text-xs font-bold border border-brand-200 inline-block uppercase tracking-wider">
                📚 مركز المعرفة
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 leading-tight">
                كيف يمكننا <span class="text-brand-600">مساعدتك اليوم؟</span>
            </h1>
            
            <!-- Search Bar Placeholder -->
            <div class="max-w-2xl mx-auto relative pt-4">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-base"></i>
                </div>
                <input type="text" placeholder="ابحث عن الشروحات (مثال: ربط دومين خاص، بيكسل فيسبوك...)" class="w-full pl-4 pr-12 py-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white text-sm shadow-xs transition-all">
            </div>
        </div>
    </section>

    <!-- Support Categories -->
    <section class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Category 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:border-brand-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-rocket text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">البدء السريع والتسجيل</h3>
                    <p class="text-slate-600 text-xs leading-relaxed mb-4">تعلم كيفية إنشاء حسابك وأول متجر إلكتروني وضبط الإعدادات الأساسية.</p>
                    <a href="#quick-start" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1.5">
                        <span>تصفح المقالات</span>
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>

                <!-- Category 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:border-indigo-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-box text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">إدارة المنتجات والمبيعات</h3>
                    <p class="text-slate-600 text-xs leading-relaxed mb-4">طريقة رفع المنتجات والخيارات وإدارة المخزون وتجهيز طلبات العملاء الشاحنة.</p>
                    <a href="#quick-start" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5">
                        <span>تصفح المقالات</span>
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>

                <!-- Category 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-globe text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">النطاقات المخصصة (DNS)</h3>
                    <p class="text-slate-600 text-xs leading-relaxed mb-4">كيفية ربط نطاقك (دومين خاص) بمتجرك وتفعيل شهادة الحماية SSL مجاناً.</p>
                    <a href="#quick-start" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1.5">
                        <span>تصفح المقالات</span>
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>

                <!-- Category 4 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-500/50 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-bullhorn text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">التسويق وأدوات التحليل</h3>
                    <p class="text-slate-600 text-xs leading-relaxed mb-4">ربط بيكسل Meta و TikTok و Google Analytics لتتبع السلوك وزيادة تحويلك.</p>
                    <a href="#quick-start" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                        <span>تصفح المقالات</span>
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Detailed Guides / Accordion FAQs -->
    <section class="py-12 bg-white border-t border-slate-200" id="quick-start">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">الأسئلة الأكثر شيوعاً</h2>
                <p class="text-slate-600 text-sm mt-2 font-medium">إجابات سريعة وخطوات مصورة لأهم الأسئلة التي تشغل بالك</p>
            </div>

            <div class="space-y-4" x-data="{ activeFaq: null }">
                
                <!-- FAQ 1 -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full p-6 text-right flex items-center justify-between font-bold text-slate-900 hover:bg-slate-100/80">
                        <span>كيف يمكنني ربط دومين خاص (Custom Domain) بمتجري؟</span>
                        <i class="fa-solid text-xs text-brand-600 transition-transform duration-300" :class="activeFaq === 1 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="p-6 border-t border-slate-200 bg-white text-slate-600 text-sm space-y-3">
                        <p>لربط دومين مخصص بمتجرك، اتبع الخطوات التالية:</p>
                        <ol class="list-decimal list-inside space-y-2 text-slate-700">
                            <li>توجه إلى لوحة التحكم بمتجرك ثم اختر <strong class="text-slate-900">الإعدادات > النطاقات</strong>.</li>
                            <li>اكتب النطاق الخاص بك (مثال: <code class="text-brand-600 bg-slate-100 px-1.5 py-0.5 rounded font-mono">mystore.com</code>).</li>
                            <li>توجه لحسابك لدى مسجل النطاق (مثل GoDaddy أو Cloudflare) وقم بتوجيه سجلات CNAME أو A Record إلى المنصة.</li>
                            <li>اضغط على <strong class="text-slate-900">تأكيد الربط</strong>، وخلال دقائق سيتم تفعيل الدومين وتثبيت شهادة الـ SSL تلقائياً.</li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full p-6 text-right flex items-center justify-between font-bold text-slate-900 hover:bg-slate-100/80">
                        <span>كيف يتم تفعيل بيكسل فيسبوك وتيك توك لتتبع الحملات الإعلانية؟</span>
                        <i class="fa-solid text-xs text-brand-600 transition-transform duration-300" :class="activeFaq === 2 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="p-6 border-t border-slate-200 bg-white text-slate-600 text-sm space-y-2">
                        <p>
                            لقد وفرنا لك ربطاً مباشراً متكاملاً واستخراجاً ذكياً للبيكسل. اذهب إلى <strong class="text-slate-900">الإعدادات > إعدادات المتجر والبيكسلات</strong> في لوحة تحكم متجرك، ثم قم بلصق كود أو معرّف البيكسل في الحقول المخصصة، واضغط حفظ. سيتولى النظام إرسال كافة الأحداث (Events) مثل ViewContent و AddToCart و Purchase تلقائياً.
                        </p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full p-6 text-right flex items-center justify-between font-bold text-slate-900 hover:bg-slate-100/80">
                        <span>هل تدعم المنصة تخصيص أسعار شحن مختلفة لكل محافظة؟</span>
                        <i class="fa-solid text-xs text-brand-600 transition-transform duration-300" :class="activeFaq === 3 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="p-6 border-t border-slate-200 bg-white text-slate-600 text-sm space-y-2">
                        <p>
                            نعم بالتأكيد! يمكنك الذهاب إلى <strong class="text-slate-900">الإعدادات > المحافظات والشحن</strong>، وستجد قائمة بجميع المحافظات المصرية. يمكنك تفعيل المحافظات وتحديد تكلفة شحن خاصة بكل محافظة بشكل مستقل.
                        </p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden transition-all">
                    <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full p-6 text-right flex items-center justify-between font-bold text-slate-900 hover:bg-slate-100/80">
                        <span>كيف يعمل نظام شحن المحفظة (باقة الـ 2ج)؟</span>
                        <i class="fa-solid text-xs text-brand-600 transition-transform duration-300" :class="activeFaq === 4 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="p-6 border-t border-slate-200 bg-white text-slate-600 text-sm space-y-2">
                        <p>
                            تقوم بشحن رصيد محفظتك بأي مبلغ تريده (مثلاً 100ج = 50 أوردر). يتم خصم 2ج فقط مع كل طلب ناجح يتم استقباله. لا يوجد اشتراك شهري ولا تنتهي صلاحية الرصيد إطلاقاً.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Support Help Ticket Section -->
    <section class="py-16 text-center bg-slate-50 border-t border-slate-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="p-8 sm:p-12 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-6">
                <div class="w-14 h-14 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">لم تجد إجابة لسؤالك؟</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    لا داعي للقلق! فريق الدعم الفني متواجد لمساعدتك وحل أي استفسارات تقنية أو تجارية تواجهك على مدار الساعة.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('main.contact') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm text-white bg-brand-600 hover:bg-brand-700 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-envelope"></i>
                        <span>تواصل معنا عبر النموذج</span>
                    </a>
                    <a href="https://wa.me/201066571999" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>تواصل معنا عبر واتساب (01066571999)</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
