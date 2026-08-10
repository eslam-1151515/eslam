import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

// Template icons map
const TemplateIcon = ({ templateKey }) => {
    if (templateKey === 'classic') return (
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
    );
    if (templateKey === 'countdown') return (
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    );
    if (templateKey === 'product_showcase') return (
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
    );
    if (templateKey === 'product_detail') return (
        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    );
    return null;
};

// Template sections for the structure modal
const TEMPLATE_SECTIONS = {
    classic: [
        { emoji: '🎯', label: 'البانر الرئيسي', color: 'indigo' },
        { emoji: '⏱', label: 'عداد تنازلي للعرض', color: 'orange' },
        { emoji: '✨', label: 'مميزات وخصائص المنتج', color: 'emerald' },
        { emoji: '📦', label: 'كارت عرض تفاصيل المنتج', color: 'blue' },
        { emoji: '⭐', label: 'تقييمات وآراء العملاء', color: 'yellow' },
        { emoji: '✍️', label: 'فورم الشراء السريع', color: 'purple' },
    ],
    countdown: [
        { emoji: '⏱', label: 'عداد تنازلي أعلى الصفحة', color: 'orange' },
        { emoji: '🎯', label: 'البانر الرئيسي والعروض', color: 'indigo' },
        { emoji: '📦', label: 'كارت عرض تفاصيل المنتج', color: 'blue' },
        { emoji: '✍️', label: 'فورم الشراء السريع', color: 'purple' },
    ],
    product_showcase: [
        { emoji: '📦', label: 'عرض تفاصيل وصور المنتج', color: 'blue' },
        { emoji: '⭐', label: 'تقييمات وآراء العملاء', color: 'yellow' },
        { emoji: '✍️', label: 'فورم الشراء السريع', color: 'purple' },
    ],
    product_detail: [
        { emoji: '🖼️', label: 'صور المنتج الكاملة', color: 'blue' },
        { emoji: '🏷️', label: 'الاسم، السعر، الخصم', color: 'orange' },
        { emoji: '📐', label: 'اختيار الحجم واللون', color: 'emerald' },
        { emoji: '📝', label: 'وصف ومواصفات المنتج', color: 'gray' },
        { emoji: '✍️', label: 'فورم بيانات الطلب المباشر', color: 'purple' },
    ],
};

const colorMap = {
    indigo: 'bg-indigo-500/10 border-indigo-500/20 text-indigo-400',
    orange: 'bg-orange-500/10 border-orange-500/20 text-orange-400',
    emerald: 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
    blue: 'bg-blue-500/10 border-blue-500/20 text-blue-400',
    yellow: 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400',
    purple: 'bg-purple-500/10 border-purple-500/20 text-purple-400',
    gray: 'bg-gray-500/10 border-gray-500/20 text-gray-400',
};

// Preview URLs — open a real full landing page per template
const PREVIEW_URLS = {
    classic:          '/lp-previews/classic.html',
    countdown:        '/lp-previews/countdown.html',
    product_showcase: '/lp-previews/showcase.html',
    product_detail:   '/lp-previews/detail.html',
};

