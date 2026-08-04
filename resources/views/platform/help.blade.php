@extends('layouts.platform')

@section('title', 'مركز المساعدة والشروحات | فاست أوردر (Fast Order) - دليلك للنجاح')

@section('meta_description', 'تصفح شروحات ودليل استخدام منصة فاست أوردر. تعلم كيفية إنشاء متجرك، وإضافة منتجاتك، وتفعيل إعدادات الدفع والشحن، وربط دومين مخصص بسهولة.')

@section('content')
<!-- Hero Search Section -->
<section class="py-16 relative overflow-hidden bg-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="px-4 py-1.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-bold border border-cyan-500/20 inline-block uppercase tracking-wider mb-6">
            📚 مركز المعرفة
        </span>
        <h1 class="text-3xl md:text-5xl font-black text-white mb-6">
            كيف يمكننا <span class="text-gradient-primary">مساعدتك اليوم؟</span>
        </h1>
        
        <!-- Interactive Search Bar Placeholder -->
        <div class="max-w-2xl mx-auto relative mt-8">
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                <i class="fa-solid fa-magnifying-glass text-lg"></i>
            </div>
            <input type="text" placeholder="ابحث عن الشروحات (مثال: ربط دومين خاص، بكسل Meta...)" class="w-full pl-4 pr-12 py-4 rounded-2xl bg-dark-card border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500/60 focus:ring-1 focus:ring-brand-500/60 shadow-xl transition-all">
        </div>
    </div>
</section>

