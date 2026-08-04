import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function OrdersIndex({ orders, totalAmount, statusCounts, productsList, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.search || '');
    const [status, setStatus] = useState(filters?.status || '');
    const [dateFrom, setDateFrom] = useState(filters?.date_from || '');
    const [dateTo, setDateTo] = useState(filters?.date_to || '');
    const [productId, setProductId] = useState(filters?.product_id || '');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/orders', {
            search,
            status,
            date_from: dateFrom,
            date_to: dateTo,
            product_id: productId
        }, { preserveState: true });
    };

    const handleReset = () => {
        setSearch('');
        setStatus('');
        setDateFrom('');
        setDateTo('');
        setProductId('');
        router.get('/admin/orders', {}, { replace: true });
    };

    const statusConfig = {
        pending:   { text: 'في الانتظار', color: 'bg-yellow-50 text-yellow-700 border-yellow-100' },
        confirmed: { text: 'مؤكد', bg: 'bg-blue-50 text-blue-700 border-blue-100' },
        shipped:   { text: 'في التوصيل', color: 'bg-purple-50 text-purple-700 border-purple-100' },
        delivered: { text: 'تم التسليم', color: 'bg-green-50 text-green-700 border-green-100' },
        cancelled: { text: 'ملغي', color: 'bg-red-50 text-red-700 border-red-100' },
    };

    const getStatusBadge = (statusKey) => {
        const conf = statusConfig[statusKey] || { text: statusKey, color: 'bg-gray-50 text-gray-700 border-gray-100' };
        return (
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${conf.color}`}>
                {conf.text}
            </span>
        );
    };

    const formatCurrency = (amount) => {
        return Math.round(Number(amount)).toLocaleString('en-US') + ' ج.م';
    };

    return (
        <MerchantLayout title="إدارة الطلبات">
            <Head title="الطلبات" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">الطلبات</h2>
                        <p className="text-sm text-gray-500 mt-0.5">
                            إجمالي عدد الطلبات المفلترة: <span className="font-bold text-indigo-600">{statusCounts.total} طلب</span>
                        </p>
                    </div>
                    <div className="hidden md:flex flex-wrap items-center gap-2">
                        <a
                            href={`/admin/orders/export?format=excel&search=${search}&status=${status}&date_from=${dateFrom}&date_to=${dateTo}&product_id=${productId}`}
                            className="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm"
                        >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            excel تحميل
                        </a>
                        <a
                            href={`/admin/orders/export?format=pdf&search=${search}&status=${status}&date_from=${dateFrom}&date_to=${dateTo}&product_id=${productId}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm"
                        >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            pdf / طباعة
                        </a>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button
                        onClick={() => { setStatus(''); router.get('/admin/orders', { search, date_from: dateFrom, date_to: dateTo, product_id: productId }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === '' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.total}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">كل الطلبات</p>
                    </button>
                    <button
                        onClick={() => { setStatus('pending'); router.get('/admin/orders', { status: 'pending', search, date_from: dateFrom, date_to: dateTo, product_id: productId }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'pending' ? 'bg-yellow-500 text-white border-yellow-500 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.pending}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">في الانتظار</p>
                    </button>
                    <button
                        onClick={() => { setStatus('confirmed'); router.get('/admin/orders', { status: 'confirmed', search, date_from: dateFrom, date_to: dateTo, product_id: productId }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'confirmed' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.confirmed}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">مؤكد</p>
                    </button>
                    <button
                        onClick={() => { setStatus('cancelled'); router.get('/admin/orders', { status: 'cancelled', search, date_from: dateFrom, date_to: dateTo, product_id: productId }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'cancelled' ? 'bg-red-600 text-white border-red-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.cancelled}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">ملغي</p>
                    </button>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <form onSubmit={handleSearch} className="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div className="relative">
                            <input
                                type="text"
                                placeholder="الاسم، الهاتف، الرقم المرجعي..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pr-3 pl-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                            />
                        </div>
                        <div>
                            <select
                                value={productId}
                                onChange={(e) => setProductId(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none"
                            >
                                <option value="">كل المنتجات</option>
                                {productsList.map(prod => (
                                    <option key={prod.id} value={prod.id}>{prod.name}</option>
                                ))}
                            </select>
                        </div>
                        <div>
                            <input
                                type="date"
                                value={dateFrom}
                                onChange={(e) => setDateFrom(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none"
                            />
                        </div>
                        <div>
                            <input
                                type="date"
                                value={dateTo}
                                onChange={(e) => setDateTo(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none"
                            />
                        </div>
                        <div className="flex gap-2">
                            <button
                                type="submit"
                                className="flex-1 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors"
                            >
                                تصفية
                            </button>
                            <button
                                type="button"
                                onClick={handleReset}
                                className="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors"
                            >
                                إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>

                {/* Mobile Export Buttons (Side by Side) */}
                <div className="md:hidden flex gap-2">
                    <a
                        href={`/admin/orders/export?format=excel&search=${search}&status=${status}&date_from=${dateFrom}&date_to=${dateTo}&product_id=${productId}`}
                        className="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        excel تحميل
                    </a>
                    <a
                        href={`/admin/orders/export?format=pdf&search=${search}&status=${status}&date_from=${dateFrom}&date_to=${dateTo}&product_id=${productId}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors shadow-sm"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        pdf / طباعة
                    </a>
                </div>

                {/* Table Container */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-1">
                    {/* Desktop View: Table */}
                    <div className="hidden md:block overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500">
                                    <th className="px-6 py-3.5">#</th>
                                    <th className="px-6 py-3.5">الرقم المرجعي</th>
                                    <th className="px-6 py-3.5">العميل والهاتف</th>
                                    <th className="px-6 py-3.5">المحافظة</th>
                                    <th className="px-6 py-3.5">الحالة</th>
                                    <th className="px-6 py-3.5">الإجمالي</th>
                                    <th className="px-6 py-3.5">التاريخ</th>
                                    <th className="px-6 py-3.5">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm text-gray-700">
                                {orders.data.length > 0 ? (
                                    orders.data.map((order, idx) => (
                                        <tr key={order.id} className="hover:bg-gray-50/80 transition-colors">
                                            <td className="px-6 py-4 font-semibold text-gray-400">
                                                {(orders.current_page - 1) * orders.per_page + idx + 1}
                                            </td>
                                            <td className="px-6 py-4 font-mono font-bold text-gray-900">
                                                {order.reference_number}
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="font-semibold text-gray-900">{order.customer_name}</div>
                                                <div className="text-xs text-gray-500 font-mono mt-0.5">{order.customer_phone}</div>
                                            </td>
                                            <td className="px-6 py-4 text-gray-600 font-medium">
                                                {order.governorate}
                                            </td>
                                            <td className="px-6 py-4">
                                                {getStatusBadge(order.status)}
                                            </td>
                                            <td className="px-6 py-4 font-extrabold text-indigo-600">
                                                {formatCurrency(order.total)}
                                            </td>
                                            <td className="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                                                {new Date(order.created_at).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                })}
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-2">
                                                    <Link
                                                        href={`/admin/orders/${order.id}`}
                                                        className="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold hover:bg-indigo-100 transition-colors"
                                                    >
                                                        تفاصيل
                                                    </Link>
                                                    <a
                                                        href={`/admin/orders/${order.id}/invoice`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-250 transition-colors"
                                                    >
                                                        الفاتورة
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="8" className="px-6 py-10 text-center text-gray-400">
                                            لا توجد طلبات مطابقة للبحث أو الفلترة.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Mobile View: Cards */}
                    <div className="md:hidden divide-y divide-gray-100">
                        {orders.data.length > 0 ? (
                            orders.data.map((order, idx) => (
                                <div key={order.id} className="p-4 bg-white space-y-3">
                                    <div className="flex justify-between items-center">
                                        <div className="flex items-center gap-2">
                                            <span className="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded font-bold">
                                                # {(orders.current_page - 1) * orders.per_page + idx + 1}
                                            </span>
                                            <span className="font-mono font-bold text-gray-900 text-sm">
                                                رقم: {order.reference_number}
                                            </span>
                                        </div>
                                        {getStatusBadge(order.status)}
                                    </div>
                                    <div className="text-sm space-y-1.5 text-gray-600">
                                        <div className="flex justify-between">
                                            <span className="font-semibold text-gray-900">{order.customer_name}</span>
                                            <span className="font-bold text-indigo-600">{formatCurrency(order.total)}</span>
                                        </div>
                                        <div className="flex justify-between text-xs">
                                            <span className="font-mono">{order.customer_phone}</span>
                                            <span>{order.governorate}</span>
                                        </div>
                                        <div className="text-xs text-gray-400">
                                            {new Date(order.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-2 pt-1">
                                        <Link
                                            href={`/admin/orders/${order.id}`}
                                            className="flex-1 py-2 text-center bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-colors"
                                        >
                                            تفاصيل
                                        </Link>
                                        <a
                                            href={`/admin/orders/${order.id}/invoice`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex-1 py-2 text-center bg-gray-100 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-200 transition-colors"
                                        >
                                            الفاتورة
                                        </a>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="p-8 text-center text-gray-400 text-sm">
                                لا توجد طلبات مطابقة للبحث أو الفلترة.
                            </div>
                        )}
                    </div>

                    {/* Total Amount Box - Matched Exactly to Backup Store Design */}
                    <div className="px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-t border-emerald-200">
                        <div className="flex flex-col sm:flex-row justify-between items-center gap-3">
                            <span className="text-base sm:text-lg font-bold text-emerald-800">إجمالي المبالغ المعروضة:</span>
                            <div className="text-xl sm:text-2xl font-bold text-emerald-700 bg-white px-5 py-2 rounded-xl shadow-sm border border-emerald-200 flex items-center gap-2">
                                <span>{formatCurrency(totalAmount)}</span>
                            </div>
                        </div>
                    </div>

                    {/* Pagination */}
                    {orders.links && orders.links.length > 3 && (
                        <div className="bg-white border-t border-gray-100 px-6 py-4 flex justify-center gap-1.5">
                            {orders.links.map((link, idx) => {
                                let label = link.label || '';
                                if (label.includes('Previous') || label.includes('&laquo;') || label.includes('«')) {
                                    label = 'السابق';
                                } else if (label.includes('Next') || label.includes('&raquo;') || label.includes('»')) {
                                    label = 'التالي';
                                }
                                return (
                                    <button
                                        key={idx}
                                        disabled={!link.url || link.active}
                                        onClick={() => router.get(link.url)}
                                        className={`px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-colors ${
                                            link.active
                                                ? 'bg-orange-600 border-orange-600 text-white shadow-sm'
                                                : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                                        } disabled:opacity-50`}
                                        dangerouslySetInnerHTML={{ __html: label }}
                                    />
                                );
                            })}
                        </div>
                    )}
                </div>
            </div>
        </MerchantLayout>
    );
}
