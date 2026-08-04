import React from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function LandingPagesIndex({ landingPages }) {
    const { flash } = usePage().props;

    const handleToggleStatus = (id) => {
        router.post(`/admin/landing-pages/${id}/toggle`, {}, {
            preserveScroll: true,
        });
    };

    const handleDuplicate = (id) => {
        router.post(`/admin/landing-pages/${id}/duplicate`);
    };

    const handleDelete = (id, title) => {
        if (confirm(`هل أنت متأكد من حذف صفحة الهبوط "${title}"؟`)) {
            router.delete(`/admin/landing-pages/${id}`);
        }
    };

    return (
        <MerchantLayout title="صفحات الهبوط">
            <Head title="صفحات الهبوط" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">صفحات الهبوط (Landing Pages)</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            أنشئ صفحات هبوط ترويجية سريعة لزيادة المبيعات وعرض عروضك المميزة مباشرة.
                        </p>
                    </div>
                    <Link
                        href="/admin/landing-pages/create"
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 w-fit"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        إنشاء صفحة هبوط جديدة
                    </Link>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}

                {/* Table / List */}
                {landingPages.length > 0 ? (
                    <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-right border-collapse">
                                <thead>
                                    <tr className="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <th className="px-6 py-4">العنوان والرابط</th>
                                        <th className="px-6 py-4 text-center">الحالة</th>
                                        <th className="px-6 py-4 text-center">المشاهدات</th>
                                        <th className="px-6 py-4 text-center">التحويلات</th>
                                        <th className="px-6 py-4 text-center">نسبة التحويل</th>
                                        <th className="px-6 py-4 text-center">تاريخ الإنشاء</th>
                                        <th className="px-6 py-4 text-left">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 text-sm text-gray-700">
                                    {landingPages.map((page) => (
                                        <tr key={page.id} className="hover:bg-gray-50/50 transition-colors">
                                            <td className="px-6 py-4">
                                                <div className="font-bold text-gray-900">{page.title}</div>
                                                <a
                                                    href={page.url}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="text-xs text-orange-600 hover:text-orange-700 font-medium inline-flex items-center gap-1 mt-1 hover:underline break-all"
                                                    dir="ltr"
                                                >
                                                    {page.url}
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </td>
                                            <td className="px-6 py-4 text-center">
                                                <button
                                                    onClick={() => handleToggleStatus(page.id)}
                                                    className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${
                                                        page.is_active
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}
                                                >
                                                    {page.is_active ? 'نشط' : 'معطل'}
                                                </button>
                                            </td>
                                            <td className="px-6 py-4 text-center font-semibold text-gray-900">
                                                {page.views_count}
                                            </td>
                                            <td className="px-6 py-4 text-center font-semibold text-gray-900">
                                                {page.conversions_count}
                                            </td>
                                            <td className="px-6 py-4 text-center">
                                                <span className="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700">
                                                    {page.conversion_rate}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 text-center text-xs text-gray-500">
                                                {page.created_at}
                                            </td>
                                            <td className="px-6 py-4 text-left">
                                                <div className="flex items-center justify-end gap-2">
                                                    <Link
                                                        href={`/admin/landing-pages/${page.id}/edit`}
                                                        className="p-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1"
                                                        title="تعديل صفحة الهبوط"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        تعديل
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDuplicate(page.id)}
                                                        className="p-2 bg-orange-50 text-orange-700 hover:bg-orange-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1"
                                                        title="تكرار صفحة الهبوط"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                        </svg>
                                                        نسخ
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(page.id, page.title)}
                                                        className="p-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1"
                                                        title="حذف صفحة الهبوط"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        حذف
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                ) : (
                    <div className="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
                        <div className="w-16 h-16 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 className="text-lg font-bold text-gray-900 mb-1">لا توجد صفحات هبوط بعد</h3>
                        <p className="text-gray-500 text-sm max-w-sm mx-auto mb-6">
                            أنشئ أول صفحة هبوط لمنتجاتك المميزة الآن وابدأ في زيادة مبيعاتك مباشرة.
                        </p>
                        <Link
                            href="/admin/landing-pages/create"
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-all shadow-md"
                        >
                            إنشاء صفحة هبوط جديدة
                        </Link>
                    </div>
                )}
            </div>
        </MerchantLayout>
    );
}
