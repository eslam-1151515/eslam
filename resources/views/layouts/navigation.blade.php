<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sm:hidden">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between py-2 items-start">
            <div class="flex flex-col items-end gap-1">
                <!-- Brand: Logo + Name -->
                <div class="flex items-center gap-2">
                    <a href="/admin/dashboard" class="inline-flex items-center gap-2" aria-label="{{ __('لوحة التحكم - أوردر سيف') }}">
                        <img src="{{ asset('images/logo2.png') }}?v={{ time() }}" alt="Order Saif" class="h-9 w-auto max-h-9 object-contain shrink-0" />
                        <span class="text-lg font-bold text-gray-800 leading-none">Order Saif</span>
                    </a>
                </div>

                <!-- Navigation Links hidden (we'll use drawer instead) -->
                <div class="hidden">
                    <x-nav-link :href="'/admin/dashboard'" :active="request()->is('admin/dashboard')">
                        {{ __('الرئيسية') }}
                    </x-nav-link>
                    <x-nav-link :href="'/admin/categories'" :active="request()->is('admin/categories*')">
                        {{ __('الأقسام') }}
                    </x-nav-link>
                    <x-nav-link :href="'/admin/products'" :active="request()->is('admin/products*')">
                        {{ __('المنتجات') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:me-6 gap-2">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" aria-label="{{ __('قائمة الحساب') }}">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="'/admin/profile'">
                            {{ __('الملف الشخصي') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="/admin/logout">
                            @csrf

                            <x-dropdown-link :href="'/admin/logout'"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                  {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" aria-label="{{ __('القائمة الرئيسية') }}" :aria-expanded="open.toString()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" id="mobile-menu" role="menu">
        <div class="px-4 pt-3 pb-2 border-b border-gray-200">
            <a href="/admin/dashboard" class="inline-flex items-center gap-2" aria-label="{{ __('شعار بيرد') }}">
                @php
                    $logoPath = public_path('images/logo.png?v=202604031');
                @endphp
                @if (file_exists($logoPath))
                    <img src="{{ asset('images/logo.png?v=202604031') }}" alt="{{ __('شعار بيرد') }}" class="h-9 w-9 max-h-9 max-w-9 rounded-full border border-gray-200 object-cover shrink-0" width="36" height="36" style="width:36px;height:36px;" />
                @else
                    <img src="https://dummyimage.com/36x36/111827/ffffff&text=SM" alt="{{ __('شعار بيرد') }}" class="h-9 w-9 max-h-9 max-w-9 rounded-full border border-gray-200 object-cover shrink-0" width="36" height="36" style="width:36px;height:36px;" />
                @endif
                <span class="text-lg font-bold text-gray-800">Bird</span>
            </a>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="'/admin/dashboard'" :active="request()->is('admin/dashboard')">
                {{ __('لوحة التحكم') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/orders'" :active="request()->is('admin/orders*')">
                {{ __('الطلبات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/categories'" :active="request()->is('admin/categories*')">
                {{ __('الأقسام') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/products'" :active="request()->is('admin/products*')">
                {{ __('المنتجات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/shipping'" :active="request()->is('admin/shipping*')">
                {{ __('الشحن') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/banners'" :active="request()->is('admin/banners*')">
                {{ __('البانرات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="'/admin/settings'" :active="request()->is('admin/settings*')">
                {{ __('الإعدادات') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Language Switcher -->
        <div class="pt-4 pb-2 border-t border-gray-200 px-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-globe mr-1"></i>{{ __('اللغة') }}</span>
            <div class="flex items-center gap-1 bg-gray-100 p-0.5 rounded-lg">
                <a href="{{ route('lang.switch', 'ar') }}" class="px-2.5 py-1 text-xs rounded-md transition-all {{ app()->getLocale() == 'ar' ? 'bg-white text-indigo-600 font-bold shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">العربية</a>
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 text-xs rounded-md transition-all {{ app()->getLocale() == 'en' ? 'bg-white text-indigo-600 font-bold shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">English</a>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="'/admin/profile'">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="/admin/logout">
                    @csrf

                    <x-responsive-nav-link :href="'/admin/logout'"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

</nav>
