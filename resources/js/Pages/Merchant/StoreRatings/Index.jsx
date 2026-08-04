import React from 'react';
import { Head, router } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function StoreRatingsIndex({ ratings, stats }) {
    const formatNumber = (num) => {
        return Number(num).toLocaleString('en-US', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
    };

    const toggleVisibility = (id) => {
        router.post(`/admin/store-ratings/${id}/toggle-visibility`, {}, {
            preserveScroll: true
        });
    };

    const renderStars = (rating) => {
        return (
            <div className="flex gap-0.5 text-amber-400" dir="ltr">
                {[1, 2, 3, 4, 5].map((star) => (
                    <svg
                        key={star}
                        className={`h-5 w-5 ${star <= rating ? 'fill-current' : 'text-gray-250'}`}
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                ))}
            </div>
        );
    };

    return (
        <MerchantLayout title="تقييمات المتجر">
            <Head title="تقييمات المتجر" />

            <div className="space-y-8" dir="rtl">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-black text-gray-900">تقييمات المتجر</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            استعراض تقييمات العملاء لمتجرك ومستويات رضاهم عبر معايير المنتجات والشحن والخدمة.
                        </p>
                    </div>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    {/* Overall Average */}
                    <div className="bg-gradient-to-br from-indigo-50 to-indigo-100/50 rounded-2xl border border-indigo-100 p-5 shadow-sm flex flex-col justify-between">
                        <div className="flex justify-between items-start">
                            <span className="text-xs font-bold text-indigo-700 uppercase tracking-wider">التقييم العام</span>
                            <div className="p-1.5 bg-white/80 rounded-lg text-indigo-600 font-bold">
                                ⭐
                            </div>
                        </div>
                        <div className="mt-4">
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-indigo-900">{formatNumber(stats.overall_average)}</span>
                                <span className="text-sm text-indigo-655 font-semibold">/ 5</span>
                            </div>
                            <div className="mt-1">
                                {renderStars(Math.round(stats.overall_average))}
                            </div>
                        </div>
                    </div>

                    {/* Product Quality */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col justify-between">
                        <div className="flex justify-between items-start">
                            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">جودة المنتجات</span>
                            <div className="p-1.5 bg-gray-50 rounded-lg text-gray-500">
                                📦
                            </div>
                        </div>
                        <div className="mt-4">
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-gray-900">{formatNumber(stats.avg_products)}</span>
                                <span className="text-sm text-gray-400 font-semibold">/ 5</span>
                            </div>
                            <div className="mt-1">
                                {renderStars(Math.round(stats.avg_products))}
                            </div>
                        </div>
                    </div>

                    {/* Shipping Speed */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col justify-between">
                        <div className="flex justify-between items-start">
                            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">سرعة الشحن</span>
                            <div className="p-1.5 bg-gray-50 rounded-lg text-gray-500">
                                🚀
                            </div>
                        </div>
                        <div className="mt-4">
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-gray-900">{formatNumber(stats.avg_shipping)}</span>
                                <span className="text-sm text-gray-400 font-semibold">/ 5</span>
                            </div>
                            <div className="mt-1">
                                {renderStars(Math.round(stats.avg_shipping))}
                            </div>
                        </div>
                    </div>

                    {/* Customer Service */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col justify-between">
                        <div className="flex justify-between items-start">
                            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">خدمة العملاء</span>
                            <div className="p-1.5 bg-gray-50 rounded-lg text-gray-500">
                                💬
                            </div>
                        </div>
                        <div className="mt-4">
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-gray-900">{formatNumber(stats.avg_service)}</span>
                                <span className="text-sm text-gray-400 font-semibold">/ 5</span>
                            </div>
                            <div className="mt-1">
                                {renderStars(Math.round(stats.avg_service))}
                            </div>
                        </div>
                    </div>

                    {/* Total Reviews */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col justify-between">
                        <div className="flex justify-between items-start">
                            <span className="text-xs font-bold text-gray-500 uppercase tracking-wider">إجمالي المقيمين</span>
                            <div className="p-1.5 bg-gray-50 rounded-lg text-gray-500">
                                👥
                            </div>
                        </div>
                        <div className="mt-4">
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-gray-900">{stats.total_count}</span>
                                <span className="text-sm text-gray-400 font-semibold">تقييم</span>
                            </div>
                            <p className="text-xs text-gray-400 mt-1">تراكمي لجميع طلبات المتجر</p>
                        </div>
                    </div>
                </div>

                {/* Ratings Table */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 className="text-lg font-bold text-gray-900">سجل التقييمات التفصيلي</h3>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th className="px-6 py-4">العميل</th>
                                    <th className="px-6 py-4">الطلب المرتبط</th>
                                    <th className="px-6 py-4">جودة المنتجات</th>
                                    <th className="px-6 py-4">سرعة الشحن</th>
                                    <th className="px-6 py-4">خدمة العملاء</th>
                                    <th className="px-6 py-4 max-w-sm">التعليق</th>
                                    <th className="px-6 py-4 text-center">تاريخ التقييم</th>
                                    <th className="px-6 py-4 text-center">الظهور بالمتجر</th>
                                    <th className="px-6 py-4 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm text-gray-700">
                                {ratings.data.length > 0 ? (
                                    ratings.data.map((rating) => (
                                        <tr key={rating.id} className="hover:bg-gray-50/50 transition-colors">
                                            <td className="px-6 py-4">
                                                <div className="font-semibold text-gray-900">
                                                    {rating.user ? rating.user.name : 'عميل زائر'}
                                                </div>
                                                <div className="text-xs text-gray-400 mt-0.5">
                                                    {rating.user ? rating.user.phone : '-'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                {rating.order ? (
                                                    <span className="font-mono font-bold text-gray-800">
                                                        #{rating.order.reference_number}
                                                    </span>
                                                ) : (
                                                    <span className="text-gray-400">تقييم مباشر</span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4">{renderStars(rating.rating_products)}</td>
                                            <td className="px-6 py-4">{renderStars(rating.rating_shipping)}</td>
                                            <td className="px-6 py-4">{renderStars(rating.rating_service)}</td>
                                            <td className="px-6 py-4 max-w-xs truncate text-gray-600" title={rating.comment}>
                                                {rating.comment || <span className="text-gray-300 italic">بدون تعليق</span>}
                                            </td>
                                            <td className="px-6 py-4 text-center text-xs text-gray-500">
                                                {new Date(rating.created_at).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric'
                                                })}
                                            </td>
                                            <td className="px-6 py-4 text-center">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${
                                                    rating.is_visible
                                                        ? 'bg-green-50 text-green-700 border border-green-200'
                                                        : 'bg-red-50 text-red-700 border border-red-200'
                                                }`}>
                                                    {rating.is_visible ? 'مرئي' : 'مخفي'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 text-center">
                                                <button
                                                    onClick={() => toggleVisibility(rating.id)}
                                                    className={`px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer ${
                                                        rating.is_visible
                                                            ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                                            : 'bg-green-50 text-green-700 hover:bg-green-100'
                                                    }`}
                                                >
                                                    {rating.is_visible ? 'إخفاء بالمتجر' : 'إظهار بالمتجر'}
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="9" className="px-6 py-12 text-center text-gray-400">
                                            لا توجد تقييمات مسجلة للمتجر حتى الآن.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {ratings.links && ratings.links.length > 3 && (
                        <div className="bg-white border-t border-gray-100 px-6 py-4 flex justify-center gap-1.5">
                            {ratings.links.map((link, idx) => (
                                <button
                                    key={idx}
                                    disabled={!link.url || link.active}
                                    onClick={() => router.get(link.url)}
                                    className={`px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-colors ${
                                        link.active
                                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm'
                                            : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                                    } disabled:opacity-50 cursor-pointer`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </MerchantLayout>
    );
}

