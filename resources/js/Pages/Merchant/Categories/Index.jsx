import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function CategoriesIndex({ categories, parentCategories, mainCategoryOptions, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.q || '');
    const [editingCategory, setEditingCategory] = useState(null);
    const [showFormModal, setShowFormModal] = useState(false);
    const [zoomImage, setZoomImage] = useState(null);

    // Form for create/edit
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name_ar: '',
        name_en: '',
        description: '',
        parent_id: '',
        main_category: mainCategoryOptions?.[0] || '',
        image: null,
        _method: 'POST',
    });

    const [imagePreview, setImagePreview] = React.useState(null);

    React.useEffect(() => {
        if (!data.image) {
            setImagePreview(null);
            return;
        }
        if (data.image instanceof File) {
            const reader = new FileReader();
            reader.onloadend = () => {
                let result = reader.result;
                if (typeof result === 'string') {
                    // If the browser fails to recognize the image type (e.g. extension ends in _timestamp)
                    // it reads it as application/octet-stream. Force it to image/jpeg so <img> tag displays it.
                    if (result.startsWith('data:application/octet-stream;') || result.startsWith('data:;')) {
                        result = result.replace(/^data:[^;]*/, 'data:image/jpeg');
                    }
                }
                setImagePreview(result);
            };
            reader.readAsDataURL(data.image);
        }
    }, [data.image]);

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/categories', { q: search }, { preserveState: true, replace: true });
    };

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingCategory(null);
        setData({
            name_ar: '',
            name_en: '',
            description: '',
            parent_id: '',
            main_category: mainCategoryOptions?.[0] || '',
            image: null,
            _method: 'POST',
        });
        setShowFormModal(true);
    };

    const openEditModal = (category) => {
        clearErrors();
        setEditingCategory(category);
        setData({
            name_ar: category.name_ar || '',
            name_en: category.name_en || '',
            description: category.description || '',
            parent_id: category.parent_id || '',
            main_category: category.main_category || mainCategoryOptions?.[0] || '',
            image: null,
            _method: 'PUT',
        });
        setShowFormModal(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingCategory) {
            // For files inside multipart form we must use post with _method=PUT in Laravel if image is uploaded
            // But since image is optional and category update might not have image, let's post with _method PUT just to be safe.
            post(`/admin/categories/${editingCategory.id}`, {
                forceFormData: true,
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        } else {
            post('/admin/categories', {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        }
    };

    const handleDelete = (category) => {
        if (!confirm(`هل أنت متأكد من حذف تصنيف "${category.name_ar || category.name}"؟`)) return;
        router.delete(`/admin/categories/${category.id}`);
    };

    return (
        <MerchantLayout title="إدارة التصنيفات">
            <Head title="التصنيفات" />

            <div className="space-y-5">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">التصنيفات</h2>
                        <p className="text-sm text-gray-500 mt-0.5">
                            إجمالي {categories.total} تصنيف
                        </p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة تصنيف جديد
                    </button>
                </div>

                {/* Search */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <form onSubmit={handleSearch} className="flex gap-3">
                        <div className="flex-1 relative">
                            <svg className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                placeholder="ابحث باسم التصنيف..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pr-9 pl-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                            />
                        </div>
                        <button
                            type="submit"
                            className="px-5 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors"
                        >
                            بحث
                        </button>
                        {filters?.q && (
                            <button
                                type="button"
                                onClick={() => {
                                    setSearch('');
                                    router.get('/admin/categories', {}, { replace: true });
                                }}
                                className="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors"
                            >
                                إعادة تعيين
                            </button>
                        )}
                    </form>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-lg text-green-800 text-sm font-medium flex items-center gap-2">
                        <span>✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-lg text-red-800 text-sm font-medium flex items-center gap-2">
                        <span>⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Table */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th className="px-6 py-4">الصورة</th>
                                    <th className="px-6 py-4">اسم التصنيف</th>
                                    <th className="px-6 py-4">الأقسام الرئيسية</th>
                                    <th className="px-6 py-4">العمليات</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm text-gray-700">
                                {categories.data.length > 0 ? (
                                    categories.data.map((cat) => (
                                        <tr key={cat.id} className="hover:bg-gray-50/50 transition-colors">
                                            <td className="px-6 py-4">
                                                {cat.image_path ? (
                                                    <div
                                                        onClick={() => {
                                                            const src = cat.image_path.startsWith('/') || cat.image_path.startsWith('http') ? cat.image_path : `/storage/${cat.image_path}`;
                                                            setZoomImage({ src, title: cat.name_ar });
                                                        }}
                                                        className="relative group cursor-pointer w-10 h-10 rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:ring-2 hover:ring-orange-500 transition-all"
                                                        title="انقر لتكبير الصورة"
                                                    >
                                                        <img
                                                            src={cat.image_path.startsWith('/') || cat.image_path.startsWith('http') ? cat.image_path : `/storage/${cat.image_path}`}
                                                            alt={cat.name_ar}
                                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                                        />
                                                        <div className="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                                            🔍
                                                        </div>
                                                    </div>
                                                ) : (
                                                    <div className="w-10 h-10 bg-gray-100 text-gray-400 rounded-lg border border-gray-200 flex items-center justify-center text-xs font-medium">
                                                        بلا صورة
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 font-semibold text-gray-900">{cat.name_ar}</td>
                                            <td className="px-6 py-4">
                                                <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-800 border border-amber-100">
                                                    {cat.main_category}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-2">
                                                    <button
                                                        onClick={() => openEditModal(cat)}
                                                        className="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold hover:bg-indigo-100 transition-colors"
                                                    >
                                                        تعديل
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(cat)}
                                                        className="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors"
                                                    >
                                                        حذف
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="px-6 py-10 text-center text-gray-400">
                                            لا توجد تصنيفات مضافة حالياً.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {categories.links && categories.links.length > 3 && (
                        <div className="bg-white border-t border-gray-100 px-6 py-4 flex justify-center gap-1.5">
                            {categories.links.map((link, idx) => (
                                <button
                                    key={idx}
                                    disabled={!link.url || link.active}
                                    onClick={() => router.get(link.url)}
                                    className={`px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-colors ${
                                        link.active
                                            ? 'bg-orange-600 border-orange-600 text-white shadow-sm'
                                            : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                                    } disabled:opacity-50`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Modal Form */}
            {showFormModal && (
                <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
                    <div className="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-gray-100 animate-in fade-in zoom-in duration-200">
                        <div className="h-16 bg-gray-50 border-b border-gray-100 px-6 flex items-center justify-between">
                            <h3 className="font-bold text-gray-900">
                                {editingCategory ? 'تعديل التصنيف' : 'إضافة تصنيف جديد'}
                            </h3>
                            <button
                                onClick={() => setShowFormModal(false)}
                                className="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                ✕
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {editingCategory && (
                                <input type="hidden" name="_method" value="PUT" />
                            )}
                            
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1">اسم التصنيف <span className="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    value={data.name_ar}
                                    onChange={(e) => setData('name_ar', e.target.value)}
                                    placeholder="مثال: فساتين، قمصان"
                                    className={`w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                        errors.name_ar ? 'border-red-400 bg-red-50' : 'border-gray-300'
                                    }`}
                                />
                                {errors.name_ar && <p className="text-xs text-red-600 mt-1">{errors.name_ar}</p>}
                            </div>

                             <div>
                                 <div className="flex justify-between items-center mb-1">
                                     <label className="block text-sm font-semibold text-gray-700">الأقسام الرئيسية <span className="text-red-500">*</span></label>
                                     <a
                                         href="/admin/settings?tab=categories"
                                         target="_blank"
                                         rel="noopener noreferrer"
                                         className="text-xs text-orange-600 hover:text-orange-700 font-semibold flex items-center gap-0.5 cursor-pointer"
                                     >
                                         إضافة/ تعديل الأقسام
                                     </a>
                                 </div>
                                 <select
                                     value={data.main_category || ''}
                                     onChange={(e) => setData('main_category', e.target.value)}
                                     className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white"
                                 >
                                     <option value="">-- اختياري (بدون قسم رئيسي) --</option>
                                     {Array.from(new Set([...(mainCategoryOptions || []), data.main_category].filter(Boolean))).map((opt) => (
                                         <option key={opt} value={opt}>{opt}</option>
                                     ))}
                                 </select>
                                 {errors.main_category && <p className="text-xs text-red-600 mt-1">{errors.main_category}</p>}
                             </div>



                             <div>
                                 <label className="block text-sm font-semibold text-gray-700 mb-1">أيقونة/صورة التصنيف</label>
                                  <input
                                      type="file"
                                      onChange={(e) => setData('image', e.target.files[0])}
                                      className="w-full text-sm text-gray-500 file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                  />
                                 {errors.image && <p className="text-xs text-red-600 mt-1">{errors.image}</p>}
                                 
                                 {/* Image Preview */}
                                  <div className="mt-3">
                                      {imagePreview ? (
                                          <div className="flex items-center gap-3 bg-green-50 p-2.5 rounded-xl border border-green-100">
                                              <img
                                                  src={imagePreview}
                                                  alt="معاينة الصورة الجديدة"
                                                  onClick={() => setZoomImage({ src: imagePreview, title: 'معاينة الصورة الجديدة' })}
                                                  className="w-14 h-14 object-cover rounded-lg border border-green-200 cursor-pointer shadow-sm hover:scale-105 transition-transform"
                                                  title="انقر لتكبير الصورة"
                                              />
                                              <div>
                                                  <span className="text-xs text-green-700 font-bold block">صورة جديدة مختارة ✓</span>
                                                  <span className="text-[11px] text-green-600">انقر على المصغّر لتكبير المعاينة</span>
                                              </div>
                                          </div>
                                      ) : (
                                          editingCategory && editingCategory.image_path && (
                                              <div className="flex items-center gap-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                                                  {(() => {
                                                      const currentSrc = editingCategory.image_path.startsWith('/') || editingCategory.image_path.startsWith('http')
                                                          ? editingCategory.image_path
                                                          : `/storage/${editingCategory.image_path}`;
                                                      return (
                                                          <>
                                                              <img
                                                                  src={currentSrc}
                                                                  alt="الصورة الحالية"
                                                                  onClick={() => setZoomImage({ src: currentSrc, title: `الصورة الحالية: ${editingCategory.name_ar}` })}
                                                                  className="w-14 h-14 object-cover rounded-lg border border-gray-300 cursor-pointer shadow-sm hover:scale-105 transition-transform"
                                                                  title="انقر للتكبير بالحجم الكامل"
                                                              />
                                                              <div>
                                                                  <span className="text-xs text-gray-700 font-bold block">الصورة الحالية للتصنيف</span>
                                                                  <button
                                                                      type="button"
                                                                      onClick={() => setZoomImage({ src: currentSrc, title: `الصورة الحالية: ${editingCategory.name_ar}` })}
                                                                      className="text-[11px] text-orange-600 font-bold hover:underline"
                                                                  >
                                                                      🔍 تكبير الصورة بالحجم الكامل
                                                                  </button>
                                                              </div>
                                                          </>
                                                      );
                                                  })()}
                                              </div>
                                          )
                                      )}
                                  </div>
                             </div>

                            <div className="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    onClick={() => setShowFormModal(false)}
                                    className="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm disabled:opacity-50"
                                >
                                    {processing ? 'جاري الحفظ...' : 'حفظ التصنيف ✓'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* ─── Image Zoom Lightbox Modal ─────────────────────────────── */}
            {zoomImage && (
                <div
                    className="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-in fade-in duration-200"
                    onClick={() => setZoomImage(null)}
                >
                    <div
                        className="relative max-w-4xl w-full max-h-[90vh] bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-2xl flex flex-col items-center justify-center p-2"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="w-full p-4 border-b border-white/10 flex items-center justify-between bg-slate-950/60">
                            <h3 className="text-sm font-bold text-white flex items-center gap-2">
                                🔍 {zoomImage.title || 'عرض الصورة بالحجم الكامل'}
                            </h3>
                            <button
                                onClick={() => setZoomImage(null)}
                                className="px-3 py-1 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-bold transition-colors"
                            >
                                إغلاق ✕
                            </button>
                        </div>

                        {/* Image Canvas */}
                        <div className="p-4 flex items-center justify-center overflow-auto max-h-[78vh] w-full">
                            <img
                                src={zoomImage.src}
                                alt={zoomImage.title || 'معاينة'}
                                className="max-w-full max-h-[72vh] object-contain rounded-lg shadow-lg border border-white/5"
                            />
                        </div>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