export default function LandingPagesCreate({ defaultSections, templates, products }) {
    const [selectedTemplate, setSelectedTemplate] = useState('classic');
    const [showAdvanced, setShowAdvanced] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        title: '',
        slug: '',
        product_id: '',
        template: 'classic',
        color_theme: 'light',
        facebook_pixel_id: '',
        tiktok_pixel_id: '',
        sections: defaultSections,
    });

    const handleSelectTemplate = (templateKey) => {
        setSelectedTemplate(templateKey);
        setData('template', templateKey);
    };

    const handleProductChange = (productId) => {
        const product = products.find(p => p.id === parseInt(productId));
        if (product) {
            const generatedSlug = product.name
                .toLowerCase()
                .replace(/[^a-z0-9\u0600-\u06FF\s]/g, '')
                .replace(/\s+/g, '-');
            setData({ ...data, product_id: productId, title: `صفحة هبوط: ${product.name}`, slug: generatedSlug });
        } else {
            setData({ ...data, product_id: '' });
        }
    };

    const handleFBPixelChange = (val) => {
        let text = val;
        if (text && (text.includes('fbq') || text.includes('script') || text.includes('facebook.com') || text.includes('<noscript>'))) {
            const found = [];
            const initMatches = [...text.matchAll(/fbq\s*\(\s*['"]init['"]\s*,\s*['"]?(\d+)['"]?/gi)];
            initMatches.forEach(m => { if (m[1]) found.push(m[1]); });

            const idMatches = [...text.matchAll(/[?&]id=(\d+)/gi)];
            idMatches.forEach(m => { if (m[1]) found.push(m[1]); });

            const digitMatches = text.match(/\b\d{13,17}\b/g);
            if (digitMatches) {
                digitMatches.forEach(num => found.push(num));
            }

            const unique = [...new Set(found)];
            if (unique.length > 0) {
                text = unique.join('\n');
            }
        }
        setData('facebook_pixel_id', text);
    };

    const handleTTPixelChange = (val) => {
        let text = val;
        if (text && (text.includes('ttq') || text.includes('script') || text.includes('analytics.tiktok.com'))) {
            const found = [];
            const loadMatches = [...text.matchAll(/ttq\.load\s*\(\s*['"]([a-zA-Z0-9_-]+)['"]\s*\)/gi)];
            loadMatches.forEach(m => { if (m[1]) found.push(m[1]); });

            const sdkMatches = [...text.matchAll(/[?&]sdkid=([a-zA-Z0-9_-]+)/gi)];
            sdkMatches.forEach(m => { if (m[1]) found.push(m[1]); });

            const unique = [...new Set(found)];
            if (unique.length > 0) {
                text = unique.join('\n');
            }
        }
        setData('tiktok_pixel_id', text);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/landing-pages');
    };

    return (
        <MerchantLayout title="إنشاء صفحة هبوط">
            <Head title="إنشاء صفحة هبوط جديدة" />

            <div className="max-w-4xl mx-auto space-y-6 pb-12">
                {/* Breadcrumbs */}
                <div className="flex items-center gap-2 text-sm text-gray-500">
                    <Link href="/admin/landing-pages" className="hover:text-orange-600 font-medium">صفحات الهبوط</Link>
                    <span>/</span>
                    <span className="text-gray-900 font-semibold">إنشاء صفحة جديدة</span>
                </div>

                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-8">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">إنشاء صفحة هبوط جديدة</h2>
                        <p className="text-sm text-gray-500 mt-1">ابدأ بربط منتجك واختيار قالب جاهز ومناسب لتبدأ العمل فوراً.</p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-8">

                        {/* ─── Product Selector ─────────────────────────── */}
                        <div className="bg-orange-50/30 border border-orange-100 rounded-2xl p-5 space-y-4">
                            <h3 className="text-sm font-bold text-orange-950 flex items-center gap-2">
                                <svg className="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                ربط منتج من متجرك (اختياري لكن يوصى به)
                            </h3>
                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-2">اختر منتجاً لتوليد الصفحة له تلقائياً:</label>
                                <select
                                    value={data.product_id}
                                    onChange={(e) => handleProductChange(e.target.value)}
                                    className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm bg-white"
                                >
                                    <option value="">-- بدون ربط منتج حالياً (إنشاء صفحة فارغة) --</option>
                                    {products.map(p => (
                                        <option key={p.id} value={p.id}>{p.name} ({p.price} ج.م)</option>
                                    ))}
                                </select>
                                <p className="text-[11px] text-gray-500 mt-1.5">عند اختيار منتج، سيقوم النظام تلقائياً بملء عنوان ورابط صفحة الهبوط وتجهيز كارت الشراء المباشر بالمنتج المختار.</p>
                            </div>
                        </div>

                        {/* ─── Page Title ───────────────────────────────── */}
                        <div>
                            <label className="block text-sm font-bold text-gray-700 mb-2">
                                عنوان الصفحة (للاستخدام الداخلي) <span className="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                placeholder="مثال: عرض ساعة الفانتوم الفاخرة"
                                className={`w-full px-4 py-3 rounded-xl border ${errors.title ? 'border-red-500' : 'border-gray-200'} focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm`}
                                required
                            />
                            {errors.title && <p className="text-red-500 text-xs mt-1">{errors.title}</p>}
                        </div>

                        {/* ─── Template Selector ────────────────────────── */}
                        <div className="space-y-4">
                            <div className="flex items-center justify-between">
                                <label className="block text-sm font-bold text-gray-800">اختر قالب صفحة الهبوط:</label>
                                <span className="text-xs text-gray-400 font-medium">يمكنك معاينة كل قالب قبل التنشيط</span>
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                {Object.entries(templates).map(([key, template]) => {
                                    const isSelected = selectedTemplate === key;
                                    return (
                                        <div
                                            key={key}
                                            onClick={() => handleSelectTemplate(key)}
                                            className={`relative rounded-2xl border-2 cursor-pointer transition-all duration-200 overflow-hidden bg-white flex flex-col justify-between p-4 group hover:shadow-md ${
                                                isSelected
                                                    ? 'border-orange-600 bg-orange-50/20 ring-4 ring-orange-500/10'
                                                    : 'border-gray-200 hover:border-gray-300'
                                            }`}
                                        >
                                            {/* Selection Radio / Badge */}
                                            <div className="flex items-center justify-between mb-4">
                                                <div className={`w-10 h-10 rounded-xl flex items-center justify-center transition-colors ${
                                                    isSelected ? 'bg-orange-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 group-hover:bg-orange-100 group-hover:text-orange-600'
                                                }`}>
                                                    <TemplateIcon templateKey={key} />
                                                </div>

                                                <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all ${
                                                    isSelected ? 'border-orange-600 bg-orange-600' : 'border-gray-300'
                                                }`}>
                                                    {isSelected && (
                                                        <svg className="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    )}
                                                </div>
                                            </div>

                                            {/* Template Title */}
                                            <div className="mb-5">
                                                <h4 className={`font-extrabold text-base transition-colors ${isSelected ? 'text-orange-600' : 'text-gray-900'}`}>
                                                    {template.name}
                                                </h4>
                                            </div>

                                            {/* Actions Footer */}
                                            <div className="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                                <button
                                                    type="button"
                                                    onClick={(e) => {
                                                        e.stopPropagation();
                                                        const origin = window.location.origin;
                                                        const mainDomainUrl = origin.replace(/\/\/[^.]+\./, '//') + PREVIEW_URLS[key];
                                                        window.open(mainDomainUrl, '_blank');
                                                    }}
                                                    className="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 border border-gray-200 hover:border-orange-200 px-3 py-1.5 rounded-xl transition-all"
                                                >
                                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    معاينة
                                                </button>

                                                <span className={`text-xs font-bold ${isSelected ? 'text-orange-600' : 'text-gray-400'}`}>
                                                    {isSelected ? 'مفعل الآن' : 'تفعيل'}
                                                </span>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>

                        {/* ─── Advanced Settings (collapsible) ─────────── */}
                        <div className="border border-gray-200 rounded-2xl overflow-hidden">
                            <button
                                type="button"
                                onClick={() => setShowAdvanced(!showAdvanced)}
                                className="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-right"
                            >
                                <span className="flex items-center gap-2 text-sm font-bold text-gray-700">
                                    <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    إعدادات متقدمة
                                    <span className="text-xs font-normal text-gray-400">(رابط الصفحة، المظهر، البيكسل)</span>
                                </span>
                                <svg
                                    className={`w-5 h-5 text-gray-400 transition-transform duration-200 ${showAdvanced ? 'rotate-180' : ''}`}
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                >
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {showAdvanced && (
                                <div className="p-5 space-y-6 border-t border-gray-200 bg-white">
                                    {/* Slug */}
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-2">
                                            رابط الصفحة (Slug) <span className="text-gray-400 font-normal">(اختياري)</span>
                                        </label>
                                        <div className="relative flex items-center">
                                            <span className="absolute right-4 text-gray-400 text-sm" dir="ltr">/lp/</span>
                                            <input
                                                type="text"
                                                value={data.slug}
                                                onChange={(e) => setData('slug', e.target.value)}
                                                placeholder="phantom-watch"
                                                className={`w-full pr-12 pl-4 py-3 rounded-xl border ${errors.slug ? 'border-red-500' : 'border-gray-200'} focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm`}
                                                dir="ltr"
                                            />
                                        </div>
                                        <p className="text-xs text-gray-400 mt-1">سيتم توليده تلقائياً من العنوان إذا تركته فارغاً.</p>
                                        {errors.slug && <p className="text-red-500 text-xs mt-1">{errors.slug}</p>}
                                    </div>

                                    {/* Color Theme */}
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                            <svg className="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                                            </svg>
                                            لون مظهر صفحة الهبوط (Color Theme)
                                        </label>
                                        <select
                                            value={data.color_theme}
                                            onChange={(e) => setData('color_theme', e.target.value)}
                                            className="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm bg-white"
                                        >
                                            <option value="light">الوضع الفاتح (Light Mode) - افتراضي</option>
                                            <option value="dark">الوضع الداكن (Dark Mode)</option>
                                        </select>
                                    </div>

                                    {/* Pixel Tracking */}
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                            <svg className="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                            </svg>
                                            أكواد البيكسل لتتبع الحملة الإعلانية <span className="text-gray-400 font-normal">(اختياري - مخصص لهذه الصفحة)</span>
                                        </label>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-xs font-bold text-gray-600 mb-2">Facebook Pixel ID:</label>
                                                <textarea
                                                    rows={2}
                                                    value={data.facebook_pixel_id}
                                                    onChange={(e) => handleFBPixelChange(e.target.value)}
                                                    placeholder="ضع الـ ID أو لزق كود فيسبوك بيكسل بالكامل وسيتم استخراج الرقم أوتوماتيكياً"
                                                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm font-mono text-right dir-rtl placeholder:text-right"
                                                    dir="rtl"
                                                />
                                                {errors.facebook_pixel_id && <p className="text-red-500 text-xs mt-1">{errors.facebook_pixel_id}</p>}
                                            </div>
                                            <div>
                                                <label className="block text-xs font-bold text-gray-600 mb-2">TikTok Pixel ID:</label>
                                                <textarea
                                                    rows={2}
                                                    value={data.tiktok_pixel_id}
                                                    onChange={(e) => handleTTPixelChange(e.target.value)}
                                                    placeholder="ضع الـ ID أو لزق كود تيك توك بيكسل بالكامل وسيتم استخراج الرقم أوتوماتيكياً"
                                                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm font-mono text-right dir-rtl placeholder:text-right"
                                                    dir="rtl"
                                                />
                                                {errors.tiktok_pixel_id && <p className="text-red-500 text-xs mt-1">{errors.tiktok_pixel_id}</p>}
                                            </div>
                                        </div>
                                        <p className="text-[11px] text-gray-400 mt-2">في حال تحديد بيكسل مخصص هنا، سيُستخدم في صفحة الهبوط بدلاً من البيكسل العام للمتجر.</p>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* ─── Submit ───────────────────────────────────── */}
                        <div className="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <Link
                                href="/admin/landing-pages"
                                className="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors"
                            >
                                إلغاء
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-md hover:shadow-lg disabled:opacity-50"
                            >
                                {processing ? 'جاري الإنشاء...' : 'إنشاء صفحة الهبوط والتعديل'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </MerchantLayout>
    );
}
