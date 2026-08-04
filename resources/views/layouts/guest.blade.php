<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>تسجيل الدخول - {{ $storeName ?? config('app.name', 'Store') }}</title>

        <!-- Arabic Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="font-family: 'Cairo', sans-serif;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 login-container">
            <div class="mb-6">
                <a href="/">
                    @php
                        $logoPath = \App\Models\Setting::get('logo');
                        $logoUrl  = $logoPath
                            ? asset('storage/' . $logoPath)
                            : asset('images/logo.png?v=202604031');
                    @endphp
                    <img src="{{ $logoUrl }}" alt="شعار الشركة" class="object-cover rounded-full drop-shadow-2xl border-4 border-white/30" style="width:230px;height:230px;" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 login-card shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
