import React from 'react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ stats, graphs }) {
    return (
        <SuperAdminLayout>
            <Head title="الرئيسية - لوحة التحكم العامة" />

            <div className="p-6">
                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {/* Card 1 */}
                    <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-400 mb-1">إجمالي المتاجر</p>
                            <h3 className="text-3xl font-bold text-gray-800">{stats.total_stores}</h3>
                        </div>
                        <div className="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>

                    {/* Card 2 */}
                    <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-400 mb-1">الاشتراكات النشطة</p>
                            <h3 className="text-3xl font-bold text-emerald-600">{stats.total_subscriptions}</h3>
                        </div>
                        <div className="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {/* Card 3 */}
                    <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-400 mb-1">طلبات دفع معلقة</p>
                            <h3 className="text-3xl font-bold text-amber-500">{stats.pending_payments}</h3>
                        </div>
                        <div className="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {/* Visual Data Representation */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Registrations Chart/List */}
                    <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h4 className="text-lg font-bold text-gray-800 mb-4">التسجيلات الشهرية الجديدة</h4>
                        {graphs.registrations && graphs.registrations.length > 0 ? (
                            <div className="space-y-4">
                                {graphs.registrations.map((item, idx) => (
                                    <div key={idx} className="flex items-center justify-between">
                                        <span className="text-sm text-gray-500 font-medium">{item.month}</span>
                                        <div className="flex-1 mx-4 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                            <div 
                                                className="bg-indigo-500 h-full rounded-full" 
                                                style={{ width: `${Math.min(100, (item.count / Math.max(1, ...graphs.registrations.map(r => r.count))) * 100)}%` }}
                                            ></div>
                                        </div>
                                        <span className="text-sm font-bold text-gray-800">{item.count} متجر</span>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-gray-400 text-center py-8">لا توجد بيانات تسجيلات حالياً</p>
                        )}
                    </div>

                    {/* Revenue List */}
                    <div className="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h4 className="text-lg font-bold text-gray-800 mb-4">الأرباح والتحصيلات الشهرية</h4>
                        {graphs.revenue && graphs.revenue.length > 0 ? (
                            <div className="space-y-4">
                                {graphs.revenue.map((item, idx) => (
                                    <div key={idx} className="flex items-center justify-between">
                                        <span className="text-sm text-gray-500 font-medium">{item.month}</span>
                                        <div className="flex-1 mx-4 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                                            <div 
                                                className="bg-emerald-500 h-full rounded-full" 
                                                style={{ width: `${Math.min(100, (item.total_amount / Math.max(1, ...graphs.revenue.map(r => r.total_amount))) * 100)}%` }}
                                            ></div>
                                        </div>
                                        <span className="text-sm font-bold text-gray-800">{Math.round(item.total_amount)} ج.م</span>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-gray-400 text-center py-8">لا توجد أرباح مسجلة حالياً</p>
                        )}
                    </div>
                </div>
            </div>
        </SuperAdminLayout>
    );
}
