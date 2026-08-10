@props([
    'storeName' => null,
    'logoUrl' => null,
    'navLinks' => [],
    'cartCount' => 0,
    'wishlistCount' => 0,
    'showSearch' => true,
    'customClass' => '',
])

@php
    $nStoreName = $storeName ?? (isset($store) ? data_get($store, 'name') : config('app.name', 'Fast Order'));
    $nLogoUrl = $logoUrl ?? (isset($store) ? data_get($store, 'logo_url') : null);
    
    // Default navigation links if empty
    $nLinks = !empty($navLinks) ? $navLinks : [
        ['title' => __('Home'), 'url' => route('shop.home'), 'active' => request()->routeIs('shop.home')],
        ['title' => __('Products'), 'url' => route('shop.products'), 'active' => request()->routeIs('shop.products*')],
        ['title' => __('Categories'), 'url' => route('shop.categories'), 'active' => request()->routeIs('shop.categories*')],
        ['title' => __('Contact Us'), 'url' => route('shop.contact'), 'active' => request()->routeIs('shop.contact')],
    ];
@endphp

<header {{ $attributes->merge(['class' => 'fo-navbar ' . $customClass]) }}>
    <div class="fo-navbar__container">
        <!-- Brand Logo / Name -->
        <a href="{{ route('shop.home') }}" class="fo-navbar__brand">
            @if($nLogoUrl)
                <img src="{{ $nLogoUrl }}" alt="{{ $nStoreName }}" class="fo-navbar__brand-logo">
            @else
                <span>{{ $nStoreName }}</span>
            @endif
        </a>

        <!-- Main Navigation -->
        <nav aria-label="{{ __('Main Navigation') }}">
            <ul class="fo-navbar__nav">
                @foreach($nLinks as $link)
                    <li>
                        <a href="{{ data_get($link, 'url', '#') }}" class="fo-navbar__link {{ data_get($link, 'active') ? 'is-active' : '' }}">
                            {{ data_get($link, 'title') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- Search Bar (Optional inline or mobile trigger) -->
        @if($showSearch)
            <div style="flex-grow: 1; max-width: 400px; display: none; @media(min-width: 992px) { display: block; }">
                <x-shop.themes.components.search-bar :placeholder="__('Search products...')" />
            </div>
        @endif

        <!-- Navbar Actions (Cart, Wishlist, Account, Language Switcher) -->
        <div class="fo-navbar__actions">
            <!-- Language Switcher (AR / EN) - Hidden by Request -->
            {{--
            @php
                $currentLang = app()->getLocale();
                $switchLang = $currentLang === 'ar' ? 'en' : 'ar';
            @endphp
            <a href="{{ route('locale.switch', $switchLang) }}" class="fo-navbar__icon-btn" title="{{ $switchLang === 'ar' ? 'العربية' : 'English' }}" aria-label="{{ $switchLang === 'ar' ? 'تغيير اللغة إلى العربية' : 'Switch language to English' }}" style="font-weight: 700; font-size: 0.75rem;">
                {{ strtoupper($switchLang) }}
            </a>
            --}}

            <!-- Wishlist Button -->
            <a href="{{ route('shop.wishlist') }}" class="fo-navbar__icon-btn" title="{{ __('Wishlist') }}" aria-label="{{ __('Wishlist') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                </svg>
                @if($wishlistCount > 0)
                    <span class="fo-navbar__badge id-wishlist-badge">{{ $wishlistCount }}</span>
                @endif
            </a>

            <!-- Cart Trigger Button -->
            <button type="button" class="fo-navbar__icon-btn" onclick="toggleCartDrawer(true)" title="{{ __('Cart') }}" aria-label="{{ __('Cart') }}" aria-expanded="false" aria-controls="foCartDrawer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <span class="fo-navbar__badge id-cart-badge" id="foCartCountBadge" @if($cartCount === 0) style="display: none;" @endif>{{ $cartCount }}</span>
            </button>

            <!-- Account / Profile Button -->
            <a href="{{ auth()->guard('customer')->check() ? route('shop.account') : route('shop.login') }}" class="fo-navbar__icon-btn" title="{{ __('My Account') }}" aria-label="{{ __('My Account') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
            </a>
        </div>
    </div>
</header>
