import React, { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ProfileEdit({ user: propUser }) {
    const { auth } = usePage().props;
    const user = propUser || auth?.user;
    const isGoogleUser = Boolean(user?.is_google_user || user?.google_id);

    // Password Visibility Toggles
    const [showCurrentPassword, setShowCurrentPassword] = useState(false);
    const [showNewPassword, setShowNewPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    // Profile info form
    const { 
        data: profileData, 
        setData: setProfileData, 
        patch: patchProfile, 
        processing: profileProcessing, 
        errors: profileErrors,
        recentlySuccessful: profileSuccessful
    } = useForm({
        name: user?.name || '',
        email: user?.email || '',
        phone: user?.phone || '',
    });

    // Password form
    const { 
        data: passwordData, 
        setData: setPasswordData, 
        put: putPassword, 
        processing: passwordProcessing, 
        errors: passwordErrors, 
        recentlySuccessful: passwordSuccessful,
        reset: resetPassword
    } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleProfileSubmit = (e) => {
        e.preventDefault();
        patchProfile('/admin/profile', {
            preserveScroll: true
        });
    };

    const handlePasswordSubmit = (e) => {
        e.preventDefault();
        putPassword('/admin/password', {
            preserveScroll: true,
            onSuccess: () => resetPassword('current_password', 'password', 'password_confirmation'),
        });
    };

    return (
        <MerchantLayout title="الملف الشخصي">
            <Head title="الملف الشخصي" />

            <div className="max-w-3xl space-y-6">
                {/* Account Details Card */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 className="font-bold text-gray-900 text-base">بيانات الحساب</h3>
                        <p className="text-xs text-gray-500 mt-1">حدّث معلومات حسابك وبريدك الإلكتروني الشخصي.</p>
                    </div>

                    <form onSubmit={handleProfileSubmit} className="p-5 space-y-4">
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">الاسم</label>
                            <input
                                type="text"
                                value={profileData.name}
                                onChange={(e) => setProfileData('name', e.target.value)}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    profileErrors.name ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                required
                            />
                            {profileErrors.name && (
                                <p className="text-xs text-red-600 mt-1">{profileErrors.name}</p>
                            )}
                        </div>

                        <div>
                            <div className="flex items-center justify-between mb-1.5">
                                <label className="block text-sm font-semibold text-gray-700">البريد الإلكتروني</label>
                                {isGoogleUser && (
                                    <span className="text-[11px] font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200 flex items-center gap-1">
                                        <span>🔒</span> مسجل بواسطة Google
                                    </span>
                                )}
                            </div>
                            <input
                                type="email"
                                value={profileData.email}
                                onChange={(e) => setProfileData('email', e.target.value)}
                                disabled={isGoogleUser}
                                readOnly={isGoogleUser}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none transition-all ${
                                    isGoogleUser 
                                        ? 'bg-gray-100 border-gray-200 text-gray-500 cursor-not-allowed select-none font-medium' 
                                        : (profileErrors.email ? 'border-red-400 bg-red-50/30' : 'border-gray-300 focus:ring-2 focus:ring-orange-400 focus:border-transparent')
                                }`}
                                required
                            />
                            {isGoogleUser ? (
                                <p className="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <span>ℹ️</span> تم تسجيل الدخول عبر حساب جوجل، ولا يمكن تعديل البريد الإلكتروني.
                                </p>
                            ) : (
                                profileErrors.email && <p className="text-xs text-red-600 mt-1">{profileErrors.email}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">رقم الهاتف الشخصي</label>
                            <input
                                type="text"
                                value={profileData.phone}
                                onChange={(e) => setProfileData('phone', e.target.value)}
                                placeholder="مثال: 01012345678"
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    profileErrors.phone ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                dir="ltr"
                            />
                            {profileErrors.phone && (
                                <p className="text-xs text-red-600 mt-1">{profileErrors.phone}</p>
                            )}
                            <p className="text-xs text-gray-400 mt-1">يُستخدم للتواصل الإداري وتنبيهات الحساب.</p>
                        </div>

                        <div className="flex items-center gap-3 pt-2">
                            <button
                                type="submit"
                                disabled={profileProcessing}
                                className="px-5 py-2.5 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm disabled:opacity-60"
                            >
                                {profileProcessing ? 'جاري الحفظ...' : 'حفظ التعديلات ✓'}
                            </button>
                            
                            {profileSuccessful && (
                                <span className="text-sm text-green-600 font-medium animate-fade-in flex items-center gap-1">
                                    <span>✓</span> تم تحديث البيانات بنجاح.
                                </span>
                            )}
                        </div>
                    </form>
                </div>

                {/* Password Change Card */}
                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div className="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div>
                            <h3 className="font-bold text-gray-900 text-base">تغيير كلمة المرور</h3>
                            <p className="text-xs text-gray-500 mt-0.5">احرص على استخدام كلمة مرور قوية وعشوائية لحماية حسابك.</p>
                        </div>
                        <a 
                            href="/forgot-password" 
                            target="_blank"
                            rel="noopener noreferrer"
                            className="text-xs font-bold text-orange-600 hover:text-orange-800 hover:underline flex items-center gap-1 transition-colors bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-200"
                        >
                            <span>🔑</span> نسيت كلمة السر؟
                        </a>
                    </div>

                    <form onSubmit={handlePasswordSubmit} className="p-5 space-y-4">
                        {/* Current Password */}
                        <div>
                            <div className="flex items-center justify-between mb-1.5">
                                <label className="block text-sm font-semibold text-gray-700">كلمة المرور الحالية</label>
                                <a 
                                    href="/forgot-password" 
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="text-xs text-orange-600 font-semibold hover:underline"
                                >
                                    نسيت كلمة السر؟
                                </a>
                            </div>
                            <div className="relative">
                                <input
                                    type={showCurrentPassword ? "text" : "password"}
                                    value={passwordData.current_password}
                                    onChange={(e) => setPasswordData('current_password', e.target.value)}
                                    className={`w-full px-3 py-2.5 pl-10 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                        passwordErrors.current_password ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowCurrentPassword(!showCurrentPassword)}
                                    className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none p-1"
                                    title={showCurrentPassword ? "إخفاء كلمة المرور" : "إظهار كلمة المرور"}
                                >
                                    {showCurrentPassword ? (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    ) : (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    )}
                                </button>
                            </div>
                            {passwordErrors.current_password && (
                                <p className="text-xs text-red-600 mt-1">{passwordErrors.current_password}</p>
                            )}
                        </div>

                        {/* New Password */}
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الجديدة</label>
                            <div className="relative">
                                <input
                                    type={showNewPassword ? "text" : "password"}
                                    value={passwordData.password}
                                    onChange={(e) => setPasswordData('password', e.target.value)}
                                    className={`w-full px-3 py-2.5 pl-10 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                        passwordErrors.password ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowNewPassword(!showNewPassword)}
                                    className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none p-1"
                                    title={showNewPassword ? "إخفاء كلمة المرور" : "إظهار كلمة المرور"}
                                >
                                    {showNewPassword ? (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    ) : (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    )}
                                </button>
                            </div>
                            {passwordErrors.password && (
                                <p className="text-xs text-red-600 mt-1">{passwordErrors.password}</p>
                            )}
                        </div>

                        {/* Confirm Password */}
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">تأكيد كلمة المرور الجديدة</label>
                            <div className="relative">
                                <input
                                    type={showConfirmPassword ? "text" : "password"}
                                    value={passwordData.password_confirmation}
                                    onChange={(e) => setPasswordData('password_confirmation', e.target.value)}
                                    className={`w-full px-3 py-2.5 pl-10 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                        passwordErrors.password_confirmation ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                    className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none p-1"
                                    title={showConfirmPassword ? "إخفاء كلمة المرور" : "إظهار كلمة المرور"}
                                >
                                    {showConfirmPassword ? (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    ) : (
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    )}
                                </button>
                            </div>
                            {passwordErrors.password_confirmation && (
                                <p className="text-xs text-red-600 mt-1">{passwordErrors.password_confirmation}</p>
                            )}
                        </div>

                        <div className="flex items-center gap-3 pt-2">
                            <button
                                type="submit"
                                disabled={passwordProcessing}
                                className="px-5 py-2.5 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm disabled:opacity-60"
                            >
                                {passwordProcessing ? 'جاري الحفظ...' : 'تحديث كلمة المرور ✓'}
                            </button>
                            
                            {passwordSuccessful && (
                                <span className="text-sm text-green-600 font-medium animate-fade-in flex items-center gap-1">
                                    <span>✓</span> تم تحديث كلمة المرور بنجاح.
                                </span>
                            )}
                        </div>
                    </form>
                </div>
            </div>
        </MerchantLayout>
    );
}
