import React from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ProfileEdit({ user: propUser }) {
    const { auth } = usePage().props;
    const user = propUser || auth?.user;

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
            onSuccess: () => resetPassword(),
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
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">البريد الإلكتروني</label>
                            <input
                                type="email"
                                value={profileData.email}
                                onChange={(e) => setProfileData('email', e.target.value)}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    profileErrors.email ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                required
                            />
                            {profileErrors.email && (
                                <p className="text-xs text-red-600 mt-1">{profileErrors.email}</p>
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
                    <div className="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 className="font-bold text-gray-900 text-base">تغيير كلمة المرور</h3>
                        <p className="text-xs text-gray-500 mt-1">احرص على استخدام كلمة مرور قوية وعشوائية لحماية حسابك.</p>
                    </div>

                    <form onSubmit={handlePasswordSubmit} className="p-5 space-y-4">
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الحالية</label>
                            <input
                                type="password"
                                value={passwordData.current_password}
                                onChange={(e) => setPasswordData('current_password', e.target.value)}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    passwordErrors.current_password ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                required
                            />
                            {passwordErrors.current_password && (
                                <p className="text-xs text-red-600 mt-1">{passwordErrors.current_password}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">كلمة المرور الجديدة</label>
                            <input
                                type="password"
                                value={passwordData.password}
                                onChange={(e) => setPasswordData('password', e.target.value)}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    passwordErrors.password ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                required
                            />
                            {passwordErrors.password && (
                                <p className="text-xs text-red-600 mt-1">{passwordErrors.password}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-1.5">تأكيد كلمة المرور الجديدة</label>
                            <input
                                type="password"
                                value={passwordData.password_confirmation}
                                onChange={(e) => setPasswordData('password_confirmation', e.target.value)}
                                className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent ${
                                    passwordErrors.password_confirmation ? 'border-red-400 bg-red-50/30' : 'border-gray-300'
                                }`}
                                required
                            />
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
