import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function BannersIndex({ banners }) {
    const { flash } = usePage().props;
    const [editingBanner, setEditingBanner] = useState(null);
    const [showFormModal, setShowFormModal] = useState(false);
    const [imagePreview, setImagePreview] = useState(null);

    // Form for create/edit
    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        title: '',
        link: '',
        order: '0',
        image: null,
    });

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingBanner(null);
        setImagePreview(null);
        setShowFormModal(true);
    };

    const openEditModal = (banner) => {
        clearErrors();
        setEditingBanner(banner);
        setData({
            title: banner.title || '',
            link: banner.link || '',
            order: String(banner.order || 0),
            image: null,
        });
        setImagePreview(banner.image_url);
        setShowFormModal(true);
    };

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('image', file);
            const reader = new FileReader();
            reader.onloadend = () => {
                setImagePreview(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingBanner) {
            // Multipart form upload with method spoofing for Laravel PUT requests
            post(`/admin/banners/${editingBanner.id}`, {
                forceFormData: true,
                _method: 'PUT',
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        } else {
            post('/admin/banners', {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        }
    };

    const handleDelete = (banner) => {
        if (!confirm(`هل أنت متأكد من حذف هذا البانر؟`)) return;
        router.delete(`/admin/banners/${banner.id}`);
    };

    const handleToggleStatus = (banner) => {
        router.patch(`/admin/banners/${banner.id}/toggle`, {}, {
            preserveScroll: true
        });
    };

    return (
        <MerchantLayout title="إدارة البانرات الترويجية">
            <Head title="البانرات الترويجية" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">البانرات الترويجية</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            إدارة وتعديل البانرات الترويجية المعروضة بواجهة المتجر للعملاء.
                        </p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة بانر جديد
                    </button>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                        <span className="flex items-center justify-center w-5 h-5 bg-red-100 rounded-full text-red-600 text-xs">⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Banners Grid */}
                {banners.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {banners.map((banner) => (
                            <div key={banner.id} className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-all group duration-300">
                                {/* Banner Image & Badges */}
                                <div className="relative aspect-[16/7] bg-gray-100 overflow-hidden">
                                    {banner.image_url ? (
                                        <img
                                            src={banner.image_url}
                                            alt={banner.title || 'بنر ترويجي'}
                                            className="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                                        />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                            بلا صورة
                                        </div>
                                    )}
                                    <div className="absolute top-3 right-3 flex gap-2">
                                        <span className={`inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold backdrop-blur-md shadow-sm border ${
                                            banner.is_active 
                                                ? 'bg-green-500/90 text-white border-green-400/20' 
                                                : 'bg-gray-500/90 text-white border-gray-400/20'
                                        }`}>
                                            {banner.is_active ? 'نشط' : 'غير نشط'}
                                        </span>
                                        <span className="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-900/80 text-white border border-white/10 shadow-sm">
                                            الترتيب: {banner.order}
                                        </span>
                                    </div>
                                </div>

                                {/* Banner Details */}
                                <div className="p-5 flex-1 flex flex-col justify-between">
                                    <div className="space-y-2 mb-4">
                                        <h3 className="font-bold text-gray-900 text-lg line-clamp-1">
                                            {banner.title || <span className="text-gray-400 font-normal italic text-sm">بدون عنوان</span>}
                                        </h3>
                                        {banner.link ? (
                                            <a
                                                href={banner.link}
                                                target="_blank"
                                                rel="noreferrer"
                                                className="text-xs text-orange-600 hover:text-orange-700 font-medium inline-flex items-center gap-1 hover:underline break-all"
                                            >
                                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                {banner.link}
                                            </a>
                                        ) : (
                                            <span className="text-xs text-gray-400">لا يوجد رابط ارتباطي</span>
                                        )}
                                    </div>

                                    {/* Action Buttons */}
                                    <div className="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                        <div className="flex items-center gap-2">
                                            <button
                                                onClick={() => openEditModal(banner)}
                                                className="p-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1.5"
                                                title="تعديل البانر"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                تعديل
                                            </button>
                                            <button
                                                onClick={() => handleDelete(banner)}
                                                className="p-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1.5"
                                                title="حذف البانر"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                حذف
                                            </button>
                                        </div>

                                        {/* Quick Toggle switch */}
                                        <button
                                            onClick={() => handleToggleStatus(banner)}
                                            className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ${
                                                banner.is_active ? 'bg-orange-600' : 'bg-gray-200'
                                            }`}
                                            type="button"
                                            title={banner.is_active ? 'تعطيل البانر' : 'تفعيل البانر'}
                                        >
                                            <span
                                                aria-hidden="true"
                                                className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${
                                                    banner.is_active ? '-translate-x-5' : 'translate-x-0'
                                                }`}
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="bg-white rounded-2xl border border-gray-200 py-16 px-6 text-center shadow-sm">
                        <div className="w-16 h-16 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 className="text-lg font-bold text-gray-900 mb-1">لا توجد بانرات ترويجية</h3>
                        <p className="text-gray-500 text-sm max-w-sm mx-auto mb-5">
                            لم تقم بإضافة أي بانر ترويجي بعد. أضف بانرات ترويجية لعرضها في صدارة متجرك.
                        </p>
                        <button
                            onClick={openCreateModal}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm"
                        >
                            إضافة بنر الآن
                        </button>
                    </div>
                )}
            </div>

            {/* Modal Form */}
            {showFormModal && (
                <div className="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm">
                    <div className="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden border border-gray-100 animate-in fade-in zoom-in duration-200">
                        {/* Modal Header */}
                        <div className="h-16 bg-gray-50 border-b border-gray-100 px-6 flex items-center justify-between">
                            <h3 className="font-extrabold text-gray-950 text-base">
                                {editingBanner ? 'تعديل البانر الترويجي' : 'إضافة بانر جديد'}
                            </h3>
                            <button
                                onClick={() => setShowFormModal(false)}
                                className="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all focus:outline-none"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {/* Modal Body / Form */}
                        <form onSubmit={handleSubmit} className="p-6 space-y-5">
                            {/* Title */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">عنوان البانر (اختياري)</label>
                                <input
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="مثال: خصومات الصيف حتى 50%"
                                    className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all ${
                                        errors.title ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                    }`}
                                />
                                {errors.title && <p className="text-xs text-red-600 mt-1">{errors.title}</p>}
                            </div>

                            {/* Link */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">رابط البانر (اختياري)</label>
                                <input
                                    type="url"
                                    value={data.link}
                                    onChange={(e) => setData('link', e.target.value)}
                                    placeholder="https://example.com/category-or-product"
                                    className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all ${
                                        errors.link ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                    }`}
                                />
                                <p className="text-xs text-gray-400 mt-1">الرابط الموجه للعميل عند النقر على البانر (يجب أن يبدأ بـ http:// أو https://)</p>
                                {errors.link && <p className="text-xs text-red-600 mt-1">{errors.link}</p>}
                            </div>

                            {/* Order */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">الترتيب</label>
                                <input
                                    type="number"
                                    min="0"
                                    value={data.order}
                                    onChange={(e) => setData('order', e.target.value)}
                                    className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all ${
                                        errors.order ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                    }`}
                                />
                                <p className="text-xs text-gray-400 mt-1">يتحكم في ترتيب ظهور البانرات الترويجية تصاعدياً.</p>
                                {errors.order && <p className="text-xs text-red-600 mt-1">{errors.order}</p>}
                            </div>

                            {/* Image Upload & Preview */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">
                                    صورة البانر <span className="text-red-500">*</span>
                                </label>
                                
                                <div className="space-y-3">
                                    {imagePreview && (
                                        <div className="relative aspect-[16/7] rounded-xl overflow-hidden bg-gray-50 border border-gray-200">
                                            <img src={imagePreview} alt="معاينة الصورة" className="w-full h-full object-cover" />
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    setImagePreview(null);
                                                    setData('image', null);
                                                }}
                                                className="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1.5 hover:bg-red-700 transition-colors shadow-sm focus:outline-none"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    )}
                                    
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleImageChange}
                                        className={`w-full text-sm text-gray-500 file:ml-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors cursor-pointer ${
                                            !imagePreview ? 'border-2 border-dashed border-gray-300 rounded-xl p-4 text-center' : ''
                                        }`}
                                    />
                                </div>
                                {errors.image && <p className="text-xs text-red-600 mt-1">{errors.image}</p>}
                            </div>

                            {/* Modal Footer */}
                            <div className="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    onClick={() => setShowFormModal(false)}
                                    className="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors focus:outline-none"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-all shadow-md disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                                >
                                    {processing ? 'جاري الحفظ...' : 'حفظ البانر ✓'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
