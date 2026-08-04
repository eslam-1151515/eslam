import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ReturnsIndex({ returns, statusCounts, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.search || '');
    const [status, setStatus] = useState(filters?.status || '');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/returns', { search, status }, { preserveState: true });
    };

    const handleReset = () => {
        setSearch('');
        setStatus('');
        router.get('/admin/returns', {}, { replace: true });
    };

    const statusConfig = {
        pending:   { text: 'قيد الانتظار', color: 'bg-yellow-50 text-yellow-700 border-yellow-100' },
        approved:  { text: 'مقبول مبدئياً', color: 'bg-blue-50 text-blue-700 border-blue-100' },
        completed: { text: 'مكتمل ومسترد', color: 'bg-green-50 text-green-700 border-green-100' },
        rejected:  { text: 'مرفوض', color: 'bg-red-50 text-red-700 border-red-100' },
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
        <MerchantLayout title="طلبات المرتجعات">
            <Head title="طلبات المرتجعات" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">إرجاع واستبدال المنتجات</h2>
                        <p className="text-sm text-gray-500 mt-0.5">
                            إدارة طلبات إرجاع المنتجات والمبالغ المستردة للعملاء
                        </p>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <button
                        onClick={() => { setStatus(''); router.get('/admin/returns', { search }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === '' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.total}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">كل الطلبات</p>
                    </button>
                    <button
                        onClick={() => { setStatus('pending'); router.get('/admin/returns', { status: 'pending', search }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'pending' ? 'bg-yellow-500 text-white border-yellow-500 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.pending}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">⏳ قيد الانتظار</p>
                    </button>
                    <button
                        onClick={() => { setStatus('approved'); router.get('/admin/returns', { status: 'approved', search }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'approved' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.approved}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">👍 مقبول مبدئياً</p>
                    </button>
                    <button
                        onClick={() => { setStatus('completed'); router.get('/admin/returns', { status: 'completed', search }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'completed' ? 'bg-green-600 text-white border-green-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.completed}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">✅ مكتمل ومسترد</p>
                    </button>
                    <button
                        onClick={() => { setStatus('rejected'); router.get('/admin/returns', { status: 'rejected', search }); }}
                        className={`p-4 rounded-xl border text-right transition-all ${status === 'rejected' ? 'bg-red-600 text-white border-red-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'}`}
                    >
                        <p className="text-2xl font-bold">{statusCounts.rejected}</p>
                        <p className="text-xs font-medium opacity-80 mt-1">❌ مرفوض</p>
                    </button>
                </div>

                {/* Filter Form */}
                <div className="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4 items-end">
                        <div className="flex-1 w-full">
                            <label className="block text-xs font-semibold text-gray-600 mb-1.5">البحث في الطلبات</label>
                            <input
                                type="text"
                                placeholder="ابحث باسم العميل، هاتف العميل، أو كود الطلب..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                        <div className="flex gap-2 w-full md:w-auto">
                            <button
                                type="submit"
                                className="flex-1 md:flex-none px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm"
                            >
                                تصفية
                            </button>
                            <button
                                type="button"
                                onClick={handleReset}
                                className="flex-1 md:flex-none px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors border border-gray-200"
                            >
                                إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 text-green-800 rounded-xl border border-green-200 text-sm font-semibold">
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 text-red-800 rounded-xl border border-red-200 text-sm font-semibold">
                        {flash.error}
                    </div>
                )}

                {/* Returns Table */}
                <div className="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <th className="px-6 py-3.5">كود الطلب</th>
                                    <th className="px-6 py-3.5">العميل</th>
                                    <th className="px-6 py-3.5">المنتجات المرتجعة</th>
                                    <th className="px-6 py-3.5">السبب</th>
                                    <th className="px-6 py-3.5">مبلغ التعويض</th>
                                    <th className="px-6 py-3.5">الحالة</th>
                                    <th className="px-6 py-3.5">التاريخ</th>
                                    <th className="px-6 py-3.5 text-left">التحكم</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200 text-sm">
                                {returns.data.length > 0 ? (
                                    returns.data.map((r) => (
                                        <tr key={r.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 font-bold text-indigo-600">
                                                #{r.order?.reference_number || r.order_id}
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="font-semibold text-gray-900">{r.order?.customer_name}</div>
                                                <div className="text-xs text-gray-500 mt-0.5">{r.order?.customer_phone}</div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <span className="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-xs font-semibold">
                                                    {Array.isArray(r.items) ? r.items.length : 0} منتجات
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 max-w-xs truncate text-gray-600" title={r.reason}>
                                                {r.reason}
                                            </td>
                                            <td className="px-6 py-4 font-bold text-emerald-600">
                                                {formatCurrency(r.refund_amount)}
                                            </td>
                                            <td className="px-6 py-4">
                                                {getStatusBadge(r.status)}
                                            </td>
                                            <td className="px-6 py-4 text-xs text-gray-500">
                                                {new Date(r.created_at).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric'
                                                })}
                                            </td>
                                            <td className="px-6 py-4 text-left">
                                                <Link
                                                    href={`/admin/returns/${r.id}`}
                                                    className="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition-colors"
                                                >
                                                    عرض التفاصيل 👁️
                                                </Link>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="8" className="text-center py-8 text-gray-500">
                                            لا توجد طلبات إرجاع تطابق الفلترة الحالية.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {returns.links && returns.links.length > 3 && (
                        <div className="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="flex-1 flex justify-between sm:hidden">
                                {returns.prev_page_url && (
                                    <Link href={returns.prev_page_url} className="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        السابق
                                    </Link>
                                )}
                                {returns.next_page_url && (
                                    <Link href={returns.next_page_url} className="ml-3 px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        التالي
                                    </Link>
                                )}
                            </div>
                            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-xs text-gray-700">
                                        عرض <span className="font-semibold">{returns.from || 0}</span> إلى <span className="font-semibold">{returns.to || 0}</span> من أصل <span className="font-semibold">{returns.total}</span> طلب إرجاع
                                    </p>
                                </div>
                                <div>
                                    <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        {returns.links.map((link, idx) => {
                                            const cleanLabel = link.label
                                                .replace('&laquo; Previous', 'السابق')
                                                .replace('Next &raquo;', 'التالي');

                                            return (
                                                <Link
                                                    key={idx}
                                                    href={link.url || '#'}
                                                    disabled={!link.url}
                                                    dangerouslySetInnerHTML={{ __html: cleanLabel }}
                                                    className={`relative inline-flex items-center px-4 py-2 border text-xs font-semibold ${
                                                        link.active
                                                            ? 'z-10 bg-indigo-600 border-indigo-600 text-white'
                                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                                    } ${idx === 0 ? 'rounded-r-md' : ''} ${idx === returns.links.length - 1 ? 'rounded-l-md' : ''} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                                />
                                            );
                                        })}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </MerchantLayout>
    );
}

