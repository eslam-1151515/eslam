import React, { useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ShippingGatewaysIndex({ providers = [] }) {
    const { flash, errors } = usePage().props;
    const [selectedProvider, setSelectedProvider] = useState(null);
    const [emailInput, setEmailInput] = useState('');
    const [passwordInput, setPasswordInput] = useState('');
    const [loading, setLoading] = useState(false);
    const [imageErrors, setImageErrors] = useState({});

    const handleOpenModal = (provider) => {
        setSelectedProvider(provider);
        setEmailInput('');
        setPasswordInput('');
    };

    const handleConnectSubmit = (e) => {
        e.preventDefault();
        if (!selectedProvider) return;

        setLoading(true);
        router.post('/admin/shipping-gateways/connect-account', {
            provider: selectedProvider.id,
            email: emailInput,
            password: passwordInput,
        }, {
            onFinish: () => setLoading(false),
            onSuccess: () => setSelectedProvider(null),
        });
    };

    const handleDisconnect = (providerId) => {
        if (!confirm('هل أنت متأكد من إلغاء ربط شركة الشحن؟')) return;
        router.patch(`/admin/shipping-gateways/${providerId}/toggle`);
    };

    const handleImageError = (providerId) => {
        setImageErrors((prev) => ({ ...prev, [providerId]: true }));
    };

    return (
        <MerchantLayout title="ربط شركات الشحن">
            <Head title="ربط شركات الشحن" />

            <div className="max-w-6xl mx-auto space-y-6" dir="rtl">
                {/* Header Banner */}
                <div className="bg-gradient-to-r from-indigo-900 via-indigo-800 to-blue-900 rounded-2xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                    <div className="relative z-10 space-y-2 max-w-2xl">
                        <div className="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full text-xs font-medium text-amber-300 border border-white/10">
                            🚚 شركاء الشحن المعتمدون في مصر
                        </div>
                        <h1 className="text-2xl md:text-3xl font-bold tracking-tight flex items-center gap-3 flex-wrap">
                            <span>إدارة وربط شركات الشحن تلقائياً</span>
                            <span className="px-3 py-1 bg-amber-400 text-amber-950 text-xs font-extrabold rounded-full shadow-sm">
                                قريباً ⏳
                            </span>
                        </h1>
                        <p className="text-indigo-200 text-sm leading-relaxed">
                            ربط فوري وحقيقي مع شركات الشحن. سجّل دخولك بحساب الشركة أو أنشئ حساباً جديداً واطلع على أسعار وحاسبة الشحن لكل شركة مباشرة.
                        </p>
                    </div>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-sm">
                        <span>✓</span> {flash.success}
                    </div>
                )}

                {/* Gateway Cards Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {providers.map((p) => (
                        <div key={p.id} className="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                            <div className="space-y-4">
                                {/* Header: Logo & Status Badge */}
                                <div className="flex items-center justify-between gap-3 h-14 border-b border-gray-100 pb-3">
                                    <div className="flex items-center gap-3">
                                        {!imageErrors[p.id] && p.logo ? (
                                            <img
                                                src={p.logo}
                                                alt={p.name}
                                                onError={() => handleImageError(p.id)}
                                                className="h-10 max-w-[130px] object-contain"
                                            />
                                        ) : (
                                            <div className="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center font-bold text-indigo-700 text-lg shadow-inner">
                                                {p.id === 'bosta' ? '📦' : (p.id === 'jnt' ? '⚡' : '📮')}
                                            </div>
                                        )}
                                    </div>

                                    <span className={`px-2.5 py-1 text-xs font-semibold rounded-full shrink-0 ${
                                        p.is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600'
                                    }`}>
                                        {p.is_active ? 'نشطة ومربوطة ✓' : 'غير مفعلة'}
                                    </span>
                                </div>

                                {/* Title & Description */}
                                <div>
                                    <h3 className="text-lg font-bold text-gray-900">{p.name}</h3>
                                    <p className="text-xs text-gray-500 mt-1 leading-relaxed">{p.description}</p>

                                    {/* Connected Account Display */}
                                    {p.is_active && p.connected_account && (
                                        <div className="mt-3 p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 font-semibold flex items-center gap-1.5 shadow-sm">
                                            <span>🔗 الحساب المربوط:</span>
                                            <span className="font-bold truncate dir-ltr">{p.connected_account}</span>
                                        </div>
                                    )}
                                </div>

                                {/* Useful External Links (Registration & Pricing) */}
                                <div className="flex flex-col gap-2 pt-2">
                                    {p.register_url && (
                                        <a
                                            href={p.register_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center justify-between px-3 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-xs text-gray-700 font-medium transition-colors"
                                        >
                                            <span className="flex items-center gap-1.5">
                                                <span>📝</span>
                                                <span>ليس لديك حساب؟ (إنشاء حساب جديد)</span>
                                            </span>
                                            <svg className="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    )}

                                    {p.pricing_url && (
                                        <a
                                            href={p.pricing_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center justify-between px-3 py-2 bg-amber-50 hover:bg-amber-100/80 border border-amber-200 rounded-xl text-xs text-amber-800 font-medium transition-colors"
                                        >
                                            <span className="flex items-center gap-1.5">
                                                <span>💰</span>
                                                <span>عرض حاسبة وأسعار الشحن</span>
                                            </span>
                                            <svg className="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    )}
                                </div>
                            </div>

                            {/* Action Buttons */}
                            <div className="pt-4 border-t border-gray-100 mt-5">
                                {!p.is_active ? (
                                    <button
                                        onClick={() => handleOpenModal(p)}
                                        className="w-full py-3 px-4 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm flex items-center justify-center gap-2"
                                    >
                                        <span>🔐</span>
                                        <span>تسجيل الدخول وربط الحساب</span>
                                    </button>
                                ) : (
                                    <button
                                        onClick={() => handleDisconnect(p.id)}
                                        className="w-full py-2.5 px-4 rounded-xl text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 transition-colors flex items-center justify-center gap-1"
                                    >
                                        <span>إلغاء الربط والتعطيل</span>
                                    </button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Modal for User Login & Connect Flow */}
            {selectedProvider && (
                <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" dir="rtl">
                    <div className="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                        <div className="flex items-center justify-between border-b pb-3">
                            <div className="flex items-center gap-2">
                                {selectedProvider.logo && !imageErrors[selectedProvider.id] && (
                                    <img src={selectedProvider.logo} alt="" className="h-6 object-contain" />
                                )}
                                <h3 className="text-lg font-bold text-gray-900">تسجيل الدخول والربط مع {selectedProvider.name}</h3>
                            </div>
                            <button onClick={() => setSelectedProvider(null)} className="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                        </div>

                        <form onSubmit={handleConnectSubmit} className="space-y-4">
                            <p className="text-xs text-gray-500 leading-relaxed">
                                أدخل بيانات حسابك المسجل لدى <strong>{selectedProvider.name}</strong> لإتمام الاتصال والتحقق تلقائياً:
                            </p>

                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1">
                                    البريد الإلكتروني للشركة <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    required
                                    value={emailInput}
                                    onChange={(e) => setEmailInput(e.target.value)}
                                    placeholder="أدخل بريدك الإلكتروني لدى شركة الشحن..."
                                    className="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                {errors?.email && <p className="text-xs text-red-600 mt-1">{errors.email}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1">
                                    كلمة المرور للشركة <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    required
                                    value={passwordInput}
                                    onChange={(e) => setPasswordInput(e.target.value)}
                                    placeholder="أدخل كلمة المرور الخاص بحساب الشحن..."
                                    className="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                {errors?.password && <p className="text-xs text-red-600 mt-1">{errors.password}</p>}
                            </div>

                            <div className="flex items-center justify-between text-xs pt-1">
                                {selectedProvider.register_url && (
                                    <a
                                        href={selectedProvider.register_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-indigo-600 hover:underline font-medium"
                                    >
                                        ليس لديك حساب؟ سجل هنا ↗
                                    </a>
                                )}
                                {selectedProvider.pricing_url && (
                                    <a
                                        href={selectedProvider.pricing_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-amber-700 hover:underline font-medium"
                                    >
                                        حاسبة الأسعار ↗
                                    </a>
                                )}
                            </div>

                            <div className="flex items-center justify-end gap-2 pt-3 border-t">
                                <button
                                    type="button"
                                    onClick={() => setSelectedProvider(null)}
                                    className="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm disabled:opacity-60 flex items-center gap-2"
                                >
                                    {loading ? 'جاري التحقق والربط...' : 'تسجيل الدخول والربط تلقائياً ⚡'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
