@props([
    'category' => null,
    'title' => null,
    'url' => '#',
    'icon' => null,
    'image' => null,
    'count' => null,
    'customClass' => '',
])

@php
    $cTitle = $title ?? data_get($category, 'name') ?? data_get($category, 'title', __('Category Name'));
    $cUrl = $url !== '#' ? $url : (data_get($category, 'url') ?? (data_get($category, 'slug') ? route('shop.category', data_get($category, 'slug')) : '#'));
    $cIcon = $icon ?? data_get($category, 'icon');
    $cImage = $image ?? data_get($category, 'image_url') ?? data_get($category, 'image');
    $cCount = $count ?? data_get($category, 'products_count') ?? data_get($category, 'count');
@endphp

<a href="{{ $cUrl }}" {{ $attributes->merge(['class' => 'fo-category-card ' . $customClass]) }}>
    <div class="fo-category-card__icon">
        @if($cImage)
            <img src="{{ $cImage }}" alt="{{ $cTitle }}" class="fo-category-card__image" loading="lazy" onerror="this.style.display='none'">
        @elseif($cIcon)
            {!! $cIcon !!}
        @else
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
            </svg>
        @endif
    </div>

    <h3 class="fo-category-card__title">{{ $cTitle }}</h3>

    @if(!is_null($cCount))
        <span class="fo-category-card__count">{{ $cCount }} {{ __('Products') }}</span>
    @endif
</a>
