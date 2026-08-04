import React, { useState, useMemo } from 'react';
import { Head } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function TutorialsIndex({ tutorials, categories }) {
    const [selectedCategory, setSelectedCategory] = useState('الكل');
    const [searchQuery, setSearchQuery] = useState('');
    const [activeVideo, setActiveVideo] = useState(null);

    const filteredTutorials = useMemo(() => {
        return tutorials.filter((item) => {
            const matchesCategory = selectedCategory === 'الكل' || item.category === selectedCategory;
            const matchesSearch = searchQuery.trim() === '' ||
                item.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
                (item.description && item.description.toLowerCase().includes(searchQuery.toLowerCase()));
            return matchesCategory && matchesSearch;
        });
    }, [tutorials, selectedCategory, searchQuery]);

    return (
        <MerchantLayout title="الشروحات والدروس">
            <Head title="مكتبة الشروحات والدروس التعليمية" />

            <div className="space-y-8 py-4">
                {/* Header & Search */}
                <div className="relative rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 overflow-hidden shadow-xl border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div className="space-y-2 max-w-xl">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold">
                            🎓 مركز الشروحات والدروس التعليمية
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-black text-white">
                            دليلك الشامل لتعلم واستخدام منصة فاست أوردر
                        </h1>
                        <p className="text-xs text-gray-300 leading-relaxed">
                            شروحات بالفيديو تغطي كافة ميزات متجرك — اختر الدرس وابدأ في التعلم والتطوير مباشرة.
                        </p>
                    </div>

                    {/* Search Input */}
                    <div className="w-full md:w-80 relative">
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            placeholder="بحث في الدروس والشروحات..."
                            className="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/15 rounded-2xl text-xs text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white/15 transition-all"
                        />
                        <span className="absolute left-3 top-3.5 text-gray-400 text-xs">🔍</span>
                    </div>
                </div>

                {/* Category Tabs Filter */}
                {categories.length > 1 && (
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                        {categories.map((cat) => (
                            <button
                                key={cat}
                                onClick={() => setSelectedCategory(cat)}
                                className={`px-4 py-2.5 rounded-2xl text-xs font-extrabold whitespace-nowrap transition-all border ${
                                    selectedCategory === cat
                                        ? 'bg-orange-600 text-white border-orange-600 shadow-md shadow-orange-600/20'
                                        : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 hover:bg-gray-50'
                                }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>
                )}

                {/* Tutorials Video Cards Grid */}
                {filteredTutorials.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredTutorials.map((tutorial) => (
                            <div
                                key={tutorial.id}
                                onClick={() => setActiveVideo(tutorial)}
                                className="group bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer flex flex-col justify-between"
                            >
                                <div>
                                    {/* Thumbnail container */}
                                    <div className="aspect-video bg-slate-900 relative overflow-hidden">
                                        <img
                                            src={`https://img.youtube.com/vi/${tutorial.youtube_id}/hqdefault.jpg`}
                                            alt={tutorial.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            onError={(e) => { e.target.src = 'https://dummyimage.com/600x400/0f172a/ffffff&text=Video+Lesson'; }}
                                        />

                                        {/* Play Overlay */}
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-center justify-center">
                                            <div className="w-14 h-14 rounded-2xl bg-orange-600/90 text-white flex items-center justify-center text-xl shadow-lg shadow-orange-600/40 group-hover:scale-110 transition-transform">
                                                ▶
                                            </div>
                                        </div>

                                        {/* Category Badge & Duration */}
                                        <div className="absolute top-3 right-3">
                                            <span className="px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-bold border border-white/10">
                                                {tutorial.category}
                                            </span>
                                        </div>
                                        {tutorial.duration && (
                                            <div className="absolute bottom-3 left-3">
                                                <span className="px-2.5 py-1 rounded-lg bg-black/70 text-white text-[10px] font-mono font-bold">
                                                    ⏱ {tutorial.duration}
                                                </span>
                                            </div>
                                        )}
                                    </div>

                                    {/* Content */}
                                    <div className="p-5 space-y-2">
                                        <h3 className="font-extrabold text-base text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-2">
                                            {tutorial.title}
                                        </h3>
                                        <p className="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                                            {tutorial.description || 'اضغط هنا لمشاهدة شرح هذا الفيديو بالتفصيل.'}
                                        </p>
                                    </div>
                                </div>

                                <div className="p-5 pt-0">
                                    <div className="pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-orange-600 group-hover:translate-x-[-4px] transition-transform">
                                        <span>مشاهدة الدرس الآن</span>
                                        <span>←</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="bg-white p-12 rounded-3xl border border-gray-200 text-center space-y-3">
                        <div className="text-4xl">🎬</div>
                        <h3 className="font-bold text-base text-gray-800">لا تتوفر شروحات تطابق البحث حالياً</h3>
                        <p className="text-xs text-gray-500">جرب تصفح أقسام أخرى أو تغيير كلمة البحث.</p>
                    </div>
                )}

                {/* Video Modal Player */}
                {activeVideo && (
                    <div
                        className="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-in fade-in duration-200"
                        onClick={() => setActiveVideo(null)}
                    >
                        <div
                            className="bg-slate-900 border border-white/10 rounded-3xl max-w-4xl w-full overflow-hidden shadow-2xl flex flex-col"
                            onClick={(e) => e.stopPropagation()}
                        >
                            {/* Header */}
                            <div className="p-4 border-b border-white/10 flex items-center justify-between bg-slate-950/70">
                                <div>
                                    <span className="text-[11px] text-orange-400 font-bold block">{activeVideo.category}</span>
                                    <h3 className="text-base font-bold text-white line-clamp-1">{activeVideo.title}</h3>
                                </div>
                                <button
                                    onClick={() => setActiveVideo(null)}
                                    className="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition-colors"
                                >
                                    إغلاق ✕
                                </button>
                            </div>

                            {/* Player */}
                            <div className="aspect-video w-full bg-black">
                                <iframe
                                    src={activeVideo.embed_url + "?autoplay=1"}
                                    title={activeVideo.title}
                                    className="w-full h-full border-0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowFullScreen
                                ></iframe>
                            </div>

                            {/* Description Footer */}
                            {activeVideo.description && (
                                <div className="p-5 bg-slate-950/40 border-t border-white/5 space-y-1">
                                    <h4 className="text-xs font-bold text-gray-400">تفاصيل الدرس:</h4>
                                    <p className="text-xs text-gray-300 leading-relaxed">{activeVideo.description}</p>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </MerchantLayout>
    );
}
