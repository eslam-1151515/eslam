@props([
    'id' => 'foModalDefault',
    'title' => null,
    'size' => 'md', // sm, md, lg
    'showClose' => true,
    'customClass' => '',
])

@php
    $sizeClass = match($size) {
        'sm' => 'fo-modal--sm',
        'lg' => 'fo-modal--lg',
        default => '',
    };
@endphp

<div id="{{ $id }}" class="fo-modal-overlay {{ $customClass }}" onclick="handleModalOverlayClick(event, '{{ $id }}')" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}Title" style="display: none;">
    <div class="fo-modal {{ $sizeClass }}">
        @if($title || $showClose || isset($header))
            <div class="fo-modal__header">
                @if(isset($header))
                    {{ $header }}
                @else
                    <h3 id="{{ $id }}Title" class="fo-modal__title">{{ $title }}</h3>
                @endif

                @if($showClose)
                    <button type="button" class="fo-modal__close" onclick="closeFoModal('{{ $id }}')" aria-label="{{ __('Close') }}">
                        &times;
                    </button>
                @endif
            </div>
        @endif

        <div class="fo-modal__body">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="fo-modal__footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

<script>
    if (typeof window.openFoModal !== 'function') {
        window.openFoModal = function(modalId) {
            const overlay = document.getElementById(modalId);
            if (!overlay) return;
            overlay.style.display = 'flex';
            // Trigger reflow for transition
            void overlay.offsetWidth;
            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        };

        window.closeFoModal = function(modalId) {
            const overlay = document.getElementById(modalId);
            if (!overlay) return;
            overlay.classList.remove('is-active');
            setTimeout(() => {
                if (!overlay.classList.contains('is-active')) {
                    overlay.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }, 300);
        };

        window.handleModalOverlayClick = function(event, modalId) {
            if (event.target.id === modalId) {
                closeFoModal(modalId);
            }
        };

        // Close on Esc key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fo-modal-overlay.is-active').forEach(modal => {
                    closeFoModal(modal.id);
                });
            }
        });
    }
</script>
