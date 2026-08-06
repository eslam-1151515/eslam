<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'actionUrl' => null,
    'placeholder' => null,
    'value' => '',
    'liveSearch' => true,
    'customClass' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'actionUrl' => null,
    'placeholder' => null,
    'value' => '',
    'liveSearch' => true,
    'customClass' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sActionUrl = $actionUrl ?? route('shop.search');
    $sPlaceholder = $placeholder ?? __('Search for products, categories...');
?>

<div <?php echo e($attributes->merge(['class' => 'fo-search-bar ' . $customClass])); ?>>
    <form action="<?php echo e($sActionUrl); ?>" method="GET" class="fo-search-bar__form" role="search">
        <input 
            type="search" 
            name="q" 
            value="<?php echo e($value); ?>" 
            placeholder="<?php echo e($sPlaceholder); ?>" 
            class="fo-search-bar__input" 
            aria-label="<?php echo e(__('Search products')); ?>"
            autocomplete="off"
            <?php if($liveSearch): ?> oninput="handleLiveSearch(this.value, '<?php echo e($sActionUrl); ?>')" <?php endif; ?>
        >
        <button type="submit" class="fo-search-bar__submit" aria-label="<?php echo e(__('Search')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </form>

    <?php if($liveSearch): ?>
        <div id="foSearchResults" class="fo-search-bar__results">
            <!-- AJAX search results will be injected here -->
        </div>
    <?php endif; ?>
</div>

<?php if($liveSearch): ?>
<script>
    let foSearchTimeout = null;
    function handleLiveSearch(query, endpoint) {
        const resultsContainer = document.getElementById('foSearchResults');
        if (!resultsContainer) return;

        if (!query || query.trim().length < 2) {
            resultsContainer.classList.remove('is-active');
            resultsContainer.innerHTML = '';
            return;
        }

        clearTimeout(foSearchTimeout);
        foSearchTimeout = setTimeout(() => {
            fetch(`${endpoint}?q=${encodeURIComponent(query)}&ajax=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    resultsContainer.innerHTML = data.html;
                } else if (data.products && data.products.length > 0) {
                    let html = '<div style="padding: 0.5rem 0;">';
                    data.products.forEach(p => {
                        html += `
                            <a href="${p.url || '#'}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; text-decoration: none; color: inherit; border-bottom: 1px solid var(--theme-border);">
                                <img src="${p.image_url || 'https://via.placeholder.com/40x40'}" alt="${p.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <div style="flex-grow: 1;">
                                    <div style="font-weight: 600; font-size: 0.875rem;">${p.name}</div>
                                    <div style="color: var(--theme-primary); font-weight: 700; font-size: 0.8125rem;">${p.price}</div>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                    resultsContainer.innerHTML = html;
                } else {
                    resultsContainer.innerHTML = `<div style="padding: 1rem; text-align: center; color: var(--theme-text-muted); font-size: 0.875rem;">${'<?php echo e(__("No products found")); ?>'}</div>`;
                }
                resultsContainer.classList.add('is-active');
            })
            .catch(err => {
                console.error('Live search error:', err);
            });
        }, 300);
    }

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        const resultsContainer = document.getElementById('foSearchResults');
        if (resultsContainer && !e.target.closest('.fo-search-bar')) {
            resultsContainer.classList.remove('is-active');
        }
    });
</script>
<?php endif; ?>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\shop\themes\components\search-bar.blade.php ENDPATH**/ ?>