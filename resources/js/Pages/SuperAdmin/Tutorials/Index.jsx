import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';

export default function TutorialsIndex({ tutorials, categories }) {
    const { flash } = usePage().props;
    const [editingTutorial, setEditingTutorial] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [previewVideo, setPreviewVideo] = useState(null);

    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        title: '',
        category: 'المنتجات والأقسام',
        youtube_url: '',
        description: '',
        duration: '',
        sort_order: 0,
        is_published: true,
    });

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingTutorial(null);
        setData({
            title: '',
            category: 'المنتجات والأقسام',
            youtube_url: '',
            description: '',
            duration: '',
            sort_order: 0,
            is_published: true,
        });
        setShowModal(true);
    };

    const openEditModal = (tutorial) => {
        clearErrors();
        setEditingTutorial(tutorial);
        setData({
            title: tutorial.title,
            category: tutorial.category || 'عام',
            youtube_url: tutorial.youtube_url,
            description: tutorial.description || '',
            duration: tutorial.duration || '',
            sort_order: tutorial.sort_order || 0,
            is_published: tutorial.is_published,
        });
        setShowModal(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingTutorial) {
            put(`/super-admin/tutorials/${editingTutorial.id}`, {
                onSuccess: () => {
                    setShowModal(false);
                    reset();
                },
            });
        } else {
            post('/super-admin/tutorials', {
                onSuccess: () => {
                    setShowModal(false);
                    reset();
                },
            });
        }
    };

    const handleToggle = (tutorial) => {
        router.patch(`/super-admin/tutorials/${tutorial.id}/toggle`, {}, { preserveScroll: true });
    };

    const handleDelete = (tutorial) => {
        if (confirm(`هل أنت متأكد من حذف الشرح "${tutorial.title}"؟`)) {
            router.delete(`/super-admin/tutorials/${tutorial.id}`, { preserveScroll: true });
        }
    };

    return (
        <SuperAdminLayout>
            <Head title="إدارة الشروحات والدروس" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">إدارة الشروحات وفيديوهات اليوتيوب</h1>
                        <p className="text-xs text-gray-500 mt-1">إضافة وتنظيم فيديوهات شرح استخدام المنصة للتجار بحجم وقدرة استيعابية عالية</p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2"
                    >
                        <span>+</span> إضافة فيديو شرح جديد
                    </button>
                </div>

                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-800 text-xs font-bold">
                        {flash.success}
                    </div>
                )}

                {/* Tutorials Table */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table className="w-full text-right border-collapse">
                        <thead>
                            <tr className="bg-slate-50 border-b border-gray-100 text-xs font-bold text-gray-500">
                                <th className="p-4">الفيديو والمعاينة</th>
                                <th className="p-4">عنوان الشرح</th>
                                <th className="p-4">القسم/التصنيف</th>
                                <th className="p-4">المدة</th>
                                <th className="p-4">الحالة</th>
                                <th className="p-4">العمليات</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 text-xs text-gray-700">
                            {tutorials.map((t) => (
                                <tr key={t.id} className="hover:bg-slate-50/50 transition-colors">
                                    <td className="p-4">
                                        <div
                                            onClick={() => setPreviewVideo(t)}
                                            className="w-24 h-14 bg-slate-900 rounded-lg overflow-hidden relative cursor-pointer group shadow-sm"
                                        >
                                            <img
                                                src={`https://img.youtube.com/vi/${t.youtube_id}/mqdefault.jpg`}
                                                alt={t.title}
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                                onError={(e) => { e.target.src = 'https://dummyimage.com/120x80/0f172a/ffffff&text=Video'; }}
                                            />
                                            <div className="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-xs group-hover:bg-black/20 transition-colors">
                                                ▶
                                            </div>
                                        </div>
                                    </td>
                                    <td className="p-4">
                                        <div className="font-bold text-gray-900 text-sm">{t.title}</div>
                                        <div className="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{t.description || 'بدون وصف إضافي'}</div>
                                    </td>
                                    <td className="p-4">
                                        <span className="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[11px]">
                                            {t.category}
                                        </span>
                                    </td>
                                    <td className="p-4 text-gray-500 font-semibold">{t.duration || '-'}</td>
                                    <td className="p-4">
                                        <button
                                            onClick={() => handleToggle(t)}
                                            className={`px-2.5 py-1 rounded-full text-[10px] font-bold ${
                                                t.is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700'
                                            }`}
                                        >
                                            {t.is_published ? 'منشور ✓' : 'مسودة ✖'}
                                        </button>
                                    </td>
                                    <td className="p-4">
                                        <div className="flex items-center gap-2">
                                            <button
                                                onClick={() => openEditModal(t)}
                                                className="px-3 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 font-bold rounded-lg transition-colors"
                                            >
                                                تعديل
                                            </button>
                                            <button
                                                onClick={() => handleDelete(t)}
                                                className="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-lg transition-colors"
                                            >
                                                حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Video Preview Modal */}
                {previewVideo && (
                    <div className="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4" onClick={() => setPreviewVideo(null)}>
                        <div className="bg-slate-900 rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl border border-white/10" onClick={(e) => e.stopPropagation()}>
                            <div className="p-4 border-b border-white/10 flex items-center justify-between">
                                <h3 className="text-sm font-bold text-white">🎬 {previewVideo.title}</h3>
                                <button onClick={() => setPreviewVideo(null)} className="text-gray-400 hover:text-white text-xs font-bold">إغلاق ✕</button>
                            </div>
                            <div className="aspect-video w-full bg-black">
                                <iframe
                                    src={previewVideo.embed_url}
                                    title={previewVideo.title}
                                    className="w-full h-full border-0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowFullScreen
                                ></iframe>
                            </div>
                        </div>
                    </div>
                )}

                {/* Form Modal */}
                {showModal && (
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div className="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                            <div className="flex items-center justify-between border-b pb-3">
                                <h3 className="font-bold text-base text-gray-900">
                                    {editingTutorial ? 'تعديل الشرح' : 'إضافة شرح تعليمي جديد'}
                                </h3>
                                <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">✕</button>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">عنوان الشرح/الدرس</label>
                                    <input
                                        type="text"
                                        value={data.title}
                                        onChange={(e) => setData('title', e.target.value)}
                                        placeholder="مثال: شرح كيفية إضافة منتج وتحديد الخيارات والمواصفات"
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500"
                                    />
                                    {errors.title && <p className="text-[11px] text-red-600 mt-1">{errors.title}</p>}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">القسم/التصنيف التعليمي</label>
                                    <input
                                        type="text"
                                        value={data.category}
                                        onChange={(e) => setData('category', e.target.value)}
                                        placeholder="مثال: المنتجات والأقسام، صفحات الهبوط، الطلبات..."
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">رابط فيديو اليوتيوب (YouTube URL)</label>
                                    <input
                                        type="text"
                                        value={data.youtube_url}
                                        onChange={(e) => setData('youtube_url', e.target.value)}
                                        placeholder="مثال: https://www.youtube.com/watch?v=XXXXXX"
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 dir-ltr text-right"
                                    />
                                    {errors.youtube_url && <p className="text-[11px] text-red-600 mt-1">{errors.youtube_url}</p>}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">وصف تفصيلي للشرح</label>
                                    <textarea
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        rows="3"
                                        placeholder="اكتب نبذة عن المحتوى والنقاط التي يتناولها الفيديو..."
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div className="grid grid-cols-2 gap-3">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1">مدة الفيديو (اختياري)</label>
                                        <input
                                            type="text"
                                            value={data.duration}
                                            onChange={(e) => setData('duration', e.target.value)}
                                            placeholder="مثال: 05:20"
                                            className="w-full px-3 py-2 border rounded-xl text-xs"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1">ترتيب الظهور</label>
                                        <input
                                            type="number"
                                            value={data.sort_order}
                                            onChange={(e) => setData('sort_order', parseInt(e.target.value) || 0)}
                                            className="w-full px-3 py-2 border rounded-xl text-xs"
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center pt-2">
                                    <label className="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">
                                        <input
                                            type="checkbox"
                                            checked={data.is_published}
                                            onChange={(e) => setData('is_published', e.target.checked)}
                                            className="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        />
                                        نشر الفيديو ورؤيته للتجار
                                    </label>
                                </div>

                                <div className="pt-3 border-t flex justify-end gap-2">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50"
                                    >
                                        إلغاء
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md"
                                    >
                                        {editingTutorial ? 'حفظ التعديلات' : 'إضافة الدرس'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </SuperAdminLayout>
    );
}
