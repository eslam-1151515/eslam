<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlatformController extends Controller
{
    /**
     * Display the platform landing page (OrderSaif.test).
     */
    public function index(Request $request)
    {
        $stats = [
            'active_stores' => [
                'ar' => '+1,500',
                'en' => '+1,500',
                'label_ar' => 'متجر إلكتروني نشط',
                'label_en' => 'Active Online Stores',
            ],
            'daily_orders' => [
                'ar' => '+50,000',
                'en' => '+50,000',
                'label_ar' => 'طلب يتم معالجته يومياً',
                'label_en' => 'Orders Processed Daily',
            ],
            'uptime' => [
                'ar' => '99.9%',
                'en' => '99.9%',
                'label_ar' => 'نسبة استقرار السيرفرات',
                'label_en' => 'Server Uptime Guarantee',
            ],
            'monthly_sales' => [
                'ar' => '+15M ج.م',
                'en' => '+15M EGP',
                'label_ar' => 'مبيعات شهرية للمتاجر',
                'label_en' => 'Monthly Store Sales',
            ],
        ];

        $features = [
            [
                'icon' => 'bolt',
                'title_ar' => 'سرعة فائقة وأداء استثنائي',
                'title_en' => 'Blazing Fast Performance',
                'description_ar' => 'تقنيات تحميل فورية وكاش ذكي تضمن تصفح سريع لعملائك ومعدل تحويل أعلى بكثير.',
                'description_en' => 'Instant load times and smart caching ensure quick browsing for your customers and higher conversion rates.',
                'color' => 'from-amber-500 to-orange-500',
            ],
            [
                'icon' => 'palette',
                'title_ar' => 'ثيمات احترافية وقابلة للتخصيص',
                'title_en' => 'Professional Custom Themes',
                'description_ar' => 'مكتبة ثيمات فاخرة تناسب جميع المجالات مع إمكانية التخصيص الفوري للألوان والخطوط بدون كود.',
                'description_en' => 'A library of premium themes for all niches with instant visual customization of colors and fonts without code.',
                'color' => 'from-indigo-500 to-purple-500',
            ],
            [
                'icon' => 'chart-line',
                'title_ar' => 'أدوات تسويق وسيو متقدمة',
                'title_en' => 'Advanced SEO & Marketing Tools',
                'description_ar' => 'ربط مباشر بـ Facebook & TikTok Pixel و Google Analytics مع بيانات منظمة وخرائط موقع تلقائية.',
                'description_en' => 'Direct integration with Meta & TikTok Pixels and Google Analytics, featuring structured data and automatic sitemaps.',
                'color' => 'from-emerald-500 to-teal-500',
            ],
            [
                'icon' => 'box',
                'title_ar' => 'إدارة الطلبات والشحن الذكي',
                'title_en' => 'Order Management & Smart Shipping',
                'description_ar' => 'متابعة سلسة للطلبات وتحديث حالاتها، مع تخصيص أسعار ومناطق الشحن لجميع المحافظات بسهولة.',
                'description_en' => 'Seamless tracking and state updates for orders, with easily customizable shipping rates and zones for all regions.',
                'color' => 'from-blue-500 to-cyan-500',
            ],
            [
                'icon' => 'chart-pie',
                'title_ar' => 'تقارير وإحصائيات دقيقة',
                'title_en' => 'Accurate Analytics & Reports',
                'description_ar' => 'لوحة تحكم ذكية تعرض أداء المبيعات وسلوك العملاء والمنتجات الأكثر مبيعاً لاتخاذ قرارات صائبة.',
                'description_en' => 'Smart dashboard showcasing sales performance, customer behavior, and best-selling products for data-driven decisions.',
                'color' => 'from-rose-500 to-pink-500',
            ],
            [
                'icon' => 'shield-check',
                'title_ar' => 'أمان عالي ودفع متعدد',
                'title_en' => 'High Security & Multi-Payment',
                'description_ar' => 'حماية متقدمة لمتجرك وعملائك، مع دعم الدفع عند الاستلام (COD) وربط بوابات الدفع الإلكتروني.',
                'description_en' => 'Advanced protection for your store and customers, supporting Cash on Delivery (COD) and electronic payment gateways.',
                'color' => 'from-violet-500 to-fuchsia-500',
            ],
        ];

        $steps = [
            [
                'number' => '01',
                'title_ar' => 'سجل حسابك واختر اسم متجرك',
                'title_en' => 'Register & Choose Your Store Name',
                'description_ar' => 'أنشئ حسابك في أقل من دقيقة، حدد اسم النطاق الخاص بك، واستمتع بتجربة مجانية فورية بدون بطاقة ائتمان.',
                'description_en' => 'Create your account in under a minute, set your domain name, and enjoy a free trial instantly without a credit card.',
            ],
            [
                'number' => '02',
                'title_ar' => 'اختر تصميم متجرك وأضف منتجاتك',
                'title_en' => 'Select Theme & Add Products',
                'description_ar' => 'اختر الثيم المناسب لعلامتك التجارية، أضف منتجاتك وأسعارها، وقم بتخصيص الألوان والخطوط بضغطة زر واحدة.',
                'description_en' => 'Pick the perfect theme for your brand, add your products with pricing, and customize colors and fonts with one click.',
            ],
            [
                'number' => '03',
                'title_ar' => 'أطلق متجرك وابدأ باستقبال المبيعات!',
                'title_en' => 'Launch Store & Start Selling!',
                'description_ar' => 'شارك رابط متجرك مع عملائك، استقبل الطلبات فوراً على لوحة التحكم، وتابع نمو أرباحك لحظة بلحظة.',
                'description_en' => 'Share your store link with customers, receive orders instantly on your control panel, and track your revenue live.',
            ],
        ];

        $plans = [
            [
                'id' => 'trial_free',
                'name_ar' => 'الباقة التجريبية المجانية',
                'name_en' => 'Free Trial Plan',
                'badge_ar' => '🎁 100 ج.م رصيد هدية',
                'badge_en' => '🎁 100 EGP Gift',
                'price' => '0',
                'period_ar' => 'رصيد افتتاحي',
                'period_en' => 'opening balance',
                'description_ar' => 'احصل على 100 ج.م رصيد هدية مجانية في محفظتك ترحيباً بك لتجربة المنصة واستقبال ومعاينة أول 50 أوردر مجاناً، مدة مفتوحة بدون أي قيود.',
                'description_en' => 'Get 100 EGP free gift in your wallet to explore the platform and view first 50 orders free with open duration.',
                'featured' => false,
                'features_ar' => [
                    '100 ج.م رصيد هدية ترحيبي في محفظتك فوراً',
                    'متجر إلكتروني فائق السرعة ومتكامل',
                    'لوحة تحكم كاملة وسهلة بالعربية',
                    'تجربة فتح ومعاينة الأوردرات مجاناً',
                    '0% عمولة على المبيعات',
                    'ميزة الأوتوكونفرم تتفعل بعد أول شحن فعلي',
                    'دعم فني عبر الواتس اب أو الاتصال',
                ],
                'features_en' => [
                    '100 EGP free opening balance in your wallet',
                    'Integrated ultra-fast online store',
                    'Full Arabic / English control panel',
                    'Free order viewing experience',
                    '0% commission on sales',
                    'Auto-confirm activates after first top-up',
                    'Support via WhatsApp or Phone call',
                ],
                'cta_text_ar' => 'ابدأ مجاناً واحصل على 100ج هدية',
                'cta_text_en' => 'Start Free & Get 100 EGP Gift',
                'cta_link' => Route::has('register') ? route('register') : '#',
            ],
            [
                'id' => 'pay_per_order',
                'name_ar' => 'باقة الدفع لكل أوردر (2ج)',
                'name_en' => 'Pay Per Order Plan (2 EGP)',
                'badge_ar' => 'الأكثر مرونة وتوفيراً',
                'badge_en' => 'Most Flexible',
                'price' => '2',
                'period_ar' => 'لكل أوردر مفتوح',
                'period_en' => 'per opened order',
                'description_ar' => 'بدون أي اشتراك شهري ثابت! شحن المحفظة برصيد وخصم 2 ج.م فقط عند فتح ومعاينة كل أوردر.',
                'description_en' => 'Zero fixed monthly fees! Top up your wallet and pay only 2 EGP when unlocking each order.',
                'featured' => true,
                'features_ar' => [
                    'خصم 2 ج.م فقط لكل أوردر يتم فتحه',
                    '0ج رسوم أو اشتراكات شهرية ثابتة',
                    'شحن المحفظة فوراً بـ فودافون كاش وإنستا باي',
                    'منتجات وتصميمات وثيمات غير محدودة',
                    'ميزة تأكيد الواتساب (1ج للرسالة من المحفظة)',
                    'ربط دومين خاص وشبكات البكسل مجاناً',
                    'دعم فني عبر الواتس اب أو الاتصال',
                ],
                'features_en' => [
                    'Pay only 2 EGP per unlocked order',
                    'Zero monthly subscription or hidden fees',
                    'Instant top-up via Vodafone Cash & InstaPay',
                    'Unlimited products, themes & designs',
                    'WhatsApp auto-confirm (1 EGP per message)',
                    'Free custom domain & pixel connections',
                    'Support via WhatsApp or Phone call',
                ],
                'cta_text_ar' => 'اختر باقة الطلب (2ج لكل أوردر)',
                'cta_text_en' => 'Choose Pay Per Order Plan',
                'cta_link' => Route::has('register') ? route('register') : '#',
            ],
            [
                'id' => 'monthly_unlimited',
                'name_ar' => 'الاشتراك الشهري الشامل',
                'name_en' => 'Monthly Unlimited Plan',
                'badge_ar' => '🚀 غير محدود وبدون عمولة',
                'badge_en' => '🚀 Unlimited & 0% Commission',
                'price' => '4000',
                'period_ar' => 'شهرياً',
                'period_en' => 'monthly',
                'description_ar' => 'اشتراك 4000 ج.م شهرياً مع فتح وإدارة غير محدودة لجميع الأوردرات والمنتجات و0% عمولة، مع ميزة الأوتوكونفرم بـ 1ج للرسالة من المحفظة.',
                'description_en' => '4000 EGP/mo with unlimited order & product management, 0% commissions, and WhatsApp auto-confirm for 1 EGP/msg from wallet.',
                'featured' => false,
                'features_ar' => [
                    'فتح وإدارة غير محدودة لجميع الأوردرات والمنتجات',
                    '0% عمولة على مبيعات المتجر (بدون خصم 2ج على الأوردر)',
                    'ميزة تأكيد الطلبات عبر الواتساب (1ج لكل رسالة من المحفظة)',
                    'فتح جميع الثيمات الاحترافية والتخصيصات بالكامل',
                    'سيرفرات فائقة السرعة وأولوية معالجة قصوى',
                    'دعم فني عبر الواتس اب أو الاتصال 24/7',
                ],
                'features_en' => [
                    'Unlimited order & product management',
                    '0% commission on all store sales',
                    'WhatsApp confirmation (1 EGP/msg from wallet)',
                    'All premium themes & customizations unlocked',
                    'Dedicated high-performance servers',
                    'Support via WhatsApp or Phone call 24/7',
                ],
                'cta_text_ar' => 'اشترك في الباقة الشهرية (4,000ج)',
                'cta_text_en' => 'Subscribe for 4,000 EGP/mo',
                'cta_link' => Route::has('register') ? route('register') : '#',
            ],
        ];

        $testimonials = [
            [
                'name_ar' => 'أحمد محمد',
                'name_en' => 'Ahmed Mohamed',
                'role_ar' => 'مؤسس براند "أناقة للملابس"',
                'role_en' => 'Founder of "Anaka Fashion"',
                'avatar' => 'https://ui-avatars.com/api/?name=أحمد+محمد&background=6366f1&color=fff&size=128',
                'quote_ar' => 'انتقالنا إلى منصة أوردر سيف كان أفضل قرار اتخذناه هذا العام. سرعة المتجر ارتفعت بشكل ملحوظ ومعدل تحويل الزوار إلى مشترين زاد بنسبة 35% خلال أول شهر فقط!',
                'quote_en' => 'Moving to Order Saif was the best decision we made this year. Store speed increased significantly, and our conversion rate jumped by 35% in the first month!',
                'rating' => 5,
            ],
            [
                'name_ar' => 'سارة خالد',
                'name_en' => 'Sarah Khaled',
                'role_ar' => 'مالكة متجر "جلوري لمستحضرات التجميل"',
                'role_en' => 'Owner of "Glory Cosmetics"',
                'avatar' => 'https://ui-avatars.com/api/?name=سارة+خالد&background=ec4899&color=fff&size=128',
                'quote_ar' => 'أكثر ما أبهرني هو سهولة التخصيص وربط بكسل التيك توك والفيسبوك بضغطة زر واحدة. الدعم الفني متواجد دائماً ويساعدنا في حل أي استفسار خلال دقائق.',
                'quote_en' => 'What impressed me most was the ease of customization and integrating TikTok/Facebook pixels with one click. Support is always there to help within minutes.',
                'rating' => 5,
            ],
            [
                'name_ar' => 'محمود علي',
                'name_en' => 'Mahmoud Ali',
                'role_ar' => 'مدير عام "تِك زون للإلكترونيات"',
                'role_en' => 'General Manager of "TechZone Electronics"',
                'avatar' => 'https://ui-avatars.com/api/?name=محمود+علي&background=3b82f6&color=fff&size=128',
                'quote_ar' => 'كنا نعاني من بطء السيرفرات وعمولات المنصات الأخرى. مع أوردر سيف حصلنا على استقرار 100% وعمولة صفر! لوحة التحكم وإدارة الطلبات والشحن أصبحت أسهل بكثير.',
                'quote_en' => 'We used to suffer from slow servers and commissions on other platforms. With Order Saif, we got 100% stability and zero commission! The control panel is extremely easy.',
                'rating' => 5,
            ],
        ];

        $faqs = [
            [
                'question_ar' => 'هل أحتاج إلى أي خبرة تقنية أو برمجية لإنشاء متجري؟',
                'question_en' => 'Do I need any technical or programming experience to create my store?',
                'answer_ar' => 'على الإطلاق! منصة أوردر سيف مصممة لتكون سهلة وبسيطة للغاية. يمكنك إنشاء وتخصيص وإطلاق متجرك الإلكتروني بالكامل في أقل من 5 دقائق باستخدام واجهات مرئية وبدون كتابة سطر كود واحد.',
                'answer_en' => 'Not at all! Order Saif is designed to be extremely simple. You can create, customize, and launch your online store in under 5 minutes using visual interfaces and without writing a single line of code.',
            ],
            [
                'question_ar' => 'هل توجد أي عمولات على المبيعات التي أحققها؟',
                'question_en' => 'Are there any commissions on the sales I make?',
                'answer_ar' => 'لا، نحن لا نفرض أي عمولات (0% عمولة) على مبيعاتك في جميع الباقات. كل الأرباح التي تحققها من متجرك تعود إليك بنسبة 100% دون أي اقتطاع.',
                'answer_en' => 'No, we do not charge any commission (0% commission) on your sales in all plans. All profits you make from your store belong to you 100%.',
            ],
            [
                'question_ar' => 'هل يمكنني ربط النطاق الخاص بي (Custom Domain) بمتجري؟',
                'question_en' => 'Can I connect my custom domain to my store?',
                'answer_ar' => 'نعم بالتأكيد! يمكنك ربط أي نطاق خاص بك بسهولة من لوحة التحكم، ونقوم نحن بتوفير وتفعيل شهادة الحماية SSL مجاناً لضمان أمان متجرك وعملائك.',
                'answer_en' => 'Yes, absolutely! You can connect your own custom domain easily from the control panel, and we will activate a free SSL certificate to secure your store and customers.',
            ],
            [
                'question_ar' => 'ما هي بوابات الدفع وشركات الشحن المدعومة في المنصة؟',
                'question_en' => 'What payment gateways and shipping companies are supported?',
                'answer_ar' => 'نعم، نحن ندعم الدفع عند الاستلام (Cash on Delivery) وهو الأوسع انتشاراً، بالإضافة إلى ربط بوابات الدفع الإلكتروني المحلية والدولية، مع إمكانية تخصيص أسعار ومناطق الشحن لجميع المحافظات بسهولة تامة.',
                'answer_en' => 'We support Cash on Delivery (COD) as it is the most popular, plus integration with local and international online payment gateways. You can easily customize shipping rates for all regions.',
            ],
            [
                'question_ar' => 'كيف أستفيد من فترة التجربة المجانية (7 أيام)؟',
                'question_en' => 'How can I benefit from the 7-day free trial?',
                'answer_ar' => 'يمكنك التسجيل الآن والبدء فوراً في استخدام المنصة وتجربة جميع المميزات لمدة 7 أيام مجاناً، ولا نطلب منك إدخال أي بيانات بطاقة ائتمانية عند التسجيل.',
                'answer_en' => 'You can sign up now and start using the platform and all its features for 7 days free. We do not require a credit card to sign up.',
            ],
            [
                'question_ar' => 'هل يمكنني تغيير باقة الاشتراك أو إلغائها لاحقاً؟',
                'question_en' => 'Can I change or cancel my subscription plan later?',
                'answer_ar' => 'نعم، يمكنك الترقية إلى باقة أعلى، أو الرجوع لباقة أقل، أو إلغاء اشتراكك في أي وقت مباشرة من خلال لوحة تحكم متجرك دون أي قيود أو شروط جزائية.',
                'answer_en' => 'Yes, you can upgrade, downgrade, or cancel your subscription at any time directly through your store dashboard without any restrictions.',
            ],
        ];

        $phoneContact = class_exists(\App\Models\SupportContact::class)
            ? \App\Models\SupportContact::where('type', 'phone')->where('is_active', true)->first()
            : null;

        $whatsappContact = class_exists(\App\Models\SupportContact::class)
            ? \App\Models\SupportContact::where('type', 'whatsapp')->where('is_active', true)->first()
            : null;

        return view('platform.index', compact('stats', 'features', 'steps', 'plans', 'testimonials', 'faqs', 'phoneContact', 'whatsappContact'));
    }

    /**
     * Display the about platform page.
     */
    public function about(Request $request)
    {
        return view('platform.about');
    }

    /**
     * Display the pricing page.
     */
    public function pricing(Request $request)
    {
        return redirect()->route('main.home', ['#pricing']);
    }

    /**
     * Display the contact page.
     */
    public function contact(Request $request)
    {
        return view('platform.contact');
    }

    /**
     * Handle the contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:support,sales,custom,other',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'حقل الاسم الكامل مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'الرجاء إدخال بريد إلكتروني صحيح.',
            'subject.required' => 'الرجاء اختيار موضوع الاستفسار.',
            'message.required' => 'حقل الرسالة مطلوب.',
        ]);

        return redirect()->route('main.contact')->with('success', 'شكراً لتواصلك معنا! لقد تم استلام رسالتك بنجاح وسيتواصل معك فريق أوردر سيف في أقرب وقت ممكن.');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy(Request $request)
    {
        return view('platform.privacy');
    }

    /**
     * Display the terms of service page.
     */
    public function terms(Request $request)
    {
        return view('platform.terms');
    }

    /**
     * Display the Service Level Agreement (SLA) page.
     */
    public function sla(Request $request)
    {
        return view('platform.sla');
    }

    /**
     * Display the Help Center page.
     */
    public function help(Request $request)
    {
        return view('platform.help');
    }
}
