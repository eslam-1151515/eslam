import React, { useState } from 'react';
import MerchantLayout from '@/Layouts/MerchantLayout';
import { Head, useForm, usePage } from '@inertiajs/react';

export default function WalletIndex({ wallet_balance, paymentInfo, depositRequests, transactions }) {
    const { flash } = usePage().props;

    const [activeTab, setActiveTab] = useState('deposit'); // deposit, history
    const [quickAmount, setQuickAmount] = useState(300);
    const [copiedVodafone, setCopiedVodafone] = useState(false);
    const [copiedInstapay, setCopiedInstapay] = useState(false);
    const [copiedCodeId, setCopiedCodeId] = useState(null);
    const [previewImage, setPreviewImage] = useState(null);
    const [viewingReceipt, setViewingReceipt] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        amount: 300,
        payment_method: 'vodafone_cash',
        payment_reference: '',
        receipt: null,
    });

    const handleQuickAmount = (val) => {
        setQuickAmount(val);
        if (val !== 'custom') {
            setData('amount', val);
        } else {
            setData('amount', '');
        }
    };

    const handleCopy = (text, type) => {
        navigator.clipboard.writeText(text);
        if (type === 'vodafone') {
            setCopiedVodafone(true);
            setTimeout(() => setCopiedVodafone(false), 2000);
        } else {
            setCopiedInstapay(true);
            setTimeout(() => setCopiedInstapay(false), 2000);
        }
    };

    const handleCopyRefCode = (code, id) => {
        navigator.clipboard.writeText(code);
        setCopiedCodeId(id);
        setTimeout(() => setCopiedCodeId(null), 2000);
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('receipt', file);
            setPreviewImage(URL.createObjectURL(file));
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('merchant.wallet.deposit'), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setPreviewImage(null);
                setQuickAmount(300);
                setActiveTab('history'); // Automatic redirect to requests & transactions tab!
            },
        });
    };

    const whatsappLink = `https://wa.me/2${paymentInfo.support_phone?.replace(/[^0-9]/g, '')}?text=${encodeURIComponent('مرحباً، أود الاستفسار عن طلب شحن محفظتي.')}`;

    return (
        <MerchantLayout title="المحفظة والرصيد">
            <Head title="المحفظة والرصيد" />

            <div className="max-w-6xl space-y-6">
                {/* Header & Balance Card */}
                <div className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                    <div className="absolute left-0 top-0 translate-y-[-20%] translate-x-[-10%] w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div className="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <div className="space-y-1">
                            <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                👛 محفظة المتجر
                            </span>
                            <h1 className="text-2xl sm:text-3xl font-extrabold tracking-tight">إدارة المحفظة والرصيد</h1>
                        </div>
                        <div className="bg-white/10 backdrop-blur-md border border-white/15 p-5 rounded-2xl flex flex-col items-start sm:items-end min-w-[220px]">
                            <span className="text-xs text-indigo-200 font-semibold mb-1">الرصيد الحالي بالمحفظة</span>
                            <div className="flex items-baseline gap-1.5">
                                <span className="text-3xl font-black text-emerald-400 font-mono" dir="ltr">
                                    {Math.round(Number(wallet_balance)).toLocaleString('en-US')}
                                </span>
                                <span className="text-sm font-bold text-emerald-300">ج.م</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Flash Success Message */}
                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-900 text-sm font-bold flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm animate-fade-in">
                        <div className="flex items-center gap-2">
                            <span className="text-base">✓</span>
                            <span>{flash.success}</span>
                        </div>
                        <a
                            href={whatsappLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm self-start sm:self-auto"
                        >
                            <span>💬</span>
                            <span>التواصل مع الدعم الفني</span>
                        </a>
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-rose-50 border-r-4 border-rose-500 rounded-xl text-rose-800 text-sm font-bold flex items-center gap-2 shadow-sm animate-fade-in">
                        <span>⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Navigation Tabs (2 Tabs Only) */}
                <div className="flex border-b border-gray-200 bg-white rounded-xl shadow-sm p-1.5 gap-2">
                    <button
                        onClick={() => setActiveTab('deposit')}
                        className={`flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2 ${
                            activeTab === 'deposit'
                                ? 'bg-indigo-600 text-white shadow-md'
                                : 'text-gray-600 hover:bg-gray-50'
                        }`}
                    >
                        <span>💳</span>
                        <span>اشحن المحفظة</span>
                    </button>
                    <button
                        onClick={() => setActiveTab('history')}
                        className={`flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2 ${
                            activeTab === 'history'
                                ? 'bg-indigo-600 text-white shadow-md'
                                : 'text-gray-600 hover:bg-gray-50'
                        }`}
                    >
                        <span>📋</span>
                        <span>طلبات الشحن والمعاملات ({depositRequests.length})</span>
                    </button>
                </div>

                {/* Tab 1: Charge Wallet Form */}
                {activeTab === 'deposit' && (
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left Column: Transfer Payment Accounts & Info */}
                        <div className="lg:col-span-1 space-y-6">
                            <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                                <h3 className="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 flex items-center gap-2">
                                    <span>📲</span>
                                    <span>أرقام استقبال التحويلات</span>
                                </h3>

                                <p className="text-xs text-gray-500 leading-relaxed">
                                    قم بتحويل المبلغ المطلوب لأحد الأرقام التابعة لنا، ثم املأ نموذج الشحن:
                                </p>

                                {/* Vodafone Cash Card */}
                                <div className="p-4 bg-red-50/70 border border-red-200 rounded-xl space-y-2">
                                    <div className="flex items-center justify-between">
                                        <span className="text-xs font-bold text-red-800 flex items-center gap-1.5">
                                            <span>🔴</span> رقم فودافون كاش (Vodafone Cash)
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between bg-white p-2.5 rounded-lg border border-red-100">
                                        <span className="font-mono font-bold text-gray-900 text-base tracking-wide" dir="ltr">
                                            {paymentInfo.vodafone_cash}
                                        </span>
                                        <button
                                            type="button"
                                            onClick={() => handleCopy(paymentInfo.vodafone_cash, 'vodafone')}
                                            className="px-3 py-1 bg-red-600 text-white rounded text-xs font-bold hover:bg-red-700 transition-colors shadow-sm"
                                        >
                                            {copiedVodafone ? 'تم النسخ ✓' : 'نسخ الرقم 📋'}
                                        </button>
                                    </div>
                                </div>

                                {/* InstaPay Phone Number Card */}
                                <div className="p-4 bg-purple-50/70 border border-purple-200 rounded-xl space-y-2">
                                    <div className="flex items-center justify-between">
                                        <span className="text-xs font-bold text-purple-800 flex items-center gap-1.5">
                                            <span>⚡</span> رقم إنستا باي (InstaPay)
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between bg-white p-2.5 rounded-lg border border-purple-100">
                                        <span className="font-mono font-bold text-gray-900 text-base tracking-wide" dir="ltr">
                                            {paymentInfo.instapay}
                                        </span>
                                        <button
                                            type="button"
                                            onClick={() => handleCopy(paymentInfo.instapay, 'instapay')}
                                            className="px-3 py-1 bg-purple-600 text-white rounded text-xs font-bold hover:bg-purple-700 transition-colors shadow-sm flex-shrink-0"
                                        >
                                            {copiedInstapay ? 'تم النسخ ✓' : 'نسخ الرقم 📋'}
                                        </button>
                                    </div>
                                </div>

                                <div className="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs space-y-1">
                                    <p className="font-bold flex items-center gap-1">
                                        <span>⏰</span> مواعيد عمل المراجعة والشحن:
                                    </p>
                                    <p className="text-amber-800 leading-relaxed font-semibold">
                                        {paymentInfo.work_hours || 'من 10 صباحاً حتى 2 بعد منتصف الليل'}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Right Column: Deposit Form */}
                        <div className="lg:col-span-2 space-y-6">
                            <form onSubmit={handleSubmit} className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
                                <h3 className="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 flex items-center justify-between">
                                    <span className="flex items-center gap-2">
                                        <span>📝</span>
                                        <span>تقديم طلب شحن المحفظة</span>
                                    </span>
                                    <span className="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">
                                        الحد الأدنى: 300 ج.م
                                    </span>
                                </h3>

                                {/* Quick Amounts Selection */}
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-700">اختر مبلغ الشحن السريع:</label>
                                    <div className="grid grid-cols-3 sm:grid-cols-5 gap-2">
                                        {[300, 600, 1000, 2000].map((amt) => (
                                            <button
                                                key={amt}
                                                type="button"
                                                onClick={() => handleQuickAmount(amt)}
                                                className={`py-2.5 px-3 rounded-lg text-xs font-bold border transition-all ${
                                                    quickAmount === amt
                                                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                                        : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100'
                                                }`}
                                            >
                                                {amt} ج.م
                                            </button>
                                        ))}
                                        <button
                                            type="button"
                                            onClick={() => handleQuickAmount('custom')}
                                            className={`py-2.5 px-3 rounded-lg text-xs font-bold border transition-all ${
                                                quickAmount === 'custom'
                                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                                    : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100'
                                            }`}
                                        >
                                            مبلغ آخر ✏️
                                        </button>
                                    </div>
                                </div>

                                {/* Custom Amount Input */}
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">
                                        مبلغ الشحن (جنيه مصري):
                                    </label>
                                    <input
                                        type="number"
                                        min="300"
                                        step="1"
                                        required
                                        value={data.amount}
                                        onChange={(e) => setData('amount', e.target.value)}
                                        placeholder="أدخل مبلغ الشحن (300 كحد أدنى)"
                                        className="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    {errors.amount && <span className="text-xs text-rose-600 mt-1 block font-medium">{errors.amount}</span>}
                                </div>

                                {/* Transfer Method Selection */}
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-700">وسيلة التحويل المستخدمة:</label>
                                    <div className="grid grid-cols-2 gap-3">
                                        <label
                                            className={`flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all ${
                                                data.payment_method === 'vodafone_cash'
                                                    ? 'border-red-500 bg-red-50/50 ring-2 ring-red-500/20'
                                                    : 'border-gray-200 hover:bg-gray-50'
                                            }`}
                                        >
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="vodafone_cash"
                                                checked={data.payment_method === 'vodafone_cash'}
                                                onChange={(e) => setData('payment_method', e.target.value)}
                                                className="text-red-600 focus:ring-red-500"
                                            />
                                            <span className="text-xs font-bold text-gray-800">فودافون كاش 🔴</span>
                                        </label>

                                        <label
                                            className={`flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all ${
                                                data.payment_method === 'instapay'
                                                    ? 'border-purple-500 bg-purple-50/50 ring-2 ring-purple-500/20'
                                                    : 'border-gray-200 hover:bg-gray-50'
                                            }`}
                                        >
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="instapay"
                                                checked={data.payment_method === 'instapay'}
                                                onChange={(e) => setData('payment_method', e.target.value)}
                                                className="text-purple-600 focus:ring-purple-500"
                                            />
                                            <span className="text-xs font-bold text-gray-800">إنستا باي ⚡</span>
                                        </label>
                                    </div>
                                    {errors.payment_method && <span className="text-xs text-rose-600 mt-1 block font-medium">{errors.payment_method}</span>}
                                </div>

                                {/* Sender Number Field */}
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">
                                        الرقم المُنقَل منه (الرقم المحوّل منه):
                                    </label>
                                    <input
                                        type="text"
                                        required
                                        value={data.payment_reference}
                                        onChange={(e) => setData('payment_reference', e.target.value)}
                                        placeholder="أدخل رقم الهاتف المحول منه"
                                        className="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    {errors.payment_reference && <span className="text-xs text-rose-600 mt-1 block font-medium">{errors.payment_reference}</span>}
                                </div>

                                {/* Receipt Image Upload Field */}
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-700">صورة إشعار التحويل (إسكرين شوت):</label>
                                    <div className="flex flex-col sm:flex-row items-center gap-4">
                                        <label className="flex-1 w-full flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/20 transition-all text-center">
                                            <svg className="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span className="text-xs font-bold text-indigo-600">اختر صورة الإشعار للرفع</span>
                                            <span className="text-[11px] text-gray-400 mt-0.5">PNG, JPG حتى 3 ميجابايت</span>
                                            <input
                                                type="file"
                                                accept="image/*"
                                                required
                                                onChange={handleFileChange}
                                                className="hidden"
                                            />
                                        </label>
                                        {previewImage && (
                                            <div className="w-24 h-24 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 flex-shrink-0 shadow-sm relative group">
                                                <img src={previewImage} alt="معاينة الإشعار" className="w-full h-full object-cover" />
                                                <span className="absolute inset-0 bg-black/40 text-white text-[10px] font-bold flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    معاينة
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    {errors.receipt && <span className="text-xs text-rose-600 mt-1 block font-medium">{errors.receipt}</span>}
                                </div>

                                {/* Work Hours Notice */}
                                <div className="p-3.5 bg-indigo-50/70 border border-indigo-100 rounded-xl text-xs text-indigo-900 font-bold flex items-center gap-2">
                                    <span>⏳</span>
                                    <span>مواعيد العمل للمراجعة والتأكيد: من 10 صباحاً حتى 2 بعد منتصف الليل.</span>
                                </div>

                                {/* Submit Button */}
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full py-3.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2"
                                >
                                    {processing ? 'جاري إرسال الطلب...' : 'إرسال طلب الشحن 🚀'}
                                </button>
                            </form>
                        </div>
                    </div>
                )}

                {/* Tab 2: Combined Requests & Transactions History */}
                {activeTab === 'history' && (
                    <div className="space-y-6">
                        {/* Fixed Support Action Bar Above Tables */}
                        <div className="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-xl p-4 text-white flex flex-col sm:flex-row items-center justify-between gap-3 shadow-md">
                            <div className="flex items-center gap-2 text-sm font-extrabold">
                                <span>💬</span>
                                <span>هل لديك أي استفسار حول حالة طلب الشحن أو الرصيد؟</span>
                            </div>
                            <a
                                href={whatsappLink}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="px-5 py-2 bg-white text-emerald-800 rounded-lg text-xs font-black hover:bg-emerald-50 transition-all shadow-sm flex items-center gap-1.5 self-stretch sm:self-auto justify-center"
                            >
                                <span>💬</span>
                                <span>الدعم الفني عبر واتساب</span>
                            </a>
                        </div>

                        {/* Section 1: Deposit Requests */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                            <h3 className="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 flex items-center justify-between">
                                <span className="flex items-center gap-2">
                                    <span>📋</span>
                                    <span>طلبات الشحن المقدمة</span>
                                </span>
                                <span className="text-xs font-normal text-gray-500">إجمالي الطلبات: {depositRequests.length}</span>
                            </h3>

                            {depositRequests.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="w-full text-right border-collapse">
                                        <thead>
                                            <tr className="bg-gray-50 text-gray-500 text-xs font-bold border-b border-gray-100">
                                                <th className="px-4 py-3">الرقم المرجعي (6 أرقام)</th>
                                                <th className="px-4 py-3">المبلغ</th>
                                                <th className="px-4 py-3">وسيلة التحويل</th>
                                                <th className="px-4 py-3">الرقم المحول منه</th>
                                                <th className="px-4 py-3">الإشعار</th>
                                                <th className="px-4 py-3">التاريخ والوقت</th>
                                                <th className="px-4 py-3">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-100 text-xs">
                                            {depositRequests.map((req) => (
                                                <tr key={req.id} className="hover:bg-gray-50/50 transition-colors">
                                                    <td className="px-4 py-3.5">
                                                        <div className="flex items-center gap-1.5" dir="ltr">
                                                            <span className="font-mono font-black text-indigo-900 bg-indigo-50 border border-indigo-200/80 px-2.5 py-1 rounded-lg text-sm select-all">
                                                                {req.reference_code}
                                                            </span>
                                                            <button
                                                                type="button"
                                                                onClick={() => handleCopyRefCode(req.reference_code, req.id)}
                                                                className="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-[11px] font-bold transition-all shadow-sm"
                                                                title="نسخ الرقم المرجعي"
                                                            >
                                                                {copiedCodeId === req.id ? 'تم النسخ ✓' : 'نسخ 📋'}
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td className="px-4 py-3.5 font-extrabold text-gray-900 text-sm">
                                                        {Math.round(req.amount).toLocaleString('en-US')} ج.م
                                                    </td>
                                                    <td className="px-4 py-3.5 font-bold text-gray-700">
                                                        {req.payment_method === 'vodafone_cash' ? 'فودافون كاش 🔴' : 'إنستا باي ⚡'}
                                                    </td>
                                                    <td className="px-4 py-3.5 font-mono font-bold text-gray-800" dir="ltr">{req.payment_reference}</td>
                                                    <td className="px-4 py-3.5">
                                                        {req.receipt_url ? (
                                                            <button
                                                                type="button"
                                                                onClick={() => setViewingReceipt(req.receipt_url)}
                                                                className="text-xs font-bold text-indigo-600 hover:underline"
                                                            >
                                                                عرض الإشعار 🖼️
                                                            </button>
                                                        ) : (
                                                            <span className="text-gray-400">-</span>
                                                        )}
                                                    </td>
                                                    <td className="px-4 py-3.5 text-gray-600">
                                                        <div className="space-y-0.5">
                                                            <span className="font-bold text-gray-800 block">{req.date_formatted}</span>
                                                            <span className="text-[11px] text-gray-400 font-mono block" dir="ltr">{req.time_formatted}</span>
                                                        </div>
                                                    </td>
                                                    <td className="px-4 py-3.5">
                                                        {req.status === 'pending' && (
                                                            <span className="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full font-bold inline-block">
                                                                ⏳ قيد المراجعة
                                                            </span>
                                                        )}
                                                        {req.status === 'approved' && (
                                                            <span className="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full font-bold inline-block">
                                                                ✅ مقبول ومُضاف
                                                            </span>
                                                        )}
                                                        {req.status === 'rejected' && (
                                                            <div className="space-y-1">
                                                                <span className="px-2.5 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded-full font-bold inline-block">
                                                                    ❌ مرفوض
                                                                </span>
                                                                {req.rejection_reason && (
                                                                    <p className="text-[11px] text-rose-600 font-semibold">{req.rejection_reason}</p>
                                                                )}
                                                            </div>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="py-10 text-center text-gray-400 space-y-2">
                                    <span className="text-3xl block">📭</span>
                                    <p className="text-sm font-medium">لا توجد طلبات شحن سابقة حتى الآن.</p>
                                </div>
                            )}
                        </div>

                        {/* Section 2: Wallet Transactions Log */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                            <h3 className="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 flex items-center justify-between">
                                <span className="flex items-center gap-2">
                                    <span>📊</span>
                                    <span>سجل حركات المحفظة المؤكدة</span>
                                </span>
                                <span className="text-xs font-normal text-gray-500">إجمالي المعاملات: {transactions.length}</span>
                            </h3>

                            {transactions.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="w-full text-right border-collapse">
                                        <thead>
                                            <tr className="bg-gray-50 text-gray-500 text-xs font-bold border-b border-gray-100">
                                                <th className="px-4 py-3">نوع العملية</th>
                                                <th className="px-4 py-3">المبلغ</th>
                                                <th className="px-4 py-3">التفاصيل والوصف</th>
                                                <th className="px-4 py-3">التاريخ والوقت</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-100 text-xs">
                                            {transactions.map((tx) => (
                                                <tr key={tx.id} className="hover:bg-gray-50/50 transition-colors">
                                                    <td className="px-4 py-3.5">
                                                        {tx.type === 'credit' ? (
                                                            <span className="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-bold">
                                                                ➕ إيداع
                                                            </span>
                                                        ) : (
                                                            <span className="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full font-bold">
                                                                ➖ خصم
                                                            </span>
                                                        )}
                                                    </td>
                                                    <td className={`px-4 py-3.5 font-mono font-extrabold text-sm ${tx.type === 'credit' ? 'text-emerald-600' : 'text-rose-600'}`}>
                                                        {tx.type === 'credit' ? '+' : '-'}{Math.round(tx.amount).toLocaleString('en-US')} ج.م
                                                    </td>
                                                    <td className="px-4 py-3.5 font-semibold text-gray-800">{tx.description || '-'}</td>
                                                    <td className="px-4 py-3.5 text-gray-600">
                                                        <div className="space-y-0.5">
                                                            <span className="font-bold text-gray-800 block">{tx.date_formatted}</span>
                                                            <span className="text-[11px] text-gray-400 font-mono block" dir="ltr">{tx.time_formatted}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="py-10 text-center text-gray-400 space-y-2">
                                    <span className="text-3xl block">📋</span>
                                    <p className="text-sm font-medium">لا توجد حركات سابقة مؤكدة في المحفظة حتى الآن.</p>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>

            {/* Receipt Modal */}
            {viewingReceipt && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div className="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden p-4 space-y-4">
                        <div className="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h4 className="font-bold text-gray-900 text-sm">صورة إشعار التحويل المرفقة</h4>
                            <button
                                onClick={() => setViewingReceipt(null)}
                                className="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold"
                            >
                                ✕
                            </button>
                        </div>
                        <div className="max-h-[70vh] overflow-y-auto rounded-xl border border-gray-200">
                            <img src={viewingReceipt} alt="الإشعار" className="w-full h-auto object-contain" />
                        </div>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
