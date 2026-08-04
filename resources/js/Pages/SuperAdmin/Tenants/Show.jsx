import React from 'react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Show({ tenant, settings, plans, productsCount = 0, ordersCount = 0 }) {
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [isWalletModalOpen, setIsWalletModalOpen] = useState(false);
    const [isAssignModalOpen, setIsAssignModalOpen] = useState(false);

    const { data: assignData, setData: setAssignData, post: postAssign, processing: assigning, errors: assignErrors, reset: resetAssign } = useForm({
        plan_id: '',
        ends_at: '',
    });

    const { data: walletData, setData: setWalletData, post: postWallet, processing: walletProcessing, errors: walletErrors, reset: resetWallet } = useForm({
        amount: '',
    });

    const handleAssignSubmit = (e) => {
        e.preventDefault();
        postAssign(route('superadmin.tenants.assign-subscription', tenant.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsAssignModalOpen(false);
                resetAssign();
            },
        });
    };

    const handleWalletSubmit = (e) => {
        e.preventDefault();
        postWallet(route('superadmin.tenants.add-wallet-balance', tenant.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsWalletModalOpen(false);
                resetWallet();
            },
        });
    };

    const handleDelete = () => {
        router.delete(route('superadmin.tenants.destroy', tenant.id), {
            preserveScroll: true,
        });
    };

    const toggleStatus = () => {
        if (confirm('هل أنت متأكد من تغيير حالة هذا المتجر؟')) {
            router.patch(
                route('superadmin.tenants.toggle-status', tenant.id),
                {},
                { preserveScroll: true }
            );
        }
    };

    // Group settings by their group field or list them
    const groupedSettings = settings.reduce((acc, setting) => {
        const groupName = setting.group || 'عام';
        if (!acc[groupName]) {
            acc[groupName] = [];
        }
        acc[groupName].push(setting);
        return acc;
    }, {});

    const groupNamesAr = {
        general: 'إعدادات عامة',
        appearance: 'المظهر والتصميم',
        pixels: 'أكواد التتبع والبيكسل',
        social: 'روابط التواصل الاجتماعي',
        shipping: 'الشحن والتوصيل',
        payment: 'بوابات الدفع',
    };

    return (
        <SuperAdminLayout>
            <Head title={`تفاصيل المتجر: ${tenant.name}`} />

            {/* Back link */}
            <div className="mb-6">
                <Link
                    href={route('superadmin.tenants.index')}
                    className="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors gap-2"
                >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span>العودة لقائمة المتاجر</span>
                </Link>
            </div>

            {/* Top Store Summary Card */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div className="flex items-center gap-4">
                        <div className="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-2xl text-gray-700 overflow-hidden">
                            {tenant.logo ? (
                                <img src={`/storage/${tenant.logo}`} alt={tenant.name} className="w-full h-full object-cover" />
                            ) : (
                                tenant.name.substring(0, 2).toUpperCase()
                            )}
                        </div>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-2xl font-bold text-gray-800">{tenant.name}</h1>
                                {tenant.is_active ? (
                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        نشط
                                    </span>
                                ) : (
                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                        موقوف
                                    </span>
                                )}
                            </div>
                            <p className="text-sm text-gray-400 mt-1">
                                {tenant.slug}.{typeof window !== 'undefined' ? window.location.host.replace('app.', '') : 'fastorder.localhost'}
                                {tenant.custom_domain && <span className="mr-3 font-semibold text-indigo-600">({tenant.custom_domain})</span>}
                            </p>
                        </div>
                    </div>

                    <div className="flex flex-wrap items-center gap-3">
                        <button
                            onClick={() => setIsAssignModalOpen(true)}
                            className="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg text-sm font-semibold transition-colors"
                        >
                            تعديل الاشتراك
                        </button>
                        <button
                            onClick={() => setIsDeleteModalOpen(true)}
                            className="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-sm font-semibold transition-colors"
                        >
                            حذف المتجر
                        </button>
                        <button
                            onClick={toggleStatus}
                            className={`px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 ${
                                tenant.is_active
                                    ? 'bg-rose-500 hover:bg-rose-600 text-white focus:ring-rose-500'
                                    : 'bg-emerald-500 hover:bg-emerald-600 text-white focus:ring-emerald-500'
                            }`}
                        >
                            {tenant.is_active ? 'إيقاف المتجر مؤقتاً' : 'تفعيل المتجر'}
                        </button>
                    </div>
                </div>
            </div>

            {/* Quick KPI Stats Bar */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        📦
                    </div>
                    <div>
                        <span className="text-xs text-gray-400 font-medium block">عدد المنتجات</span>
                        <span className="text-lg font-bold text-gray-800">{productsCount} منتج</span>
                    </div>
                </div>

                <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        🛒
                    </div>
                    <div>
                        <span className="text-xs text-gray-400 font-medium block">إجمالي الطلبات</span>
                        <span className="text-lg font-bold text-gray-800">{ordersCount} طلب</span>
                    </div>
                </div>

                <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        💰
                    </div>
                    <div>
                        <span className="text-xs text-gray-400 font-medium block">رصيد المحفظة</span>
                        <span className="text-lg font-bold text-gray-800">{tenant.wallet_balance || '0.00'} ج.م</span>
                    </div>
                </div>

                <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                        ⚡
                    </div>
                    <div>
                        <span className="text-xs text-gray-400 font-medium block">حالة المتجر</span>
                        <span className={`text-sm font-bold ${tenant.is_active ? 'text-emerald-600' : 'text-rose-600'}`}>
                            {tenant.is_active ? 'نشط' : 'موقوف'}
                        </span>
                    </div>
                </div>
            </div>

            {/* Main Details Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {/* Column 1: Store & Owner Info */}
                <div className="lg:col-span-1 space-y-6">
                    {/* Store Info Card */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 className="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">بيانات المتجر</h3>
                        <div className="space-y-4 text-sm">
                            <div>
                                <span className="block text-gray-400 font-medium">البريد الإلكتروني للمتجر</span>
                                <span className="text-gray-700 font-semibold">{tenant.email || '-'}</span>
                            </div>
                            <div>
                                <span className="block text-gray-400 font-medium">رقم الهاتف</span>
                                <span className="text-gray-700 font-semibold">{tenant.phone || '-'}</span>
                            </div>
                            <div>
                                <span className="block text-gray-400 font-medium">تاريخ التسجيل</span>
                                <span className="text-gray-700 font-semibold">
                                    {new Date(tenant.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
                                </span>
                            </div>
                            <div>
                                <span className="block text-gray-400 font-medium">معرف المتجر UUID</span>
                                <span className="text-xs text-gray-500 font-mono select-all bg-gray-50 p-1.5 rounded block mt-1">
                                    {tenant.uuid}
                                </span>
                            </div>
                            <div className="pt-2 border-t border-gray-100">
                                <span className="block text-gray-400 font-medium mb-1">رصيد المحفظة</span>
                                <div className="flex items-center justify-between">
                                    <span className="text-emerald-600 font-bold text-lg">{tenant.wallet_balance || '0.00'} ج.م</span>
                                    <button 
                                        onClick={() => setIsWalletModalOpen(true)}
                                        className="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold hover:bg-indigo-100 transition-colors"
                                    >
                                        إضافة رصيد
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Owner Info Card */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 className="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">بيانات مالك المتجر</h3>
                        <div className="space-y-4 text-sm">
                            <div>
                                <span className="block text-gray-400 font-medium">الاسم بالكامل</span>
                                <span className="text-gray-700 font-semibold">{tenant.owner?.name || 'غير معروف'}</span>
                            </div>
                            <div>
                                <span className="block text-gray-400 font-medium">البريد الإلكتروني للعميل</span>
                                <span className="text-gray-700 font-semibold">{tenant.owner?.email || '-'}</span>
                            </div>
                            <div>
                                <span className="block text-gray-400 font-medium">تاريخ إنشاء حساب العميل</span>
                                <span className="text-gray-700 font-semibold">
                                    {tenant.owner?.created_at ? (
                                        new Date(tenant.owner.created_at).toLocaleDateString('en-US')
                                    ) : (
                                        '-'
                                    )}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Column 2 & 3: Subscription History & Settings */}
                <div className="lg:col-span-2 space-y-6">
                    {/* Active Subscription Status Card */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 className="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4 font-bold">الاشتراك الحالي والفعال</h3>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                            <div className="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100/50">
                                <span className="block text-indigo-500 font-semibold">الحالة الحالية</span>
                                <span className="text-lg font-bold text-indigo-900 mt-1 block">
                                    {tenant.subscription_status === 'active' 
                                        ? 'نشط' 
                                        : tenant.subscription_status === 'trial' 
                                        ? 'فترة تجريبية' 
                                        : 'منتهي / غير نشط'}
                                </span>
                            </div>
                            <div className="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100/50">
                                <span className="block text-emerald-500 font-semibold">تاريخ انتهاء الاشتراك</span>
                                <span className="text-lg font-bold text-emerald-900 mt-1 block">
                                    {tenant.subscription_ends_at 
                                        ? new Date(tenant.subscription_ends_at).toLocaleDateString('en-US')
                                        : 'لا يوجد'}
                                </span>
                            </div>
                            <div className="bg-amber-50/50 p-4 rounded-xl border border-amber-100/50">
                                <span className="block text-amber-500 font-semibold">نهاية الفترة التجريبية</span>
                                <span className="text-lg font-bold text-amber-900 mt-1 block">
                                    {tenant.trial_ends_at 
                                        ? new Date(tenant.trial_ends_at).toLocaleDateString('en-US')
                                        : 'منتهية'}
                                </span>
                            </div>
                        </div>

                        {/* Subscription History Table */}
                        <div className="mt-8">
                            <h4 className="text-md font-bold text-gray-700 mb-4">سجل الاشتراكات</h4>
                            <div className="overflow-x-auto">
                                <table className="w-full text-right border-collapse">
                                    <thead>
                                        <tr className="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                                            <th className="px-4 py-3">الخطة</th>
                                            <th className="px-4 py-3">دورة الدفع</th>
                                            <th className="px-4 py-3">المبلغ</th>
                                            <th className="px-4 py-3">تاريخ البدء</th>
                                            <th className="px-4 py-3">تاريخ الانتهاء</th>
                                            <th className="px-4 py-3">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-100 text-xs">
                                        {tenant.subscriptions && tenant.subscriptions.length > 0 ? (
                                            tenant.subscriptions.map((sub) => (
                                                <tr key={sub.id} className="hover:bg-gray-50/30 transition-colors">
                                                    <td className="px-4 py-3 font-semibold text-gray-800">{sub.plan?.name || 'غير معروف'}</td>
                                                    <td className="px-4 py-3 text-gray-600">
                                                        {sub.billing_cycle === 'yearly' ? 'سنوي' : 'شهري'}
                                                    </td>
                                                    <td className="px-4 py-3 font-medium text-gray-800">{Math.round(sub.price)} ج.م</td>
                                                    <td className="px-4 py-3 text-gray-600">
                                                        {new Date(sub.starts_at).toLocaleDateString('en-US')}
                                                    </td>
                                                    <td className="px-4 py-3 text-gray-600">
                                                        {sub.ends_at ? new Date(sub.ends_at).toLocaleDateString('en-US') : 'دائم'}
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        {sub.status === 'active' ? (
                                                            <span className="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full">نشط</span>
                                                        ) : sub.status === 'expired' ? (
                                                            <span className="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-full">منتهي</span>
                                                        ) : (
                                                            <span className="px-2 py-0.5 bg-gray-50 text-gray-700 border border-gray-100 rounded-full">{sub.status}</span>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="6" className="px-4 py-6 text-center text-gray-400">
                                                    لا توجد اشتراكات سابقة مسجلة.
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {/* Settings Details Card */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 className="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">إعدادات المتجر الحالية</h3>
                        
                        {Object.keys(groupedSettings).length > 0 ? (
                            <div className="space-y-6">
                                {Object.entries(groupedSettings).map(([group, list]) => (
                                    <div key={group} className="border border-gray-100 rounded-xl p-4">
                                        <h4 className="font-bold text-sm text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg inline-block mb-4">
                                            {groupNamesAr[group] || group}
                                        </h4>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                            {list.map((setting) => (
                                                <div key={setting.id} className="flex justify-between border-b border-gray-50 pb-2">
                                                    <span className="text-gray-400 font-medium">{setting.key}</span>
                                                    <span className="text-gray-800 font-semibold max-w-[200px] truncate select-all" title={setting.value}>
                                                        {setting.value || 'فارغ'}
                                                    </span>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-center py-6 text-sm text-gray-400">لا توجد إعدادات مخصصة لهذا المتجر حالياً.</p>
                        )}
                    </div>
                </div>

            </div>

            {/* Delete Modal */}
            {isDeleteModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <div className="p-6 border-b border-gray-100">
                            <h3 className="text-lg font-bold text-gray-800">تحذير: حذف المتجر نهائياً</h3>
                        </div>
                        <div className="p-6">
                            <p className="text-gray-600 mb-4">
                                هل أنت متأكد أنك تريد حذف المتجر <strong>{tenant.name}</strong> بجميع بياناته، والمنتجات، والطلبات، وحساب المالك؟
                            </p>
                            <p className="text-red-600 font-semibold text-sm">
                                هذا الإجراء لا يمكن التراجع عنه بأي شكل من الأشكال!
                            </p>
                        </div>
                        <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                            <button
                                onClick={() => setIsDeleteModalOpen(false)}
                                className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors"
                            >
                                إلغاء
                            </button>
                            <button
                                onClick={handleDelete}
                                className="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-md transition-colors"
                            >
                                نعم، احذف المتجر
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Wallet Modal */}
            {isWalletModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <form onSubmit={handleWalletSubmit}>
                            <div className="p-6 border-b border-gray-100">
                                <h3 className="text-lg font-bold text-gray-800">إضافة رصيد للمحفظة</h3>
                            </div>
                            <div className="p-6 space-y-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        المبلغ (جنيه مصري)
                                    </label>
                                    <input
                                        type="number"
                                        required
                                        min="1"
                                        step="1"
                                        value={walletData.amount}
                                        onChange={(e) => setWalletData('amount', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="مثال: 500"
                                    />
                                    {walletErrors.amount && <span className="text-xs text-red-500 mt-1 block">{walletErrors.amount}</span>}
                                </div>
                            </div>
                            <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setIsWalletModalOpen(false);
                                        resetWallet();
                                    }}
                                    className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors"
                                    disabled={walletProcessing}
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={walletProcessing}
                                    className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-md transition-colors disabled:opacity-50"
                                >
                                    {walletProcessing ? 'جاري الإضافة...' : 'إضافة الرصيد'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Assign Subscription Modal */}
            {isAssignModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <form onSubmit={handleAssignSubmit}>
                            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
                                <h3 className="text-lg font-bold text-gray-800">تعديل اشتراك المتجر</h3>
                                <button type="button" onClick={() => setIsAssignModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div className="p-6 space-y-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">اختر الباقة</label>
                                    <select
                                        required
                                        value={assignData.plan_id}
                                        onChange={(e) => setAssignData('plan_id', e.target.value)}
                                        style={{
                                            backgroundImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E")`,
                                            backgroundPosition: 'left 0.75rem center',
                                            backgroundSize: '1.25rem',
                                            backgroundRepeat: 'no-repeat',
                                        }}
                                        className="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm appearance-none"
                                    >
                                        <option value="">-- اختر باقة --</option>
                                        {plans?.map((p) => (
                                            <option key={p.id} value={p.id}>{p.name}</option>
                                        ))}
                                    </select>
                                    {assignErrors.plan_id && <span className="text-xs text-red-500 mt-1 block">{assignErrors.plan_id}</span>}
                                </div>
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">تاريخ انتهاء الاشتراك</label>
                                    <input
                                        type="date"
                                        required
                                        value={assignData.ends_at}
                                        onChange={(e) => setAssignData('ends_at', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                                    />
                                    {assignErrors.ends_at && <span className="text-xs text-red-500 mt-1 block">{assignErrors.ends_at}</span>}
                                </div>
                            </div>
                            <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                                <button type="button" onClick={() => setIsAssignModalOpen(false)} className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors" disabled={assigning}>
                                    إلغاء
                                </button>
                                <button type="submit" className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-md transition-colors disabled:opacity-50" disabled={assigning}>
                                    {assigning ? 'جاري الحفظ...' : 'حفظ التعديل'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </SuperAdminLayout>
    );
}

