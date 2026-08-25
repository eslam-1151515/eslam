<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'أوردر سيف (Order Saif) | أسرع منصة لإنشاء وإدارة المتاجر الإلكترونية في مصر')</title>
    <meta name="description" content="@yield('meta_description', 'المنصة الأسرع والأذكى في مصر لإنشاء متجرك الإلكتروني وزيادة مبيعاتك دون عمولات، مع ربط فوري للبيكسل وباقات مرنة تبدأ من 2ج للأوردر وتجربة مجانية 7 أيام.')">
    <meta name="keywords" content="تجارة إلكترونية, إنشاء متجر إلكتروني, منصة متاجر, أوردر سيف, Order Saif, بدون عمولة, متجر إلكتروني مصر, ربط بيكسل فيسبوك, تسويق إلكتروني">
    <meta name="author" content="Order Saif Platform">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Order Saif — أوردر سيف" />
    <meta property="og:title" content="@yield('og_title', 'أوردر سيف (Order Saif) | منصة التجارة الإلكترونية الأسرع في مصر')" />
    <meta property="og:description" content="@yield('og_description', 'أنشئ متجرك الإلكتروني المتكامل في أقل من 3 دقائق بدون عمولات وبسرعة تحميل خارقة.')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:locale" content="ar_EG" />
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'أوردر سيف | أنشئ متجرك الإلكتروني المتكامل')">
    <meta name="twitter:description" content="@yield('og_description', 'المنصة الأسرع والأذكى لإدارة تجارتك الإلكترونية بدون عمولات على المبيعات وبتقنيات حديثة.')">

    <!-- JSON-LD Structured Data Schema for Google -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "Order Saif",
      "alternateName": "أوردر سيف",
      "operatingSystem": "All",
      "applicationCategory": "BusinessApplication",
      "description": "منصة متكاملة لبناء وتطوير المتاجر الإلكترونية في مصر بدون عمولات وبتقنيات سرعة فائقة.",
      "url": "https://ordersaif.com",
      "offers": {
        "@@type": "Offer",
        "price": "500",
        "priceCurrency": "EGP",
        "availability": "https://schema.org/InStock"
      },
      "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "4.9",
        "ratingCount": "1450"
      }
    }
    </script>
    
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
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        accent: {
                            50: '#fff7ed',
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    },
                    fontFamily: {
                        sans: ['Cairo', 'Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
        }
        .light-glass-header {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .light-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .light-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(99, 102, 241, 0.12);
            border-color: #cbd5e1;
        }
        .text-gradient {
            background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-brand-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Navigation Bar -->
    <header class="light-glass-header sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('main.home') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-500 p-0.5 shadow-sm group-hover:scale-105 transition-transform">
                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center p-1 overflow-hidden">
                        <img src="{{ asset('images/logo2.png') }}?v={{ time() }}" alt="Order Saif" class="w-full h-full object-contain">
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-2xl font-black tracking-tight text-slate-900 font-sans">
                        Order Saif
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-bold text-slate-600">
                <a href="{{ route('main.home') }}#features" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-bolt text-xs text-amber-500"></i> المميزات
                </a>
                <a href="{{ route('main.home') }}#how-it-works" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-route text-xs text-brand-500"></i> كيف تبدأ
                </a>
                <a href="{{ route('main.home') }}#pricing" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-tags text-xs text-emerald-500"></i> الباقات والأسعار
                </a>
                <a href="{{ route('main.home') }}#testimonials" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-star text-xs text-amber-400"></i> آراء العملاء
                </a>
                <a href="{{ route('main.home') }}#faqs" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-question text-xs text-indigo-500"></i> الأسئلة الشائعة
                </a>
                <a href="{{ route('main.contact') }}" class="hover:text-brand-600 transition-colors py-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-headset text-xs text-rose-500"></i> اتصل بنا
                </a>
            </nav>

            <!-- Action Buttons -->
            <div class="hidden md:flex items-center gap-3">
                @if(auth()->check())
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:border-brand-500 hover:text-brand-600 shadow-xs transition-all">
                        <i class="fa-solid fa-gauge-high ml-1.5 text-brand-500"></i> لوحة التحكم
                    </a>
                @else
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:text-brand-600 transition-all">
                        تسجيل الدخول
                    </a>
                    <a href="{{ Route::has('register') ? route('register') : url('/register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 shadow-md shadow-brand-500/25 hover:shadow-lg transition-all">
                        <i class="fa-solid fa-rocket ml-1.5"></i> ابدأ متجرك مجاناً
                    </a>
                @endif
            </div>

            <!-- Mobile Header Control Button -->
            <div class="flex items-center gap-2 md:hidden">
                <a href="{{ Route::has('register') ? route('register') : url('/register') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 shadow-sm">
                    <i class="fa-solid fa-rocket ml-1"></i> ابدأ
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-slate-900" aria-label="القائمة الرئيسية">
                    <i class="fa-solid text-lg" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="md:hidden bg-white border-b border-slate-200 px-4 pt-3 pb-6 space-y-2 absolute w-full left-0 shadow-xl">
            <a href="{{ route('main.home') }}#features" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                المميزات
            </a>
            <a href="{{ route('main.home') }}#how-it-works" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                كيف تبدأ
            </a>
            <a href="{{ route('main.home') }}#pricing" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                الباقات والأسعار
            </a>
            <a href="{{ route('main.home') }}#testimonials" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                آراء العملاء
            </a>
            <a href="{{ route('main.home') }}#faqs" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                الأسئلة الشائعة
            </a>
            <a href="{{ route('main.contact') }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl hover:bg-slate-50 font-bold text-slate-700">
                اتصل بنا
            </a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                @if(auth()->check())
                    <a href="{{ url('/dashboard') }}" class="w-full text-center py-2.5 rounded-xl font-bold text-slate-800 bg-slate-100">
                        لوحة التحكم
                    </a>
                @else
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="w-full text-center py-2.5 rounded-xl font-bold text-slate-700 bg-slate-100">
                        تسجيل الدخول
                    </a>
                    <a href="{{ Route::has('register') ? route('register') : url('/register') }}" class="w-full text-center py-2.5 rounded-xl font-bold text-white bg-brand-600 shadow-sm">
                        ابدأ متجرك الآن
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main id="main-content" tabindex="-1" class="focus:outline-none relative z-10 min-h-[60vh]">
        @yield('content')
    </main>

    <!-- Light Footer Section -->
    <footer class="bg-white border-t border-slate-200 pt-16 pb-12 relative z-10 text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 pb-12 border-b border-slate-200">
                
                <!-- Col 1: Brand & Bio -->
                <div class="lg:col-span-4 space-y-5">
                    <a href="{{ route('main.home') }}" class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-500 p-0.5 shadow-sm">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center p-1 overflow-hidden">
                                <img src="{{ asset('images/logo2.png') }}?v={{ time() }}" alt="Order Saif" class="w-full h-full object-contain">
                            </div>
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900 font-sans">
                            Order Saif
                        </span>
                    </a>
                    <p class="text-slate-500 text-sm leading-relaxed pr-1">
                        أوردر سيف هي المنصة الأسرع والأذكى في مصر لبناء وتطوير المتاجر الإلكترونية وصفحات الهبوط، مصممة لتمكين التجار وأصحاب البراندات من مضاعفة مبيعاتهم بدون عمولات أو قيود تقنية.
                    </p>
                    <div class="flex items-center gap-2.5 pt-1">
                        <a href="https://wa.me/201066571999" target="_blank" aria-label="واتساب" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-emerald-500 hover:text-white flex items-center justify-center text-slate-600 transition-all">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </a>
                        <a href="tel:01066571999" aria-label="اتصال" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-brand-600 hover:text-white flex items-center justify-center text-slate-600 transition-all">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="lg:col-span-2 space-y-3">
                    <h4 class="text-base font-bold text-slate-900 tracking-wide">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm font-semibold text-slate-500">
                        <li><a href="{{ route('main.home') }}#features" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-chevron-left text-[9px] text-brand-500"></i> المميزات</a></li>
                        <li><a href="{{ route('main.home') }}#how-it-works" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-chevron-left text-[9px] text-brand-500"></i> كيف تبدأ</a></li>
                        <li><a href="{{ route('main.home') }}#pricing" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-chevron-left text-[9px] text-brand-500"></i> الباقات والأسعار</a></li>
                        <li><a href="{{ route('main.about') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-chevron-left text-[9px] text-brand-500"></i> من نحن</a></li>
                    </ul>
                </div>

                <!-- Col 3: Support & Resources -->
                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-base font-bold text-slate-900 tracking-wide">الدعم والاتفاقيات</h4>
                    <ul class="space-y-2 text-sm font-semibold text-slate-500">
                        <li><a href="{{ route('main.help') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-circle-question text-xs text-brand-500"></i> مركز المساعدة</a></li>
                        <li><a href="{{ route('main.contact') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-headset text-xs text-emerald-500"></i> التواصل والدعم الفني</a></li>
                        <li><a href="{{ route('main.terms') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-file-contract text-xs text-amber-500"></i> شروط الاستخدام</a></li>
                        <li><a href="{{ route('main.privacy') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-xs text-rose-500"></i> سياسة الخصوصية</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Info -->
                <div class="lg:col-span-3 space-y-3">
                    <h4 class="text-base font-bold text-slate-900 tracking-wide">بيانات التواصل المباشر</h4>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </div>
                            <div>
                                <div class="text-[11px] text-slate-400">الهاتف والواتساب</div>
                                <div class="font-bold text-slate-800 font-mono text-sm">01066571999</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <div>
                                <div class="text-[11px] text-slate-400">البريد الإلكتروني</div>
                                <div class="font-bold text-slate-800 font-mono text-xs">support@ordersaif.com</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Legal -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-400">
                <div>
                    جميع الحقوق محفوظة © {{ date('Y') }} <strong class="text-slate-700">أوردر سيف (Order Saif)</strong>.
                </div>
                <div class="flex items-center gap-5">
                    <a href="{{ route('main.terms') }}" class="hover:text-slate-700 transition-colors">شروط الاستخدام</a>
                    <a href="{{ route('main.privacy') }}" class="hover:text-slate-700 transition-colors">سياسة الخصوصية</a>
                    <a href="{{ route('main.sla') }}" class="hover:text-slate-700 transition-colors">اتفاقية مستوى الخدمة</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Floating Sticky WhatsApp Quick Button -->
    <a href="https://wa.me/201066571999" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="fixed bottom-6 left-6 z-50 group flex items-center p-3.5 rounded-full text-white shadow-2xl hover:scale-105 transition-all duration-300 shadow-emerald-500/30"
       style="background-color: #25D366;"
       title="تواصل معنا عبر الواتساب">
        <i class="fa-brands fa-whatsapp text-3xl shrink-0"></i>
        <span class="max-w-0 opacity-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs group-hover:opacity-100 group-hover:px-2 transition-all duration-300 text-sm font-bold">
            تواصل معنا عبر الواتساب
        </span>
    </a>

</body>
</html>
