import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function CouponsIndex({ coupons, stats, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.q || '');
    const [editingCoupon, setEditingCoupon] = useState(null);
    const [showFormModal, setShowFormModal] = useState(false);

    // Form hook
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        code: '',
        type: 'percentage',
        value: '',
        min_order_value: '',
        max_uses: '',
        starts_at: '',
        expires_at: '',
        is_active: true,
    });

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/coupons', { q: search }, { preserveState: true, replace: true });
    };

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingCoupon(null);
        setShowFormModal(true);
    };

    const openEditModal = (coupon) => {
        clearErrors();
        setEditingCoupon(coupon);
        setData({
            code: coupon.code || '',
            type: coupon.type || 'percentage',
            value: coupon.value || '',
            min_order_value: coupon.min_order_value || '',
            max_uses: coupon.max_uses || '',
            starts_at: coupon.starts_at_formatted || '',
            expires_at: coupon.expires_at_formatted || '',
            is_active: coupon.is_active !== undefined ? coupon.is_active : true,
        });
        setShowFormModal(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingCoupon) {
            put(`/admin/coupons/${editingCoupon.id}`, {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        } else {
            post('/admin/coupons', {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        }
    };

    const handleDelete = (coupon) => {
        if (!confirm(`هل أنت متأكد من حذف الكوبون "${coupon.code}"؟`)) return;
        router.delete(`/admin/coupons/${coupon.id}`);
    };

    const handleToggle = (coupon) => {
        router.patch(`/admin/coupons/${coupon.id}/toggle`, {}, { preserveScroll: true });
    };

    return (
        <MerchantLayout title="إدارة الكوبونات والخصومات">
            <Head title="الكوبونات والخصومات" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">الكوبونات والخصومات</h2>
                        <p className="text-sm text-gray-500 mt-0.5">
                            أنشئ وأدر كوبونات الخصم لعملائك لزيادة المبيعات والولاء
                        </p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all duration-200 shadow-lg shadow-indigo-150 hover:-translate-y-0.5"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إنشاء كوبون جديد
                    </button>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {/* Stat Card 1 */}
                    <div className="bg-gradient-to-br from-white to-gray-50/50 p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all duration-300">
                        <div className="space-y-2">
                            <span className="text-sm text-gray-500 font-medium">إجمالي الكوبونات</span>
                            <h3 className="text-3xl font-bold text-gray-900 group-hover:scale-105 transition-transform duration-300 origin-right">
                                {stats.total}
                            </h3>
                        </div>
                        <div className="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                    </div>

                    {/* Stat Card 2 */}
                    <div className="bg-gradient-to-br from-white to-gray-50/50 p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all duration-300">
                        <div className="space-y-2">
                            <span className="text-sm text-gray-500 font-medium">الكوبونات النشطة</span>
                            <h3 className="text-3xl font-bold text-green-600 group-hover:scale-105 transition-transform duration-300 origin-right">
                                {stats.active}
                            </h3>
                        </div>
                        <div className="p-3 bg-green-50 text-green-600 rounded-xl">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {/* Stat Card 3 */}
                    <div className="bg-gradient-to-br from-white to-gray-50/50 p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all duration-300">
                        <div className="space-y-2">
                            <span className="text-sm text-gray-500 font-medium">إجمالي مرات الاستخدام</span>
                            <h3 className="text-3xl font-bold text-amber-600 group-hover:scale-105 transition-transform duration-300 origin-right">
                                {stats.total_uses}
                            </h3>
                        </div>
                        <div className="p-3 bg-amber-50 text-amber-600 rounded-xl">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                {/* Search Bar */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <form onSubmit={handleSearch} className="flex flex-col sm:flex-row gap-3">
                        <div className="flex-1 relative">
                            <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                placeholder="ابحث برمز الكوبون..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pr-10 pl-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            />
                        </div>
                        <div className="flex gap-2">
                            <button
                                type="submit"
                                className="px-6 py-2.5 bg-gray-800 text-white rounded-xl text-sm font-medium hover:bg-gray-700 transition-colors shadow-sm"
                            >
                                بحث
                            </button>
                            {filters?.q && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setSearch('');
                                        router.get('/admin/coupons', {}, { replace: true });
                                    }}
                                    className="px-5 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors"
                                >
                                    إعادة تعيين
                                </button>
                            )}
                        </div>
                    </form>
                </div>

                {/* Flash Notifications */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-2.5 shadow-sm animate-in slide-in-from-top duration-300">
                        <span className="text-lg">✓</span>
                        <span>{flash.success}</span>
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-semibold flex items-center gap-2.5 shadow-sm animate-in slide-in-from-top duration-300">
                        <span className="text-lg">⚠️</span>
                        <span>{flash.error}</span>
                    </div>
                )}

                {/* Coupons Table */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th className="px-6 py-4">الرمز</th>
                                    <th className="px-6 py-4">النوع</th>
                                    <th className="px-6 py-4">القيمة</th>
                                    <th className="px-6 py-4">الحد الأدنى للطلب</th>
                                    <th className="px-6 py-4">الاستخدام</th>
                                    <th className="px-6 py-4">تاريخ الصلاحية</th>
                                    <th className="px-6 py-4 text-center">الحالة</th>
                                    <th className="px-6 py-4 text-left">العمليات</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm text-gray-750">
                                {coupons.data.length > 0 ? (
                                    coupons.data.map((coupon) => (
                                        <tr key={coupon.id} className="hover:bg-gray-50/40 transition-colors">
                                            {/* Code */}
                                            <td className="px-6 py-4">
                                                <span className="font-mono font-bold text-gray-900 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200/60 select-all">
                                                    {coupon.code}
                                                </span>
                                            </td>

                                            {/* Type */}
                                            <td className="px-6 py-4">
                                                {coupon.type === 'percentage' ? (
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                                        نسبة مئوية %
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                        مبلغ ثابت
                                                    </span>
                                                )}
                                            </td>

                                            {/* Value */}
                                            <td className="px-6 py-4 font-semibold text-gray-900">
                                                {coupon.type === 'percentage' ? `${coupon.value}%` : `${Math.round(coupon.value)} ج.م`}
                                            </td>

                                            {/* Min Order Value */}
                                            <td className="px-6 py-4 text-gray-600">
                                                {coupon.min_order_value ? `${Math.round(coupon.min_order_value)} ج.م` : '-'}
                                            </td>

                                            {/* Uses */}
                                            <td className="px-6 py-4">
                                                <div className="flex flex-col gap-1 max-w-[120px]">
                                                    <span className="text-xs text-gray-600 font-medium">
                                                        {coupon.uses_count} {coupon.max_uses ? `/ ${coupon.max_uses}` : 'استخدام'}
                                                    </span>
                                                    {coupon.max_uses && (
                                                        <div className="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                                            <div
                                                                className={`h-full rounded-full transition-all duration-500 ${
                                                                    (coupon.uses_count / coupon.max_uses) >= 1 ? 'bg-red-500' : 'bg-indigo-500'
                                                                }`}
                                                                style={{ width: `${Math.min(100, (coupon.uses_count / coupon.max_uses) * 100)}%` }}
                                                            />
                                                        </div>
                                                    )}
                                                </div>
                                            </td>

                                            {/* Validity Dates */}
                                            <td className="px-6 py-4 text-xs text-gray-500 space-y-0.5">
                                                {coupon.starts_at_formatted || coupon.expires_at_formatted ? (
                                                    <>
                                                        {coupon.starts_at_formatted && <div>من: {coupon.starts_at_formatted}</div>}
                                                        {coupon.expires_at_formatted && <div>إلى: {coupon.expires_at_formatted}</div>}
                                                    </>
                                                ) : (
                                                    <span className="text-gray-400">غير محدد</span>
                                                )}
                                            </td>

                                            {/* Toggle Status */}
                                            <td className="px-6 py-4 text-center">
                                                <button
                                                    onClick={() => handleToggle(coupon)}
                                                    className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-all ${
                                                        coupon.is_active
                                                            ? 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100'
                                                            : 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100'
                                                    }`}
                                                >
                                                    <span className={`w-1.5 h-1.5 rounded-full ${coupon.is_active ? 'bg-green-500' : 'bg-red-500'}`} />
                                                    {coupon.is_active ? 'نشط' : 'معطل'}
                                                </button>
                                            </td>

                                            {/* Operations */}
                                            <td className="px-6 py-4 text-left">
                                                <div className="flex items-center justify-end gap-2">
                                                    <button
                                                        onClick={() => openEditModal(coupon)}
                                                        className="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold hover:bg-indigo-100 transition-colors"
                                                    >
                                                        تعديل
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(coupon)}
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
                                        <td colSpan="8" className="px-6 py-12 text-center text-gray-400">
                                            لا توجد كوبونات خصم مضافة حالياً.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {coupons.links && coupons.links.length > 3 && (
                        <div className="bg-white border-t border-gray-100 px-6 py-4 flex justify-center gap-1.5">
                            {coupons.links.map((link, idx) => (
                                <button
                                    key={idx}
                                    disabled={!link.url || link.active}
                                    onClick={() => router.get(link.url)}
                                    className={`px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-colors ${
                                        link.active
                                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm'
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
                <div className="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
                    <div className="bg-white rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden border border-gray-100 animate-in fade-in zoom-in-95 duration-200 text-right" dir="rtl">
                        <div className="h-16 bg-gray-50 border-b border-gray-100 px-6 flex items-center justify-between">
                            <h3 className="font-bold text-lg text-gray-900">
                                {editingCoupon ? 'تعديل كوبون الخصم' : 'إضافة كوبون خصم جديد'}
                            </h3>
                            <button
                                onClick={() => setShowFormModal(false)}
                                className="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                ✕
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            {/* Code & Type */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">رمز الكوبون <span className="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        value={data.code}
                                        onChange={(e) => setData('code', e.target.value.toUpperCase())}
                                        placeholder="مثال: SAVE20"
                                        className={`w-full px-3.5 py-2 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                                            errors.code ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                        }`}
                                    />
                                    {errors.code && <p className="text-xs text-red-600 mt-1">{errors.code}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">نوع الخصم <span className="text-red-500">*</span></label>
                                    <select
                                        value={data.type}
                                        onChange={(e) => setData('type', e.target.value)}
                                        className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
                                    >
                                        <option value="percentage">نسبة مئوية (%)</option>
                                        <option value="fixed">مبلغ ثابت (ج.م)</option>
                                    </select>
                                    {errors.type && <p className="text-xs text-red-600 mt-1">{errors.type}</p>}
                                </div>
                            </div>

                            {/* Value & Min Order Value */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        قيمة الخصم <span className="text-red-500">*</span>
                                    </label>
                                    <div className="relative">
                                        <input
                                            type="number"
                                            step="0.01"
                                            value={data.value}
                                            onChange={(e) => setData('value', e.target.value)}
                                            placeholder={data.type === 'percentage' ? 'مثال: 15' : 'مثال: 50'}
                                            className={`w-full pr-3.5 pl-10 py-2 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                                                errors.value ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                            }`}
                                        />
                                        <span className="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">
                                            {data.type === 'percentage' ? '%' : 'ج.م'}
                                        </span>
                                    </div>
                                    {errors.value && <p className="text-xs text-red-600 mt-1">{errors.value}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">الحد الأدنى للطلب (اختياري)</label>
                                    <div className="relative">
                                        <input
                                            type="number"
                                            step="0.01"
                                            value={data.min_order_value}
                                            onChange={(e) => setData('min_order_value', e.target.value)}
                                            placeholder="لا يوجد حد أدنى"
                                            className="w-full pr-3.5 pl-10 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        />
                                        <span className="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">
                                            ج.م
                                        </span>
                                    </div>
                                    {errors.min_order_value && <p className="text-xs text-red-600 mt-1">{errors.min_order_value}</p>}
                                </div>
                            </div>

                            {/* Max Uses */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1">الحد الأقصى لمرات الاستخدام للكوبون ككل (اختياري)</label>
                                <input
                                    type="number"
                                    value={data.max_uses}
                                    onChange={(e) => setData('max_uses', e.target.value)}
                                    placeholder="عدد مرات استخدام غير محدود"
                                    className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                />
                                {errors.max_uses && <p className="text-xs text-red-600 mt-1">{errors.max_uses}</p>}
                            </div>

                            {/* Starts at & Expires at */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">تاريخ بدء الصلاحية (اختياري)</label>
                                    <input
                                        type="date"
                                        value={data.starts_at}
                                        onChange={(e) => setData('starts_at', e.target.value)}
                                        className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-right"
                                    />
                                    {errors.starts_at && <p className="text-xs text-red-600 mt-1">{errors.starts_at}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">تاريخ انتهاء الصلاحية (اختياري)</label>
                                    <input
                                        type="date"
                                        value={data.expires_at}
                                        onChange={(e) => setData('expires_at', e.target.value)}
                                        className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-right"
                                    />
                                    {errors.expires_at && <p className="text-xs text-red-600 mt-1">{errors.expires_at}</p>}
                                </div>
                            </div>

                            {/* Status switch */}
                            <div className="flex items-center gap-3 pt-2">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="w-4 h-4 text-indigo-600 border-gray-350 rounded-sm focus:ring-indigo-500"
                                />
                                <label htmlFor="is_active" className="text-sm font-semibold text-gray-700 select-none">
                                    تفعيل الكوبون مباشرة بعد الحفظ
                                </label>
                            </div>

                            {/* Buttons */}
                            <div className="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={() => setShowFormModal(false)}
                                    className="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors disabled:opacity-50"
                                >
                                    {processing ? 'جاري الحفظ...' : 'حفظ الكوبون'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
