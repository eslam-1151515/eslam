import React, { useState } from 'react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';
import { Head } from '@inertiajs/react';

export default function Plans({ plans }) {
    const [viewMode, setViewMode] = useState('grid'); // grid or table

    const formatLimit = (value) => {
        if (value === null || value === undefined || value === -1 || value === 'unlimited') {
            return 'غير محدود';
        }
        return value;
    };

    return (
        <SuperAdminLayout>
            <Head title="خطط الاشتراك - لوحة التحكم" />

            <div className="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 className="text-xl font-bold text-gray-800">خطط الاشتراك المتاحة</h2>
                    <p className="text-sm text-gray-500 mt-1">عرض وتفاصيل خطط أسعار الاشتراكات وحدود كل باقة للمشتركين.</p>
                </div>
                
                {/* View Switcher */}
                <div className="bg-white p-1 rounded-lg border border-gray-200 inline-flex self-start md:self-auto shadow-sm">
                    <button
                        onClick={() => setViewMode('grid')}
                        className={`px-4 py-1.5 rounded-md text-xs font-bold transition-all ${
                            viewMode === 'grid'
                                ? 'bg-indigo-600 text-white'
                                : 'text-gray-600 hover:text-gray-800'
                        }`}
                    >
                        عرض شبكي (بطاقات)
                    </button>
                    <button
                        onClick={() => setViewMode('table')}
                        className={`px-4 py-1.5 rounded-md text-xs font-bold transition-all ${
                            viewMode === 'table'
                                ? 'bg-indigo-600 text-white'
                                : 'text-gray-600 hover:text-gray-800'
                        }`}
                    >
                        عرض جدول
                    </button>
                </div>
            </div>

            {viewMode === 'grid' ? (
                /* Grid View */
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {plans && plans.length > 0 ? (
                        plans.map((plan) => {
                            const limits = plan.limits || {};
                            return (
                                <div 
                                    key={plan.id} 
                                    className={`bg-white rounded-2xl border transition-all duration-300 overflow-hidden shadow-sm hover:shadow-md ${
                                        plan.is_active ? 'border-gray-100' : 'border-rose-100 opacity-80'
                                    }`}
                                >
                                    {/* Header & Status */}
                                    <div className="p-6 border-b border-gray-50 relative">
                                        <div className="flex items-center justify-between mb-4">
                                            <span className="font-mono text-xs uppercase tracking-wider text-gray-400 bg-gray-100 px-2.5 py-1 rounded-md">
                                                {plan.slug}
                                            </span>
                                            {plan.is_active ? (
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    نشط
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                                    غير نشط
                                                </span>
                                            )}
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-800">{plan.name}</h3>
                                        <p className="text-sm text-gray-400 mt-2 line-clamp-2 h-10">{plan.description || 'لا يوجد وصف لهذه الخطة.'}</p>
                                    </div>

                                    {/* Pricing Section */}
                                    <div className="p-6 bg-gray-50/50 border-b border-gray-50 flex items-center justify-around gap-4 text-center">
                                        <div>
                                            <span className="block text-xs text-gray-400 font-semibold mb-1">السعر الشهري</span>
                                            <span className="text-xl font-extrabold text-indigo-600">{Math.round(parseFloat(plan.price_monthly)).toLocaleString('en-US')}</span>
                                            <span className="text-xs text-gray-400 mr-1">ج.م / شهر</span>
                                        </div>
                                        <div className="w-px h-10 bg-gray-200"></div>
                                        <div>
                                            <span className="block text-xs text-gray-400 font-semibold mb-1">السعر السنوي</span>
                                            <span className="text-xl font-extrabold text-emerald-600">{Math.round(parseFloat(plan.price_yearly)).toLocaleString('en-US')}</span>
                                            <span className="text-xs text-gray-400 mr-1">ج.م / سنة</span>
                                        </div>
                                    </div>

                                    {/* Limits & Details */}
                                    <div className="p-6 space-y-4">
                                        <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider">حدود ومميزات الخطة:</h4>
                                        <ul className="space-y-3 text-sm text-gray-600">
                                            <li className="flex items-center justify-between">
                                                <div className="flex items-center gap-2">
                                                    <svg className="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>عدد المنتجات المسموح بها</span>
                                                </div>
                                                <span className="font-bold text-gray-800">{formatLimit(limits.max_products)}</span>
                                            </li>
                                            <li className="flex items-center justify-between">
                                                <div className="flex items-center gap-2">
                                                    <svg className="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>الطلبات الشهرية</span>
                                                </div>
                                                <span className="font-bold text-gray-800">{formatLimit(limits.max_orders)}</span>
                                            </li>
                                            <li className="flex items-center justify-between">
                                                <div className="flex items-center gap-2">
                                                    <svg className="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>عدد الموظفين (المشرفين)</span>
                                                </div>
                                                <span className="font-bold text-gray-800">{formatLimit(limits.max_staff)}</span>
                                            </li>
                                            <li className="flex items-center justify-between border-t border-gray-50 pt-3">
                                                <div className="flex items-center gap-2">
                                                    <svg className="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>الفترة التجريبية المجانية</span>
                                                </div>
                                                <span className="font-bold text-gray-800">
                                                    {plan.trial_days > 0 ? `${plan.trial_days} يوم` : 'لا يوجد تجربة'}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            );
                        })
                    ) : (
                        <div className="col-span-full bg-white rounded-xl p-12 text-center text-gray-400">
                            لا توجد خطط اشتراك مسجلة حالياً.
                        </div>
                    )}
                </div>
            ) : (
                /* Table View */
                <div className="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                                    <th className="px-6 py-4">الخطة</th>
                                    <th className="px-6 py-4">السعر الشهري</th>
                                    <th className="px-6 py-4">السعر السنوي</th>
                                    <th className="px-6 py-4">المنتجات</th>
                                    <th className="px-6 py-4">الطلبات</th>
                                    <th className="px-6 py-4">الموظفين</th>
                                    <th className="px-6 py-4">التجربة</th>
                                    <th className="px-6 py-4">الحالة</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm">
                                {plans && plans.length > 0 ? (
                                    plans.map((plan) => {
                                        const limits = plan.limits || {};
                                        return (
                                            <tr key={plan.id} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="px-6 py-4">
                                                    <span className="font-bold text-gray-800 block">{plan.name}</span>
                                                    <span className="font-mono text-xs text-gray-400">{plan.slug}</span>
                                                </td>
                                                <td className="px-6 py-4 font-bold text-indigo-600">
                                                    {Math.round(parseFloat(plan.price_monthly)).toLocaleString('en-US')} ج.م
                                                </td>
                                                <td className="px-6 py-4 font-bold text-emerald-600">
                                                    {Math.round(parseFloat(plan.price_yearly)).toLocaleString('en-US')} ج.م
                                                </td>
                                                <td className="px-6 py-4 text-gray-700 font-medium">
                                                    {formatLimit(limits.max_products)}
                                                </td>
                                                <td className="px-6 py-4 text-gray-700 font-medium">
                                                    {formatLimit(limits.max_orders)}
                                                </td>
                                                <td className="px-6 py-4 text-gray-700 font-medium">
                                                    {formatLimit(limits.max_staff)}
                                                </td>
                                                <td className="px-6 py-4 text-gray-600">
                                                    {plan.trial_days > 0 ? `${plan.trial_days} يوم` : '-'}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {plan.is_active ? (
                                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                            نشط
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                                            غير نشط
                                                        </span>
                                                    )}
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan="8" className="px-6 py-12 text-center text-gray-400">
                                            لا توجد خطط اشتراك مسجلة حالياً.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}
        </SuperAdminLayout>
    );
}

