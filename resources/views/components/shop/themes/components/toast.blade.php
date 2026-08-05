@props([
    'position' => 'bottom-right',
    'autoDismiss' => 4000,
    'customClass' => '',
])

<div id="foToastContainer" class="fo-toast-container {{ $customClass }}" role="region" aria-live="polite" aria-label="{{ __('Notifications') }}">
    {{-- Check session flash messages and display them automatically --}}
    @if(session()->has('success'))
        <div class="fo-toast fo-toast--success is-active" data-auto-dismiss="{{ $autoDismiss }}">
            <span class="fo-toast__icon">✓</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title">{{ __('Success') }}</h4>
                <p class="fo-toast__message">{{ session('success') }}</p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}">&times;</button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fo-toast fo-toast--error is-active" data-auto-dismiss="{{ $autoDismiss }}">
            <span class="fo-toast__icon">✕</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title">{{ __('Error') }}</h4>
                <p class="fo-toast__message">{{ session('error') }}</p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}">&times;</button>
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="fo-toast fo-toast--warning is-active" data-auto-dismiss="{{ $autoDismiss }}">
            <span class="fo-toast__icon">⚠</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title">{{ __('Warning') }}</h4>
                <p class="fo-toast__message">{{ session('warning') }}</p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}">&times;</button>
        </div>
    @endif

    @if(session()->has('info'))
        <div class="fo-toast fo-toast--info is-active" data-auto-dismiss="{{ $autoDismiss }}">
            <span class="fo-toast__icon">ℹ</span>
            <div class="fo-toast__content">
                <h4 class="fo-toast__title">{{ __('Information') }}</h4>
                <p class="fo-toast__message">{{ session('info') }}</p>
            </div>
            <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}">&times;</button>
        </div>
    @endif
</div>

<script>
    if (typeof window.showFoToast !== 'function') {
        window.showFoToast = function(message, type = 'success', title = null, duration = {{ $autoDismiss }}) {
            const container = document.getElementById('foToastContainer');
            if (!container) return;

            const iconMap = {
                'success': '✓',
                'error': '✕',
                'warning': '⚠',
                'info': 'ℹ'
            };

            const titleMap = {
                'success': '{{ __("Success") }}',
                'error': '{{ __("Error") }}',
                'warning': '{{ __("Warning") }}',
                'info': '{{ __("Information") }}'
            };

            const displayTitle = title || titleMap[type] || '{{ __("Notification") }}';
            const icon = iconMap[type] || 'ℹ';

            const toast = document.createElement('div');
            toast.className = `fo-toast fo-toast--${type}`;
            toast.innerHTML = `
                <span class="fo-toast__icon">${icon}</span>
                <div class="fo-toast__content">
                    <h4 class="fo-toast__title">${displayTitle}</h4>
                    <p class="fo-toast__message">${message}</p>
                </div>
                <button type="button" class="fo-toast__close" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}">&times;</button>
            `;

            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('is-active');
            }, 50);

            if (duration > 0) {
                setTimeout(() => {
                    toast.classList.remove('is-active');
                    setTimeout(() => toast.remove(), 350);
                }, duration);
            }
        };

        // Auto dismiss session toasts
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fo-toast[data-auto-dismiss]').forEach(toast => {
                const duration = parseInt(toast.getAttribute('data-auto-dismiss')) || 4000;
                if (duration > 0) {
                    setTimeout(() => {
                        toast.classList.remove('is-active');
                        setTimeout(() => toast.remove(), 350);
                    }, duration);
                }
            });
        });
    }
</script>
