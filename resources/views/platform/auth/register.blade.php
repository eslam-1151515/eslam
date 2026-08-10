<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل تاجر جديد - {{ config('app.name', 'فاست أوردر') }}</title>

    <!-- Google Fonts: Cairo & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#effefb',
                            100: '#cbfef6',
                            200: '#97fded',
                            300: '#5bf9e2',
                            400: '#27ebd2',
                            500: '#0ecdb6',
                            600: '#07a493',
                            700: '#0a8377',
                            800: '#0d675f',
                            900: '#10544f',
                            950: '#043230',
                        },
                        accent: {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                        }
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(14, 205, 182, 0.15)',
                        'glass-lg': '0 12px 48px 0 rgba(0, 0, 0, 0.25)',
                        'glow': '0 0 25px rgba(14, 205, 182, 0.4)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN for Interactive Wizard -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #060913;
            color: #f3f4f6;
            overflow-x: hidden;
        }
        /* Animated Gradient Background */
        .bg-ambient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(135deg, #060913 0%, #0a1128 50%, #060913 100%);
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.35;
            animation: float 20s infinite ease-in-out alternate;
        }
        .blob-1 {
            top: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: #0ecdb6;
            animation-delay: 0s;
        }
        .blob-2 {
            bottom: -15%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: #7c3aed;
            animation-delay: -5s;
        }
        .blob-3 {
            top: 40%;
            left: 30%;
            width: 400px;
            height: 400px;
            background: #3b82f6;
            animation-delay: -10s;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -50px) scale(1.1); }
            100% { transform: translate(-30px, 30px) scale(0.95); }
        }
        /* Glassmorphism Card */
        .glass-card {
            background: rgba(18, 25, 43, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #060913; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #0ecdb6; }
        
        .step-transition {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8 relative">

    <!-- Ambient Glow Background -->
    <div class="bg-ambient">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Header Logo & Nav -->
    <header class="max-w-5xl mx-auto w-full flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-400 flex items-center justify-center shadow-glow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <span class="text-2xl font-black tracking-tight bg-gradient-to-l from-white via-gray-200 to-brand-300 bg-clip-text text-transparent">فاست أوردر</span>
                <span class="block text-xs text-brand-400 font-semibold uppercase tracking-widest">منصة المتاجر الذكية</span>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span class="text-gray-400 hidden sm:inline">لديك حساب بالفعل؟</span>
            <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="px-5 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium transition duration-200 flex items-center gap-2">
                <span>تسجيل الدخول</span>
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </header>

    <!-- Wizard Container -->
    <main class="max-w-4xl mx-auto w-full flex-1 flex flex-col justify-center" x-data="merchantWizard()">
        <div class="glass-card rounded-3xl p-6 sm:p-10 relative overflow-hidden">
            
            <!-- Glow Ribbon -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Title & Progress Bar -->
            <div class="mb-10">
                <div class="text-center max-w-xl mx-auto mb-8">
                    <h1 class="text-2xl sm:text-3xl font-black text-white mb-2" x-text="stepTitles[currentStep - 1]"></h1>
                    <p class="text-gray-400 text-sm sm:text-base" x-text="stepDescriptions[currentStep - 1]"></p>
                </div>

                <!-- Step Tracker -->
                <div class="relative">
                    <div class="overflow-hidden h-1.5 mb-6 text-xs flex rounded-full bg-white/10">
                        <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-l from-brand-400 via-brand-500 to-accent-500 transition-all duration-500 ease-out" :style="'width: ' + ((currentStep / 5) * 100) + '%'"></div>
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <template x-for="step in 5" :key="step">
                            <div class="flex flex-col items-center cursor-pointer" @click="step < currentStep ? goToStep(step) : null">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                                     :class="step === currentStep ? 'bg-brand-500 text-white shadow-glow scale-110 ring-4 ring-brand-500/20' : (step < currentStep ? 'bg-brand-500/20 text-brand-400 border border-brand-500/40' : 'bg-white/5 text-gray-500 border border-white/5')">
                                    <template x-if="step < currentStep">
                                        <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <template x-if="step >= currentStep">
                                        <span x-text="step"></span>
                                    </template>
                                </div>
                                <span class="text-[11px] font-medium mt-2 hidden sm:block transition-colors duration-200"
                                      :class="step === currentStep ? 'text-brand-300 font-bold' : (step < currentStep ? 'text-gray-300' : 'text-gray-600')"
                                      x-text="stepNames[step - 1]"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-300 text-sm flex items-start gap-3 animate-shake">
                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold mb-1">يرجى تصحيح الأخطاء التالية:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs text-red-300/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form id="wizardForm" method="POST" action="{{ Route::has('merchant.register.submit') ? route('merchant.register.submit') : (Route::has('register') ? route('register') : url('/register')) }}" @submit="handleSubmit($event)">
                @csrf
                @if ($googleUser)
                    <input type="hidden" name="is_google" value="1">
                @endif
                <input type="hidden" name="plan_id" :value="form.plan_id">

                <!-- STEP 1: Email / Google OAuth -->
                <div x-show="currentStep === 1" x-transition:enter="step-transition" x-cloak>
                    @if ($googleUser)
                        <div class="p-5 rounded-2xl bg-brand-500/10 border border-brand-500/30 flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-brand-600 shadow">
                                    G
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-sm">تم الربط بحساب Google بنجاح</h4>
                                    <p class="text-brand-300 text-xs">{{ $googleUser['email'] ?? '' }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 text-xs font-semibold">موثق</span>
                        </div>
                        <input type="hidden" name="email" value="{{ $googleUser['email'] ?? '' }}">
                    @else
                        <!-- Google OAuth Button -->
                        <div class="mb-6">
                            <a href="{{ Route::has('auth.google') ? route('auth.google') : url('/auth/google') }}"
                               class="w-full py-3.5 px-4 rounded-xl bg-white hover:bg-gray-100 text-gray-900 font-bold text-sm flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transition duration-200">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>التسجيل السريع بواسطة Google</span>
                            </a>
                        </div>

                        <div class="relative flex items-center justify-center mb-6">
                            <div class="border-t border-white/10 w-full"></div>
                            <span class="bg-[#12192b] px-4 text-xs text-gray-400 font-medium absolute">أو التسجيل عبر البريد الإلكتروني</span>
                        </div>

                        <!-- Email Input -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">البريد الإلكتروني <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <input type="email" name="email" x-model="form.email"
                                           class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition"
                                           placeholder="merchant@example.com" required>
                                    <div class="absolute left-3 top-3.5 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2">كلمة المرور <span class="text-red-400">*</span></label>
                                    <input type="password" name="password" x-model="form.password"
                                           class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition"
                                           placeholder="8 أحرف على الأقل">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2">تأكيد كلمة المرور <span class="text-red-400">*</span></label>
                                    <input type="password" name="password_confirmation" x-model="form.password_confirmation"
                                           class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition"
                                           placeholder="أعد كتابة كلمة المرور">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- STEP 2: Merchant Details (Name & Phone) -->
                <div x-show="currentStep === 2" x-transition:enter="step-transition" x-cloak>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">الاسم الكامل للتاجر / المسؤول <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input type="text" name="name" x-model="form.name"
                                       class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition"
                                       placeholder="مثال: أحمد محمد" required>
                                <div class="absolute left-3 top-3.5 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">رقم الهاتف أو الواتساب <span class="text-gray-500 text-xs font-normal">(اختياري)</span></label>
                            <div class="relative">
                                <input type="tel" name="phone" x-model="form.phone"
                                       class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition text-left"
                                       dir="ltr" placeholder="+20 100 000 0000">
                                <div class="absolute right-3 top-3.5 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-brand-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                سنستخدم هذا الرقم لإرسال تنبيهات الطلبات الهامة وتفعيل حسابك.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Store Details (Name, Slug, Activity) -->
                <div x-show="currentStep === 3" x-transition:enter="step-transition" x-cloak>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">اسم المتجر <span class="text-red-400">*</span></label>
                            <input type="text" name="store_name" x-model="form.store_name" @input="autoGenerateSlug()"
                                   class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm transition"
                                   placeholder="مثال: متجر الأناقة الحديثة" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">رابط المتجر الإلكتروني (Domain Slug) <span class="text-red-400">*</span></label>
                            <div class="relative flex items-center" dir="ltr">
                                <span class="bg-white/5 border border-r-0 border-white/10 rounded-l-xl px-3.5 py-3.5 text-gray-400 text-xs sm:text-sm font-mono whitespace-nowrap">https://</span>
                                <input type="text" name="slug" x-model="form.slug" @input.debounce.500ms="checkSlugAvailability()"
                                       class="w-full bg-black/40 border-y border-white/10 px-3 py-3.5 text-brand-300 font-mono text-sm focus:outline-none focus:border-brand-500 transition"
                                       placeholder="my-store" required>
                                <span class="bg-white/5 border border-l-0 border-white/10 rounded-r-xl px-3.5 py-3.5 text-gray-400 text-xs sm:text-sm font-mono whitespace-nowrap">.fastorder.com</span>
                            </div>
                            
                            <!-- Slug Availability Feedback -->
                            <div class="mt-2 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <template x-if="slugStatus === 'checking'">
                                        <span class="text-gray-400 flex items-center gap-1">
                                            <svg class="animate-spin w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            جاري فحص إتاحة الرابط...
                                        </span>
                                    </template>
                                    <template x-if="slugStatus === 'available'">
                                        <span class="text-green-400 flex items-center gap-1 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span x-text="slugMessage"></span>
                                        </span>
                                    </template>
                                    <template x-if="slugStatus === 'taken' || slugStatus === 'error'">
                                        <span class="text-red-400 flex items-center gap-1 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span x-text="slugMessage"></span>
                                        </span>
                                    </template>
                                </div>
                                <span class="text-gray-500 font-mono text-[11px]" x-show="form.slug">رابطك: <span class="text-brand-300 font-bold" x-text="form.slug + '.fastorder.com'"></span></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-3">نشاط المتجر التجاري</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <template x-for="(cat, index) in activities" :key="index">
                                    <div @click="form.activity = cat.name"
                                         class="cursor-pointer border rounded-xl p-3 text-center transition duration-200 flex flex-col items-center justify-center gap-2"
                                         :class="form.activity === cat.name ? 'bg-brand-500/20 border-brand-500 text-white shadow-glow' : 'bg-black/20 border-white/10 text-gray-400 hover:border-white/20 hover:text-gray-200'">
                                        <span class="text-2xl" x-text="cat.icon"></span>
                                        <span class="text-xs font-semibold" x-text="cat.name"></span>
                                    </div>
                                </template>
                            </div>
                            <input type="hidden" name="activity" :value="form.activity">
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Plan Selection -->
                <div x-show="currentStep === 4" x-transition:enter="step-transition" x-cloak>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        @forelse ($plans as $plan)
                            <div @click="form.plan_id = '{{ $plan->id }}'; form.plan_name = '{{ $plan->name }}'"
                                 class="cursor-pointer rounded-2xl p-5 border transition-all duration-300 relative flex flex-col justify-between"
                                 :class="form.plan_id == '{{ $plan->id }}' ? 'bg-gradient-to-b from-brand-900/40 to-black border-brand-400 shadow-glow scale-[1.02]' : 'bg-black/40 border-white/10 hover:border-white/20'">
                                
                                @if ($loop->first || $plan->slug === 'pro')
                                    <span class="absolute -top-3 left-4 bg-gradient-to-r from-brand-500 to-accent-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow">الأكثر طلباً</span>
                                @endif

                                <div>
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-bold text-lg text-white">{{ $plan->name }}</h3>
                                        <div class="w-5 h-5 rounded-full border flex items-center justify-center transition"
                                             :class="form.plan_id == '{{ $plan->id }}' ? 'border-brand-400 bg-brand-400 text-black font-black text-xs' : 'border-gray-600'">
                                            <template x-if="form.plan_id == '{{ $plan->id }}'">✓</template>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mb-4">{{ $plan->description ?? 'باقة ممتازة لإدارة متجرك بكفاءة وسرعة.' }}</p>
                                    <div class="mb-5">
                                        <span class="text-2xl font-black text-white">{{ number_format($plan->price_monthly ?? 0) }}</span>
                                        <span class="text-xs text-brand-300 font-semibold"> ج.م / شهر</span>
                                    </div>
                                    <ul class="space-y-2.5 text-xs text-gray-300 border-t border-white/5 pt-4">
                                        <li class="flex items-center gap-2">
                                            <span class="text-brand-400 font-bold">✓</span>
                                            <span><strong>{{ $plan->trial_days ?? 7 }} أيام</strong> تجربة مجانية</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-brand-400 font-bold">✓</span>
                                            <span>إدارة المنتجات والطلبات</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="text-brand-400 font-bold">✓</span>
                                            <span>دعم فني متواصل</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-6 pt-3 text-center border-t border-white/5">
                                    <span class="text-[11px] font-semibold" :class="form.plan_id == '{{ $plan->id }}' ? 'text-brand-300' : 'text-gray-500'">اختر هذه الباقة</span>
                                </div>
                            </div>
                        @empty
                            <!-- Fallback Plans if DB is empty -->
                            <template x-for="fallbackPlan in fallbackPlans" :key="fallbackPlan.id">
                                <div @click="form.plan_id = fallbackPlan.id; form.plan_name = fallbackPlan.name"
                                     class="cursor-pointer rounded-2xl p-5 border transition-all duration-300 relative flex flex-col justify-between"
                                     :class="form.plan_id == fallbackPlan.id ? 'bg-gradient-to-b from-brand-900/40 to-black border-brand-400 shadow-glow scale-[1.02]' : 'bg-black/40 border-white/10 hover:border-white/20'">
                                    
                                    <template x-if="fallbackPlan.popular">
                                        <span class="absolute -top-3 left-4 bg-gradient-to-r from-brand-500 to-accent-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow">الأكثر شهرة</span>
                                    </template>

                                    <div>
                                        <div class="flex justify-between items-start mb-3">
                                            <h3 class="font-bold text-lg text-white" x-text="fallbackPlan.name"></h3>
                                            <div class="w-5 h-5 rounded-full border flex items-center justify-center transition"
                                                 :class="form.plan_id == fallbackPlan.id ? 'border-brand-400 bg-brand-400 text-black font-black text-xs' : 'border-gray-600'">
                                                <template x-if="form.plan_id == fallbackPlan.id">✓</template>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400 mb-4" x-text="fallbackPlan.desc"></p>
                                        <div class="mb-5">
                                            <span class="text-2xl font-black text-white" x-text="fallbackPlan.price"></span>
                                            <span class="text-xs text-brand-300 font-semibold"> ج.م / شهر</span>
                                        </div>
                                        <ul class="space-y-2.5 text-xs text-gray-300 border-t border-white/5 pt-4">
                                            <li class="flex items-center gap-2">
                                                <span class="text-brand-400 font-bold">✓</span>
                                                <span><strong>7 أيام</strong> تجربة مجانية</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="text-brand-400 font-bold">✓</span>
                                                <span x-text="fallbackPlan.features"></span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="text-brand-400 font-bold">✓</span>
                                                <span>تحويل فوري للوحة التحكم</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-6 pt-3 text-center border-t border-white/5">
                                        <span class="text-[11px] font-semibold" :class="form.plan_id == fallbackPlan.id ? 'text-brand-300' : 'text-gray-500'">اختر هذه الباقة</span>
                                    </div>
                                </div>
                            </template>
                        @endforelse
                    </div>
                </div>

                <!-- STEP 5: Activation & Confirmation -->
                <div x-show="currentStep === 5" x-transition:enter="step-transition" x-cloak>
                    <div class="bg-gradient-to-b from-brand-900/30 via-black/40 to-black/60 rounded-2xl p-6 border border-brand-500/30 text-center mb-6 relative overflow-hidden">
                        
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-500 to-accent-500 text-white mx-auto flex items-center justify-center mb-4 shadow-glow scale-110 animate-bounce">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">أنت على بعد خطوة واحدة من إطلاق متجرك!</h3>
                        <p class="text-sm text-brand-200/80 max-w-md mx-auto mb-6">سيتم تفعيل الفترة التجريبية المجانية لمدة <strong class="text-white font-bold">7 أيام</strong> تلقائياً، دون الحاجة لإدخال أي بطاقة بنكية الآن.</p>

                        <!-- Summary Table -->
                        <div class="bg-black/50 rounded-xl p-4 text-right space-y-3 border border-white/10 text-xs sm:text-sm max-w-lg mx-auto">
                            <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                                <span class="text-gray-400">الاسم والمسؤول:</span>
                                <span class="font-semibold text-white" x-text="form.name || '---'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                                <span class="text-gray-400">البريد الإلكتروني:</span>
                                <span class="font-semibold text-white" x-text="form.email || '{{ $googleUser['email'] ?? '---' }}'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                                <span class="text-gray-400">رقم الهاتف:</span>
                                <span class="font-semibold text-white" x-text="form.phone || '---'" dir="ltr"></span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                                <span class="text-gray-400">اسم المتجر:</span>
                                <span class="font-semibold text-brand-300" x-text="form.store_name || '---'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                                <span class="text-gray-400">رابط المتجر:</span>
                                <span class="font-mono text-brand-300 font-bold" dir="ltr" x-text="form.slug ? form.slug + '.fastorder.com' : '---'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-gray-400">الباقة المختارة:</span>
                                <span class="px-2.5 py-0.5 rounded bg-brand-500/20 text-brand-300 font-bold" x-text="form.plan_name || 'الباقة الأساسية (تجربة مجانية)'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Actions / Navigation Buttons -->
                <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between gap-4">
                    <button type="button" @click="prevStep()" x-show="currentStep > 1"
                            class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span>السابق</span>
                    </button>
                    <div x-show="currentStep === 1"></div>

                    <!-- Next Button -->
                    <button type="button" @click="nextStep()" x-show="currentStep < 5"
                            class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-bold text-sm shadow-glow transition duration-200 flex items-center gap-2">
                        <span>التالي</span>
                        <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Final Submit Button -->
                    <button type="submit" x-show="currentStep === 5" :disabled="submitting"
                            class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-brand-500 via-brand-600 to-accent-600 hover:opacity-95 text-white font-black text-sm sm:text-base shadow-glow transition duration-200 flex items-center justify-center gap-3 w-full sm:w-auto disabled:opacity-50">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <span>تفعيل المتجر والتحويل للوحة التحكم</span>
                                <span>🚀</span>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>جاري إنشاء متجرك وإعداد بيئة العمل...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-4xl mx-auto w-full text-center mt-8 text-xs text-gray-500">
        <p>© {{ date('Y') }} فاست أوردر (Fast Order). جميع الحقوق محفوظة. بتسجيلك أنت توافق على <a href="#" class="text-gray-400 underline">شروط الخدمة</a> و <a href="#" class="text-gray-400 underline">سياسة الخصوصية</a>.</p>
    </footer>

    <!-- Alpine.js Wizard Logic -->
    <script>
        function merchantWizard() {
            return {
                currentStep: 1,
                submitting: false,
                slugStatus: '', // 'checking', 'available', 'taken', 'error'
                slugMessage: '',
                stepNames: ['الحساب', 'التاجر', 'المتجر', 'الباقة', 'التفعيل'],
                stepTitles: [
                    'إنشاء حساب جديد أو الدخول السريع',
                    'بيانات التاجر ورقم التواصل',
                    'تفاصيل متجرك الإلكتروني الجديد',
                    'اختر الباقة المناسبة لطموحك',
                    'تأكيد وإطلاق المتجر فوراً'
                ],
                stepDescriptions: [
                    'ابدأ رحلتك في التجارة الإلكترونية بإنشاء حسابك المؤمن',
                    'أدخل اسمك ورقم الهاتف لتلقي إشعارات الطلبات وتفعيل المتجر',
                    'اختر اسماً مميزاً ورابطاً فريداً لمتجرك ليتمكن العملاء من الوصول إليك',
                    'اختر الباقة التي تلبي احتياجاتك. جميع الباقات تشمل فترة تجريبية مجانية',
                    'راجع بياناتك وسنقوم بإنشاء لوحة التحكم وتفعيل الـ 7 أيام المجانية'
                ],
                activities: [
                    { name: 'ملابس وأزياء', icon: '👗' },
                    { name: 'إلكترونيات وتقنية', icon: '📱' },
                    { name: 'مطاعم ومقاهي', icon: '🍔' },
                    { name: 'تجميل وعطور', icon: '💄' },
                    { name: 'أثاث وديكور', icon: '🛋️' },
                    { name: 'تجارة عامة وأخرى', icon: '🛍️' }
                ],
                fallbackPlans: [
                    { id: 1, name: 'الباقة الأساسية', desc: 'مثالية للمتاجر الناشئة وبدء المبيعات السريعة.', price: '299', features: 'حتى 100 منتج و500 طلب شهرياً', popular: false },
                    { id: 2, name: 'الباقة الاحترافية', desc: 'الخيار الأفضل للنمو وزيادة المبيعات والتقارير.', price: '599', features: 'منتجات وطلبات غير محدودة + تقارير متقدمة', popular: true },
                    { id: 3, name: 'باقة الشركات', desc: 'للعلامات التجارية الكبرى وأدوات الربط المخصصة.', price: '999', features: 'دعم خاص + ربط شركات الشحن والدفع المتعددة', popular: false }
                ],
                form: {
                    email: '{{ old('email', $googleUser['email'] ?? '') }}',
                    password: '',
                    password_confirmation: '',
                    name: '{{ old('name', $googleUser['name'] ?? '') }}',
                    phone: '{{ old('phone') }}',
                    store_name: '{{ old('store_name') }}',
                    slug: '{{ old('slug') }}',
                    activity: '{{ old('activity', 'ملابس وأزياء') }}',
                    plan_id: '{{ old('plan_id', '') }}',
                    plan_name: ''
                },
                init() {
                    @if($googleUser)
                        // If returning from Google OAuth, skip directly to step 2 if email is present
                        this.currentStep = 2;
                    @endif
                    @if($errors->any())
                        // Check which step generated error
                        @if($errors->has('store_name') || $errors->has('slug'))
                            this.currentStep = 3;
                        @elseif($errors->has('name') || $errors->has('phone'))
                            this.currentStep = 2;
                        @else
                            this.currentStep = 1;
                        @endif
                    @endif
                },
                goToStep(step) {
                    this.currentStep = step;
                },
                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                validateCurrentStep() {
                    @if(!$googleUser)
                    if (this.currentStep === 1) {
                        if (!this.form.email || !this.form.password) {
                            alert('يرجى إدخال البريد الإلكتروني وكلمة المرور للمتابعة.');
                            return false;
                        }
                        if (this.form.password !== this.form.password_confirmation) {
                            alert('تأكيد كلمة المرور غير متطابق.');
                            return false;
                        }
                        if (this.form.password.length < 8) {
                            alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل.');
                            return false;
                        }
                    }
                    @endif

                    if (this.currentStep === 2) {
                        if (!this.form.name) {
                            alert('يرجى إدخال الاسم الكامل للمتابعة.');
                            return false;
                        }
                    }

                    if (this.currentStep === 3) {
                        if (!this.form.store_name || !this.form.slug) {
                            alert('يرجى إدخال اسم المتجر ورابط المتجر (Slug) للمتابعة.');
                            return false;
                        }
                        if (this.slugStatus === 'taken') {
                            alert('رابط المتجر مستخدم بالفعل، يرجى اختيار رابط آخر.');
                            return false;
                        }
                    }

                    return true;
                },
                autoGenerateSlug() {
                    if (!this.form.slug || this.form.slug === '' || this.form.slug === this.lastAutoSlug) {
                        let slug = this.form.store_name
                            .toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                        if (slug) {
                            this.form.slug = slug;
                            this.lastAutoSlug = slug;
                            this.checkSlugAvailability();
                        }
                    }
                },
                async checkSlugAvailability() {
                    if (!this.form.slug) {
                        this.slugStatus = '';
                        this.slugMessage = '';
                        return;
                    }
                    this.form.slug = this.form.slug.toLowerCase().replace(/[^a-z0-9_-]/g, '-').replace(/--+/g, '-');
                    this.slugStatus = 'checking';
                    try {
                        const response = await fetch('{{ url('/register/check-slug') }}?slug=' + encodeURIComponent(this.form.slug));
                        const data = await response.json();
                        this.slugStatus = data.available ? 'available' : 'taken';
                        this.slugMessage = data.message;
                    } catch (error) {
                        this.slugStatus = 'error';
                        this.slugMessage = 'تعذر فحص الرابط الآن';
                    }
                },
                handleSubmit(e) {
                    if (!this.validateCurrentStep()) {
                        e.preventDefault();
                        return false;
                    }
                    this.submitting = true;
                }
            }
        }
    </script>
</body>
</html>
