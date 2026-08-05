@props([
    'cartItems' => [],
    'total' => '0.00',
    'checkoutUrl' => null,
    'cartUrl' => null,
    'customClass' => '',
])

@php
    $cCheckoutUrl = $checkoutUrl ?? route('shop.checkout');
    $cCartUrl = $cartUrl ?? route('shop.cart');
@endphp

<div id="foCartDrawerOverlay" class="fo-cart-drawer-overlay {{ $customClass }}" onclick="toggleCartDrawer(false)"></div>

<aside id="foCartDrawer" class="fo-cart-drawer {{ $customClass }}" aria-labelledby="foCartDrawerTitle" role="dialog" aria-modal="true">
    <div class="fo-cart-drawer__header">
        <h2 id="foCartDrawerTitle" class="fo-cart-drawer__title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <span>{{ __('Shopping Cart') }}</span>
        </h2>
        <button type="button" class="fo-cart-drawer__close" onclick="toggleCartDrawer(false)" aria-label="{{ __('Close Cart') }}">
            &times;
        </button>
    </div>

    <div class="fo-cart-drawer__body" id="foCartDrawerBody">
        @if(empty($cartItems) || (is_countable($cartItems) && count($cartItems) === 0))
            <div style="text-align: center; padding: 3rem 1rem; color: var(--theme-text-muted);">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="margin: 0 auto 1rem auto; opacity: 0.5;" aria-hidden="true">
                    <path d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 8l-1.147 1.646a.5.5 0 0 0 .708.708l1.5-1.5a.5.5 0 0 0 0-.708l-1.5-1.5z"/>
                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.596l-1.313 7h-8.17z"/>
                </svg>
                <p style="margin: 0; font-weight: 600; font-size: 1rem;">{{ __('Your cart is empty') }}</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">{{ __('Start adding some products to checkout.') }}</p>
            </div>
        @else
            @foreach($cartItems as $item)
                @php
                    $iName = data_get($item, 'name') ?? data_get($item, 'product.name', __('Product'));
                    $iPrice = data_get($item, 'price', '0.00');
                    $iQty = data_get($item, 'quantity', 1);
                    $iImage = data_get($item, 'image_url') ?? data_get($item, 'product.image_url', asset('images/default-product.png'));
                    $iId = data_get($item, 'id', uniqid());
                @endphp
                <div class="fo-cart-item" data-cart-item-id="{{ $iId }}">
                    <img src="{{ $iImage }}" alt="{{ $iName }}" class="fo-cart-item__image" onerror="this.src='https://via.placeholder.com/100x100?text=No+Image'">
                    <div class="fo-cart-item__details">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                            <h4 class="fo-cart-item__name">{{ $iName }}</h4>
                            <button type="button" class="fo-cart-drawer__close btn-remove-item" data-item-id="{{ $iId }}" style="font-size: 1.125rem; padding: 0;" title="{{ __('Remove') }}" aria-label="{{ __('Remove item') }}">&times;</button>
                        </div>
                        <span class="fo-cart-item__price">{{ is_numeric($iPrice) ? number_format($iPrice, 0) . ' ' . __('SAR') : $iPrice }}</span>
                        
                        <div class="fo-cart-item__actions">
                            <div class="fo-quantity-ctrl">
                                <button type="button" class="fo-quantity-ctrl__btn btn-qty-dec" data-item-id="{{ $iId }}" aria-label="{{ __('Decrease quantity') }}">-</button>
                                <input type="number" class="fo-quantity-ctrl__input" value="{{ $iQty }}" min="1" readonly aria-label="{{ __('Quantity') }}">
                                <button type="button" class="fo-quantity-ctrl__btn btn-qty-inc" data-item-id="{{ $iId }}" aria-label="{{ __('Increase quantity') }}">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="fo-cart-drawer__footer">
        <div class="fo-cart-drawer__summary-row">
            <span>{{ __('Subtotal') }}</span>
            <span id="foCartSubtotal">{{ is_numeric($total) ? number_format($total, 0) . ' ' . __('SAR') : $total }}</span>
        </div>
        <div class="fo-cart-drawer__summary-row">
            <span>{{ __('Shipping') }}</span>
            <span>{{ __('Calculated at checkout') }}</span>
        </div>
        <div class="fo-cart-drawer__summary-row fo-cart-drawer__summary-row--total">
            <span>{{ __('Total') }}</span>
            <span id="foCartTotal">{{ is_numeric($total) ? number_format($total, 0) . ' ' . __('SAR') : $total }}</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="{{ $cCheckoutUrl }}" class="fo-btn fo-btn--primary" style="text-align: center;">
                {{ __('Proceed to Checkout') }}
            </a>
            <a href="{{ $cCartUrl }}" class="fo-btn fo-btn--outline" style="text-align: center;">
                {{ __('View Cart') }}
            </a>
        </div>
    </div>
</aside>

<script>
    function toggleCartDrawer(show) {
        const drawer = document.getElementById('foCartDrawer');
        const overlay = document.getElementById('foCartDrawerOverlay');
        if (!drawer || !overlay) return;
        
        if (show === undefined) {
            show = !drawer.classList.contains('is-active');
        }
        
        if (show) {
            drawer.classList.add('is-active');
            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        } else {
            drawer.classList.remove('is-active');
            overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }
    }
</script>