<!-- Support Categories -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Category 1 -->
            <div class="glass-card p-6 rounded-2xl border-white/5 hover:border-brand-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-brand-500/10 text-brand-400 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-rocket text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">البدء السريع والتسجيل</h3>
                <p class="text-gray-400 text-xs leading-relaxed mb-4">تعلم كيفية إنشاء حسابك وأول متجر إلكتروني وضبط الإعدادات الأساسية.</p>
                <a href="#quick-start" class="text-xs font-bold text-brand-400 hover:text-brand-300 flex items-center gap-1.5">
                    <span>تصفح المقالات</span>
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            </div>

            <!-- Category 2 -->
            <div class="glass-card p-6 rounded-2xl border-white/5 hover:border-pink-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-box text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">إدارة المنتجات والمبيعات</h3>
                <p class="text-gray-400 text-xs leading-relaxed mb-4">طريقة رفع المنتجات والخيارات وإدارة المخزون وتجهيز طلبات العملاء الشاحنة.</p>
                <a href="#products-sales" class="text-xs font-bold text-pink-400 hover:text-pink-300 flex items-center gap-1.5">
                    <span>تصفح المقالات</span>
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            </div>

            <!-- Category 3 -->
            <div class="glass-card p-6 rounded-2xl border-white/5 hover:border-amber-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-globe text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">النطاقات المخصصة (DNS)</h3>
                <p class="text-gray-400 text-xs leading-relaxed mb-4">كيفية ربط نطاقك (دومين خاص) بمتجرك وتفعيل شهادة الحماية SSL مجاناً.</p>
                <a href="#domains" class="text-xs font-bold text-amber-400 hover:text-amber-300 flex items-center gap-1.5">
                    <span>تصفح المقالات</span>
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            </div>

            <!-- Category 4 -->
            <div class="glass-card p-6 rounded-2xl border-white/5 hover:border-emerald-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-bullhorn text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">التسويق وأدوات التحليل</h3>
                <p class="text-gray-400 text-xs leading-relaxed mb-4">ربط بكسل Meta و TikTok و Google Analytics لتتبع السلوك وزيادة تحويلك.</p>
                <a href="#marketing" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1.5">
                    <span>تصفح المقالات</span>
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- Detailed Guides / Accordion FAQs -->
<section class="py-12" id="quick-start">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-black text-white">الأسئلة الأكثر شيوعاً</h2>
            <p class="text-gray-400 text-sm mt-2">إجابات سريعة وخطوات مصورة لأهم الأسئلة التي تشغل بالك</p>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            
            <!-- FAQ 1 -->
            <div class="glass-card rounded-xl border-white/5 overflow-hidden transition-all">
                <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full p-6 text-right flex items-center justify-between font-bold text-white hover:bg-white/5">
                    <span>كيف يمكنني ربط دومين خاص (Custom Domain) بمتجري؟</span>
                    <i class="fa-solid text-xs text-brand-400 transition-transform duration-300" :class="activeFaq === 1 ? 'fa-minus' : 'fa-plus'"></i>
                </button>
                <div x-show="activeFaq === 1" x-collapse class="p-6 border-t border-white/5 bg-white/5 text-gray-300 text-sm space-y-3">
                    <p>لربط دومين مخصص بمتجرك، اتبع الخطوات التالية:</p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-400">
                        <li>توجه إلى لوحة التحكم بمتجرك ثم اختر <strong class="text-white">الإعدادات > النطاقات</strong>.</li>
                        <li>اكتب النطاق الخاص بك (مثال: <code class="text-brand-300">mystore.com</code>).</li>
                        <li>توجه لحسابك لدى مسجل النطاق (مثل GoDaddy أو Namecheap) وقم بتهيئة سجلات DNS:
                            <ul class="list-disc list-inside pr-6 mt-1 space-y-1">
                                <li>إنشاء سجل <strong class="text-white">CNAME</strong> يوجه من <code class="text-brand-300">www</code> إلى <code class="text-brand-300">app.fastorder.test</code></li>
                                <li>أو إنشاء سجل <strong class="text-white">A Record</strong> يوجه إلى عنوان الـ IP الخاص بالمنصة.</li>
                            </ul>
                        </li>
                        <li>اضغط على <strong class="text-white">تأكيد الربط</strong>، وخلال أقل من 24 ساعة سيتم تفعيل الدومين وتثبيت شهادة الـ SSL تلقائياً.</li>
                    </ol>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="glass-card rounded-xl border-white/5 overflow-hidden transition-all">
                <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full p-6 text-right flex items-center justify-between font-bold text-white hover:bg-white/5">
                    <span>كيف يتم تفعيل بكسل فيسبوك وتيك توك لتتبع الحملات الإعلانية؟</span>
                    <i class="fa-solid text-xs text-brand-400 transition-transform duration-300" :class="activeFaq === 2 ? 'fa-minus' : 'fa-plus'"></i>
                </button>
                <div x-show="activeFaq === 2" x-collapse class="p-6 border-t border-white/5 bg-white/5 text-gray-300 text-sm space-y-2">
                    <p>
                        لقد وفرنا لك ربطاً مباشراً متكاملاً دون كتابة أكواد برمجية. اذهب إلى <strong class="text-white">الإعدادات > الربط التسويقي</strong> في لوحة تحكم متجرك، ثم قم بنسخ ولصق معرّف البكسل (Pixel ID) الخاص بفيسبوك أو تيك توك في الحقول المخصصة، واضغط حفظ. سيتولى النظام إرسال كافة الأحداث (Events) مثل ViewContent و AddToCart و Purchase تلقائياً.
                    </p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="glass-card rounded-xl border-white/5 overflow-hidden transition-all">
                <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full p-6 text-right flex items-center justify-between font-bold text-white hover:bg-white/5">
                    <span>هل تدعم المنصة تخصيص أسعار شحن مختلفة لكل محافظة؟</span>
                    <i class="fa-solid text-xs text-brand-400 transition-transform duration-300" :class="activeFaq === 3 ? 'fa-minus' : 'fa-plus'"></i>
                </button>
                <div x-show="activeFaq === 3" x-collapse class="p-6 border-t border-white/5 bg-white/5 text-gray-300 text-sm space-y-2">
                    <p>
                        نعم بالتأكيد! يمكنك الذهاب إلى <strong class="text-white">الإعدادات > خيارات الشحن والتوصيل</strong>، وستجد قائمة بجميع المحافظات. يمكنك تفعيل المحافظات التي تخدمها فقط وتحديد تكلفة شحن خاصة بكل محافظة بشكل مستقل، أو تعيين شحن مجاني عند تخطي قيمة طلب معينة.
                    </p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="glass-card rounded-xl border-white/5 overflow-hidden transition-all">
                <button @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full p-6 text-right flex items-center justify-between font-bold text-white hover:bg-white/5">
                    <span>كيف أقوم بترقية أو إلغاء باقة اشتراكي؟</span>
                    <i class="fa-solid text-xs text-brand-400 transition-transform duration-300" :class="activeFaq === 4 ? 'fa-minus' : 'fa-plus'"></i>
                </button>
                <div x-show="activeFaq === 4" x-collapse class="p-6 border-t border-white/5 bg-white/5 text-gray-300 text-sm space-y-2">
                    <p>
                        يمكنك ترقية اشتراكك، أو الرجوع لباقة أقل، أو إلغاء الاشتراك في أي وقت مباشرة عبر لوحة تحكم متجرك بالذهاب إلى <strong class="text-white">الاشتراك والفوترة</strong>. في حال الإلغاء، سيظل متجرك متاحاً للعمل حتى نهاية فترة الفوترة المدفوعة مسبقاً دون تجديد تلقائي.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Support Help Ticket Section -->
<section class="py-16 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-tr from-brand-950/40 via-dark-card to-pink-950/20 border border-white/10 space-y-6">
            <div class="w-14 h-14 rounded-full bg-brand-500/10 text-brand-400 flex items-center justify-center mx-auto border border-brand-500/20">
                <i class="fa-solid fa-headset text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">لم تجد إجابة لسؤالك؟</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                لا داعي للقلق! فريق الدعم الفني متواجد لمساعدتك وحل أي استفسارات تقنية أو تجارية تواجهك على مدار الساعة.
            </p>
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('main.contact') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm text-white bg-brand-600 hover:bg-brand-500 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-envelope"></i>
                    <span>فتح تذكرة دعم فني</span>
                </a>
                <a href="https://wa.me/201146520922" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 transition-all flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>تواصل معنا عبر واتساب</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
