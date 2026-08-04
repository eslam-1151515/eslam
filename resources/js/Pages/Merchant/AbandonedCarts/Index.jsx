import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function AbandonedCartsIndex({ records, statistics, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.search || '');
    const [statusFilter, setStatusFilter] = useState(filters?.status || '');
    const [selectedCart, setSelectedCart] = useState(null);
    const [isReminderModalOpen, setIsReminderModalOpen] = useState(false);

    // Form for sending manual recovery email
    const { data, setData, post, processing, errors, reset } = useForm({
        discount_code: '',
        discount_percentage: '',
        locale: 'ar',
    });

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/abandoned-carts', { search, status: statusFilter }, { preserveState: true });
    };

    const handleReset = () => {
        setSearch('');
        setStatusFilter('');
        router.get('/admin/abandoned-carts', {}, { replace: true });
    };

    const handleDelete = (id) => {
        if (confirm('هل أنت متأكد من حذف هذا السجل؟ لن تتمكن من تتبعه أو استعادته بعد الحذف.')) {
            router.delete(`/admin/abandoned-carts/${id}`);
        }
    };

    const openReminderModal = (cart) => {
        setSelectedCart(cart);
        setIsReminderModalOpen(true);
        reset();
    };

    const handleSendReminder = (e) => {
        e.preventDefault();
        post(`/admin/abandoned-carts/${selectedCart.id}/send-reminder`, {
            onSuccess: () => {
                setIsReminderModalOpen(false);
                setSelectedCart(null);
            },
        });
    };

    const copyToClipboard = (token) => {
        const url = `${window.location.origin}/shop/cart/recover/${token}`;
        navigator.clipboard.writeText(url);
        alert('تم نسخ رابط الاستعادة إلى الحافظة!');
    };

    // Format currency
    const fmt = (val) => {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP', maximumFractionDigits: 0 }).format(Math.round(val));
    };

    return (
        <MerchantLayout title="تتبع السلات المتروكة">
            <Head title="نظام Abandoned Cart Recovery" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">سلات التسوق المتروكة (Abandoned Carts)</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            تتبع السلات المتروكة التي لم يكمل أصحابها عملية الدفع، وأرسل تنبيهات مخصصة لاستعادتها وزيادة مبيعاتك.
                        </p>
                    </div>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-red-100 rounded-full text-red-600 text-xs">⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Statistics Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {/* Card 1: Total Carts */}
                    <div className="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                        <div className="space-y-1">
                            <span className="text-xs text-gray-400 font-bold block">إجمالي السلات المتروكة</span>
                            <span className="text-2xl font-extrabold text-gray-900 block">{statistics.total_carts}</span>
                            <span className="text-xs text-gray-500 block">
                                قيد الانتظار: <span className="font-bold text-amber-600">{statistics.pending_carts}</span>
                            </span>
                        </div>
                        <div className="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-xl">🛒</div>
                    </div>

                    {/* Card 2: Recovered Carts */}
                    <div className="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                        <div className="space-y-1">
                            <span className="text-xs text-gray-400 font-bold block">السلات المستردة</span>
                            <span className="text-2xl font-extrabold text-green-600 block">{statistics.recovered_carts}</span>
                            <span className="text-xs text-gray-500 block">
                                نسبة الاسترداد: <span className="font-bold text-green-600">{statistics.recovery_rate}%</span>
                            </span>
                        </div>
                        <div className="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-xl">🎉</div>
                    </div>

                    {/* Card 3: Lost Value */}
                    <div className="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                        <div className="space-y-1">
                            <span className="text-xs text-gray-400 font-bold block">مبيعات مفقودة تقريبية</span>
                            <span className="text-2xl font-extrabold text-red-500 block">{fmt(statistics.lost_value)}</span>
                            <span className="text-xs text-gray-500 block">سلات غير مكتملة</span>
                        </div>
                        <div className="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-xl">💸</div>
                    </div>

                    {/* Card 4: Recovered Value */}
                    <div className="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                        <div className="space-y-1">
                            <span className="text-xs text-gray-400 font-bold block">مبيعات مستردة</span>
                            <span className="text-2xl font-extrabold text-emerald-600 block">{fmt(statistics.recovered_value)}</span>
                            <span className="text-xs text-gray-500 block">عبر حملات التنبيه</span>
                        </div>
                        <div className="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-xl">💰</div>
                    </div>
                </div>

                {/* Filters */}
                <div className="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div className="flex flex-1 flex-col sm:flex-row gap-3 w-full">
                            <div className="flex-1 min-w-[200px]">
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="بحث بالبريد الإلكتروني، الهاتف، أو محتويات السلة..."
                                    className="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                />
                            </div>
                            <div className="w-full sm:w-48">
                                <select
                                    value={statusFilter}
                                    onChange={(e) => setStatusFilter(e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all cursor-pointer"
                                >
                                    <option value="">جميع الحالات</option>
                                    <option value="pending">قيد الانتظار (Pending)</option>
                                    <option value="recovered">مستردة بنجاح (Recovered)</option>
                                </select>
                            </div>
                        </div>
                        <div className="flex gap-2 w-full md:w-auto justify-end">
                            <button
                                type="submit"
                                className="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-2"
                            >
                                🔍 تصفية
                            </button>
                            <button
                                type="button"
                                onClick={handleReset}
                                className="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all"
                            >
                                إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>

                {/* Table list */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs font-bold uppercase">
                                    <th className="px-6 py-4">العميل / طريقة التتبع</th>
                                    <th className="px-6 py-4">محتويات السلة</th>
                                    <th className="px-6 py-4">الإجمالي</th>
                                    <th className="px-6 py-4">آخر نشاط</th>
                                    <th className="px-6 py-4">حالة التنبيه</th>
                                    <th className="px-6 py-4">الاستعادة</th>
                                    <th className="px-6 py-4 text-left">العمليات</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm">
                                {records.data.length === 0 ? (
                                    <tr>
                                        <td colSpan="7" className="text-center py-10 text-gray-400">
                                            لا توجد سلات متروكة مطابقة للبحث أو الفلاتر حالياً.
                                        </td>
                                    </tr>
                                ) : (
                                    records.data.map((record) => (
                                        <tr key={record.id} className="hover:bg-gray-50/50 transition-colors">
                                            {/* Customer Info */}
                                            <td className="px-6 py-4">
                                                <div className="space-y-1">
                                                    {record.email ? (
                                                        <span className="font-semibold text-gray-900 block">{record.email}</span>
                                                    ) : (
                                                        <span className="text-gray-400 italic block">بدون بريد إلكتروني</span>
                                                    )}
                                                    {record.phone && (
                                                        <span className="text-xs text-gray-500 font-mono block">📞 {record.phone}</span>
                                                    )}
                                                    {record.user_id ? (
                                                        <span className="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600">
                                                            👤 مستخدم مسجل
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-50 text-gray-600">
                                                            🌐 زائر (Guest)
                                                        </span>
                                                    )}
                                                </div>
                                            </td>

                                            {/* Cart Items list */}
                                            <td className="px-6 py-4 max-w-xs">
                                                <div className="space-y-1">
                                                    {(record.cart_data?.items || []).slice(0, 2).map((item, idx) => (
                                                        <div key={idx} className="text-xs text-gray-600 truncate">
                                                            🛍️ {item.name} <span className="font-bold">({item.quantity}×)</span>
                                                        </div>
                                                    ))}
                                                    {(record.cart_data?.items || []).length > 2 && (
                                                        <span className="text-[10px] text-orange-600 font-bold block">
                                                            + {(record.cart_data.items.length - 2)} منتجات أخرى
                                                        </span>
                                                    )}
                                                </div>
                                            </td>

                                            {/* Total */}
                                            <td className="px-6 py-4 font-bold text-gray-900">
                                                {fmt(record.cart_data?.total || 0)}
                                            </td>

                                            {/* Last activity */}
                                            <td className="px-6 py-4 text-xs text-gray-500">
                                                {new Date(record.updated_at).toLocaleString('en-US')}
                                            </td>

                                            {/* Notification status */}
                                            <td className="px-6 py-4">
                                                {record.recovery_email_sent_at ? (
                                                    <div className="space-y-1">
                                                        <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                                            ✉️ تم إرسال تنبيه
                                                        </span>
                                                        <span className="text-[10px] text-gray-400 block font-mono">
                                                            {new Date(record.recovery_email_sent_at).toLocaleDateString('en-US')}
                                                        </span>
                                                    </div>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                        ⏳ لم يتم التنبيه
                                                    </span>
                                                )}
                                            </td>

                                            {/* Recovery Status */}
                                            <td className="px-6 py-4">
                                                {record.recovered_at ? (
                                                    <div className="space-y-1">
                                                        <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                                            ✓ تم الاسترداد
                                                        </span>
                                                        <span className="text-[10px] text-green-600 block font-mono">
                                                            {new Date(record.recovered_at).toLocaleDateString('en-US')}
                                                        </span>
                                                    </div>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                                        ⚠️ قيد الانتظار
                                                    </span>
                                                )}
                                            </td>

                                            {/* Actions */}
                                            <td className="px-6 py-4 text-left">
                                                <div className="flex items-center justify-end gap-2">
                                                    {!record.recovered_at && record.email && (
                                                        <button
                                                            onClick={() => openReminderModal(record)}
                                                            className="p-2 hover:bg-orange-50 text-orange-600 rounded-lg transition-all"
                                                            title="إرسال تنبيه بالبريد"
                                                        >
                                                            ✉️ إرسال تذكير
                                                        </button>
                                                    )}
                                                    {!record.recovered_at && (
                                                        <button
                                                            onClick={() => copyToClipboard(record.recovery_token)}
                                                            className="p-2 hover:bg-blue-50 text-blue-600 rounded-lg transition-all"
                                                            title="نسخ رابط الاستعادة لمشاركته عبر واتساب"
                                                        >
                                                            🔗 نسخ الرابط
                                                        </button>
                                                    )}
                                                    <button
                                                        onClick={() => handleDelete(record.id)}
                                                        className="p-2 hover:bg-red-50 text-red-500 rounded-lg transition-all"
                                                        title="حذف"
                                                    >
                                                        🗑️
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {records.links && records.links.length > 3 && (
                        <div className="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-center gap-1">
                            {records.links.map((link, idx) => (
                                <button
                                    key={idx}
                                    disabled={!link.url}
                                    onClick={() => router.get(link.url)}
                                    className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${
                                        link.active
                                            ? 'bg-orange-600 text-white'
                                            : 'bg-white hover:bg-gray-100 text-gray-700 border border-gray-200'
                                    } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Reminder Modal */}
            {isReminderModalOpen && selectedCart && (
                <div className="fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 text-right space-y-4" dir="rtl">
                        <div className="flex items-center justify-between pb-2 border-b border-gray-100">
                            <h3 className="text-lg font-bold text-gray-900">إرسال بريد تذكيري ترويجي</h3>
                            <button
                                onClick={() => setIsReminderModalOpen(false)}
                                className="text-gray-400 hover:text-gray-600 text-xl font-bold"
                            >
                                &times;
                            </button>
                        </div>

                        <form onSubmit={handleSendReminder} className="space-y-4">
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1">البريد المستهدف</label>
                                <input
                                    type="text"
                                    value={selectedCart.email}
                                    disabled
                                    className="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono"
                                />
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1">لغة البريد</label>
                                <div className="grid grid-cols-2 gap-2">
                                    <label className={`flex items-center justify-center p-2.5 border rounded-xl cursor-pointer text-sm font-semibold transition-all ${data.locale === 'ar' ? 'border-orange-500 bg-orange-50/50 text-orange-700 font-bold' : 'border-gray-200 bg-white text-gray-600'}`}>
                                        <input
                                            type="radio"
                                            name="locale"
                                            value="ar"
                                            checked={data.locale === 'ar'}
                                            onChange={() => setData('locale', 'ar')}
                                            className="hidden"
                                        />
                                        العربية (Arabic)
                                    </label>
                                    <label className={`flex items-center justify-center p-2.5 border rounded-xl cursor-pointer text-sm font-semibold transition-all ${data.locale === 'en' ? 'border-orange-500 bg-orange-50/50 text-orange-700 font-bold' : 'border-gray-200 bg-white text-gray-600'}`}>
                                        <input
                                            type="radio"
                                            name="locale"
                                            value="en"
                                            checked={data.locale === 'en'}
                                            onChange={() => setData('locale', 'en')}
                                            className="hidden"
                                        />
                                        الإنجليزية (English)
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1">كود خصم تحفيزي (اختياري)</label>
                                <input
                                    type="text"
                                    value={data.discount_code}
                                    onChange={(e) => setData('discount_code', e.target.value.toUpperCase())}
                                    placeholder="مثال: SAVE15"
                                    className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm uppercase text-left font-mono"
                                    dir="ltr"
                                />
                                {errors.discount_code && <p className="text-xs text-red-600 mt-1">{errors.discount_code}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1">نسبة الخصم % (مطلوب إذا أدخلت كوداً)</label>
                                <input
                                    type="number"
                                    value={data.discount_percentage}
                                    onChange={(e) => setData('discount_percentage', e.target.value)}
                                    placeholder="مثال: 15"
                                    min="1"
                                    max="100"
                                    className="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm text-left font-mono"
                                    dir="ltr"
                                />
                                {errors.discount_percentage && <p className="text-xs text-red-600 mt-1">{errors.discount_percentage}</p>}
                            </div>

                            <div className="flex gap-2 justify-end pt-2 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={() => setIsReminderModalOpen(false)}
                                    className="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm disabled:opacity-50"
                                >
                                    {processing ? 'جاري الإرسال...' : 'إرسال التنبيه الآن ✉️'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}

