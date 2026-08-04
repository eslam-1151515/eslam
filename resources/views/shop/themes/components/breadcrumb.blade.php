@props([
    'items' => [],
    'customClass' => '',
])

@if(!empty($items) && count($items) > 0)
<nav aria-label="{{ __('Breadcrumb') }}" {{ $attributes->merge(['class' => 'fo-breadcrumb ' . $customClass]) }}>
    <ol class="fo-breadcrumb__list">
        <!-- Home item always first if not explicitly provided -->
        @if(!isset($items[0]['is_home']))
            <li class="fo-breadcrumb__item">
                <a href="{{ route('shop.home') }}" class="fo-breadcrumb__link" title="{{ __('Home') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146z"/>
                    </svg>
                    <span>{{ __('Home') }}</span>
                </a>
                <span class="fo-breadcrumb__separator">&rsaquo;</span>
            </li>
        @endif

        @foreach($items as $index => $item)
            @php
                $isLast = $index === count($items) - 1 || data_get($item, 'active');
                $title = data_get($item, 'title', data_get($item, 'name', ''));
                $url = data_get($item, 'url', '#');
            @endphp

            <li class="fo-breadcrumb__item {{ $isLast ? 'is-active' : '' }}" @if($isLast) aria-current="page" @endif>
                @if(!$isLast && $url !== '#')
                    <a href="{{ $url }}" class="fo-breadcrumb__link">{{ $title }}</a>
                    <span class="fo-breadcrumb__separator">&rsaquo;</span>
                @else
                    <span>{{ $title }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
