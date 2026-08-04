import React, { useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function MediaIndex({ mediaItems }) {
    const { flash } = usePage().props;
    const [selectedItem, setSelectedItem] = useState(null);
    const [activeFilter, setActiveFilter] = useState('all');
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [itemToDelete, setItemToDelete] = useState(null);
    const [showCopiedAlert, setShowCopiedAlert] = useState(false);

    // فلترة العناصر
    const filteredItems = mediaItems.filter(item => {
        if (activeFilter === 'all') return true;
        if (activeFilter === 'products') return item.model_type === 'Product' || item.model_type === 'ProductImage';
        if (activeFilter === 'banners') return item.model_type === 'Banner';
        if (activeFilter === 'settings') return item.model_type === 'Setting';
        if (activeFilter === 'categories') return item.model_type === 'Category';
        return true;
    });

    // إحصائيات سريعة للمكتبة
    const totalCount = mediaItems.length;
    const totalSize = mediaItems.reduce((acc, item) => acc + (item.size_bytes || 0), 0);
    const formattedTotalSize = (totalSize / (1024 * 1024)).toFixed(2) + ' MB';

    // نسخ الرابط للحافظة
    const copyToClipboard = (url) => {
        navigator.clipboard.writeText(url).then(() => {
            setShowCopiedAlert(true);
            setTimeout(() => {
                setShowCopiedAlert(false);
            }, 3000);
        }).catch(err => {
            console.error('فشل نسخ الرابط: ', err);
        });
    };

    // تأكيد الحذف
    const confirmDelete = (item) => {
        setItemToDelete(item);
        setShowDeleteModal(true);
    };

    // تنفيذ الحذف الفعلي
    const handleDelete = () => {
        if (!itemToDelete) return;
        
        router.delete('/admin/media', {
            data: {
                path: itemToDelete.path,
                model_type: itemToDelete.model_type,
                model_id: itemToDelete.model_id,
                field: itemToDelete.field
            },
            onSuccess: () => {
                setShowDeleteModal(false);
                setItemToDelete(null);
                setSelectedItem(null);
            },
            onError: () => {
                setShowDeleteModal(false);
                setItemToDelete(null);
            }
        });
    };

    return (
        <MerchantLayout title="مكتبة الوسائط">
            <Head title="مكتبة الوسائط والملفات" />

            <div className="space-y-6" dir="rtl">
                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">مكتبة الوسائط والملفات</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            عرض وإدارة جميع الملفات والصور المرفوعة للمنتجات، البانرات، التصنيفات، والشعارات.
                        </p>
                    </div>

                    {/* Stats Widget */}
                    <div className="flex items-center gap-4 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm">
                        <div className="text-right border-l pl-4 border-gray-100">
                            <span className="text-xs text-gray-400 block font-medium">إجمالي الملفات</span>
                            <span className="text-lg font-bold text-indigo-600">{totalCount} ملف</span>
                        </div>
                        <div className="text-right">
                            <span className="text-xs text-gray-400 block font-medium">الحجم الإجمالي التقريبي</span>
                            <span className="text-lg font-bold text-amber-600">{formattedTotalSize}</span>
                        </div>
                    </div>
                </div>

                {/* Flash Success Message */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}

                {/* Copied Alert */}
                {showCopiedAlert && (
                    <div className="fixed bottom-6 right-6 z-50 p-4 bg-indigo-600 text-white rounded-xl shadow-2xl flex items-center gap-3 animate-bounce">
                        <svg className="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span className="text-sm font-bold">تم نسخ رابط الصورة بنجاح إلى الحافظة!</span>
                    </div>
                )}

                {/* Main Content Layout */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    {/* Media grid & Filter - Left Side */}
                    <div className="lg:col-span-8 space-y-4">
                        
                        {/* Filters Tabs */}
                        <div className="flex flex-wrap gap-2 p-1.5 bg-gray-100/80 rounded-2xl w-fit">
                            <button
                                onClick={() => setActiveFilter('all')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === 'all'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                }`}
                            >
                                الكل ({totalCount})
                            </button>
                            <button
                                onClick={() => setActiveFilter('products')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === 'products'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                }`}
                            >
                                المنتجات ({mediaItems.filter(i => i.model_type === 'Product' || i.model_type === 'ProductImage').length})
                            </button>
                            <button
                                onClick={() => setActiveFilter('banners')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === 'banners'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                }`}
                            >
                                البانرات ({mediaItems.filter(i => i.model_type === 'Banner').length})
                            </button>
                            <button
                                onClick={() => setActiveFilter('categories')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === 'categories'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                }`}
                            >
                                التصنيفات ({mediaItems.filter(i => i.model_type === 'Category').length})
                            </button>
                            <button
                                onClick={() => setActiveFilter('settings')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === 'settings'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-600 hover:text-gray-900'
                                }`}
                            >
                                الشعارات والإعدادات ({mediaItems.filter(i => i.model_type === 'Setting').length})
                            </button>
                        </div>

                        {/* Images Grid */}
                        {filteredItems.length === 0 ? (
                            <div className="bg-white rounded-3xl border border-dashed border-gray-200 py-16 flex flex-col items-center justify-center text-center shadow-sm">
                                <div className="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4 text-gray-300">
                                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-gray-600 font-bold text-base">لا توجد وسائط متطابقة</h3>
                                <p className="text-gray-400 text-xs mt-1 max-w-[280px]">لم يتم رفع صور تندرج تحت هذا التصنيف أو التصنيفات المحددة بعد.</p>
                            </div>
                        ) : (
                            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                {filteredItems.map((item, index) => {
                                    const isSelected = selectedItem?.path === item.path && selectedItem?.model_id === item.model_id;
                                    return (
                                        <div
                                            key={index}
                                            onClick={() => setSelectedItem(item)}
                                            className={`relative group bg-white rounded-2xl overflow-hidden border transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md ${
                                                isSelected 
                                                    ? 'border-indigo-600 ring-2 ring-indigo-600/20 shadow-indigo-100' 
                                                    : 'border-gray-100 hover:border-gray-200'
                                            }`}
                                        >
                                            {/* Image Thumbnail wrapper */}
                                            <div className="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden relative">
                                                <img
                                                    src={item.url}
                                                    alt={item.filename}
                                                    className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                    loading="lazy"
                                                />
                                                {/* Badge */}
                                                <span className="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-[10px] text-white px-2 py-0.5 rounded-md font-bold">
                                                    {item.size}
                                                </span>
                                            </div>

                                            {/* Info */}
                                            <div className="p-3 text-right">
                                                <p className="text-xs font-semibold text-gray-800 truncate" title={item.filename}>
                                                    {item.filename}
                                                </p>
                                                <p className="text-[10px] text-gray-400 mt-1 truncate">
                                                    {item.source}
                                                </p>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>

                    {/* Details Panel - Right Side */}
                    <div className="lg:col-span-4">
                        {selectedItem ? (
                            <div className="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm space-y-5 sticky top-6">
                                <h3 className="font-extrabold text-gray-900 border-b pb-3 text-right text-base">تفاصيل الصورة</h3>
                                
                                {/* Full Preview */}
                                <div className="aspect-video bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden flex items-center justify-center relative">
                                    <img
                                        src={selectedItem.url}
                                        alt={selectedItem.filename}
                                        className="w-full h-full object-contain"
                                    />
                                    <a
                                        href={selectedItem.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="absolute bottom-2 left-2 bg-white/90 backdrop-blur-sm p-1.5 rounded-lg text-gray-600 hover:text-indigo-600 hover:bg-white transition-all shadow-sm"
                                        title="فتح في علامة تبويب جديدة"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>

                                {/* Metas List */}
                                <div className="space-y-3 text-right text-xs">
                                    <div className="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span className="text-gray-400">اسم الملف:</span>
                                        <span className="font-semibold text-gray-800 truncate max-w-[200px]" title={selectedItem.filename}>
                                            {selectedItem.filename}
                                        </span>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span className="text-gray-400">المصدر والجهة:</span>
                                        <span className="font-semibold text-indigo-600">{selectedItem.source}</span>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span className="text-gray-400">حجم الملف:</span>
                                        <span className="font-semibold text-gray-800">{selectedItem.size}</span>
                                    </div>
                                    <div className="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span className="text-gray-400">صيغة الملف:</span>
                                        <span className="font-semibold text-gray-800 uppercase">{selectedItem.type}</span>
                                    </div>
                                    <div className="flex flex-col gap-1 py-2">
                                        <span className="text-gray-400">المسار النسبي:</span>
                                        <span className="font-mono text-[10px] text-gray-500 bg-gray-50 p-2 rounded-lg break-all" dir="ltr">
                                            {selectedItem.path}
                                        </span>
                                    </div>
                                </div>

                                {/* Actions buttons */}
                                <div className="grid grid-cols-2 gap-3 pt-2">
                                    <button
                                        onClick={() => copyToClipboard(selectedItem.url)}
                                        className="flex items-center justify-center gap-2 py-2.5 px-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition-all"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                        نسخ الرابط
                                    </button>
                                    <button
                                        onClick={() => confirmDelete(selectedItem)}
                                        className="flex items-center justify-center gap-2 py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-bold transition-all"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        حذف الصورة
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div className="bg-white rounded-3xl border border-gray-100 p-8 text-center shadow-sm text-gray-400 flex flex-col items-center justify-center min-h-[300px] sticky top-6">
                                <svg className="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p className="text-xs font-semibold">اضغط على أي صورة لعرض تفاصيلها وخيارات التحكم بها.</p>
                            </div>
                        )}
                    </div>

                </div>
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 text-right border border-gray-100" dir="rtl">
                        <div className="flex items-center gap-3 text-red-600 border-b pb-3">
                            <div className="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 text-red-600">
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 className="font-extrabold text-gray-900 text-base">تأكيد حذف الصورة نهائياً؟</h3>
                        </div>

                        <div className="space-y-2 text-sm text-gray-600">
                            <p>هل أنت متأكد من رغبتك في حذف هذا الملف من الخادم؟</p>
                            <p className="bg-amber-50 text-amber-800 p-3 rounded-xl text-xs font-medium leading-relaxed">
                                ⚠️ <strong>تنبيه هام:</strong> سيتم تصفير حقل الصورة في السجل المرتبط بها (مثل المنتج أو التصنيف) وإزالتها نهائياً من القرص، ولا يمكن التراجع عن هذا الإجراء.
                            </p>
                        </div>

                        {itemToDelete && (
                            <div className="bg-gray-50 p-3 rounded-2xl flex items-center gap-3">
                                <img
                                    src={itemToDelete.url}
                                    alt="Preview"
                                    className="w-12 h-12 rounded-lg object-cover bg-white border border-gray-200 flex-shrink-0"
                                />
                                <div className="overflow-hidden">
                                    <p className="text-xs font-bold text-gray-800 truncate" title={itemToDelete.filename}>
                                        {itemToDelete.filename}
                                    </p>
                                    <p className="text-[10px] text-gray-400 truncate mt-0.5">
                                        المصدر: {itemToDelete.source}
                                    </p>
                                </div>
                            </div>
                        )}

                        <div className="flex gap-3 justify-end pt-3">
                            <button
                                onClick={handleDelete}
                                className="py-2.5 px-5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition-all shadow-md hover:shadow-lg"
                            >
                                نعم، احذف نهائياً
                            </button>
                            <button
                                onClick={() => {
                                    setShowDeleteModal(false);
                                    setItemToDelete(null);
                                }}
                                className="py-2.5 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all"
                            >
                                إلغاء
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
