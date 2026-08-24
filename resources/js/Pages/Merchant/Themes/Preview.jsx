import React, { useState, useEffect, useRef } from 'react';
import { Head, router, usePage } from '@inertiajs/react';

export default function ThemePreview({
    theme,
    activeThemeSlug,
    themeConfig = {},
    allThemes = {},
    customizations = {},
    storeData = {},
    deviceModes = {},
    thumbnails = {},
    previewFrameUrl,
    saveUrl,
    sessionUrl,
    resetUrl
}) {
    const iframeRef = useRef(null);
    const [currentCustoms, setCurrentCustoms] = useState({
        primary_color: customizations.primary_color || '#4f46e5',
        secondary_color: customizations.secondary_color || '#64748b',
        background_color: customizations.background_color || '#ffffff',
        text_color: customizations.text_color || '#1e293b',
        font_family: customizations.font_family || 'Cairo',
        header_layout: customizations.header_layout || 'Classic',
        banner_layout: customizations.banner_layout || 'Slider'
    });

    const [activeDevice, setActiveDevice] = useState('desktop');
    const [currentPage, setCurrentPage] = useState('index.html');
    const [isSaving, setIsSaving] = useState(false);
    const [iframeSrc, setIframeSrc] = useState(previewFrameUrl || `/merchant/themes/preview/${theme}/frame/index.html`);

    // Handle Live Color / Typography changes
    const handleCustomChange = (key, value) => {
        const updated = { ...currentCustoms, [key]: value };
        setCurrentCustoms(updated);

        // Send instant postMessage to iframe for real-time CSS variable updating
        if (iframeRef.current && iframeRef.current.contentWindow) {
            iframeRef.current.contentWindow.postMessage({
                type: 'UPDATE_THEME_PREVIEW',
                customizations: updated
            }, '*');
        }

        // Debounced session update
        const timer = setTimeout(() => {
            fetch(sessionUrl || `/merchant/themes/preview/${theme}/session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ customizations: updated })
            }).catch(err => console.error('Error syncing preview session:', err));
        }, 350);

        return () => clearTimeout(timer);
    };

    // Switch device viewport simulation
    const getViewportStyles = () => {
        switch (activeDevice) {
            case 'mobile':
                return { width: '375px', height: '812px', maxWidth: '375px' };
            case 'tablet':
                return { width: '768px', height: '1024px', maxWidth: '768px' };
            case 'desktop':
            default:
                return { width: '100%', height: '100%', maxWidth: '1440px' };
        }
    };

    const getViewportLabel = () => {
        switch (activeDevice) {
            case 'mobile':
                return '375px × 812px — هاتف ذكي (Mobile)';
            case 'tablet':
                return '768px × 1024px — جهاز لوحي (Tablet)';
            case 'desktop':
            default:
                return 'شاشة كاملة — سطح المكتب (Desktop)';
        }
    };

    // Change page inside iframe
    const handlePageChange = (e) => {
        const page = e.target.value;
        setCurrentPage(page);
        const baseUrl = previewFrameUrl ? previewFrameUrl.replace(/\/index\.html$/, '') : `/merchant/themes/preview/${theme}/frame`;
        setIframeSrc(`${baseUrl}/${page}`);
    };

    // Save and activate theme
    const handleSaveAndActivate = () => {
        setIsSaving(true);
        router.post(saveUrl || `/merchant/themes/preview/${theme}/save`, {
            customizations: currentCustoms
        }, {
            onSuccess: () => {
                setIsSaving(false);
                alert('تم حفظ التخصيصات وتفعيل الثيم في متجرك بنجاح ✓');
            },
            onError: (err) => {
                setIsSaving(false);
                alert('حدث خطأ أثناء حفظ التخصيصات.');
                console.error(err);
            }
        });
    };

    // Reset customizations
    const handleReset = () => {
        if (!window.confirm('هل أنت متأكد من رغبتك في استعادة الإعدادات الافتراضية لهذا الثيم؟')) return;
        router.post(resetUrl || `/merchant/themes/preview/${theme}/reset`, {}, {
            onSuccess: () => {
                window.location.reload();
            }
        });
    };

    return (
        <div className="min-h-screen bg-gray-900 text-gray-100 flex flex-col font-['Almarai'] rtl text-right" dir="rtl">
            <Head>
                <title>{`معاينة وتخصيص الثيم - ${themeConfig.name || theme}`}</title>
                <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet" />
            </Head>

            {/* Top Header Bar */}
            <header className="bg-gray-800 border-b border-gray-700 px-6 py-3 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-50">
                <div className="flex items-center gap-3">
                    <a href="/admin/themes" className="bg-gray-700 hover:bg-gray-600 text-gray-200 px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span>&larr;</span> خروج من المعاينة
                    </a>
                    <h1 className="text-lg font-bold text-white flex items-center gap-2">
                        <span>🎨 معاينة الثيم:</span>
                        <span className="text-emerald-400">{themeConfig.name || theme}</span>
                        {activeThemeSlug === theme ? (
                            <span className="bg-emerald-500/20 text-emerald-300 text-xs px-2.5 py-0.5 rounded-full border border-emerald-500/30">النشط حالياً</span>
                        ) : (
                            <span className="bg-amber-500/20 text-amber-300 text-xs px-2.5 py-0.5 rounded-full border border-amber-500/30">معاينة حية قبل التفعيل</span>
                        )}
                    </h1>
                </div>

                {/* Device Simulator Switcher */}
                <div className="flex items-center bg-gray-900/80 p-1 rounded-xl border border-gray-700 gap-1">
                    {Object.entries(deviceModes).map(([key, mode]) => (
                        <button
                            key={key}
                            type="button"
                            onClick={() => setActiveDevice(key)}
                            className={`px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition ${
                                activeDevice === key
                                    ? 'bg-indigo-600 text-white shadow-md'
                                    : 'text-gray-400 hover:text-white hover:bg-gray-800'
                            }`}
                        >
                            <span>{key === 'desktop' ? '💻' : key === 'tablet' ? '📱' : '📲'}</span>
                            <span>{mode.name}</span>
                        </button>
                    ))}
                </div>

                {/* Page Selector */}
                <div className="flex items-center gap-2">
                    <label className="text-xs text-gray-400 font-medium">الصفحة:</label>
                    <select
                        value={currentPage}
                        onChange={handlePageChange}
                        className="bg-gray-900 border border-gray-700 text-sm rounded-lg text-gray-200 px-3 py-1 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="index.html">الصفحة الرئيسية (Home)</option>
                        <option value="products.html">شبكة المنتجات (Products)</option>
                        <option value="product.html">تفاصيل المنتج (Product Detail)</option>
                        <option value="cart.html">سلة التسوق (Cart)</option>
                        <option value="checkout.html">إتمام الطلب (Checkout)</option>
                    </select>
                </div>

                {/* Action Buttons */}
                <div className="flex items-center gap-3">
                    <button
                        type="button"
                        onClick={handleReset}
                        className="bg-gray-700 hover:bg-rose-600/80 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg text-sm font-medium transition"
                    >
                        ↺ استعادة الافتراضي
                    </button>
                    <button
                        type="button"
                        onClick={handleSaveAndActivate}
                        disabled={isSaving}
                        className="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-5 py-1.5 rounded-lg text-sm font-bold shadow-lg shadow-emerald-500/20 transition flex items-center gap-2 disabled:opacity-50"
                    >
                        <span>{isSaving ? '⏳' : '✓'}</span>
                        <span>{isSaving ? 'جاري الحفظ...' : 'حفظ وتفعيل في المتجر'}</span>
                    </button>
                </div>
            </header>

            {/* Main Workspace */}
            <div className="flex-1 flex overflow-hidden">
                {/* Right Sidebar: Controls */}
                <aside className="w-80 bg-gray-800 border-l border-gray-700 overflow-y-auto p-5 space-y-6 shrink-0">
                    <div>
                        <h2 className="text-base font-bold text-white mb-1">تخصيص الهوية البصرية</h2>
                        <p className="text-xs text-gray-400">أي تعديل يظهر فوراً في شاشة المعاينة دون الحاجة لتحديث الصفحة.</p>
                    </div>

                    {/* Theme Selector List */}
                    <div className="space-y-2">
                        <label className="block text-xs font-bold text-gray-300 uppercase tracking-wider">اختيار الثيم</label>
                        <div className="grid grid-cols-1 gap-2.5 max-h-48 overflow-y-auto pr-1">
                            {Object.entries(allThemes).map(([slug, tConfig]) => (
                                <a
                                    key={slug}
                                    href={`/merchant/themes/preview/${slug}`}
                                    className={`flex items-center gap-3 p-2 rounded-xl border transition ${
                                        slug === theme
                                            ? 'bg-indigo-900/40 border-indigo-500 text-white'
                                            : 'bg-gray-900/60 border-gray-700 text-gray-300 hover:border-gray-600'
                                    }`}
                                >
                                    <div className="w-12 h-10 rounded-lg overflow-hidden shrink-0 bg-gray-800 border border-gray-700 flex items-center justify-center">
                                        {thumbnails[slug]?.svg_thumbnail ? (
                                            <img src={thumbnails[slug].svg_thumbnail} alt={tConfig.name} className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-lg">🎨</span>
                                        )}
                                    </div>
                                    <div className="overflow-hidden">
                                        <h4 className="text-xs font-bold truncate">{tConfig.name}</h4>
                                        <span className="text-[10px] text-gray-400">{tConfig.author || 'Order Saif'}</span>
                                    </div>
                                </a>
                            ))}
                        </div>
                    </div>

                    {/* Colors */}
                    <div className="space-y-3">
                        <label className="block text-xs font-bold text-gray-300 uppercase tracking-wider">لوحة الألوان</label>

                        <div className="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                            <div>
                                <span className="block text-xs font-semibold text-gray-200">اللون الأساسي</span>
                                <span className="text-[10px] text-gray-400">لأزرار الشراء والعناوين وأشرطة التنقل</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="color"
                                    value={currentCustoms.primary_color}
                                    onChange={(e) => handleCustomChange('primary_color', e.target.value)}
                                    className="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent"
                                />
                                <span className="text-xs font-mono text-gray-400">{currentCustoms.primary_color}</span>
                            </div>
                        </div>

                        <div className="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                            <div>
                                <span className="block text-xs font-semibold text-gray-200">اللون الثانوي</span>
                                <span className="text-[10px] text-gray-400">للبانرات الترويجية والعناصر المساندة</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="color"
                                    value={currentCustoms.secondary_color}
                                    onChange={(e) => handleCustomChange('secondary_color', e.target.value)}
                                    className="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent"
                                />
                                <span className="text-xs font-mono text-gray-400">{currentCustoms.secondary_color}</span>
                            </div>
                        </div>

                        <div className="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                            <div>
                                <span className="block text-xs font-semibold text-gray-200">لون الخلفية</span>
                                <span className="text-[10px] text-gray-400">خلفية الموقع العامة</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="color"
                                    value={currentCustoms.background_color}
                                    onChange={(e) => handleCustomChange('background_color', e.target.value)}
                                    className="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent"
                                />
                                <span className="text-xs font-mono text-gray-400">{currentCustoms.background_color}</span>
                            </div>
                        </div>

                        <div className="bg-gray-900/80 p-3 rounded-xl border border-gray-700 flex items-center justify-between">
                            <div>
                                <span className="block text-xs font-semibold text-gray-200">لون النصوص</span>
                                <span className="text-[10px] text-gray-400">النصوص والعناوين الرئيسية</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <input
                                    type="color"
                                    value={currentCustoms.text_color}
                                    onChange={(e) => handleCustomChange('text_color', e.target.value)}
                                    className="w-9 h-9 rounded-lg border-0 cursor-pointer bg-transparent"
                                />
                                <span className="text-xs font-mono text-gray-400">{currentCustoms.text_color}</span>
                            </div>
                        </div>
                    </div>

                    {/* Typography */}
                    <div className="space-y-2">
                        <label className="block text-xs font-bold text-gray-300 uppercase tracking-wider">الخط العربي والغربي (Typography)</label>
                        <select
                            value={currentCustoms.font_family}
                            onChange={(e) => handleCustomChange('font_family', e.target.value)}
                            className="w-full bg-gray-900 border border-gray-700 rounded-xl px-3 py-2 text-sm text-gray-200 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="Cairo">Cairo (خط كايرو العصري)</option>
                            <option value="Tajawal">Tajawal (خط تجول الأنيق)</option>
                            <option value="Almarai">Almarai (خط المراعي المتوازن)</option>
                            <option value="Inter">Inter (تصميم تقني حديث)</option>
                            <option value="Roboto">Roboto (كلاسيكي عالمي)</option>
                        </select>
                    </div>

                    {/* Layout Options */}
                    <div className="space-y-2">
                        <label className="block text-xs font-bold text-gray-300 uppercase tracking-wider">تخطيط الهيدر والبانر</label>
                        <div className="grid grid-cols-2 gap-2">
                            <div>
                                <span className="block text-[11px] text-gray-400 mb-1">الهيدر</span>
                                <select
                                    value={currentCustoms.header_layout}
                                    onChange={(e) => handleCustomChange('header_layout', e.target.value)}
                                    className="w-full bg-gray-900 border border-gray-700 rounded-lg text-xs text-gray-200 p-1.5"
                                >
                                    <option value="Classic">كلاسيك</option>
                                    <option value="Centered">متمركز</option>
                                    <option value="Minimal">بسيط</option>
                                </select>
                            </div>
                            <div>
                                <span className="block text-[11px] text-gray-400 mb-1">البانر</span>
                                <select
                                    value={currentCustoms.banner_layout}
                                    onChange={(e) => handleCustomChange('banner_layout', e.target.value)}
                                    className="w-full bg-gray-900 border border-gray-700 rounded-lg text-xs text-gray-200 p-1.5"
                                >
                                    <option value="Slider">متحرك (Slider)</option>
                                    <option value="Grid">شبكي (Grid)</option>
                                    <option value="Single">ثابت</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {/* Store Info Preview Details */}
                    <div className="bg-indigo-950/40 border border-indigo-800/60 rounded-xl p-3 text-xs space-y-1">
                        <div className="font-bold text-indigo-300 flex items-center gap-1.5">
                            <span>ℹ️</span> بيانات المتجر الفعلية:
                        </div>
                        <div className="text-gray-300">الاسم: <span className="font-semibold text-white">{storeData.store_name}</span></div>
                        <div className="text-gray-300">الأقسام: <span className="font-semibold text-white">{storeData.categories?.length || 0}</span> قسم الرئيسي</div>
                        <div className="text-gray-300">المنتجات المعروضة: <span className="font-semibold text-white">{storeData.products?.length || 0}</span> منتج</div>
                    </div>
                </aside>

                {/* Center Viewport Container */}
                <main className="flex-1 bg-gray-950 flex items-center justify-center p-6 overflow-auto relative">
                    <div
                        style={getViewportStyles()}
                        className="transition-all duration-500 ease-in-out bg-white rounded-2xl overflow-hidden shadow-2xl border-4 border-gray-800 relative flex flex-col w-full h-full"
                    >
                        {/* Device Header Bar */}
                        <div className="bg-gray-800 text-gray-400 py-1.5 px-4 text-center text-[11px] font-mono border-b border-gray-700 flex items-center justify-between shrink-0">
                            <div className="flex items-center gap-1.5">
                                <span className="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                <span className="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                                <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                            </div>
                            <span className="text-gray-300 font-bold">{getViewportLabel()}</span>
                            <div className="text-xs">⚡ Order Saif Live</div>
                        </div>

                        {/* Iframe */}
                        <iframe
                            ref={iframeRef}
                            src={iframeSrc}
                            className="w-full flex-1 border-0 bg-white transition-opacity duration-200"
                            title="Live Theme Preview"
                        />
                    </div>
                </main>
            </div>
        </div>
    );
}
