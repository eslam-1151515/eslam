import React, { useState, useMemo } from 'react';
import { Head, router, useForm, usePage, Link } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ThemeMarketplaceShow({
    theme = {},
    activeTheme = 'default'
}) {
    const { flash } = usePage().props;
    const [installingSlug, setInstallingSlug] = useState(null);
    const [activeSection, setActiveSection] = useState('overview'); // 'overview', 'features', 'reviews'

    // Form for submitting reviews
    const { data: reviewData, setData: setReviewData, post: postReview, processing: reviewProcessing, reset: resetReview, errors: reviewErrors } = useForm({
        rating: 5,
        reviewer: '',
        comment: ''
    });

    const isCurrentActive = theme.slug === activeTheme;
    const isInstalling = installingSlug === theme.slug;

    // Handle Theme Installation / Activation
    const handleInstall = () => {
        if (isCurrentActive) return;
        setInstallingSlug(theme.slug);

        router.post(`/merchant/themes/marketplace/${theme.slug}/install`, {}, {
            preserveScroll: true,
            onFinish: () => setInstallingSlug(null)
        });
    };

    // Handle Review Submission
    const handleReviewSubmit = (e) => {
        e.preventDefault();
        postReview(`/merchant/themes/marketplace/${theme.slug}/review`, {
            preserveScroll: true,
            onSuccess: () => {
                resetReview();
            }
        });
    };

    // Calculate rating distribution
    const ratingStats = useMemo(() => {
        const reviews = theme.reviews || [];
        const distribution = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };
        reviews.forEach(r => {
            const roundedRating = Math.round(Number(r.rating || 5));
            if (distribution[roundedRating] !== undefined) {
                distribution[roundedRating]++;
            }
        });
        const total = reviews.length || 1;
        return {
            distribution,
            percentages: {
                5: Math.round((distribution[5] / total) * 100),
                4: Math.round((distribution[4] / total) * 100),
                3: Math.round((distribution[3] / total) * 100),
                2: Math.round((distribution[2] / total) * 100),
                1: Math.round((distribution[1] / total) * 100),
            }
        };
    }, [theme.reviews]);

    // Helper for Theme Background Gradients
    const getThemeGradient = (slug) => {
        switch (slug) {
            case 'modern_minimalist':
                return 'from-slate-900 via-indigo-950 to-blue-900';
            case 'bold':
                return 'from-amber-600 via-orange-600 to-red-700';
            case 'dark_elegance':
                return 'from-gray-950 via-purple-950 to-slate-900';
            case 'fresh_market':
                return 'from-emerald-600 via-teal-700 to-green-800';
            case 'tech_store':
                return 'from-cyan-600 via-blue-700 to-indigo-900';
            case 'starter':
                return 'from-violet-600 via-purple-700 to-fuchsia-800';
            default:
                return 'from-orange-500 via-amber-600 to-yellow-600';
        }
    };

    return (
        <MerchantLayout>
            <Head title={`تفاصيل ثيم ${theme.name || 'الثيم'} | سوق الثيمات`} />

            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-gray-100 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-gray-950 p-4 md:p-8 font-sans" dir="rtl">
                {/* Back Link & Breadcrumbs */}
                <div className="mb-6 flex items-center justify-between">
                    <Link
                        href="/merchant/themes/marketplace"
                        className="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 transition"
                    >
                        <span>&rarr;</span>
                        <span>العودة لسوق الثيمات</span>
                    </Link>
                    <div className="text-xs text-slate-400 dark:text-slate-500">
                        سوق الثيمات / تفاصيل الثيم / {theme.name}
                    </div>
                </div>

                {/* Flash Messages */}
                {flash && flash.success && (
                    <div className="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-500/30 rounded-2xl shadow-lg flex items-center justify-between text-emerald-800 dark:text-emerald-300 animate-fade-in">
                        <div className="flex items-center gap-3">
                            <span className="text-2xl">🎉</span>
                            <span className="font-bold">{flash.success}</span>
                        </div>
                    </div>
                )}
                {flash && flash.error && (
                    <div className="mb-6 p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-500/30 rounded-2xl shadow-lg flex items-center justify-between text-rose-800 dark:text-rose-300 animate-fade-in">
                        <div className="flex items-center gap-3">
                            <span className="text-2xl">⚠️</span>
                            <span className="font-bold">{flash.error}</span>
                        </div>
                    </div>
                )}

                {/* Header Showcase Section */}
                <div className="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-2xl mb-8 border border-white/10">
                    <div className="absolute -right-10 -top-10 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div className="absolute -left-10 -bottom-10 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

                    <div className="relative z-10 p-6 md:p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div className="space-y-4 text-right max-w-3xl flex-1">
                            <div className="flex flex-wrap items-center gap-2">
                                <span className={`px-3 py-1 rounded-full text-xs font-black shadow-md ${
                                    theme.type === 'free'
                                        ? 'bg-emerald-500 text-white'
                                        : 'bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950'
                                }`}>
                                    {theme.type === 'free' ? '🎁 مجاني تماماً' : `💎 ثيم بريميوم`}
                                </span>
                                {isCurrentActive && (
                                    <span className="px-3 py-1 rounded-full bg-orange-500 text-white text-xs font-black shadow-md">
                                        ✔ الثيم النشط حالياً
                                    </span>
                                )}
                                <span className="px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-slate-300 text-xs font-medium">
                                    الإصدار {theme.version}
                                </span>
                            </div>
                            <h1 className="text-2xl md:text-4xl font-black leading-tight text-white drop-shadow-md">
                                {theme.name}
                            </h1>
                            <p className="text-slate-300 text-sm md:text-base leading-relaxed">
                                {theme.description}
                            </p>
                            <div className="flex items-center gap-4 text-xs md:text-sm text-slate-400">
                                <span>المطور: <strong className="text-white">{theme.author}</strong></span>
                                <span className="w-1.5 h-1.5 bg-slate-600 rounded-full"></span>
                                <span className="flex items-center gap-1">
                                    <span className="text-amber-400 font-bold">★ {theme.rating || '5.0'}</span>
                                    <span>({theme.reviews_count || 0} تقييم)</span>
                                </span>
                            </div>
                        </div>

                        {/* Top action block on mobile, right on desktop */}
                        <div className="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 text-center w-full lg:w-80 space-y-4 shrink-0 shadow-lg">
                            <div className="space-y-1">
                                <span className="text-xs text-slate-400 block font-medium">سعر الترخيص</span>
                                <div className="text-3xl font-black text-white">
                                    {theme.type === 'free' ? (
                                        <span className="text-emerald-400">مجاني بالكامل</span>
                                    ) : (
                                        <span>{theme.price} <span className="text-lg font-normal text-slate-300">{theme.currency || 'ج.م'}</span></span>
                                    )}
                                </div>
                            </div>

                            <div className="pt-2 space-y-3">
                                {isCurrentActive ? (
                                    <div className="w-full py-3 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-black text-sm rounded-xl text-center flex items-center justify-center gap-2">
                                        <span>✔</span>
                                        <span>هذا هو الثيم النشط لمتجرك</span>
                                    </div>
                                ) : (
                                    <button
                                        type="button"
                                        onClick={handleInstall}
                                        disabled={isInstalling}
                                        className="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-black text-sm rounded-xl shadow-lg transition flex items-center justify-center gap-2 disabled:opacity-50"
                                    >
                                        {isInstalling ? (
                                            <>
                                                <span className="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                                <span>جاري التفعيل...</span>
                                            </>
                                        ) : (
                                            <>
                                                <span>⚡</span>
                                                <span>{theme.type === 'paid' ? `شراء وتفعيل الثيم` : 'تفعيل الثيم الآن'}</span>
                                            </>
                                        )}
                                    </button>
                                )}

                                <a
                                    href={`/merchant/themes/preview/${theme.slug}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="w-full py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/10 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2"
                                >
                                    <span>👁️ معاينة تفاعلية حية</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Section Navigation Tabs */}
                <div className="flex border-b border-slate-200 dark:border-slate-800 mb-8 bg-white dark:bg-slate-900 p-1.5 rounded-2xl shadow-sm">
                    {[
                        { id: 'overview', label: ' نظرة عامة والمعرض' },
                        { id: 'features', label: 'المميزات والتوافق الفني' },
                        { id: 'reviews', label: `التقييمات والتعليقات (${theme.reviews_count || 0})` }
                    ].map(tab => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveSection(tab.id)}
                            className={`flex-1 py-3 text-center text-sm font-black rounded-xl transition-all duration-200 ${
                                activeSection === tab.id
                                    ? 'bg-slate-100 dark:bg-slate-800 text-orange-600 dark:text-orange-400 shadow-sm'
                                    : 'text-slate-500 hover:text-slate-800 dark:hover:text-white'
                            }`}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                {/* Main Content Body */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Left/Main Column - details depending on activeSection */}
                    <div className="lg:col-span-2 space-y-8">
                        {activeSection === 'overview' && (
                            <div className="space-y-6">
                                {/* Theme Mockup / Preview Showcase Card */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                                        <span>🎨</span>
                                        <span>معاينة واجهة الثيم الرقمية</span>
                                    </h2>

                                    {/* Simulated Desktop Preview Box */}
                                    <div className="rounded-2xl border-4 border-slate-800 dark:border-slate-700 overflow-hidden shadow-xl relative aspect-video bg-slate-900">
                                        {/* Browser Header Simulator */}
                                        <div className="bg-slate-800 dark:bg-slate-700 py-2 px-4 text-center text-[10px] font-mono flex items-center justify-between shrink-0 text-slate-400">
                                            <div className="flex items-center gap-1.5">
                                                <span className="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                                <span className="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                                                <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                            </div>
                                            <span className="text-slate-300">OrderSaif.eg/shop/preview/{theme.slug}</span>
                                            <span>معاينة سريعة</span>
                                        </div>

                                        {/* Simulated Preview Body */}
                                        <div className={`w-full h-full bg-gradient-to-tr ${getThemeGradient(theme.slug)} flex flex-col items-center justify-center p-8 text-center text-white relative`}>
                                            <div className="absolute inset-0 bg-black/10 backdrop-blur-[1px]"></div>
                                            <div className="relative z-10 space-y-3 max-w-md">
                                                <div className="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl mx-auto border border-white/20 shadow-lg animate-bounce">
                                                    💻
                                                </div>
                                                <h3 className="text-xl md:text-2xl font-black tracking-wide">{theme.name}</h3>
                                                <p className="text-xs text-white/80 line-clamp-2">
                                                    قم بتشغيل المعاينة الحية لتجربة الثيم وتغيير الألوان والخطوط والتفاعل مع سلة المشتريات وإتمام الطلب.
                                                </p>
                                                <div className="pt-2">
                                                    <a
                                                        href={`/merchant/themes/preview/${theme.slug}`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="px-6 py-2 bg-white text-slate-900 hover:bg-slate-100 text-xs font-black rounded-xl transition shadow-md inline-flex items-center gap-1.5"
                                                    >
                                                        <span>👁️ تشغيل المعاينة التفاعلية الآن</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Description Card */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                                        <span>📄</span>
                                        <span>تفاصيل وتصميم الثيم</span>
                                    </h2>
                                    <p className="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-slate-50 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-700/60">
                                        {theme.description}
                                    </p>
                                </div>
                            </div>
                        )}

                        {activeSection === 'features' && (
                            <div className="space-y-6">
                                {/* Features block */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                                        <span>🔥</span>
                                        <span>أبرز المميزات والخصائص للواجهة:</span>
                                    </h2>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {(theme.features || []).map((feat, idx) => (
                                            <div key={idx} className="flex items-start gap-3 p-4 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-500/20 shadow-sm">
                                                <span className="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center shrink-0 text-sm">✓</span>
                                                <span className="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-relaxed">{feat}</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* Compatibility block */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                                        <span>🛡️</span>
                                        <span>التوافقية والبيئة التشغيلية:</span>
                                    </h2>
                                    <div className="space-y-3">
                                        {(theme.compatibility || []).map((comp, idx) => (
                                            <div key={idx} className="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-700/60">
                                                <span className="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0 text-xs">⚡</span>
                                                <span className="font-medium">{comp}</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {activeSection === 'reviews' && (
                            <div className="space-y-6">
                                {/* Rating Distribution / Summary */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-4 mb-6">
                                        <span>📊</span>
                                        <span>ملخص تقييمات التجار</span>
                                    </h2>

                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                        {/* Big score */}
                                        <div className="text-center md:border-l border-slate-200 dark:border-slate-800 py-4">
                                            <div className="text-5xl font-black text-amber-500">{theme.rating || '5.0'}</div>
                                            <div className="flex justify-center text-amber-400 text-lg my-2">
                                                ★ ★ ★ ★ ★
                                            </div>
                                            <div className="text-xs text-slate-500 dark:text-slate-400">
                                                إجمالي التقييمات: {theme.reviews_count || 0} مراجعة
                                            </div>
                                        </div>

                                        {/* Bars */}
                                        <div className="md:col-span-2 space-y-2.5">
                                            {[5, 4, 3, 2, 1].map(stars => {
                                                const pct = ratingStats.percentages[stars] || 0;
                                                const count = ratingStats.distribution[stars] || 0;
                                                return (
                                                    <div key={stars} className="flex items-center gap-3 text-xs font-semibold">
                                                        <span className="w-12 text-slate-500 dark:text-slate-400 flex items-center gap-1 justify-end">
                                                            <span>{stars} نجوم</span>
                                                        </span>
                                                        <div className="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                                            <div
                                                                style={{ width: `${pct}%` }}
                                                                className="h-full bg-amber-500 rounded-full"
                                                            ></div>
                                                        </div>
                                                        <span className="w-14 text-slate-400 text-left">
                                                            {pct}% ({count})
                                                        </span>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>

                                {/* Add Review form */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                                        <span>✍️</span>
                                        <span>أضف مراجعتك وتقييمك الشخصي للثيم:</span>
                                    </h2>

                                    <form onSubmit={handleReviewSubmit} className="bg-slate-50 dark:bg-slate-800/40 p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 space-y-4">
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">التقييم بالنجوم:</label>
                                                <select
                                                    value={reviewData.rating}
                                                    onChange={(e) => setReviewData('rating', e.target.value)}
                                                    className="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800 dark:text-slate-200"
                                                >
                                                    <option value="5">⭐⭐⭐⭐⭐ 5 نجوم (ممتاز)</option>
                                                    <option value="4">⭐⭐⭐⭐ 4 نجوم (جيد جداً)</option>
                                                    <option value="3">⭐⭐⭐ 3 نجوم (جيد)</option>
                                                    <option value="2">⭐⭐ 2 نجوم (مقبول)</option>
                                                    <option value="1">⭐ 1 نجمة (سيء)</option>
                                                </select>
                                                {reviewErrors.rating && <p className="text-rose-500 text-xs mt-1">{reviewErrors.rating}</p>}
                                            </div>

                                            <div>
                                                <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">اسمك أو اسم متجرك (اختياري):</label>
                                                <input
                                                    type="text"
                                                    placeholder="مثال: متجر الأناقة الحديثة"
                                                    value={reviewData.reviewer}
                                                    onChange={(e) => setReviewData('reviewer', e.target.value)}
                                                    className="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800 dark:text-slate-200"
                                                />
                                                {reviewErrors.reviewer && <p className="text-rose-500 text-xs mt-1">{reviewErrors.reviewer}</p>}
                                            </div>
                                        </div>

                                        <div>
                                            <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">تعليقك أو مراجعتك التفصيلية:</label>
                                            <textarea
                                                rows="4"
                                                required
                                                placeholder="اكتب هنا رأيك بخصوص سرعة تحميل الصفحة، ومظهره على الجوال، وسهولة تخصيصه..."
                                                value={reviewData.comment}
                                                onChange={(e) => setReviewData('comment', e.target.value)}
                                                className="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-500 text-slate-800 dark:text-slate-200"
                                            ></textarea>
                                            {reviewErrors.comment && <p className="text-rose-500 text-xs mt-1">{reviewErrors.comment}</p>}
                                        </div>

                                        <button
                                            type="submit"
                                            disabled={reviewProcessing}
                                            className="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-md transition disabled:opacity-50 flex items-center justify-center gap-2"
                                        >
                                            <span>📨</span>
                                            <span>{reviewProcessing ? 'جاري إرسال مراجعتك...' : 'إرسال ونشر المراجعة الآن'}</span>
                                        </button>
                                    </form>
                                </div>

                                {/* Review list */}
                                <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                                    <h2 className="text-lg font-black text-slate-800 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800/80">
                                        💬 آراء ومراجعات التجار الفعليين ({theme.reviews?.length || 0})
                                    </h2>

                                    {(theme.reviews || []).length === 0 ? (
                                        <div className="text-center py-12 text-slate-400">
                                            <span className="text-4xl block mb-2">⭐</span>
                                            <span>لا توجد تقييمات لهذا الثيم حتى الآن. كن أول من يضيف تقييمه!</span>
                                        </div>
                                    ) : (
                                        <div className="space-y-4">
                                            {(theme.reviews || []).map((rev, idx) => (
                                                <div key={rev.id || idx} className="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/30 border border-slate-200/80 dark:border-slate-700/60 space-y-3">
                                                    <div className="flex items-center justify-between">
                                                        <div className="flex items-center gap-3">
                                                            <div className="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black text-sm flex items-center justify-center shadow-inner">
                                                                {(rev.reviewer || 'ت')[0]}
                                                            </div>
                                                            <div>
                                                                <span className="text-sm font-bold text-slate-800 dark:text-white block">{rev.reviewer}</span>
                                                                <span className="text-[10px] text-slate-400">{rev.date}</span>
                                                            </div>
                                                        </div>
                                                        <div className="flex items-center text-amber-400 font-black text-sm">
                                                            {"★".repeat(Math.round(rev.rating || 5))}
                                                            {"☆".repeat(5 - Math.round(rev.rating || 5))}
                                                        </div>
                                                    </div>
                                                    <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed pr-12">
                                                        {rev.comment}
                                                    </p>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Right Column - Specs & Sidebar Details */}
                    <div className="space-y-6">
                        {/* Summary Specification Panel */}
                        <div className="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                            <h3 className="text-base font-black text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-800/80 pb-3 flex items-center gap-2">
                                <span>📋</span>
                                <span>المواصفات الفنية للثيم</span>
                            </h3>

                            <div className="space-y-3 text-xs md:text-sm">
                                <div className="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/60">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">اسم المطور:</span>
                                    <span className="font-bold text-slate-800 dark:text-white">{theme.author}</span>
                                </div>
                                <div className="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/60">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">نوع الترخيص:</span>
                                    <span className={`px-2 py-0.5 rounded text-xs font-bold ${theme.type === 'free' ? 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400'}`}>
                                        {theme.type === 'free' ? 'مجاني' : 'مدفوع بريميوم'}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/60">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">سعر الثيم:</span>
                                    <span className="font-bold text-slate-800 dark:text-white">
                                        {theme.type === 'free' ? '0.00 EGP' : `${Math.round(theme.price)} ${theme.currency || 'EGP'}`}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/60">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">الإصدار الحالي:</span>
                                    <span className="font-bold text-slate-800 dark:text-white">{theme.version}</span>
                                </div>
                                <div className="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800/60">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">متوسط التقييمات:</span>
                                    <span className="font-bold text-amber-500">★ {theme.rating || '5.0'} / 5.0</span>
                                </div>
                                <div className="flex items-center justify-between py-2">
                                    <span className="text-slate-500 dark:text-slate-400 font-medium">عدد المراجعات:</span>
                                    <span className="font-bold text-slate-800 dark:text-white">{theme.reviews_count || 0} مراجعة</span>
                                </div>
                            </div>
                        </div>

                        {/* Order Saif Guarantee Badge */}
                        <div className="bg-gradient-to-r from-orange-500/10 to-amber-500/10 border border-orange-500/20 rounded-3xl p-6 text-right space-y-3">
                            <div className="w-10 h-10 rounded-2xl bg-orange-500 text-white flex items-center justify-center text-xl shadow-md">
                                🛡️
                            </div>
                            <h4 className="text-sm font-black text-orange-600 dark:text-orange-400">ضمان جودة وأمان أوردر سيف</h4>
                            <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                جميع الثيمات المعروضة في سوقنا يتم مراجعتها برمجياً وفحصها للتأكد من خلوها من أي مشاكل برمجية أو أكواد ضارة، لضمان أعلى مستويات الأداء والأمان لمتجرك وسرعة تحميل لا تضاهى.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
