@props([
    'paginator' => null,
    'customClass' => '',
])

@if(isset($paginator) && is_object($paginator) && method_exists($paginator, 'hasPages') && $paginator->hasPages())
    <nav aria-label="{{ __('Page Navigation') }}" {{ $attributes->merge(['class' => 'fo-pagination ' . $customClass]) }}>
        <ul class="fo-pagination__list">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <li class="fo-pagination__item">
                    <span class="fo-pagination__link is-disabled" aria-disabled="true" aria-label="{{ __('Previous') }}">
                        &lsaquo;
                    </span>
                </li>
            @else
                <li class="fo-pagination__item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="fo-pagination__link" rel="prev" aria-label="{{ __('Previous') }}">
                        &lsaquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if($page == $paginator->currentPage())
                    <li class="fo-pagination__item">
                        <span class="fo-pagination__link is-active" aria-current="page" aria-label="{{ __('Page :page', ['page' => $page]) }}">{{ $page }}</span>
                    </li>
                @else
                    <li class="fo-pagination__item">
                        <a href="{{ $url }}" class="fo-pagination__link" aria-label="{{ __('Page :page', ['page' => $page]) }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <li class="fo-pagination__item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="fo-pagination__link" rel="next" aria-label="{{ __('Next Page') }}">
                        &rsaquo;
                    </a>
                </li>
            @else
                <li class="fo-pagination__item">
                    <span class="fo-pagination__link is-disabled" aria-disabled="true" aria-label="{{ __('Next Page') }}">
                        &rsaquo;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@elseif(isset($paginator) && is_array($paginator) && !empty($paginator['pages']))
    {{-- Fallback array pagination for custom non-Laravel paginator --}}
    <nav aria-label="{{ __('Page Navigation') }}" {{ $attributes->merge(['class' => 'fo-pagination ' . $customClass]) }}>
        <ul class="fo-pagination__list">
            @foreach($paginator['pages'] as $p)
                <li class="fo-pagination__item">
                    @if(data_get($p, 'active'))
                        <span class="fo-pagination__link is-active" aria-current="page">{{ data_get($p, 'label') }}</span>
                    @else
                        <a href="{{ data_get($p, 'url', '#') }}" class="fo-pagination__link {{ data_get($p, 'disabled') ? 'is-disabled' : '' }}" aria-label="{{ strip_tags(data_get($p, 'label')) }}">{!! data_get($p, 'label') !!}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
@endif
