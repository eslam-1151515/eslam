import React, { useState } from 'react';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';

export default function ProfileIndex({ admin, admins }) {
    const { flash } = usePage().props;
    const [showAddAdminModal, setShowAddAdminModal] = useState(false);

    // Profile Form
    const profileForm = useForm({
        name: admin.name || '',
        email: admin.email || '',
        phone: admin.phone || '',
    });

    const handleProfileSubmit = (e) => {
        e.preventDefault();
        profileForm.patch('/profile', {
            preserveScroll: true,
        });
    };

    // Password Form
    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handlePasswordSubmit = (e) => {
        e.preventDefault();
        passwordForm.put('/profile/password', {
            preserveScroll: true,
            onSuccess: () => passwordForm.reset(),
        });
    };

    // Add Admin Form
    const addAdminForm = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
    });

    const handleAddAdminSubmit = (e) => {
        e.preventDefault();
        addAdminForm.post('/profile/admins', {
            preserveScroll: true,
            onSuccess: () => {
                setShowAddAdminModal(false);
                addAdminForm.reset();
            },
        });
    };

    const handleDeleteAdmin = (adminUser) => {
        if (adminUser.id === admin.id) {
            alert('لا يمكنك حذف حسابك الحالي المسجل به الدخول!');
            return;
        }

        if (confirm(`هل أنت متأكد من حذف حساب المدير "${adminUser.name}" (${adminUser.email}) نهائياً؟`)) {
            router.delete(`/profile/admins/${adminUser.id}`, {
                preserveScroll: true,
            });
        }
    };

    return (
        <SuperAdminLayout>
            <Head title="الملف الشخصي وإدارة المديرين - لوحة التحكم" />

            <div className="max-w-6xl mx-auto space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-xs border border-gray-100">
                    <div>
                        <h2 className="text-xl font-black text-gray-900 flex items-center gap-2.5">
                            <span className="text-2xl">👤</span>
                            <span>الملف الشخصي وإدارة حسابات المديرين</span>
                        </h2>
                        <p className="text-xs sm:text-sm text-gray-500 mt-1">
                            تحديث بيانات الدخول الخاصة بك، تغيير كلمة المرور، وإدارة حسابات السوبر أدمن المصرح لهم بالدخول.
                        </p>
                    </div>

                    <div className="flex items-center gap-2">
                        <button
                            type="button"
                            onClick={() => setShowAddAdminModal(true)}
                            className="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all cursor-pointer"
                        >
                            <span>➕</span>
                            <span>إضافة مدير جديد</span>
                        </button>
                    </div>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-800 text-sm font-bold flex items-center gap-2 shadow-xs">
                        <span>✅</span>
                        <span>{flash.success}</span>
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-bold flex items-center gap-2 shadow-xs">
                        <span>⚠️</span>
                        <span>{flash.error}</span>
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {/* Left Column: Personal Info & Password (7 cols) */}
                    <div className="lg:col-span-7 space-y-6">
                        {/* 1. Update Personal Info */}
                        <div className="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-5">
                            <div className="border-b border-gray-100 pb-3 flex items-center justify-between">
                                <h3 className="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span>📝</span>
                                    <span>البيانات الشخصية للمدير الحالي</span>
                                </h3>
                                <span className="text-[11px] font-mono text-gray-400">
                                    تاريخ التسجيل: {admin.created_at || '-'}
                                </span>
                            </div>

                            <form onSubmit={handleProfileSubmit} className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                        الاسم بالكامل <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={profileForm.data.name}
                                        onChange={(e) => profileForm.setData('name', e.target.value)}
                                        className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                        placeholder="مثال: أحمد محمد"
                                        required
                                    />
                                    {profileForm.errors.name && (
                                        <p className="text-xs text-red-600 mt-1 font-semibold">{profileForm.errors.name}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                        البريد الإلكتروني لتسجيل الدخول <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        value={profileForm.data.email}
                                        onChange={(e) => profileForm.setData('email', e.target.value)}
                                        className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                        placeholder="admin@example.com"
                                        dir="ltr"
                                        required
                                    />
                                    {profileForm.errors.email && (
                                        <p className="text-xs text-red-600 mt-1 font-semibold">{profileForm.errors.email}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                        رقم الهاتف (اختياري)
                                    </label>
                                    <input
                                        type="text"
                                        value={profileForm.data.phone}
                                        onChange={(e) => profileForm.setData('phone', e.target.value)}
                                        className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                        placeholder="01012345678"
                                        dir="ltr"
                                    />
                                    {profileForm.errors.phone && (
                                        <p className="text-xs text-red-600 mt-1 font-semibold">{profileForm.errors.phone}</p>
                                    )}
                                </div>

                                <div className="pt-2 flex justify-end">
                                    <button
                                        type="submit"
                                        disabled={profileForm.processing}
                                        className="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all disabled:opacity-50 cursor-pointer"
                                    >
                                        {profileForm.processing ? 'جاري الحفظ...' : 'حفظ التعديلات 💾'}
                                    </button>
                                </div>
                            </form>
                        </div>

                        {/* 2. Change Password */}
                        <div className="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-5">
                            <div className="border-b border-gray-100 pb-3">
                                <h3 className="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span>🔒</span>
                                    <span>تغيير كلمة المرور</span>
                                </h3>
                            </div>

                            <form onSubmit={handlePasswordSubmit} className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                        كلمة المرور الحالية <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        value={passwordForm.data.current_password}
                                        onChange={(e) => passwordForm.setData('current_password', e.target.value)}
                                        className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                        placeholder="••••••••"
                                        dir="ltr"
                                        required
                                    />
                                    {passwordForm.errors.current_password && (
                                        <p className="text-xs text-red-600 mt-1 font-semibold">{passwordForm.errors.current_password}</p>
                                    )}
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                            كلمة المرور الجديدة <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            value={passwordForm.data.password}
                                            onChange={(e) => passwordForm.setData('password', e.target.value)}
                                            className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                            placeholder="8 خانات على الأقل"
                                            dir="ltr"
                                            required
                                        />
                                        {passwordForm.errors.password && (
                                            <p className="text-xs text-red-600 mt-1 font-semibold">{passwordForm.errors.password}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                            تأكيد كلمة المرور الجديدة <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="password"
                                            value={passwordForm.data.password_confirmation}
                                            onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)}
                                            className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-gray-50/50 focus:bg-white transition-all"
                                            placeholder="أعد كتابة كلمة المرور"
                                            dir="ltr"
                                            required
                                        />
                                    </div>
                                </div>

                                <div className="pt-2 flex justify-end">
                                    <button
                                        type="submit"
                                        disabled={passwordForm.processing}
                                        className="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm transition-all disabled:opacity-50 cursor-pointer"
                                    >
                                        {passwordForm.processing ? 'جاري التحديث...' : 'تحديث كلمة المرور 🔒'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Right Column: Super Admins List (5 cols) */}
                    <div className="lg:col-span-5 space-y-6">
                        <div className="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                            <div className="border-b border-gray-100 pb-3 flex items-center justify-between">
                                <h3 className="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span>🛡️</span>
                                    <span>المديرون العامون (Super Admins)</span>
                                </h3>
                                <span className="text-xs font-black bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">
                                    {admins?.length || 1} مدير
                                </span>
                            </div>

                            <p className="text-xs text-gray-500 leading-relaxed">
                                الحسابات المسموح لها بالدخول إلى لوحة تحكم الإدارة العامة والتحكم في المتاجر والاشتراكات.
                            </p>

                            <div className="divide-y divide-gray-100">
                                {(admins || []).map((adminUser) => {
                                    const isCurrent = adminUser.id === admin.id;
                                    return (
                                        <div key={adminUser.id} className="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                                            <div className="flex items-center gap-3 min-w-0">
                                                <div className={`w-9 h-9 rounded-full flex items-center justify-center font-black text-xs shrink-0 ${
                                                    isCurrent 
                                                        ? 'bg-indigo-600 text-white shadow-xs' 
                                                        : 'bg-gray-100 text-gray-700'
                                                }`}>
                                                    {adminUser.name ? adminUser.name.substring(0, 1).toUpperCase() : 'A'}
                                                </div>
                                                <div className="min-w-0">
                                                    <div className="flex items-center gap-2">
                                                        <span className="font-bold text-gray-900 text-xs truncate">
                                                            {adminUser.name}
                                                        </span>
                                                        {isCurrent && (
                                                            <span className="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                                أنت (الحالي)
                                                            </span>
                                                        )}
                                                    </div>
                                                    <span className="text-[11px] text-gray-400 font-mono block truncate" dir="ltr">
                                                        {adminUser.email}
                                                    </span>
                                                </div>
                                            </div>

                                            {!isCurrent && (
                                                <button
                                                    type="button"
                                                    onClick={() => handleDeleteAdmin(adminUser)}
                                                    className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer shrink-0"
                                                    title="حذف هذا المدير"
                                                >
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal: Add New Super Admin */}
            {showAddAdminModal && (
                <div className="fixed inset-0 bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 animate-fade-in">
                    <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-gray-100">
                        <div className="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 className="font-bold text-gray-900 text-base flex items-center gap-2">
                                <span>➕</span>
                                <span>إضافة حساب مدير عام جديد</span>
                            </h3>
                            <button
                                type="button"
                                onClick={() => setShowAddAdminModal(false)}
                                className="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center font-bold text-sm cursor-pointer"
                            >
                                ✕
                            </button>
                        </div>

                        <form onSubmit={handleAddAdminSubmit} className="space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                    الاسم <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={addAdminForm.data.name}
                                    onChange={(e) => addAdminForm.setData('name', e.target.value)}
                                    className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    placeholder="اسم المدير الجديد"
                                    required
                                />
                                {addAdminForm.errors.name && (
                                    <p className="text-xs text-red-600 mt-1 font-semibold">{addAdminForm.errors.name}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                    البريد الإلكتروني <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    value={addAdminForm.data.email}
                                    onChange={(e) => addAdminForm.setData('email', e.target.value)}
                                    className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    placeholder="admin2@example.com"
                                    dir="ltr"
                                    required
                                />
                                {addAdminForm.errors.email && (
                                    <p className="text-xs text-red-600 mt-1 font-semibold">{addAdminForm.errors.email}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                    رقم الهاتف (اختياري)
                                </label>
                                <input
                                    type="text"
                                    value={addAdminForm.data.phone}
                                    onChange={(e) => addAdminForm.setData('phone', e.target.value)}
                                    className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    placeholder="01012345678"
                                    dir="ltr"
                                />
                                {addAdminForm.errors.phone && (
                                    <p className="text-xs text-red-600 mt-1 font-semibold">{addAdminForm.errors.phone}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                    كلمة المرور الأولية <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    value={addAdminForm.data.password}
                                    onChange={(e) => addAdminForm.setData('password', e.target.value)}
                                    className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    placeholder="8 خانات على الأقل"
                                    dir="ltr"
                                    required
                                />
                                {addAdminForm.errors.password && (
                                    <p className="text-xs text-red-600 mt-1 font-semibold">{addAdminForm.errors.password}</p>
                                )}
                            </div>

                            <div className="pt-3 flex items-center justify-end gap-2 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={() => setShowAddAdminModal(false)}
                                    className="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-colors cursor-pointer"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={addAdminForm.processing}
                                    className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all disabled:opacity-50 cursor-pointer"
                                >
                                    {addAdminForm.processing ? 'جاري الإنشاء...' : 'إنشاء الحساب 🚀'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </SuperAdminLayout>
    );
}
