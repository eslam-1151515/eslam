<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">إنشاء حساب تاجر</h2>
        <p class="text-gray-600 text-sm">انضم إلينا وابدأ متجرك الإلكتروني في دقائق</p>
    </div>

    <!-- Google Sign-in Button -->
    <div class="mb-5">
        <a href="{{ route('auth.google') }}" 
           class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 text-sm font-semibold text-gray-700 transition duration-150 ease-in-out cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.67 1.48 14.98 1 12 1 7.35 1 3.37 3.68 1.4 7.62l3.87 3c.92-2.75 3.51-4.58 6.73-4.58z"/>
                <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.34H12v4.44h6.44c-.28 1.47-1.11 2.72-2.36 3.56l3.66 2.84c2.14-1.97 3.39-4.87 3.39-8.5z"/>
                <path fill="#FBBC05" d="M5.27 14.18A7.16 7.16 0 0 1 4.9 12c0-.77.13-1.52.37-2.22V6.78H1.4C.51 8.56 0 10.43 0 12s.51 3.44 1.4 5.22l3.87-3.04z"/>
                <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.91l-3.66-2.84c-1.01.68-2.31 1.09-4.3 1.09-3.22 0-5.81-1.83-6.73-4.58l-3.87 3C3.37 20.32 7.35 23 12 23z"/>
            </svg>
            <span>التسجيل السريع عبر Google</span>
        </a>
        <div class="relative flex py-4 items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-xs font-semibold">أو بالطريقة التقليدية</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="rtl space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">الاسم الكامل</label>
            <input id="name" class="arabic-input block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="أدخل اسمك الكامل" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
            <input id="email" class="arabic-input block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   placeholder="أدخل بريدك الإلكتروني" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Phone (Optional) -->
        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف (اختياري)</label>
            <input id="phone" class="arabic-input block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                   type="text" 
                   name="phone" 
                   value="{{ old('phone') }}" 
                   placeholder="أدخل رقم هاتفك" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- Store Name -->
        <div>
            <label for="store_name" class="block text-sm font-semibold text-gray-700 mb-1.5">اسم المتجر</label>
            <input id="store_name" class="arabic-input block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                   type="text" 
                   name="store_name" 
                   value="{{ old('store_name') }}" 
                   required 
                   placeholder="أدخل اسم متجرك (بحد أقصى 4 كلمات)"
                   oninput="let words = this.value.trim().split(/\s+/); if(words.length > 4) { this.value = words.slice(0, 4).join(' '); }" />
            <x-input-error :messages="$errors->get('store_name')" class="mt-1" />
        </div>

        <!-- Store Subdomain -->
        <div>
            <label for="subdomain" class="block text-sm font-semibold text-gray-700 mb-1.5">رابط المتجر (السب دومين)</label>
            <div class="relative flex items-center" dir="ltr">
                <input id="subdomain" class="block w-full px-4 py-2.5 border border-r-0 border-gray-300 rounded-l-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                       type="text" 
                       name="subdomain" 
                       value="{{ old('subdomain', 'store-' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 4)) }}" 
                       required 
                       placeholder="store-link"
                       style="text-align: right; direction: ltr;" />
                <span class="bg-gray-100 border border-gray-300 px-3 py-2.5 rounded-r-lg text-gray-500 font-semibold text-xs" dir="ltr">.{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'fastorder.localhost' }}</span>
            </div>
            <p id="subdomain-check-msg" class="text-xs mt-1.5 font-semibold text-gray-500"></p>
            <x-input-error :messages="$errors->get('subdomain')" class="mt-1" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور</label>
            <div class="relative">
                <input id="password" class="arabic-input block w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                       x-bind:type="show ? 'text' : 'password'"
                       name="password"
                       required 
                       autocomplete="new-password"
                       placeholder="أدخل كلمة المرور" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg x-show="show" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">تأكيد كلمة المرور</label>
            <div class="relative">
                <input id="password_confirmation" class="arabic-input block w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                       x-bind:type="show ? 'text' : 'password'"
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       placeholder="أعد إدخال كلمة المرور" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg x-show="show" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                لديك حساب بالفعل؟ تسجيل الدخول
            </a>

            <button type="submit" class="w-full sm:w-auto flex justify-center py-2.5 px-6 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-[1.02] transition duration-150 ease-in-out cursor-pointer">
                إنشاء
            </button>
        </div>
    </form>

    <!-- Subdomain Generation & Check Script -->
    <script>
        document.getElementById('store_name').addEventListener('input', function() {
            const subdomainInput = document.getElementById('subdomain');
            if (!subdomainInput.dataset.manual) {
                let slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s\-]/g, '') // Remove invalid characters (keep basic English and space)
                    .trim()
                    .replace(/\s+/g, '-'); // Replace spaces with dashes
                
                // If it contains Arabic characters, replace/fallback appropriately
                // (Optional fallback: if the slug is empty because of Arabic characters, let them input manually)
                subdomainInput.value = slug;
                checkSubdomainAvailability(slug);
            }
        });

        let checkTimeout;
        const subdomainInput = document.getElementById('subdomain');
        subdomainInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\-]/g, ''); // Keep only alpha-numeric and dash
            this.value = slug;
            
            clearTimeout(checkTimeout);
            checkTimeout = setTimeout(() => {
                checkSubdomainAvailability(slug);
            }, 450);
        });

        function checkSubdomainAvailability(slug) {
            const msg = document.getElementById('subdomain-check-msg');
            if (!slug) {
                msg.innerHTML = '';
                return;
            }
            msg.innerHTML = 'جاري التحقق من توافر الرابط...';
            msg.className = 'text-xs mt-1.5 font-semibold text-gray-500';

            fetch(`/check-subdomain?subdomain=${slug}`)
                .then(res => res.json())
                .then(data => {
                    msg.innerHTML = data.message;
                    if (data.available) {
                        msg.className = 'text-xs mt-1.5 font-semibold text-green-600';
                    } else {
                        msg.className = 'text-xs mt-1.5 font-semibold text-red-600';
                    }
                })
                .catch(() => {
                    msg.innerHTML = 'خطأ في الاتصال بالخادم';
                    msg.className = 'text-xs mt-1.5 font-semibold text-red-600';
                });
        }
    </script>
</x-guest-layout>
