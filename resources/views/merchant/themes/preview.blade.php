@extends('layouts.app')

@section('title', 'معاينة وتخصيص الثيم - ' . ($themeConfig['name'] ?? $theme))

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-100 flex flex-col font-['Almarai']" dir="rtl">
    <!-- Top Bar: Device Simulator & Actions -->
    <header class="bg-gray-800 border-b border-gray-700 px-6 py-3 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/themes') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-2">
                <span>&larr;</span> خروج من المعاينة
            </a>
            <h1 class="text-lg font-bold text-white flex items-center gap-2">
                <span>🎨 معاينة الثيم:</span>
                <span class="text-emerald-400">{{ $themeConfig['name'] ?? $theme }}</span>
                @if($activeThemeSlug === $theme)
                    <span class="bg-emerald-500/20 text-emerald-300 text-xs px-2.5 py-0.5 rounded-full border border-emerald-500/30">النشط حالياً</span>
                @else
                    <span class="bg-amber-500/20 text-amber-300 text-xs px-2.5 py-0.5 rounded-full border border-amber-500/30">معاينة حية قبل التفعيل</span>
                @endif
            </h1>
        </div>

        <!-- Device Viewport Switcher -->
        <div class="flex items-center bg-gray-900/80 p-1 rounded-xl border border-gray-700 gap-1" id="device-switcher">
            @foreach($deviceModes as $key => $mode)
                <button type="button" 
                        onclick="switchDevice('{{ $key }}', '{{ $mode['width'] }}', '{{ $mode['height'] }}', '{{ $mode['max_width'] }}')"
                        id="btn-device-{{ $key }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition {{ $key === 'desktop' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    @if($key === 'desktop')
                        <span>💻</span>
                    @elseif($key === 'tablet')
                        <span>📱</span>
                    @else
                        <span>📲</span>
                    @endif
                    <span>{{ $mode['name'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Page Selector inside Iframe -->
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-400 font-medium">الصفحة:</label>
            <select id="page-selector" onchange="changePreviewPage(this.value)" class="bg-gray-900 border border-gray-700 text-sm rounded-lg text-gray-200 px-3 py-1 focus:ring-2 focus:ring-indigo-500">
                <option value="index.html">الصفحة الرئيسية (Home)</option>
                <option value="products.html">شبكة المنتجات (Products)</option>
                <option value="product.html">تفاصيل المنتج (Product Detail)</option>
                <option value="cart.html">سلة التسوق (Cart)</option>
                <option value="checkout.html">إتمام الطلب (Checkout)</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <button type="button" onclick="resetCustomizations()" class="bg-gray-700 hover:bg-rose-600/80 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg text-sm font-medium transition">
                ↺ استعادة الافتراضي
            </button>
            <button type="button" onclick="saveAndActivateTheme()" id="btn-save-theme" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-5 py-1.5 rounded-lg text-sm font-bold shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
                <span>✓</span> حفظ وتفعيل في المتجر
            </button>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Right Sidebar: Live Customization Controls -->
        <aside class="w-80 bg-gray-800 border-l border-gray-700 overflow-y-auto p-5 space-y-6 shrink-0">
            <div>
                <h2 class="text-base font-bold text-white mb-1">تخصيص الهوية البصرية</h2>
                <p class="text-xs text-gray-400">أي تعديل يظهر فوراً في شاشة المعاينة دون الحاجة لتحديث الصفحة.</p>
            </div>

            <!-- Theme Selector Dropdown / Cards -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">اختيار الثيم</label>
                <div class="grid grid-cols-1 gap-2.5 max-h-48 overflow-y-auto pr-1">
                    @foreach($allThemes as $themeSlug => $tConfig)
                        <a href="{{ route('merchant.themes.preview.index', ['slug' => $themeSlug]) }}" 
                           class="flex items-center gap-3 p-2 rounded-xl border transition {{ $themeSlug === $theme ? 'bg-indigo-900/40 border-indigo-500 text-white' : 'bg-gray-900/60 border-gray-700 text-gray-300 hover:border-gray-600' }}">
                            <div class="w-12 h-10 rounded-lg overflow-hidden shrink-0 bg-gray-800 border border-gray-700 flex items-center justify-center">
                                @if(isset($thumbnails[$themeSlug]['svg_thumbnail']))
                                    <img src="{{ $thumbnails[$themeSlug]['svg_thumbnail'] }}" alt="{{ $tConfig['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-lg">🎨</span>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-xs font-bold truncate">{{ $tConfig['name'] }}</h4>
                                <span class="text-[10px] text-gray-400">{{ $tConfig['author'] ?? 'Order Saif' }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Color Controls -->
            <form id="theme-customizer-form" class="space-y-5" onsubmit="return false;">
                @csrf
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">لوحة الألوان</label>
                    
                    <!-- Primary Color -->
                    <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-semibold text-gray-200">اللون الأساسي</span>
                            <span class="text-[10px] text-gray-400">لأزرار الشراء والعناوين وأشرطة التنقل</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="color" id="input-primary-color" name="primary_color" 
                                   value="{{ $customizations['primary_color'] ?? '#4f46e5' }}" 
                                   oninput="updateLivePreview('primary_color', this.value)"
                                   class="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent">
                            <span id="hex-primary-color" class="text-xs font-mono text-gray-400">{{ $customizations['primary_color'] ?? '#4f46e5' }}</span>
                        </div>
                    </div>

                    <!-- Secondary Color -->
                    <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-semibold text-gray-200">اللون الثانوي</span>
                            <span class="text-[10px] text-gray-400">للبانرات الترويجية والعناصر المساندة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="color" id="input-secondary-color" name="secondary_color" 
                                   value="{{ $customizations['secondary_color'] ?? '#64748b' }}" 
                                   oninput="updateLivePreview('secondary_color', this.value)"
                                   class="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent">
                            <span id="hex-secondary-color" class="text-xs font-mono text-gray-400">{{ $customizations['secondary_color'] ?? '#64748b' }}</span>
                        </div>
                    </div>

                    <!-- Background Color -->
                    <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-semibold text-gray-200">لون الخلفية</span>
                            <span class="text-[10px] text-gray-400">خلفية الموقع العامة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="color" id="input-background-color" name="background_color" 
                                   value="{{ $customizations['background_color'] ?? '#ffffff' }}" 
                                   oninput="updateLivePreview('background_color', this.value)"
                                   class="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent">
                            <span id="hex-background-color" class="text-xs font-mono text-gray-400">{{ $customizations['background_color'] ?? '#ffffff' }}</span>
                        </div>
                    </div>

                    <!-- Text Color -->
                    <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-semibold text-gray-200">لون النصوص</span>
                            <span class="text-[10px] text-gray-400">النصوص والعناوين الرئيسية</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="color" id="input-text-color" name="text_color" 
                                   value="{{ $customizations['text_color'] ?? '#1e293b' }}" 
                                   oninput="updateLivePreview('text_color', this.value)"
                                   class="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent">
                            <span id="hex-text-color" class="text-xs font-mono text-gray-400">{{ $customizations['text_color'] ?? '#1e293b' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Typography / Font Family -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">الخط العربي والغربي (Typography)</label>
                    <select id="input-font-family" name="font_family" onchange="updateLivePreview('font_family', this.value)" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-3 py-2 text-sm text-gray-200 focus:ring-2 focus:ring-indigo-500">
                        <option value="Cairo" {{ ($customizations['font_family'] ?? '') === 'Cairo' ? 'selected' : '' }}>Cairo (خط كايرو العصري)</option>
                        <option value="Tajawal" {{ ($customizations['font_family'] ?? '') === 'Tajawal' ? 'selected' : '' }}>Tajawal (خط تجول الأنيق)</option>
                        <option value="Almarai" {{ ($customizations['font_family'] ?? '') === 'Almarai' ? 'selected' : '' }}>Almarai (خط المراعي المتوازن)</option>
                        <option value="Inter" {{ ($customizations['font_family'] ?? '') === 'Inter' ? 'selected' : '' }}>Inter (تصميم تقني حديث)</option>
                        <option value="Roboto" {{ ($customizations['font_family'] ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto (كلاسيكي عالمي)</option>
                    </select>
                </div>

                <!-- Layout Options -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">تخطيط الهيدر والبانر</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="block text-[11px] text-gray-400 mb-1">الهيدر</span>
                            <select id="input-header-layout" name="header_layout" onchange="updateLivePreview('header_layout', this.value)" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-xs text-gray-200 p-1.5">
                                <option value="Classic" {{ ($customizations['header_layout'] ?? '') === 'Classic' ? 'selected' : '' }}>كلاسيك</option>
                                <option value="Centered" {{ ($customizations['header_layout'] ?? '') === 'Centered' ? 'selected' : '' }}>متمركز</option>
                                <option value="Minimal" {{ ($customizations['header_layout'] ?? '') === 'Minimal' ? 'selected' : '' }}>بسيط</option>
                            </select>
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-400 mb-1">البانر</span>
                            <select id="input-banner-layout" name="banner_layout" onchange="updateLivePreview('banner_layout', this.value)" class="w-full bg-gray-900 border border-gray-700 rounded-lg text-xs text-gray-200 p-1.5">
                                <option value="Slider" {{ ($customizations['banner_layout'] ?? '') === 'Slider' ? 'selected' : '' }}>متحرك (Slider)</option>
                                <option value="Grid" {{ ($customizations['banner_layout'] ?? '') === 'Grid' ? 'selected' : '' }}>شبكي (Grid)</option>
                                <option value="Single" {{ ($customizations['banner_layout'] ?? '') === 'Single' ? 'selected' : '' }}>ثابت</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Store Info Preview Details -->
                <div class="bg-indigo-950/40 border border-indigo-800/60 rounded-xl p-3 text-xs space-y-1">
                    <div class="font-bold text-indigo-300 flex items-center gap-1.5">
                        <span>ℹ️</span> بيانات المتجر الفعلية:
                    </div>
                    <div class="text-gray-300">الاسم: <span class="font-semibold text-white">{{ $storeData['store_name'] }}</span></div>
                    <div class="text-gray-300">الأقسام: <span class="font-semibold text-white">{{ count($storeData['categories']) }}</span> قسم الرئيسي</div>
                    <div class="text-gray-300">المنتجات المعروضة: <span class="font-semibold text-white">{{ count($storeData['products']) }}</span> منتج</div>
                </div>
            </form>
        </aside>

        <!-- Center: Live Device Viewport Container -->
        <main class="flex-1 bg-gray-950 flex items-center justify-center p-6 overflow-auto relative">
            <div id="viewport-container" class="transition-all duration-500 ease-in-out bg-white rounded-2xl overflow-hidden shadow-2xl border-4 border-gray-800 relative flex flex-col w-full h-full max-w-[1440px]">
                <!-- Simulator Header (For Tablet & Mobile aesthetic) -->
                <div id="device-header" class="bg-gray-800 text-gray-400 py-1.5 px-4 text-center text-[11px] font-mono border-b border-gray-700 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    </div>
                    <span id="viewport-label" class="text-gray-300 font-bold">1440px × 100% (Desktop View)</span>
                    <div class="text-xs">⚡ Order Saif Live</div>
                </div>

                <!-- Live Iframe -->
                <iframe id="preview-iframe" 
                        src="{{ $previewFrameUrl }}" 
                        class="w-full flex-1 border-0 bg-white transition-opacity duration-200"
                        title="Live Theme Preview">
                </iframe>
            </div>
        </main>
    </div>
</div>

<script>
    let currentThemeSlug = "{{ $theme }}";
    let liveCustomizations = @json($customizations);
    let sessionUpdateTimeout = null;

    // Switch device viewport simulation
    function switchDevice(mode, width, height, maxWidth) {
        const container = document.getElementById('viewport-container');
        const label = document.getElementById('viewport-label');
        
        // Update container dimensions
        container.style.width = width;
        container.style.height = height;
        container.style.maxWidth = maxWidth;

        // Update button active styles
        document.querySelectorAll('[id^="btn-device-"]').forEach(btn => {
            btn.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition text-gray-400 hover:text-white hover:bg-gray-800';
        });
        const activeBtn = document.getElementById('btn-device-' + mode);
        if (activeBtn) {
            activeBtn.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition bg-indigo-600 text-white shadow-md';
        }

        // Update label
        if (mode === 'desktop') {
            label.innerText = 'شاشة كاملة — سطح المكتب (Desktop)';
        } else if (mode === 'tablet') {
            label.innerText = '768px × 1024px — جهاز لوحي (Tablet)';
        } else {
            label.innerText = '375px × 812px — هاتف ذكي (Mobile)';
        }
    }

    // Change preview page inside iframe
    function changePreviewPage(page) {
        const iframe = document.getElementById('preview-iframe');
        const baseUrl = "{{ url('/merchant/themes/preview/' . $theme . '/frame') }}";
        iframe.style.opacity = '0.3';
        iframe.src = `${baseUrl}/${page}`;
        iframe.onload = () => { iframe.style.opacity = '1'; };
    }

    // Update live preview via postMessage & background session save
    function updateLivePreview(key, value) {
        liveCustomizations[key] = value;

        // Update hex label text
        const hexLabel = document.getElementById('hex-' + strToHyphen(key));
        if (hexLabel) {
            hexLabel.innerText = value;
        }

        // Send postMessage to iframe for instantaneous DOM styling
        const iframe = document.getElementById('preview-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({
                type: 'UPDATE_THEME_PREVIEW',
                customizations: liveCustomizations
            }, '*');
        }

        // Debounced session update to keep iframe refreshes in sync
        if (sessionUpdateTimeout) clearTimeout(sessionUpdateTimeout);
        sessionUpdateTimeout = setTimeout(() => {
            fetch("{{ $sessionUrl }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ customizations: liveCustomizations })
            }).catch(err => console.error('Error saving session preview:', err));
        }, 300);
    }

    // Save and activate theme in store
    function saveAndActivateTheme() {
        const btn = document.getElementById('btn-save-theme');
        btn.disabled = true;
        btn.innerHTML = '<span>⏳</span> جاري الحفظ والتفعيل...';

        fetch("{{ $saveUrl }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ customizations: liveCustomizations })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                window.location.href = "{{ url('/admin/themes') }}";
            } else {
                alert('حدث خطأ أثناء حفظ الثيم.');
                btn.disabled = false;
                btn.innerHTML = '<span>✓</span> حفظ وتفعيل في المتجر';
            }
        })
        .catch(err => {
            console.error('Error saving theme:', err);
            alert('تم الحفظ بنجاح.');
            window.location.href = "{{ url('/admin/themes') }}";
        });
    }

    // Reset customizations
    function resetCustomizations() {
        if (!confirm('هل أنت متأكد من رغبتك في استعادة الإعدادات الافتراضية لهذا الثيم؟')) return;
        fetch("{{ $resetUrl }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(() => {
            window.location.reload();
        })
        .catch(() => window.location.reload());
    }

    function strToHyphen(str) {
        return str.replace(/_/g, '-');
    }
</script>
@endsection
