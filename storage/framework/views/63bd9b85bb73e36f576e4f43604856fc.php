<?php
    $stats = $stats ?? [
        'active_stores' => ['ar' => '+1,500', 'en' => '+1,500', 'label_ar' => 'متجر إلكتروني نشط', 'label_en' => 'Active Online Stores'],
        'daily_orders' => ['ar' => '+50,000', 'en' => '+50,000', 'label_ar' => 'طلب يتم معالجته يومياً', 'label_en' => 'Orders Processed Daily'],
        'uptime' => ['ar' => '99.9%', 'en' => '99.9%', 'label_ar' => 'نسبة استقرار السيرفرات', 'label_en' => 'Server Uptime Guarantee'],
        'monthly_sales' => ['ar' => '+15M ج.م', 'en' => '+15M EGP', 'label_ar' => 'مبيعات شهرية للمتاجر', 'label_en' => 'Monthly Store Sales'],
    ];

    $features = $features ?? [
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

    $steps = $steps ?? [
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

    $plans = $plans ?? [
        [
            'id' => 'starter',
            'name_ar' => 'الباقة التجريبية (Starter)',
            'name_en' => 'Starter Trial Plan',
            'badge_ar' => 'مجاني 14 يوم',
            'badge_en' => 'Free 14 Days',
            'price_monthly' => '0',
            'price_yearly' => '0',
            'period_ar' => 'شهر',
            'period_en' => 'month',
            'description_ar' => 'مثالية للبائعين المبتدئين لتجربة المنصة واستكشاف جميع المميزات الأساسية بدون مخاطرة.',
            'description_en' => 'Perfect for beginner sellers to try the platform and explore all core features risk-free.',
            'featured' => false,
            'features_ar' => [
                'متجر إلكتروني متكامل وسريع',
                'إضافة حتى 50 منتج',
                'ثيم كلاسيكي ونظيف متجاوب',
                '0% عمولة على مبيعاتك',
                'لوحة تحكم باللغتين العربية والإنجليزية',
                'دعم فني عبر البريد الإلكتروني',
            ],
            'features_en' => [
                'Fully integrated fast online store',
                'Add up to 50 products',
                'Clean & responsive classic theme',
                '0% commission on your sales',
                'Bilingual control panel (AR/EN)',
                'Email customer support',
            ],
            'cta_text_ar' => 'ابدأ تجربتك المجانية',
            'cta_text_en' => 'Start Your Free Trial',
            'cta_link' => Route::has('register') ? route('register') : '#',
        ],
        [
            'id' => 'growth',
            'name_ar' => 'باقة النمو (Growth)',
            'name_en' => 'Growth Plan',
            'badge_ar' => 'الأكثر طلباً',
            'badge_en' => 'Most Popular',
            'price_monthly' => '299',
            'price_yearly' => '239',
            'period_ar' => 'شهر',
            'period_en' => 'month',
            'description_ar' => 'موجهة للمتاجر الناشئة والمتوسطة الراغبة في التوسع وبناء علامة تجارية قوية ومستدامة.',
            'description_en' => 'Aimed at startups and medium stores looking to expand and build a strong, sustainable brand.',
            'featured' => true,
            'features_ar' => [
                'منتجات غير محدودة',
                'ربط دومين خاص (Custom Domain) + SSL مجاني',
                'جميع الثيمات الاحترافية (Modern, Vibrant, Starter)',
                'ربط بكسل Meta و TikTok و Google Analytics',
                'تقارير مبيعات وتحليلات متقدمة',
                'دعم فني مباشر 24/7 عبر الدردشة والبريد',
            ],
            'features_en' => [
                'Unlimited products upload',
                'Custom domain connection + free SSL',
                'All professional themes (Modern, Vibrant, Starter)',
                'Meta Pixel, TikTok Pixel & Google Analytics',
                'Advanced sales reports & analytics',
                '24/7 live chat and email support',
            ],
            'cta_text_ar' => 'اشترك الآن وابدأ النمو',
            'cta_text_en' => 'Subscribe Now & Grow',
            'cta_link' => Route::has('register') ? route('register') : '#',
        ],
        [
            'id' => 'pro',
            'name_ar' => 'باقة الاحتراف (Pro)',
            'name_en' => 'Pro Professional Plan',
            'badge_ar' => 'للشركات الكبرى',
            'badge_en' => 'For Large Brands',
            'price_monthly' => '799',
            'price_yearly' => '639',
            'period_ar' => 'شهر',
            'period_en' => 'month',
            'description_ar' => 'موجهة للشركات والعلامات التجارية الكبيرة ذات الحجم العالي من الطلبات وحركة المرور الكثيفة.',
            'description_en' => 'Designed for enterprises and large brands with high order volume and intense web traffic.',
            'featured' => false,
            'features_ar' => [
                'جميع مميزات باقة النمو كاملة',
                'أولوية قصوى في معالجة السيرفرات والأداء',
                'إدارة الموظفين والصلاحيات المتقدمة',
                'تخصيص كامل للثيمات عبر أسلوب CSS مخصص',
                'تقارير ذكية وسلسلة الإمداد والمخزون',
                'مدير حساب مخصص + دعم VIP هاتف وواتساب',
            ],
            'features_en' => [
                'Includes all Growth plan features',
                'High-performance dedicated server routing',
                'Staff accounts & advanced permission management',
                'Full CSS customizing options for themes',
                'Smart inventory and supply chain reports',
                'Dedicated account manager + Phone/WhatsApp VIP support',
            ],
            'cta_text_ar' => 'تواصل مع المبيعات / اشترك الآن',
            'cta_text_en' => 'Contact Sales / Subscribe Now',
            'cta_link' => Route::has('register') ? route('register') : '#',
        ],
    ];

    $testimonials = $testimonials ?? [
        [
            'name_ar' => 'أحمد محمد',
            'name_en' => 'Ahmed Mohamed',
            'role_ar' => 'مؤسس براند "أناقة للملابس"',
            'role_en' => 'Founder of "Anaka Fashion"',
            'avatar' => 'https://ui-avatars.com/api/?name=أحمد+محمد&background=6366f1&color=fff&size=128',
            'quote_ar' => 'انتقالنا إلى منصة فاست أوردر كان أفضل قرار اتخذناه هذا العام. سرعة المتجر ارتفعت بشكل ملحوظ ومعدل تحويل الزوار إلى مشترين زاد بنسبة 35% خلال أول شهر فقط!',
            'quote_en' => 'Moving to Fast Order was the best decision we made this year. Store speed increased significantly, and our conversion rate jumped by 35% in the first month!',
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
            'quote_ar' => 'كنا نعاني من بطء السيرفرات وعمولات المنصات الأخرى. مع فاست أوردر حصلنا على استقرار 100% وعمولة صفر! لوحة التحكم وإدارة الطلبات والشحن أصبحت أسهل بكثير.',
            'quote_en' => 'We used to suffer from slow servers and commissions on other platforms. With Fast Order, we got 100% stability and zero commission! The control panel is extremely easy.',
            'rating' => 5,
        ],
    ];

    $faqs = $faqs ?? [
        [
            'question_ar' => 'هل أحتاج إلى أي خبرة تقنية أو برمجية لإنشاء متجري؟',
            'question_en' => 'Do I need any technical or programming experience to create my store?',
            'answer_ar' => 'على الإطلاق! منصة فاست أوردر مصممة لتكون سهلة وبسيطة للغاية. يمكنك إنشاء وتخصيص وإطلاق متجرك الإلكتروني بالكامل في أقل من 5 دقائق باستخدام واجهات مرئية وبدون كتابة سطر كود واحد.',
            'answer_en' => 'Not at all! Fast Order is designed to be extremely simple. You can create, customize, and launch your online store in under 5 minutes using visual interfaces and without writing a single line of code.',
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
            'question_ar' => 'كيف أستفيد من فترة التجربة المجانية (14 يوم)؟',
            'question_en' => 'How can I benefit from the 14-day free trial?',
            'answer_ar' => 'يمكنك التسجيل الآن والبدء فوراً في استخدام المنصة وتجربة جميع المميزات لمدة 14 يوماً مجاناً، ولا نطلب منك إدخال أي بيانات بطاقة ائتمانية عند التسجيل.',
            'answer_en' => 'You can sign up now and start using the platform and all its features for 14 days free. We do not require a credit card to sign up.',
        ],
        [
            'question_ar' => 'هل يمكنني تغيير باقة الاشتراك أو إلغائها لاحقاً؟',
            'question_en' => 'Can I change or cancel my subscription plan later?',
            'answer_ar' => 'نعم، يمكنك الترقية إلى باقة أعلى، أو الرجوع لباقة أقل، أو إلغاء اشتراكك في أي وقت مباشرة من خلال لوحة تحكم متجرك دون أي قيود أو شروط جزائية.',
            'answer_en' => 'Yes, you can upgrade, downgrade, or cancel your subscription at any time directly through your store dashboard without any restrictions.',
        ],
    ];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark scroll-smooth" :class="{ 'dark': darkMode }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" :lang="lang" 
      x-data="{ 
          darkMode: true, 
          lang: 'ar', 
          mobileMenuOpen: false,
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          },
          setLang(l) {
              this.lang = l;
              document.documentElement.setAttribute('lang', l);
              document.documentElement.setAttribute('dir', l === 'ar' ? 'rtl' : 'ltr');
          }
      }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاست أوردر (Fast Order) | أنشئ متجرك الإلكتروني المتكامل في ثوانٍ</title>
    <meta name="description" content="المنصة الأسرع والأذكى في الوطن العربي لإدارة تجارتك الإلكترونية دون تعقيد برمجيات، مع عمولة 0% وباقات تناسب نمو عملك.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f3ff',
                            100: '#e0e8ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        accent: {
                            50: '#fff1f2',
                            500: '#f43f5e',
                            600: '#e11d48',
                        },
                        dark: {
                            bg: '#08090e',
                            card: '#11131d',
                            border: '#1e2132'
                        }
                    },
                    fontFamily: {
                        sans: ['Cairo', 'Outfit', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 15px -5px rgba(99, 102, 241, 0.4)' },
                            '100%': { boxShadow: '0 0 30px 8px rgba(99, 102, 241, 0.8)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', 'Outfit', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Light/Dark mode backgrounds */
        body {
            background-color: #f8fafc;
            color: #334155;
        }
        .dark body {
            background-color: #08090e;
            color: #f3f4f6;
        }

        /* Glass header classes */
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .dark .glass-header {
            background: rgba(8, 9, 14, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Glass card classes */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .glass-card {
            background: rgba(17, 19, 29, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
        }

        .glass-card-hover:hover {
            transform: translateY(-6px);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.25);
        }
        .dark .glass-card-hover:hover {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 20px 45px -10px rgba(99, 102, 241, 0.35);
        }

        .text-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #4f46e5 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dark .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dark .text-gradient-primary {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(0, 0, 0, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.02) 1px, transparent 1px);
        }
        .dark .bg-grid-pattern {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .dark ::-webkit-scrollbar-track {
            background: #08090e;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #1e2132;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6366f1;
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- translations Dictionary -->
    <script>
        window.translations = {
            ar: {
                features: 'المميزات',
                howItWorks: 'كيف تبدأ',
                pricing: 'الباقات والأسعار',
                partners: 'شركاء النجاح',
                faq: 'الأسئلة الشائعة',
                dashboard: 'لوحة التحكم',
                login: 'تسجيل الدخول',
                startNow: 'ابدأ متجرك الآن',
                startFree: 'ابدأ متجرك الآن مجاناً',
                browseFeatures: 'تصفح المميزات',
                heroBadge: '🎉 إطلاق محرك الثيمات الذكي وربط بكسل Meta و TikTok بضغطة زر!',
                heroTitle: 'أنشئ متجرك الإلكتروني المتكامل',
                heroTitleGradient: 'واطلق مبيعاتك في دقائق!',
                heroDesc: 'المنصة الأسرع والأذكى في الوطن العربي لإدارة تجارتك الإلكترونية دون تعقيد برمجيات، مع عمولة 0% وباقات مرنة تناسب نمو عملك وتطلعاتك.',
                heroCtaStart: 'ابدأ متجرك الآن مجاناً',
                heroCtaFeatures: 'تصفح المميزات',
                heroCheckFree: '14 يوم تجربة مجانية',
                heroCheckNoCard: 'بدون بطاقة ائتمان',
                heroCheckZeroComm: '0% عمولة على المبيعات',
                heroCheckSupport: 'دعم فني وتدريب 24/7',
                
                mockupDailySales: 'المبيعات اليومية',
                mockupNewOrders: 'الطلبات الجديدة',
                mockupInProcessing: '🕒 8 طلبات في التجهيز',
                mockupLatestOrders: 'أحدث الطلبات المستلمة',
                mockupLive: 'مباشر 🔥',
                mockupCairo: 'القاهرة، مدينة نصر • منذ دقيقة',
                mockupAlex: 'الإسكندرية، سموحة • منذ 4 دقائق',
                mockupCod: 'الدفع عند الاستلام',
                mockupOnline: 'فيزا / أونلاين',
                mockupActiveTheme: 'الثيم النشط:',
                mockupPixelConnected: '● متصل بالبكسل',
                mockupNewSaleNotif: 'إشعار مبيعات جديد!',
                mockupConvRate: 'معدل تحويل المتجر',
                mockupAboveAvg: '4.8% (أعلى 3x من المتوسط)',
                
                featuresSub: 'لماذا تختار Fast Order؟',
                featuresTitle: 'كل ما تحتاجه للنجاح في التجارة الإلكترونية في منصة واحدة',
                featuresDesc: 'تم تصميم منصتنا بأحدث التقنيات العالمية لتوفير أقصى سرعة وأعلى معدل تحويل، مع أدوات تسويق وإدارة ذكية تغنيك عن عشرات الإضافات والاشتراكات.',
                
                transferBadge: 'تحديث جديد 🚀',
                transferTitle: 'هل تمتلك متجراً حالياً وتريد نقله إلى فاست أوردر؟',
                transferDesc: 'فريقنا الفني متخصص في نقل متجرك، منتجاتك، وبيانات عملائك مجاناً وبدون أي توقف لعملياتك البيعية في أقل من 24 ساعة!',
                transferCta: 'تواصل مع فريق الانتقال المجاني',
                
                howSub: 'خطوات البداية',
                howTitle: 'انطلاقتك نحو التجارة الإلكترونية أسهل مما تتخيل',
                howDesc: 'تخلص من التعقيدات التقنية وانتظر دقائق فقط لتصبح مالك متجر إلكتروني متكامل جاهز لاستقبال الطلبات وتحقيق الأرباح.',
                howCta: 'ابدأ خطوتك الأولى الآن مجاناً',
                
                pricingSub: 'باقات شفافة وبدون عمولات',
                pricingTitle: 'باقات مصممة لنمو أعمالك بدون رسوم خفية',
                pricingDesc: 'ابدأ مجاناً لمدة 14 يوماً وقم بالترقية عندما ينمو متجرك. جميع الباقات تتمتع بعمولة 0% على المبيعات!',
                pricingMonthlyToggle: 'دفع شهري',
                pricingAnnualToggle: 'دفع سنوي',
                pricingSaveDiscount: 'وفّر 20% 🔥',
                pricingIncluded: 'المميزات المشمولة:',
                pricingGuarantee: 'جميع الباقات تشمل استضافة سحابية فائقة الأمان، شهادة SSL مجانية، ونسخ احتياطي يومي تلقائي.',
                
                testimSub: 'قصص نجاح ملهمة',
                testimTitle: 'ماذا يقول التجار عن تجربة Fast Order؟',
                testimDesc: 'انضم لأكثر من 1,500 تاجر ورائد أعمال يثقون في منصتنا لتنمية مبيعاتهم وتحقيق أحلامهم يومياً.',
                trustStripTitle: 'شركاء حلول الدفع والشحن المتكاملة',
                
                faqSub: 'إجابات واضحة',
                faqTitle: 'الأسئلة الشائعة حول منصة Fast Order',
                faqDesc: 'هل لديك استفسار آخر؟ يمكنك التواصل مع فريق الدعم الفني المتاح لخدمتك على مدار الساعة.',
                faqContactTitle: 'لم تجد إجابة لسؤالك؟',
                faqContactDesc: 'فريقنا جاهز للإجابة على جميع استفساراتك ومساعدتك في اختيار الباقة الأنسب لمتجرك.',
                faqContactCta: 'تواصل معنا عبر واتساب',
                
                finalCtaSub: '🚀 انطلق نحو النجاح اليوم',
                finalCtaTitle: 'هل أنت مستعد لنقل تجارتك الإلكترونية إلى المستوى التالي؟',
                finalCtaDesc: 'أنشئ متجرك الآن في أقل من 3 دقائق، استمتع بـ 14 يوماً مجاناً، ولا تدفع أي عمولات على مبيعاتك أبداً!',
                finalCtaSupport: 'تواصل مع الدعم الفني',
                
                footerDesc: 'فاست أوردر هي المنصة المتكاملة لبناء وتطوير المتاجر الإلكترونية في الوطن العربي بسرعات فائقة وتقنيات ذكية، مصممة لتمكين التجار من النمو بدون عمولات أو قيود تقنية.',
                footerQuickLinks: 'روابط سريعة',
                footerHelpSupport: 'المساعدة والدعم الفني',
                footerHelpCenter: 'مركز المساعدة والشروحات',
                footerVideoAcademy: 'أكاديمية الفيديو والتطوير',
                footerContactUs: 'تواصل مباشرة',
                footerPhoneLabel: 'رقم الهاتف / الدعم',
                footerEmailLabel: 'البريد الإلكتروني',
                footerHoursLabel: 'ساعات العمل',
                footerHoursValue: 'دعم فني متواصل 24/7',
                footerCopyright: 'جميع الحقوق محفوظة © 2026 فاست أوردر (Fast Order). صُنع بشغف لتمكين التجارة الإلكترونية العربية.',
                footerTerms: 'شروط الاستخدام',
                footerPrivacy: 'سياسة الخصوصية',
                footerRefund: 'سياسة الاسترجاع والأمان',
            },
            en: {
                features: 'Features',
                howItWorks: 'How it Works',
                pricing: 'Pricing & Plans',
                partners: 'Success Partners',
                faq: 'FAQ',
                dashboard: 'Dashboard',
                login: 'Login',
                startNow: 'Start Store Now',
                startFree: 'Start Your Store Free',
                browseFeatures: 'Browse Features',
                heroBadge: '🎉 Smart Theme Engine and Meta/TikTok Pixel Integration released!',
                heroTitle: 'Create Your Integrated E-Commerce Store',
                heroTitleGradient: 'And Launch Sales in Minutes!',
                heroDesc: 'The fastest and smartest platform in the Arab world to manage your e-commerce without coding complexity, with 0% commission and flexible plans to suit your growth.',
                heroCtaStart: 'Start Your Store Free',
                heroCtaFeatures: 'Browse Features',
                heroCheckFree: '14-Day Free Trial',
                heroCheckNoCard: 'No Credit Card Required',
                heroCheckZeroComm: '0% Sales Commission',
                heroCheckSupport: '24/7 Technical Support',
                
                mockupDailySales: 'Daily Sales',
                mockupNewOrders: 'New Orders',
                mockupInProcessing: '🕒 8 Orders Processing',
                mockupLatestOrders: 'Latest Received Orders',
                mockupLive: 'LIVE 🔥',
                mockupCairo: 'Cairo, Nasr City • 1 min ago',
                mockupAlex: 'Alexandria, Smouha • 4 mins ago',
                mockupCod: 'Cash on Delivery',
                mockupOnline: 'Visa / Online',
                mockupActiveTheme: 'Active Theme:',
                mockupPixelConnected: '● Pixel Connected',
                mockupNewSaleNotif: 'New Sale Notification!',
                mockupConvRate: 'Store Conversion Rate',
                mockupAboveAvg: '4.8% (3x higher than average)',
                
                featuresSub: 'Why Choose Fast Order?',
                featuresTitle: 'Everything You Need to Succeed in E-Commerce in One Place',
                featuresDesc: 'Our platform is designed with the latest technologies to provide maximum speed and highest conversion rates, with smart marketing and management tools.',
                
                transferBadge: 'New Update 🚀',
                transferTitle: 'Do you have an existing store and want to transfer to Fast Order?',
                transferDesc: 'Our technical team will transfer your store, products, and customer data for free with zero downtime in less than 24 hours!',
                transferCta: 'Contact Free Migration Team',
                
                howSub: 'Get Started',
                howTitle: 'Your Entry to E-Commerce is Easier Than You Think',
                howDesc: 'Get rid of technical complications and wait only minutes to become an online store owner ready to receive orders and generate profits.',
                howCta: 'Start Your First Step Free',
                
                pricingSub: 'Transparent Pricing & No Commissions',
                pricingTitle: 'Plans Designed to Grow Your Business with No Hidden Fees',
                pricingDesc: 'Start free for 14 days and upgrade as your store grows. All plans enjoy 0% commission on sales!',
                pricingMonthlyToggle: 'Monthly Pay',
                pricingAnnualToggle: 'Annual Pay',
                pricingSaveDiscount: 'Save 20% 🔥',
                pricingIncluded: 'Included Features:',
                pricingGuarantee: 'All plans include ultra-secure cloud hosting, free SSL certificate, and automatic daily backups.',
                
                testimSub: 'Inspiring Success Stories',
                testimTitle: 'What do merchants say about their Fast Order experience?',
                testimDesc: 'Join over 1,500 merchants and entrepreneurs who trust our platform to grow their sales and achieve their dreams.',
                trustStripTitle: 'Integrated Payment and Shipping Partners',
                
                faqSub: 'Clear Answers',
                faqTitle: 'Frequently Asked Questions about Fast Order',
                faqDesc: 'Have another question? You can contact our support team available to serve you around the clock.',
                faqContactTitle: 'Didn\'t find an answer to your question?',
                faqContactDesc: 'Our team is ready to answer all your inquiries and help you choose the best plan for your store.',
                faqContactCta: 'Contact us via WhatsApp',
                
                finalCtaSub: '🚀 Launch Into Success Today',
                finalCtaTitle: 'Ready to Take Your E-Commerce to the Next Level?',
                finalCtaDesc: 'Create your store now in less than 3 minutes, enjoy 14 days free, and never pay any commission on your sales!',
                finalCtaSupport: 'Contact Support',
                
                footerDesc: 'Fast Order is the all-in-one platform for building and growing e-commerce stores in the Arab world with blazing speeds and smart technologies, designed to enable merchants to grow without commissions or tech limits.',
                footerQuickLinks: 'Quick Links',
                footerHelpSupport: 'Help & Technical Support',
                footerHelpCenter: 'Help Center & Tutorials',
                footerVideoAcademy: 'Video Academy & Development',
                footerContactUs: 'Direct Contact',
                footerPhoneLabel: 'Phone / Support',
                footerEmailLabel: 'Email Address',
                footerHoursLabel: 'Business Hours',
                footerHoursValue: '24/7 Continuous Support',
                footerCopyright: 'All Rights Reserved © 2026 Fast Order. Made with passion to empower Arab E-Commerce.',
                footerTerms: 'Terms of Use',
                footerPrivacy: 'Privacy Policy',
                footerRefund: 'Refund & Security Policy',
            }
        };
    </script>

    <!-- Helper trans function to use in Alpine.js components -->
    <div x-data="{ 
        trans(key) {
            return window.translations[lang][key] || key;
        }
    }">

        <!-- Background Glow Orbs -->
        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-100px] right-[10%] w-[450px] h-[450px] rounded-full bg-brand-600/10 dark:bg-brand-600/20 blur-[120px] animate-pulse-slow"></div>
            <div class="absolute top-[20%] left-[5%] w-[400px] h-[400px] rounded-full bg-pink-600/10 dark:bg-pink-600/15 blur-[130px] animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute top-[60%] right-[20%] w-[500px] h-[500px] rounded-full bg-purple-600/5 dark:bg-purple-600/10 blur-[150px]"></div>
        </div>

        <!-- Navigation Bar -->
        <header class="glass-header sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <!-- Brand Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-600 via-indigo-500 to-pink-500 flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-bolt text-white text-xl animate-bounce" style="animation-duration: 2s;"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white font-sans flex items-center gap-1.5">
                            <span x-show="lang === 'ar'">فاست أوردر</span>
                            <span x-show="lang === 'en'">Fast Order</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-brand-500/20 text-brand-600 dark:text-brand-400 border border-brand-500/30 font-bold">PRO</span>
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-gray-400 -mt-1 tracking-wider uppercase font-semibold">Fast Order Platform</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600 dark:text-gray-300">
                    <a href="#features" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-star text-xs text-brand-500"></i> <span x-text="trans('features')"></span>
                    </a>
                    <a href="#how-it-works" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-route text-xs text-pink-500"></i> <span x-text="trans('howItWorks')"></span>
                    </a>
                    <a href="#pricing" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-tags text-xs text-amber-500"></i> <span x-text="trans('pricing')"></span>
                    </a>
                    <a href="#testimonials" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-heart text-xs text-rose-500"></i> <span x-text="trans('partners')"></span>
                    </a>
                    <a href="#faq" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-question text-xs text-cyan-500"></i> <span x-text="trans('faq')"></span>
                    </a>
                </nav>

                <!-- Action Buttons & Controls (Theme / Language Toggle) -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Theme Toggle Button -->
                    <button @click="toggleDarkMode()" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-700 dark:text-gray-300 transition-all" title="Toggle Theme">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-indigo-600'"></i>
                    </button>

                    <!-- Language Toggle Button -->
                    <button @click="setLang(lang === 'ar' ? 'en' : 'ar')" class="px-3.5 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-gray-300 transition-all">
                        <i class="fa-solid fa-globe text-brand-500"></i>
                        <span x-text="lang === 'ar' ? 'EN' : 'العربية'"></span>
                    </button>

                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-800 dark:text-white bg-white dark:bg-dark-card border border-slate-200 dark:border-white/10 hover:border-brand-500/50 hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <i class="fa-solid fa-gauge-high ml-1.5 mr-1.5 text-brand-500 dark:text-brand-400"></i> <span x-text="trans('dashboard')"></span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(Route::has('login') ? route('login') : url('/login')); ?>" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-900 dark:text-gray-300 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                            <span x-text="trans('login')"></span>
                        </a>
                        <a href="<?php echo e(Route::has('register') ? route('register') : '#pricing'); ?>" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-600 via-indigo-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fa-solid fa-rocket ml-1.5 mr-1.5"></i> <span x-text="trans('startNow')"></span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Control Center -->
                <div class="flex items-center gap-2.5 md:hidden">
                    <button @click="toggleDarkMode()" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-700 dark:text-gray-300">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-400' : 'fa-moon text-indigo-600'"></i>
                    </button>
                    <button @click="setLang(lang === 'ar' ? 'en' : 'ar')" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-gray-300">
                        <span x-text="lang === 'ar' ? 'EN' : 'ع'"></span>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 focus:outline-none">
                        <i class="fa-solid text-base" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-4" 
                 class="md:hidden bg-white/95 dark:bg-dark-card/95 border-b border-slate-200 dark:border-white/10 px-6 py-6 space-y-4 backdrop-blur-2xl">
                <div class="flex flex-col space-y-3 font-semibold text-slate-700 dark:text-gray-200">
                    <a href="#features" @click="mobileMenuOpen = false" class="py-2.5 px-3 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center gap-3">
                        <i class="fa-solid fa-star text-brand-500 dark:text-brand-400 w-5"></i> <span x-text="trans('features')"></span>
                    </a>
                    <a href="#how-it-works" @click="mobileMenuOpen = false" class="py-2.5 px-3 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center gap-3">
                        <i class="fa-solid fa-route text-pink-500 dark:text-pink-400 w-5"></i> <span x-text="trans('howItWorks')"></span>
                    </a>
                    <a href="#pricing" @click="mobileMenuOpen = false" class="py-2.5 px-3 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center gap-3">
                        <i class="fa-solid fa-tags text-amber-500 dark:text-amber-400 w-5"></i> <span x-text="trans('pricing')"></span>
                    </a>
                    <a href="#testimonials" @click="mobileMenuOpen = false" class="py-2.5 px-3 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center gap-3">
                        <i class="fa-solid fa-heart text-rose-500 dark:text-rose-400 w-5"></i> <span x-text="trans('partners')"></span>
                    </a>
                    <a href="#faq" @click="mobileMenuOpen = false" class="py-2.5 px-3 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 flex items-center gap-3">
                        <i class="fa-solid fa-circle-question text-cyan-500 dark:text-cyan-400 w-5"></i> <span x-text="trans('faq')"></span>
                    </a>
                </div>
                <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex flex-col gap-3">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="w-full py-3 rounded-xl text-center font-bold text-white bg-brand-600 hover:bg-brand-500 transition-all">
                            <span x-text="trans('dashboard')"></span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(Route::has('login') ? route('login') : url('/login')); ?>" class="w-full py-3 rounded-xl text-center font-bold text-slate-700 dark:text-gray-200 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                            <span x-text="trans('login')"></span>
                        </a>
                        <a href="<?php echo e(Route::has('register') ? route('register') : '#pricing'); ?>" class="w-full py-3 rounded-xl text-center font-bold text-white bg-gradient-to-r from-brand-600 via-indigo-600 to-pink-600 hover:opacity-95 shadow-lg shadow-brand-500/25 transition-all">
                            <i class="fa-solid fa-rocket ml-1.5 mr-1.5"></i> <span x-text="trans('startNow')"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative z-10">

            <!-- Hero Section -->
            <section class="relative pt-12 pb-24 lg:pt-20 lg:pb-32 overflow-hidden bg-grid-pattern">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                        
                        <!-- Hero Content -->
                        <div class="lg:col-span-7 text-center lg:text-right space-y-8 animate-slide-up">
                            
                            <!-- Badge -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500/10 border border-brand-500/25 text-brand-600 dark:text-brand-300 text-sm font-semibold shadow-inner">
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
                                </span>
                                <span x-text="trans('heroBadge')"></span>
                            </div>

                            <!-- Heading -->
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-tight lg:leading-tight text-slate-900 dark:text-white">
                                <span x-text="trans('heroTitle')"></span> <br class="hidden sm:block">
                                <span class="text-gradient" x-text="trans('heroTitleGradient')"></span>
                            </h1>

                            <!-- Description -->
                            <p class="text-lg sm:text-xl text-slate-600 dark:text-gray-300 font-normal leading-relaxed max-w-2xl mx-auto lg:mx-0" x-text="trans('heroDesc')">
                            </p>

                            <!-- CTA Buttons -->
                            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                                <a href="<?php echo e(Route::has('register') ? route('register') : '#pricing'); ?>" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-base text-white bg-gradient-to-r from-brand-600 via-indigo-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:-translate-y-1 transition-all duration-300 text-center flex items-center justify-center gap-2.5 group">
                                    <span x-text="trans('heroCtaStart')"></span>
                                    <i class="fa-solid" :class="lang === 'ar' ? 'fa-arrow-left group-hover:-translate-x-1.5' : 'fa-arrow-right group-hover:translate-x-1.5' + ' transition-transform duration-300 text-pink-300'"></i>
                                </a>
                                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-base text-slate-700 dark:text-gray-300 bg-white dark:bg-dark-card hover:bg-slate-100 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 transition-all text-center flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-layer-group text-brand-500 dark:text-brand-400"></i>
                                    <span x-text="trans('heroCtaFeatures')"></span>
                                </a>
                            </div>

                            <!-- Trust Checkmarks -->
                            <div class="pt-6 border-t border-slate-200 dark:border-white/10 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold text-slate-500 dark:text-gray-400">
                                <div class="flex items-center justify-center lg:justify-start gap-2">
                                    <i class="fa-solid fa-check-circle text-emerald-500 dark:text-emerald-400 text-sm"></i>
                                    <span x-text="trans('heroCheckFree')"></span>
                                </div>
                                <div class="flex items-center justify-center lg:justify-start gap-2">
                                    <i class="fa-solid fa-check-circle text-emerald-500 dark:text-emerald-400 text-sm"></i>
                                    <span x-text="trans('heroCheckNoCard')"></span>
                                </div>
                                <div class="flex items-center justify-center lg:justify-start gap-2">
                                    <i class="fa-solid fa-check-circle text-emerald-500 dark:text-emerald-400 text-sm"></i>
                                    <span x-text="trans('heroCheckZeroComm')"></span>
                                </div>
                                <div class="flex items-center justify-center lg:justify-start gap-2">
                                    <i class="fa-solid fa-check-circle text-emerald-500 dark:text-emerald-400 text-sm"></i>
                                    <span x-text="trans('heroCheckSupport')"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Hero Visual Mockup -->
                        <div class="lg:col-span-5 relative">
                            <div class="relative mx-auto max-w-lg lg:max-w-none animate-float">
                                
                                <!-- Main Dashboard Glass Window -->
                                <div class="glass-card rounded-3xl p-5 border shadow-2xl relative z-10 overflow-hidden">
                                    <!-- Window Header -->
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-white/10 mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-gray-400 font-mono bg-slate-100 dark:bg-dark-bg/60 px-3 py-1 rounded-full border border-slate-200 dark:border-white/5">
                                            https://my-store.fastorder.test/admin
                                        </div>
                                        <div class="w-6"></div>
                                    </div>

                                    <!-- Mockup Content: Stats Grid -->
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-slate-50 dark:bg-dark-bg/80 p-3.5 rounded-2xl border border-slate-200 dark:border-white/5">
                                            <div class="text-[11px] text-slate-500 dark:text-gray-400 font-medium mb-1 flex items-center justify-between">
                                                <span x-text="trans('mockupDailySales')"></span>
                                                <i class="fa-solid fa-arrow-trend-up text-emerald-500 text-xs"></i>
                                            </div>
                                            <div class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">48,520 EGP</div>
                                            <div class="text-[10px] text-emerald-500 font-semibold mt-1" x-show="lang === 'ar'">↑ +24.5% عن أمس</div>
                                            <div class="text-[10px] text-emerald-500 font-semibold mt-1" x-show="lang === 'en'">↑ +24.5% vs yesterday</div>
                                        </div>
                                        <div class="bg-slate-50 dark:bg-dark-bg/80 p-3.5 rounded-2xl border border-slate-200 dark:border-white/5">
                                            <div class="text-[11px] text-slate-500 dark:text-gray-400 font-medium mb-1 flex items-center justify-between">
                                                <span x-text="trans('mockupNewOrders')"></span>
                                                <i class="fa-solid fa-cart-shopping text-brand-500 dark:text-brand-400 text-xs"></i>
                                            </div>
                                            <div class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">142 orders</div>
                                            <div class="text-[10px] text-brand-600 dark:text-brand-300 font-semibold mt-1" x-text="trans('mockupInProcessing')"></div>
                                        </div>
                                    </div>

                                    <!-- Mockup Content: Live Order List -->
                                    <div class="space-y-2.5 bg-slate-100/50 dark:bg-dark-bg/40 p-3 rounded-2xl border border-slate-200 dark:border-white/5">
                                        <div class="text-xs font-bold text-slate-700 dark:text-gray-300 px-1 mb-2 flex items-center justify-between">
                                            <span x-text="trans('mockupLatestOrders')"></span>
                                            <span class="text-[10px] text-pink-500 dark:text-pink-400 bg-pink-500/10 px-2 py-0.5 rounded-full border border-pink-500/20" x-text="trans('mockupLive')"></span>
                                        </div>
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-all">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-500 dark:text-emerald-400 text-xs font-bold font-mono">
                                                    #842
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-slate-800 dark:text-white" x-show="lang === 'ar'">محمد أحمد علي</div>
                                                    <div class="text-xs font-bold text-slate-800 dark:text-white" x-show="lang === 'en'">Mohamed Ahmed</div>
                                                    <div class="text-[10px] text-slate-500 dark:text-gray-400" x-show="lang === 'ar'">القاهرة، مدينة نصر • منذ دقيقة</div>
                                                    <div class="text-[10px] text-slate-500 dark:text-gray-400" x-show="lang === 'en'">Cairo, Nasr City • 1m ago</div>
                                                </div>
                                            </div>
                                            <div class="text-left">
                                                <div class="text-xs font-bold text-emerald-500 dark:text-emerald-400">1,450 EGP</div>
                                                <div class="text-[9px] text-slate-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-1.5 py-0.5 rounded mt-0.5" x-text="trans('mockupCod')"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-all">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-500 dark:text-brand-400 text-xs font-bold font-mono">
                                                    #841
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-slate-800 dark:text-white" x-show="lang === 'ar'">سارة عبد الرحمن</div>
                                                    <div class="text-xs font-bold text-slate-800 dark:text-white" x-show="lang === 'en'">Sarah Abdulrahman</div>
                                                    <div class="text-[10px] text-slate-500 dark:text-gray-400" x-show="lang === 'ar'">الإسكندرية، سموحة • منذ 4 دقائق</div>
                                                    <div class="text-[10px] text-slate-500 dark:text-gray-400" x-show="lang === 'en'">Alexandria, Smouha • 4m ago</div>
                                                </div>
                                            </div>
                                            <div class="text-left">
                                                <div class="text-xs font-bold text-indigo-500 dark:text-indigo-400">890 EGP</div>
                                                <div class="text-[9px] text-slate-500 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-1.5 py-0.5 rounded mt-0.5" x-text="trans('mockupOnline')"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mockup Content: Active Theme -->
                                    <div class="mt-3.5 pt-3 border-t border-slate-200 dark:border-white/10 flex items-center justify-between text-xs text-slate-500 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-palette text-pink-500 dark:text-pink-400"></i>
                                            <span><span x-text="trans('mockupActiveTheme')"></span> <strong class="text-slate-800 dark:text-white">Modern Luxury</strong></span>
                                        </div>
                                        <span class="text-emerald-500 dark:text-emerald-400 font-semibold" x-text="trans('mockupPixelConnected')"></span>
                                    </div>
                                </div>

                                <!-- Floating Element 1 (Top Left) -->
                                <div class="absolute -top-6 -left-6 z-20 bg-white/95 dark:bg-dark-card/90 backdrop-blur-xl border border-slate-200 dark:border-white/15 p-3.5 rounded-2xl shadow-xl flex items-center gap-3 animate-float-delayed">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center text-white text-lg shadow-md shadow-pink-500/30">
                                        <i class="fa-solid fa-bell animate-bounce" style="animation-duration: 3s;"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 dark:text-white" x-text="trans('mockupNewSaleNotif')"></div>
                                        <div class="text-[11px] text-emerald-500 font-semibold">+850 EGP (0% fee)</div>
                                    </div>
                                </div>

                                <!-- Floating Element 2 (Bottom Right) -->
                                <div class="absolute -bottom-6 -right-6 z-20 bg-white/95 dark:bg-dark-card/90 backdrop-blur-xl border border-slate-200 dark:border-white/15 p-3.5 rounded-2xl shadow-xl flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white text-lg shadow-md shadow-brand-500/30">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 dark:text-white" x-text="trans('mockupConvRate')"></div>
                                        <div class="text-[11px] text-brand-600 dark:text-brand-300 font-semibold" x-text="trans('mockupAboveAvg')"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Live Statistics Section -->
            <section class="py-12 border-y border-slate-200 dark:border-white/10 bg-white/50 dark:bg-dark-card/50 backdrop-blur-md relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                        
                        <div class="space-y-1 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-gradient tracking-tight">
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['active_stores']['ar']); ?>' : '<?php echo e($stats['active_stores']['en']); ?>'"></span>
                            </div>
                            <div class="text-sm font-semibold text-slate-500 dark:text-gray-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-store text-brand-500 dark:text-brand-400"></i>
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['active_stores']['label_ar']); ?>' : '<?php echo e($stats['active_stores']['label_en']); ?>'"></span>
                            </div>
                        </div>

                        <div class="space-y-1 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-gradient tracking-tight">
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['daily_orders']['ar']); ?>' : '<?php echo e($stats['daily_orders']['en']); ?>'"></span>
                            </div>
                            <div class="text-sm font-semibold text-slate-500 dark:text-gray-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-bag-shopping text-pink-500 dark:text-pink-400"></i>
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['daily_orders']['label_ar']); ?>' : '<?php echo e($stats['daily_orders']['label_en']); ?>'"></span>
                            </div>
                        </div>

                        <div class="space-y-1 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-gradient tracking-tight">
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['uptime']['ar']); ?>' : '<?php echo e($stats['uptime']['en']); ?>'"></span>
                            </div>
                            <div class="text-sm font-semibold text-slate-500 dark:text-gray-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-server text-emerald-500 dark:text-emerald-400"></i>
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['uptime']['label_ar']); ?>' : '<?php echo e($stats['uptime']['label_en']); ?>'"></span>
                            </div>
                        </div>

                        <div class="space-y-1 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-gradient tracking-tight">
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['monthly_sales']['ar']); ?>' : '<?php echo e($stats['monthly_sales']['en']); ?>'"></span>
                            </div>
                            <div class="text-sm font-semibold text-slate-500 dark:text-gray-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-money-bill-trend-up text-amber-500"></i>
                                <span x-text="lang === 'ar' ? '<?php echo e($stats['monthly_sales']['label_ar']); ?>' : '<?php echo e($stats['monthly_sales']['label_en']); ?>'"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> <span x-text="trans('featuresSub')"></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white" x-text="trans('featuresTitle')">
                        </h2>
                        <p class="text-base sm:text-lg text-slate-500 dark:text-gray-400 font-normal leading-relaxed" x-text="trans('featuresDesc')">
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="glass-card glass-card-hover rounded-3xl p-8 relative overflow-hidden group">
                                
                                <!-- Icon Background Glow -->
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr <?php echo e($feature['color']); ?> flex items-center justify-center text-white text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fa-solid fa-<?php echo e($feature['icon']); ?>"></i>
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-brand-600 dark:group-hover:text-brand-300 transition-colors"
                                    x-text="lang === 'ar' ? '<?php echo e($feature['title_ar']); ?>' : '<?php echo e($feature['title_en']); ?>'">
                                    <?php echo e($feature['title_ar']); ?>

                                </h3>

                                <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed"
                                   x-text="lang === 'ar' ? '<?php echo e($feature['description_ar']); ?>' : '<?php echo e($feature['description_en']); ?>'">
                                    <?php echo e($feature['description_ar']); ?>

                                </p>

                                <!-- Subtle bottom indicator -->
                                <div class="absolute bottom-0 right-0 left-0 h-1 bg-gradient-to-r <?php echo e($feature['color']); ?> opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Extra Highlight Banner -->
                    <div class="mt-16 rounded-3xl bg-gradient-to-r from-brand-500/10 via-indigo-500/5 to-pink-500/10 dark:from-brand-900/60 dark:via-indigo-900/40 dark:to-pink-900/60 border border-brand-500/20 dark:border-brand-500/30 p-8 lg:p-12 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="absolute -right-20 -top-20 w-60 h-60 bg-brand-500/10 dark:bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="space-y-3 text-center lg:text-right relative z-10 max-w-2xl">
                            <span class="px-3 py-1 rounded-full bg-pink-500/10 dark:bg-pink-500/20 text-pink-600 dark:text-pink-300 text-xs font-bold border border-pink-500/20 dark:border-pink-500/30 inline-block" x-text="trans('transferBadge')"></span>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white" x-text="trans('transferTitle')"></h3>
                            <p class="text-slate-600 dark:text-gray-300 text-sm sm:text-base" x-text="trans('transferDesc')"></p>
                        </div>
                        <div class="relative z-10 shrink-0 w-full sm:w-auto">
                            <a href="#contact" class="px-8 py-4 rounded-2xl font-bold text-sm text-slate-800 dark:text-dark-bg bg-white hover:bg-slate-100 dark:hover:bg-gray-100 transition-all shadow-xl shadow-slate-200 dark:shadow-none flex items-center justify-center gap-2">
                                <span x-text="trans('transferCta')"></span>
                                <i class="fa-solid fa-headset text-brand-600"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            <!-- How It Works Section -->
            <section id="how-it-works" class="py-24 bg-slate-100/40 dark:bg-dark-card/30 border-t border-slate-200 dark:border-white/5 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-500 dark:text-pink-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-shoe-prints"></i> <span x-text="trans('howSub')"></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white" x-text="trans('howTitle')">
                        </h2>
                        <p class="text-base sm:text-lg text-slate-500 dark:text-gray-400 font-normal leading-relaxed" x-text="trans('howDesc')">
                        </p>
                    </div>

                    <!-- 3 Steps Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
                        
                        <!-- Connecting line for desktop -->
                        <div class="hidden lg:block absolute top-24 right-1/6 left-1/6 h-0.5 bg-gradient-to-r from-brand-500/10 via-brand-500/40 to-brand-500/10 z-0"></div>

                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative z-10 flex flex-col items-center text-center group">
                                
                                <!-- Step Number Badge -->
                                <div class="w-20 h-20 rounded-3xl bg-white dark:bg-dark-card border-2 border-brand-500/40 flex items-center justify-center text-2xl font-black text-brand-600 dark:text-brand-400 mb-6 shadow-xl shadow-brand-500/5 group-hover:scale-110 group-hover:border-brand-500 group-hover:text-white group-hover:bg-brand-600 transition-all duration-300">
                                    <?php echo e($step['number']); ?>

                                </div>

                                <!-- Step Content -->
                                <div class="glass-card w-full p-8 rounded-3xl border group-hover:border-slate-300 dark:group-hover:border-white/20 transition-all duration-300">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-brand-600 dark:group-hover:text-brand-300 transition-colors"
                                        x-text="lang === 'ar' ? '<?php echo e($step['title_ar']); ?>' : '<?php echo e($step['title_en']); ?>'">
                                        <?php echo e($step['title_ar']); ?>

                                    </h3>
                                    <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed"
                                       x-text="lang === 'ar' ? '<?php echo e($step['description_ar']); ?>' : '<?php echo e($step['description_en']); ?>'">
                                        <?php echo e($step['description_ar']); ?>

                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                    <!-- Action CTA inside How It Works -->
                    <div class="mt-16 text-center">
                        <a href="<?php echo e(Route::has('register') ? route('register') : '#pricing'); ?>" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-bold text-white bg-gradient-to-r from-brand-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-1 transition-all duration-300">
                            <span x-text="trans('howCta')"></span>
                            <i class="fa-solid" :class="lang === 'ar' ? 'fa-arrow-left' : 'fa-arrow-right'"></i>
                        </a>
                    </div>

                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-24 relative overflow-hidden" x-data="{ annual: true }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-tag"></i> <span x-text="trans('pricingSub')"></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white" x-text="trans('pricingTitle')">
                        </h2>
                        <p class="text-base sm:text-lg text-slate-500 dark:text-gray-400 font-normal leading-relaxed" x-text="trans('pricingDesc')">
                        </p>

                        <!-- Monthly / Annual Toggle -->
                        <div class="pt-6 flex items-center justify-center gap-4">
                            <span class="text-sm font-bold text-slate-700 dark:text-gray-300" :class="!annual ? 'text-slate-900 dark:text-white font-extrabold' : 'text-slate-400 dark:text-gray-500'" x-text="trans('pricingMonthlyToggle')"></span>
                            <button type="button" 
                                    @click="annual = !annual" 
                                    class="w-16 h-8 rounded-full bg-slate-200 dark:bg-dark-card border border-slate-300 dark:border-white/20 p-1 transition-colors relative focus:outline-none"
                                    :class="annual ? 'bg-brand-600 border-brand-500 dark:bg-brand-600 dark:border-brand-500' : ''">
                                <div class="w-6 h-6 rounded-full bg-white transition-transform duration-300 shadow-md"
                                     :class="annual ? (lang === 'ar' ? '-translate-x-8' : 'translate-x-8') : 'translate-x-0'"></div>
                            </button>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-300" :class="annual ? 'text-slate-900 dark:text-white font-extrabold' : 'text-slate-400 dark:text-gray-500'" x-text="trans('pricingAnnualToggle')"></span>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-extrabold border border-emerald-500/30" x-text="trans('pricingSaveDiscount')"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="glass-card rounded-3xl p-8 border flex flex-col justify-between relative transition-all duration-500 <?php echo e($plan['featured'] ? 'border-brand-500 shadow-2xl dark:shadow-brand-500/20 bg-white dark:bg-gradient-to-b dark:from-dark-card dark:via-brand-950/20 dark:to-dark-card lg:-translate-y-4' : 'border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20'); ?>">
                                
                                <?php if($plan['featured']): ?>
                                    <!-- Popular Badge -->
                                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-600 to-pink-600 text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-lg shadow-brand-500/30">
                                        ★ <span x-text="lang === 'ar' ? '<?php echo e($plan['badge_ar']); ?>' : '<?php echo e($plan['badge_en']); ?>'"></span>
                                    </div>
                                <?php elseif(isset($plan['badge_ar'])): ?>
                                    <div class="absolute top-6 left-6 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                                        <span x-text="lang === 'ar' ? '<?php echo e($plan['badge_ar']); ?>' : '<?php echo e($plan['badge_en']); ?>'"></span>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <!-- Plan Name & Description -->
                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2"
                                        x-text="lang === 'ar' ? '<?php echo e($plan['name_ar']); ?>' : '<?php echo e($plan['name_en']); ?>'">
                                        <?php echo e($plan['name_ar']); ?>

                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 min-h-[36px] leading-relaxed mb-6"
                                       x-text="lang === 'ar' ? '<?php echo e($plan['description_ar']); ?>' : '<?php echo e($plan['description_en']); ?>'">
                                        <?php echo e($plan['description_ar']); ?>

                                    </p>

                                    <!-- Price Display -->
                                    <div class="mb-8 pb-6 border-b border-slate-200 dark:border-white/10 flex items-baseline gap-2">
                                        <span class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight" x-text="annual ? '<?php echo e($plan['price_yearly']); ?>' : '<?php echo e($plan['price_monthly']); ?>'"></span>
                                        <span class="text-base font-bold text-brand-600 dark:text-brand-400" x-text="lang === 'ar' ? 'ج.م / <?php echo e($plan['period_ar']); ?>' : 'EGP / <?php echo e($plan['period_en']); ?>'"></span>
                                    </div>

                                    <!-- Features List -->
                                    <div class="space-y-4 mb-8">
                                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-gray-400" x-text="trans('pricingIncluded')"></div>
                                        <ul class="space-y-3 text-sm text-slate-600 dark:text-gray-300">
                                            <!-- Arabic Features -->
                                            <template x-if="lang === 'ar'">
                                                <div class="space-y-3">
                                                    <?php $__currentLoopData = $plan['features_ar']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="flex items-start gap-3">
                                                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">
                                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                            </div>
                                                            <span class="leading-snug"><?php echo e($feat); ?></span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </template>
                                            <!-- English Features -->
                                            <template x-if="lang === 'en'">
                                                <div class="space-y-3">
                                                    <?php $__currentLoopData = $plan['features_en']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="flex items-start gap-3">
                                                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">
                                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                            </div>
                                                            <span class="leading-snug"><?php echo e($feat); ?></span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <!-- CTA Button -->
                                <div>
                                    <a href="<?php echo e($plan['cta_link']); ?>" class="w-full py-4 rounded-2xl font-bold text-center block transition-all duration-300 <?php echo e($plan['featured'] ? 'bg-gradient-to-r from-brand-600 via-indigo-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 text-white shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:-translate-y-0.5' : 'bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-800 dark:text-white border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20'); ?>">
                                        <span x-text="lang === 'ar' ? '<?php echo e($plan['cta_text_ar']); ?>' : '<?php echo e($plan['cta_text_en']); ?>'"></span>
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Guarantee Notice -->
                    <div class="mt-12 text-center text-sm text-slate-500 dark:text-gray-400 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-halved text-brand-500 dark:text-brand-400"></i>
                        <span x-text="trans('pricingGuarantee')"></span>
                    </div>

                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="py-24 bg-slate-50 dark:bg-dark-card/45 border-t border-slate-200 dark:border-white/5 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-quote-right"></i> <span x-text="trans('testimSub')"></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                            <span x-text="trans('testimTitle')"></span>
                        </h2>
                        <p class="text-base sm:text-lg text-slate-500 dark:text-gray-400 font-normal leading-relaxed" x-text="trans('testimDesc')">
                        </p>
                    </div>

                    <!-- Testimonials Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="glass-card glass-card-hover rounded-3xl p-8 flex flex-col justify-between relative">
                                
                                <!-- Rating Stars -->
                                <div>
                                    <div class="flex items-center gap-1 text-amber-400 text-sm mb-6">
                                        <?php for($i=0; $i < $testim['rating']; $i++): ?>
                                            <i class="fa-solid fa-star"></i>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- Quote -->
                                    <p class="text-slate-700 dark:text-gray-300 text-sm sm:text-base leading-relaxed mb-8 italic"
                                       x-text="lang === 'ar' ? '<?php echo e($testim['quote_ar']); ?>' : '<?php echo e($testim['quote_en']); ?>'">
                                        "<?php echo e($testim['quote_ar']); ?>"
                                    </p>
                                </div>

                                <!-- Author Profile -->
                                <div class="flex items-center gap-4 pt-6 border-t border-slate-200 dark:border-white/10">
                                    <img src="<?php echo e($testim['avatar']); ?>" alt="<?php echo e($testim['name_ar']); ?>" class="w-12 h-12 rounded-full border border-brand-500/40 shadow-md">
                                    <div>
                                        <div class="text-base font-bold text-slate-900 dark:text-white"
                                             x-text="lang === 'ar' ? '<?php echo e($testim['name_ar']); ?>' : '<?php echo e($testim['name_en']); ?>'">
                                            <?php echo e($testim['name_ar']); ?>

                                        </div>
                                        <div class="text-xs text-brand-600 dark:text-brand-400 font-medium"
                                             x-text="lang === 'ar' ? '<?php echo e($testim['role_ar']); ?>' : '<?php echo e($testim['role_en']); ?>'">
                                            <?php echo e($testim['role_ar']); ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Brand Logos Trust Strip -->
                    <div class="mt-20 pt-12 border-t border-slate-200 dark:border-white/5 text-center">
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-gray-400 mb-8" x-text="trans('trustStripTitle')"></div>
                        <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-16 opacity-60 hover:opacity-90 transition-opacity">
                            <span class="text-lg font-black text-slate-800 dark:text-white tracking-wider flex items-center gap-2"><i class="fa-solid fa-credit-card text-brand-500"></i> VISA & MasterCard</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tracking-wider flex items-center gap-2"><i class="fa-solid fa-truck-fast text-amber-500"></i> Bosta / بوسطة</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tracking-wider flex items-center gap-2"><i class="fa-solid fa-building-columns text-emerald-500"></i> Fawry / فوري</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tracking-wider flex items-center gap-2"><i class="fa-solid fa-box text-pink-500"></i> Mylerz / مايلرز</span>
                            <span class="text-lg font-black text-slate-800 dark:text-white tracking-wider flex items-center gap-2"><i class="fa-solid fa-wallet text-indigo-500"></i> InstaPay / إنستاباي</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-24 relative overflow-hidden" x-data="{ active: null }">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Section Header -->
                    <div class="text-center mb-16 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-circle-question"></i> <span x-text="trans('faqSub')"></span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900 dark:text-white">
                            <span x-text="trans('faqTitle')"></span>
                        </h2>
                        <p class="text-base text-slate-500 dark:text-gray-400 font-normal" x-text="trans('faqDesc')">
                        </p>
                    </div>

                    <!-- Accordion List -->
                    <div class="space-y-4">
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="glass-card rounded-2xl border border-slate-200 dark:border-white/10 overflow-hidden transition-all duration-300"
                                 :class="active === <?php echo e($index); ?> ? 'border-brand-500/50 bg-slate-50 dark:bg-white/[0.03]' : ''">
                                <button @click="active = (active === <?php echo e($index); ?> ? null : <?php echo e($index); ?>)" 
                                        class="w-full px-6 py-5 flex items-center justify-between gap-4 focus:outline-none"
                                        :class="lang === 'ar' ? 'text-right' : 'text-left'">
                                    <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                        <span class="w-7 h-7 rounded-lg bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-bold flex items-center justify-center shrink-0">
                                            Q
                                        </span>
                                        <span x-text="lang === 'ar' ? '<?php echo e($faq['question_ar']); ?>' : '<?php echo e($faq['question_en']); ?>'"><?php echo e($faq['question_ar']); ?></span>
                                    </span>
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 dark:text-gray-400 shrink-0 transition-all duration-300"
                                         :class="active === <?php echo e($index); ?> ? 'rotate-180 bg-brand-500 text-white dark:bg-brand-500 dark:text-white' : ''">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div x-show="active === <?php echo e($index); ?>" 
                                     x-collapse
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 max-h-0"
                                     x-transition:enter-end="opacity-100 max-h-[300px]"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 max-h-[300px]"
                                     x-transition:leave-end="opacity-0 max-h-0">
                                    <div class="px-6 pb-6 pt-2 text-sm sm:text-base text-slate-600 dark:text-gray-300 leading-relaxed border-t border-slate-100 dark:border-white/5"
                                         :class="lang === 'ar' ? 'pr-16' : 'pl-16'"
                                         x-text="lang === 'ar' ? '<?php echo e($faq['answer_ar']); ?>' : '<?php echo e($faq['answer_en']); ?>'">
                                        <?php echo e($faq['answer_ar']); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Still Have Questions Banner -->
                    <div class="mt-12 text-center p-8 rounded-3xl bg-white dark:bg-dark-card border border-slate-200 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="text-right" :class="lang === 'ar' ? 'text-right' : 'text-left'">
                            <div class="text-lg font-bold text-slate-900 dark:text-white mb-1" x-text="trans('faqContactTitle')"></div>
                            <div class="text-sm text-slate-500 dark:text-gray-400" x-text="trans('faqContactDesc')"></div>
                        </div>
                        <a href="https://wa.me/201146520922" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 transition-all shrink-0 flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span x-text="trans('faqContactCta')"></span>
                        </a>
                    </div>

                </div>
            </section>

            <!-- Final CTA Section -->
            <section id="contact" class="py-20 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="rounded-3xl bg-gradient-to-r from-brand-900 via-indigo-900 to-pink-900 border border-brand-500/40 p-8 sm:p-14 lg:p-20 text-center relative overflow-hidden shadow-2xl shadow-brand-950/80">
                        
                        <!-- Background Glows -->
                        <div class="absolute -top-32 -left-32 w-80 h-80 bg-pink-500/30 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-brand-500/30 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                            <span class="px-4 py-1.5 rounded-full bg-white/10 text-white text-xs font-bold border border-white/20 inline-block uppercase tracking-wider" x-text="trans('finalCtaSub')">
                            </span>
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight" x-text="trans('finalCtaTitle')">
                            </h2>
                            <p class="text-base sm:text-lg text-gray-200 font-normal leading-relaxed" x-text="trans('finalCtaDesc')">
                            </p>
                            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                                <a href="<?php echo e(Route::has('register') ? route('register') : '#pricing'); ?>" class="w-full sm:w-auto px-10 py-5 rounded-2xl font-black text-base text-dark-bg bg-white hover:bg-gray-100 shadow-2xl shadow-white/20 hover:shadow-white/30 hover:-translate-y-1 transition-all duration-300 text-center flex items-center justify-center gap-3">
                                    <span x-text="trans('heroCtaStart')"></span>
                                    <i class="fa-solid fa-arrow-left text-brand-600"></i>
                                </a>
                                <a href="mailto:support@fastorder.test" class="w-full sm:w-auto px-8 py-5 rounded-2xl font-bold text-base text-white bg-dark-bg/60 hover:bg-dark-bg/80 border border-white/20 transition-all text-center flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-envelope text-pink-400"></i>
                                    <span x-text="trans('finalCtaSupport')"></span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>

        <!-- Integrated Footer Section -->
        <footer class="bg-slate-100 dark:bg-dark-card/80 border-t border-slate-200 dark:border-white/10 pt-16 pb-12 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 pb-12 border-b border-slate-200 dark:border-white/10">
                    
                    <!-- Col 1: Brand & Bio -->
                    <div class="lg:col-span-4 space-y-6">
                        <a href="/" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-pink-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                                <i class="fa-solid fa-bolt text-lg"></i>
                            </div>
                            <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white font-sans">
                                <span x-show="lang === 'ar'">فاست أوردر</span>
                                <span x-show="lang === 'en'">Fast Order</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-brand-500/20 text-brand-600 dark:text-brand-400 border border-brand-500/30 font-bold">PRO</span>
                            </span>
                        </a>
                        <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed pr-2" x-text="trans('footerDesc')">
                        </p>
                        <div class="flex items-center gap-3 pt-2">
                            <a href="#" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 hover:bg-brand-600 border border-slate-200 dark:border-white/10 hover:border-brand-500 flex items-center justify-center text-slate-500 dark:text-gray-300 hover:text-white transition-all">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 hover:bg-brand-600 border border-slate-200 dark:border-white/10 hover:border-brand-500 flex items-center justify-center text-slate-500 dark:text-gray-300 hover:text-white transition-all">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 hover:bg-brand-600 border border-slate-200 dark:border-white/10 hover:border-brand-500 flex items-center justify-center text-slate-500 dark:text-gray-300 hover:text-white transition-all">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 hover:bg-brand-600 border border-slate-200 dark:border-white/10 hover:border-brand-500 flex items-center justify-center text-slate-500 dark:text-gray-300 hover:text-white transition-all">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Col 2: Quick Links -->
                    <div class="lg:col-span-2 space-y-4">
                        <h4 class="text-base font-bold text-slate-900 dark:text-white tracking-wide" x-text="trans('footerQuickLinks')"></h4>
                        <ul class="space-y-2.5 text-sm font-medium text-slate-500 dark:text-gray-400">
                            <li><a href="#features" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-left text-[10px] text-brand-500"></i> <span x-text="trans('features')"></span></a></li>
                            <li><a href="#how-it-works" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-left text-[10px] text-brand-500"></i> <span x-text="trans('howItWorks')"></span></a></li>
                            <li><a href="#pricing" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-left text-[10px] text-brand-500"></i> <span x-text="trans('pricing')"></span></a></li>
                            <li><a href="#testimonials" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-left text-[10px] text-brand-500"></i> <span x-text="trans('partners')"></span></a></li>
                            <li><a href="#faq" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-left text-[10px] text-brand-500"></i> <span x-text="trans('faq')"></span></a></li>
                        </ul>
                    </div>

                    <!-- Col 3: Support & Resources -->
                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-base font-bold text-slate-900 dark:text-white tracking-wide" x-text="trans('footerHelpSupport')"></h4>
                        <ul class="space-y-2.5 text-sm font-medium text-slate-500 dark:text-gray-400">
                            <li><a href="#" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-book text-pink-500 text-xs"></i> <span x-text="trans('footerHelpCenter')"></span></a></li>
                            <li><a href="https://laracasts.com" target="_blank" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-video text-amber-500 text-xs"></i> <span x-text="trans('footerVideoAcademy')"></span></a></li>
                            <li><a href="#contact" class="hover:text-brand-600 dark:hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-headset text-emerald-500 text-xs"></i> <span x-text="trans('finalCtaSupport')"></span></a></li>
                        </ul>
                    </div>

                    <!-- Col 4: Contact & Info -->
                    <div class="lg:col-span-3 space-y-4">
                        <h4 class="text-base font-bold text-slate-900 dark:text-white tracking-wide" x-text="trans('footerContactUs')"></h4>
                        <div class="space-y-3 text-sm text-slate-700 dark:text-gray-300">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5">
                                <div class="w-9 h-9 rounded-lg bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-400" x-text="trans('footerPhoneLabel')"></div>
                                    <div class="font-bold text-slate-900 dark:text-white font-mono">01146520922</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5">
                                <div class="w-9 h-9 rounded-lg bg-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-400" x-text="trans('footerEmailLabel')"></div>
                                    <div class="font-bold text-slate-900 dark:text-white font-mono text-xs">support@fastorder.test</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5">
                                <div class="w-9 h-9 rounded-lg bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-400" x-text="trans('footerHoursLabel')"></div>
                                    <div class="font-bold text-slate-900 dark:text-white" x-text="trans('footerHoursValue')"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom Copyright & Legal -->
                <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500 dark:text-gray-400">
                    <div x-text="trans('footerCopyright')">
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors" x-text="trans('footerTerms')"></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors" x-text="trans('footerPrivacy')"></a>
                        <a href="#" class="hover:text-slate-900 dark:hover:text-white transition-colors" x-text="trans('footerRefund')"></a>
                    </div>
                </div>

            </div>
        </footer>

    </div>

</body>
</html>
<?php /**PATH E:\programing\flutter project\fast order\resources\views/platform/index.blade.php ENDPATH**/ ?>