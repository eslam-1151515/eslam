@props([
    'storeName' => null,
    'description' => null,
    'links' => [],
    'socialLinks' => [],
    'copyright' => null,
    'customClass' => '',
])

@php
    $fStoreName = $storeName ?? (isset($store) ? data_get($store, 'name') : config('app.name', 'Fast Order'));
    $fDescription = $description ?? __('Your premium destination for seamless online shopping with fast and reliable delivery.');
    $fCopyright = $copyright ?? __('All Rights Reserved.');
    
    // Default Footer Columns if empty
    $fColumns = !empty($links) ? $links : [
        [
            'title' => __('Quick Links'),
            'items' => [
                ['title' => __('Home'), 'url' => route('shop.home')],
                ['title' => __('All Products'), 'url' => route('shop.products')],
                ['title' => __('Categories'), 'url' => route('shop.categories')],
                ['title' => __('Special Offers'), 'url' => route('shop.products', ['discount' => 1])],
            ]
        ],
        [
            'title' => __('Customer Service'),
            'items' => [
                ['title' => __('My Account'), 'url' => route('shop.account')],
                ['title' => __('Order History'), 'url' => route('shop.orders')],
                ['title' => __('Wishlist'), 'url' => route('shop.wishlist')],
                ['title' => __('Contact Us'), 'url' => route('shop.contact')],
            ]
        ],
        [
            'title' => __('Information'),
            'items' => [
                ['title' => __('About Us'), 'url' => '#'],
                ['title' => __('Privacy Policy'), 'url' => '#'],
                ['title' => __('Terms & Conditions'), 'url' => '#'],
                ['title' => __('Shipping & Returns'), 'url' => '#'],
            ]
        ],
    ];
@endphp

<footer {{ $attributes->merge(['class' => 'fo-footer ' . $customClass]) }}>
    <div class="fo-footer__container">
        <div class="fo-footer__grid">
            <!-- Store Info Column -->
            <div>
                <h3 class="fo-footer__col-title">{{ $fStoreName }}</h3>
                <p style="line-height: 1.6; margin-bottom: 1.25rem;">
                    {{ $fDescription }}
                </p>
                @if(!empty($socialLinks))
                    <div style="display: flex; gap: 0.75rem;">
                        @foreach($socialLinks as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" style="color: inherit; font-size: 1.25rem; transition: var(--theme-transition);" title="{{ ucfirst($platform) }}">
                                <span>🌐</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Dynamic Link Columns -->
            @foreach($fColumns as $col)
                <div>
                    <h4 class="fo-footer__col-title">{{ data_get($col, 'title') }}</h4>
                    <ul class="fo-footer__links">
                        @foreach(data_get($col, 'items', []) as $item)
                            <li>
                                <a href="{{ data_get($item, 'url', '#') }}" class="fo-footer__link">
                                    {{ data_get($item, 'title') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Footer Bottom -->
        <div class="fo-footer__bottom">
            <div>
                &copy; {{ date('Y') }} {{ $fStoreName }}. {{ $fCopyright }}
            </div>
            <div style="display: flex; gap: 1rem; align-items: center; color: var(--theme-text-muted);">
                <span>{{ __('Powered by Fast Order') }}</span>
            </div>
        </div>
    </div>
</footer>
