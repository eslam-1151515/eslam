import React, { useState, useMemo } from 'react';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ThemeMarketplace({
    themes = [],
    activeTheme = 'default',
    filters = { type: 'all', search: '' },
    stats = { total: 0, free: 0, paid: 0 }
}) {
    const { flash } = usePage().props;
    const [selectedType, setSelectedType] = useState(filters.type || 'all');
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [sortBy, setSortBy] = useState('popular');
    const [activeModalTheme, setActiveModalTheme] = useState(null);
    const [modalTab, setModalTab] = useState('features'); // 'features' or 'reviews'
    const [installingSlug, setInstallingSlug] = useState(null);

    // Form for submitting reviews inside modal
    const { data: reviewData, setData: setReviewData, post: postReview, processing: reviewProcessing, reset: resetReview, errors: reviewErrors } = useForm({
        rating: 5,
        reviewer: '',
        comment: ''
    });

    // Filter and Sort Themes
    const filteredThemes = useMemo(() => {
        return themes
            .filter(theme => {
                if (selectedType === 'free' && theme.type !== 'free') return false;
                if (selectedType === 'paid' && theme.type !== 'paid') return false;
                if (searchQuery.trim() !== '') {
                    const q = searchQuery.toLowerCase();
                    const nameMatch = (theme.name || '').toLowerCase().includes(q);
                    const descMatch = (theme.description || '').toLowerCase().includes(q);
                    return nameMatch || descMatch;
                }
                return true;
            })
            .sort((a, b) => {
                if (sortBy === 'rating') return (b.rating || 0) - (a.rating || 0);
                if (sortBy === 'reviews') return (b.reviews_count || 0) - (a.reviews_count || 0);
                if (sortBy === 'newest') return (b.id || 0) - (a.id || 0);
                return (a.sort_order || 0) - (b.sort_order || 0); // Default popular/sort_order
            });
    }, [themes, selectedType, searchQuery, sortBy]);

    // Handle Theme Installation / Activation
    const handleInstall = (theme) => {
        if (theme.slug === activeTheme) return;
        setInstallingSlug(theme.slug);

        router.post(`/merchant/themes/marketplace/${theme.slug}/install`, {}, {
            preserveScroll: true,
            onFinish: () => setInstallingSlug(null)
        });
    };

    // Handle Review Submission
    const handleReviewSubmit = (e) => {
        e.preventDefault();
        if (!activeModalTheme) return;

        postReview(`/merchant/themes/marketplace/${activeModalTheme.slug}/review`, {
            preserveScroll: true,
            onSuccess: () => {
                resetReview();
                setModalTab('reviews');
                // Temporarily update modal theme reviews locally
                const newRev = {
                    id: 'new_' + Date.now(),
                    reviewer: reviewData.reviewer || 'تاجر فاست أوردر',
                    rating: Number(reviewData.rating),
                    comment: reviewData.comment,
                    date: new Date().toISOString().split('T')[0]
                };
                setActiveModalTheme(prev => ({
                    ...prev,
                    reviews: [newRev, ...(prev.reviews || [])],
                    reviews_count: (prev.reviews_count || 0) + 1
                }));
            }
        });
    };

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
            <Head title="سوق الثيمات والتصميمات | فاست أوردر" />

            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-gray-100 to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-gray-950 p-4 md:p-8 font-sans" dir="rtl">
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

                {/* Hero Header Banner */}
                <div className="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-2xl mb-8 border border-white/10">
                    <div className="absolute -right-10 -top-10 w-72 h-72 bg-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div className="absolute -left-10 -bottom-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

                    <div className="relative z-10 p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div className="space-y-3 text-center md:text-right max-w-2xl">
                            <div className="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-gradient-to-r from-orange-500/20 to-amber-500/20 border border-orange-500/30 text-orange-400 text-xs font-bold tracking-wide">
                                <span>✨ متجر التصميمات الاحترافية</span>
                            </div>
                            <h1 className="text-3xl md:text-4xl font-black tracking-tight leading-tight">
                                سوق ثيمات فاست أوردر
                            </h1>
                            <p className="text-slate-300 text-sm md:text-base leading-relaxed">
                                اختر من بين مجموعة واجهات احترافية ومحسنة لتحويل الزوار إلى مشترين. جميع الثيمات متجاوبة بنسبة 100% مع الجوال ومحسنة لسرعة التحميل ومحركات البحث.
                            </p>
                        </div>

                        {/* Summary Stats Cards */}
                        <div className="grid grid-cols-3 gap-3 w-full md:w-auto min-w-[300px]">
                            <div className="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                                <span className="block text-2xl md:text-3xl font-black text-orange-400">{stats.total || themes.length}</span>
                                <span className="text-xs text-slate-400 font-medium mt-1 block">إجمالي الثيمات</span>
                            </div>
                            <div className="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                                <span className="block text-2xl md:text-3xl font-black text-emerald-400">{stats.free || themes.filter(t => t.type === 'free').length}</span>
                                <span className="text-xs text-slate-400 font-medium mt-1 block">ثيمات مجانية</span>
                            </div>
                            <div className="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center hover:bg-white/10 transition">
                                <span className="block text-2xl md:text-3xl font-black text-amber-400">{stats.paid || themes.filter(t => t.type === 'paid').length}</span>
                                <span className="text-xs text-slate-400 font-medium mt-1 block">ثيمات مدفوعة</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Filter & Search Toolbar */}
                <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200 dark:border-slate-800 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    {/* Category Tabs */}
                    <div className="flex items-center gap-2 w-full md:w-auto bg-slate-100 dark:bg-slate-800/80 p-1 rounded-xl">
                        {[
                            { id: 'all', label: 'الكل', count: themes.length },
                            { id: 'free', label: 'المجانية', count: themes.filter(t => t.type === 'free').length },
                            { id: 'paid', label: 'المدفوعة الاحترافية', count: themes.filter(t => t.type === 'paid').length }
                        ].map(tab => (
                            <button
                                key={tab.id}
                                onClick={() => setSelectedType(tab.id)}
                                className={`flex-1 md:flex-none px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 ${
                                    selectedType === tab.id
                                        ? 'bg-white dark:bg-slate-700 text-orange-600 dark:text-orange-400 shadow-sm'
                                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                                }`}
                            >
                                <span>{tab.label}</span>
                                <span className={`text-xs px-2 py-0.5 rounded-full ${
                                    selectedType === tab.id ? 'bg-orange-100 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-500'
                                }`}>
                                    {tab.count}
                                </span>
                            </button>
                        ))}
                    </div>

                    {/* Search & Sort Controls */}
                    <div className="flex items-center gap-3 w-full md:w-auto">
                        <div className="relative flex-1 md:w-64">
                            <input
                                type="text"
                                placeholder="ابحث باسم الثيم أو الميزة..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 text-slate-800 dark:text-slate-200 placeholder-slate-400"
                            />
                            <span className="absolute left-3 top-2.5 text-slate-400">🔍</span>
                        </div>

                        <select
                            value={sortBy}
                            onChange={(e) => setSortBy(e.target.value)}
                            className="bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
                        >
                            <option value="popular">🔥 الأكثر طلباً</option>
                            <option value="rating">⭐ الأعلى تقييماً</option>
                            <option value="reviews">💬 الأكثر مراجعة</option>
                            <option value="newest">🆕 الأحدث إضافتاً</option>
                        </select>
                    </div>
                </div>

                {/* Themes Grid */}
                {filteredThemes.length === 0 ? (
                    <div className="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-sm max-w-lg mx-auto">
                        <div className="text-5xl mb-4">🎨</div>
                        <h3 className="text-xl font-bold text-slate-800 dark:text-white mb-2">لا توجد ثيمات مطابقة لبحثك</h3>
                        <p className="text-slate-500 dark:text-slate-400 text-sm mb-6">حاول تغيير كلمات البحث أو إعادة ضبط فلتر التصنيف لعرض جميع الثيمات المتاحة.</p>
                        <button
                            onClick={() => { setSelectedType('all'); setSearchQuery(''); }}
                            className="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-md transition"
                        >
                            عرض جميع الثيمات ({themes.length})
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredThemes.map((theme) => {
                            const isCurrentActive = theme.slug === activeTheme;
                            const isInstalling = installingSlug === theme.slug;

                            return (
                                <div
                                    key={theme.slug}
                                    className={`group bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border transition-all duration-300 flex flex-col justify-between ${
                                        isCurrentActive
                                            ? 'border-orange-500 shadow-xl ring-2 ring-orange-500/20 dark:ring-orange-500/10'
                                            : 'border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:-translate-y-1 hover:border-slate-300 dark:hover:border-slate-700'
                                    }`}
                                >
                                    <div>
                                        {/* Theme Thumbnail Preview Box */}
                                        <div className={`relative h-48 bg-gradient-to-tr ${getThemeGradient(theme.slug)} p-6 flex flex-col justify-between overflow-hidden`}>
                                            <div className="absolute inset-0 bg-black/10 backdrop-blur-[1px] group-hover:scale-105 transition-transform duration-500"></div>
                                            
                                            {/* Top Badges */}
                                            <div className="relative z-10 flex items-center justify-between">
                                                <span className={`px-3 py-1 rounded-full text-xs font-black shadow-md ${
                                                    theme.type === 'free'
                                                        ? 'bg-emerald-500 text-white'
                                                        : 'bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black'
                                                }`}>
                                                    {theme.type === 'free' ? '🎁 مجاني تماماً' : `💎 ${theme.price} ${theme.currency || 'ج.م'}`}
                                                </span>

                                                {isCurrentActive && (
                                                    <span className="px-3 py-1 rounded-full bg-white text-orange-600 text-xs font-black shadow-lg flex items-center gap-1.5 animate-pulse">
                                                        <span>✔</span>
                                                        <span>الثيم الحالي لمتجرك</span>
                                                    </span>
                                                )}
                                            </div>

                                            {/* Center Title Mockup */}
                                            <div className="relative z-10 text-center">
                                                <h3 className="text-xl font-black text-white drop-shadow-md tracking-wide">
                                                    {theme.name}
                                                </h3>
                                                <p className="text-xs text-white/80 mt-1">بواسطة {theme.author || 'Fast Order Team'}</p>
                                            </div>

                                            {/* Bottom Quick Overlay */}
                                            <div className="relative z-10 flex items-center justify-between text-xs text-white/90 bg-black/20 backdrop-blur-md px-3 py-1.5 rounded-xl">
                                                <span className="flex items-center gap-1">
                                                    <span className="text-amber-400 font-bold">★ {theme.rating || '5.0'}</span>
                                                    <span className="text-white/70">({theme.reviews_count || 0})</span>
                                                </span>
                                                <span>الإصدار {theme.version || '1.0.0'}</span>
                                            </div>
                                        </div>

                                        {/* Theme Body Content */}
                                        <div className="p-6 space-y-4">
                                            <p className="text-slate-600 dark:text-slate-300 text-sm leading-relaxed line-clamp-2">
                                                {theme.description}
                                            </p>

                                            {/* Feature Highlights */}
                                            <div className="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                                                <span className="text-xs font-bold text-slate-400 uppercase tracking-wider block">أبرز المميزات:</span>
                                                <ul className="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                                                    {(theme.features || []).slice(0, 3).map((feat, idx) => (
                                                        <li key={idx} className="flex items-center gap-2">
                                                            <span className="text-emerald-500 font-bold">✓</span>
                                                            <span className="truncate">{feat}</span>
                                                        </li>
                                                    ))}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Action Buttons Footer */}
                                    <div className="p-6 pt-0 flex flex-col gap-2.5">
                                        <div className="grid grid-cols-2 gap-2">
                                            <a
                                                href={`/merchant/themes/preview/${theme.slug}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="w-full py-2.5 px-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                                            >
                                                <span>👁️</span>
                                                <span>معاينة تفاعلية</span>
                                            </a>
                                            <button
                                                type="button"
                                                onClick={() => { setActiveModalTheme(theme); setModalTab('features'); }}
                                                className="w-full py-2.5 px-3 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5"
                                            >
                                                <span>ℹ️</span>
                                                <span>التوافق والمراجعات</span>
                                            </button>
                                        </div>

                                        {isCurrentActive ? (
                                            <div className="w-full py-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 font-black text-sm rounded-xl text-center flex items-center justify-center gap-2">
                                                <span>✔</span>
                                                <span>هذا هو الثيم النشط لمتجرك حالياً</span>
                                            </div>
                                        ) : (
                                            <button
                                                type="button"
                                                onClick={() => handleInstall(theme)}
                                                disabled={isInstalling}
                                                className={`w-full py-3 font-black text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2 ${
                                                    theme.type === 'paid'
                                                        ? 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white'
                                                        : 'bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white'
                                                } disabled:opacity-50`}
                                            >
                                                {isInstalling ? (
                                                    <>
                                                        <span className="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                                        <span>جاري تفعيل الثيم...</span>
                                                    </>
                                                ) : (
                                                    <>
                                                        <span>⚡</span>
                                                        <span>{theme.type === 'paid' ? `شراء وتفعيل الآن (${Math.round(theme.price)} ج.م)` : 'تفعيل الثيم لمتجري الآن'}</span>
                                                    </>
                                                )}
                                            </button>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}

                {/* Luxurious Details & Reviews Modal */}
                {activeModalTheme && (
                    <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in">
                        <div className="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
                            {/* Modal Header */}
                            <div className={`relative bg-gradient-to-r ${getThemeGradient(activeModalTheme.slug)} p-6 text-white flex items-center justify-between`}>
                                <div>
                                    <span className="text-xs bg-black/30 px-3 py-1 rounded-full font-bold mb-2 inline-block">
                                        {activeModalTheme.type === 'free' ? '🎁 ثيم مجاني' : `💎 ثيم مدفوع (${Math.round(activeModalTheme.price)} ج.م)`}
                                    </span>
                                    <h2 className="text-2xl font-black">{activeModalTheme.name}</h2>
                                    <p className="text-xs text-white/80 mt-1">بواسطة {activeModalTheme.author || 'Fast Order Team'} - الإصدار {activeModalTheme.version || '1.0.0'}</p>
                                </div>
                                <button
                                    onClick={() => setActiveModalTheme(null)}
                                    className="w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center text-white font-bold text-lg transition"
                                >
                                    ✕
                                </button>
                            </div>

                            {/* Modal Tabs */}
                            <div className="flex border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                                <button
                                    onClick={() => setModalTab('features')}
                                    className={`flex-1 py-3.5 text-sm font-bold border-b-2 transition ${
                                        modalTab === 'features'
                                            ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-white dark:bg-slate-900'
                                            : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-white'
                                    }`}
                                >
                                    ✨ المميزات وتفاصيل التوافق
                                </button>
                                <button
                                    onClick={() => setModalTab('reviews')}
                                    className={`flex-1 py-3.5 text-sm font-bold border-b-2 transition ${
                                        modalTab === 'reviews'
                                            ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-white dark:bg-slate-900'
                                            : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-white'
                                    }`}
                                >
                                    ⭐ التقييمات والمراجعات ({activeModalTheme.reviews_count || 0})
                                </button>
                            </div>

                            {/* Modal Content Scrollable Area */}
                            <div className="p-6 overflow-y-auto flex-1 space-y-6">
                                {modalTab === 'features' ? (
                                    <div className="space-y-6">
                                        <div>
                                            <h4 className="text-sm font-bold text-slate-800 dark:text-white mb-2">📄 وصف الثيم:</h4>
                                            <p className="text-slate-600 dark:text-slate-300 text-sm leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700/60">
                                                {activeModalTheme.description}
                                            </p>
                                        </div>

                                        <div>
                                            <h4 className="text-sm font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
                                                <span className="text-orange-500">🔥</span>
                                                <span>المميزات الرئيسية للثيم:</span>
                                            </h4>
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                {(activeModalTheme.features || []).map((feat, idx) => (
                                                    <div key={idx} className="flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-500/20">
                                                        <span className="text-emerald-500 font-bold mt-0.5">✔</span>
                                                        <span className="text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300">{feat}</span>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>

                                        <div>
                                            <h4 className="text-sm font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
                                                <span className="text-blue-500">🛡️</span>
                                                <span>التوافقية والمتطلبات الفنية:</span>
                                            </h4>
                                            <ul className="space-y-2.5">
                                                {(activeModalTheme.compatibility || []).map((comp, idx) => (
                                                    <li key={idx} className="flex items-center gap-2.5 text-xs md:text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-200/60 dark:border-slate-700/60">
                                                        <span className="text-blue-500">⚡</span>
                                                        <span>{comp}</span>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="space-y-6">
                                        {/* Rating Summary Header */}
                                        <div className="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30 border border-amber-500/30 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                                            <div className="text-center md:text-right">
                                                <span className="text-4xl font-black text-amber-500">{activeModalTheme.rating || '5.0'}</span>
                                                <span className="text-sm text-slate-500 dark:text-slate-400 block mt-1">من أصل 5 نجوم ({activeModalTheme.reviews_count || 0} مراجعة)</span>
                                            </div>
                                            <div className="flex text-amber-400 text-2xl tracking-widest">
                                                ★★★★★
                                            </div>
                                        </div>

                                        {/* Add Review Form */}
                                        <form onSubmit={handleReviewSubmit} className="bg-slate-50 dark:bg-slate-800/60 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-4">
                                            <h4 className="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                                <span>✍️</span>
                                                <span>أضف مراجعتك وتقييمك لهذا الثيم:</span>
                                            </h4>

                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">التقييم بالنجوم:</label>
                                                    <select
                                                        value={reviewData.rating}
                                                        onChange={(e) => setReviewData('rating', e.target.value)}
                                                        className="w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500"
                                                    >
                                                        <option value="5">⭐⭐⭐⭐⭐ 5 نجوم (ممتاز)</option>
                                                        <option value="4">⭐⭐⭐⭐ 4 نجوم (جيد جداً)</option>
                                                        <option value="3">⭐⭐⭐ 3 نجوم (جيد)</option>
                                                        <option value="2">⭐⭐ 2 نجوم (مقبول)</option>
                                                        <option value="1">⭐ 1 نجمة (سيء)</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">اسمك أو اسم متجرك (اختياري):</label>
                                                    <input
                                                        type="text"
                                                        placeholder="مثال: متجر الأناقة الحديثة"
                                                        value={reviewData.reviewer}
                                                        onChange={(e) => setReviewData('reviewer', e.target.value)}
                                                        className="w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500"
                                                    />
                                                </div>
                                            </div>

                                            <div>
                                                <label className="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">تعليقك أو تجربتك مع الثيم:</label>
                                                <textarea
                                                    rows="3"
                                                    required
                                                    placeholder="شاركنا رأيك في سرعة وتصميم وتجاوب الثيم مع عملائك..."
                                                    value={reviewData.comment}
                                                    onChange={(e) => setReviewData('comment', e.target.value)}
                                                    className="w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-500"
                                                ></textarea>
                                            </div>

                                            <button
                                                type="submit"
                                                disabled={reviewProcessing}
                                                className="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-md transition disabled:opacity-50 flex items-center justify-center gap-2"
                                            >
                                                <span>📨</span>
                                                <span>{reviewProcessing ? 'جاري نشر المراجعة...' : 'نشر المراجعة الآن'}</span>
                                            </button>
                                        </form>

                                        {/* Reviews List */}
                                        <div className="space-y-4">
                                            <h4 className="text-sm font-bold text-slate-800 dark:text-white">💬 آراء المتاجر والتجار ({activeModalTheme.reviews?.length || 0}):</h4>
                                            {(activeModalTheme.reviews || []).length === 0 ? (
                                                <p className="text-center py-6 text-slate-400 text-sm">لم يتم إضافة مراجعات بعد، كن أول من يقيم هذا الثيم!</p>
                                            ) : (
                                                <div className="space-y-3">
                                                    {(activeModalTheme.reviews || []).map((rev, idx) => (
                                                        <div key={rev.id || idx} className="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 space-y-2">
                                                            <div className="flex items-center justify-between">
                                                                <div className="flex items-center gap-2">
                                                                    <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-bold text-xs flex items-center justify-center">
                                                                        {(rev.reviewer || 'ت')[0]}
                                                                    </div>
                                                                    <div>
                                                                        <span className="text-xs font-bold text-slate-800 dark:text-white block">{rev.reviewer}</span>
                                                                        <span className="text-[10px] text-slate-400">{rev.date}</span>
                                                                    </div>
                                                                </div>
                                                                <span className="text-amber-400 font-bold text-xs">{"★".repeat(Math.round(rev.rating || 5))}</span>
                                                            </div>
                                                            <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed pl-10">
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

                            {/* Modal Footer */}
                            <div className="p-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                                <a
                                    href={`/merchant/themes/preview/${activeModalTheme.slug}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="px-4 py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition flex items-center gap-1.5"
                                >
                                    <span>👁️</span>
                                    <span>فتح المعاينة التفاعلية في نافذة جديدة</span>
                                </a>

                                {activeModalTheme.slug === activeTheme ? (
                                    <span className="px-5 py-2.5 bg-emerald-500 text-white text-xs font-bold rounded-xl flex items-center gap-1.5">
                                        <span>✔</span>
                                        <span>الثيم المفعل حالياً</span>
                                    </span>
                                ) : (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            handleInstall(activeModalTheme);
                                            setActiveModalTheme(null);
                                        }}
                                        className="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5"
                                    >
                                        <span>⚡</span>
                                        <span>تفعيل الثيم لمتجري الآن</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </MerchantLayout>
    );
}
