import React, { useState } from 'react';
import { Head, Link, usePage, useForm } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function SubscriptionIndex({ subscription, plans, receipts, usage, tenant }) {
    const { flash } = usePage().props;
    const [billingCycle, setBillingCycle] = useState('monthly'); // 'monthly' or 'yearly'
    const [selectedPlan, setSelectedPlan] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [receiptPreview, setReceiptPreview] = useState(null);

    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        plan_id: '',
        payment_method: 'vodafone_cash',
        payment_reference: '',
        amount: '',
        receipt: null,
    });

    const openSubscribeModal = (plan) => {
        setSelectedPlan(plan);
        const price = billingCycle === 'monthly' ? plan.price_monthly : plan.price_yearly;
        setData({
            plan_id: plan.id,
            payment_method: 'vodafone_cash',
            payment_reference: '',
            amount: price,
            receipt: null,
        });
        setReceiptPreview(null);
        clearErrors();
        setShowModal(true);
    };

    const closeSubscribeModal = () => {
        setShowModal(false);
        setSelectedPlan(null);
        reset();
    };

    const handleReceiptChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('receipt', file);
            const reader = new FileReader();
            reader.onload = (ev) => setReceiptPreview(ev.target.result);
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/subscription/subscribe', {
            forceFormData: true,
            onSuccess: () => {
                closeSubscribeModal();
            },
        });
    };

    // Formatter helpers
    const formatCurrency = (amount) => {
        return Math.round(Number(amount)).toLocaleString('en-US') + ' ج.م';
    };

    const getLimitText = (max) => {
        return max >= 9999 ? 'غير محدود' : max.toLocaleString('en-US');
    };

    const getPercentage = (current, max) => {
        if (max >= 9999) return 0;
        if (max === 0) return 100;
        return Math.min(Math.round((current / max) * 100), 100);
    };

    const getStatusBadge = (status) => {
        const statuses = {
            trial: { text: 'فترة تجريبية', color: 'bg-blue-50 text-blue-700 border-blue-200' },
            active: { text: 'نشط', color: 'bg-green-50 text-green-700 border-green-200' },
            expired: { text: 'منتهي', color: 'bg-red-50 text-red-700 border-red-200' },
            suspended: { text: 'موقوف مؤقتاً', color: 'bg-amber-50 text-amber-700 border-amber-200' },
        };
        const s = statuses[status] || { text: status, color: 'bg-gray-50 text-gray-700 border-gray-200' };
        return (
            <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ${s.color}`}>
                {s.text}
            </span>
        );
    };

    const getReceiptStatusBadge = (status) => {
        const statuses = {
            pending: { text: 'قيد المراجعة', color: 'bg-yellow-50 text-yellow-700 border-yellow-200' },
            approved: { text: 'تم التفعيل', color: 'bg-green-50 text-green-700 border-green-200' },
            rejected: { text: 'مرفوض', color: 'bg-red-50 text-red-700 border-red-200' },
        };
        const s = statuses[status] || { text: status, color: 'bg-gray-50 text-gray-700 border-gray-200' };
        return (
            <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border ${s.color}`}>
                {s.text}
            </span>
        );
    };

    return (
        <MerchantLayout title="الاشتراك والفوترة">
            <Head title="الاشتراك والفوترة" />

            <div className="space-y-6">
                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-lg text-green-800 text-sm flex items-center gap-2 shadow-sm animate-pulse">
                        <svg className="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                        </svg>
                        <span className="font-semibold">{flash.success}</span>
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-lg text-red-800 text-sm flex items-center gap-2 shadow-sm">
                        <svg className="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                        </svg>
                        <span className="font-semibold">{flash.error}</span>
                    </div>
                )}

                {/* Dashboard Stats / Current Plan Details */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Active Plan Info */}
                    <div className="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-gray-500 text-xs font-bold uppercase tracking-wider">اشتراكك الحالي</h3>
                                {getStatusBadge(subscription?.status || 'expired')}
                            </div>
                            {subscription ? (
                                <>
                                    <h2 className="text-2xl font-bold text-gray-900 mb-1">{subscription.plan?.name}</h2>
                                    <p className="text-sm text-gray-500 mb-4">{subscription.plan?.description}</p>
                                    <div className="space-y-2 border-t border-gray-100 pt-3 text-sm text-gray-600">
                                        <div className="flex justify-between">
                                            <span>دورة الفوترة:</span>
                                            <span className="font-semibold text-gray-900">
                                                {subscription.billing_cycle === 'yearly' ? 'سنوي' : 'شهري'}
                                            </span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span>التكلفة:</span>
                                            <span className="font-semibold text-gray-900">{formatCurrency(subscription.price)}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span>تاريخ الانتهاء:</span>
                                            <span className="font-semibold text-red-600">
                                                {subscription.status === 'trial' ? subscription.trial_ends_at : subscription.ends_at}
                                            </span>
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <>
                                    <h2 className="text-xl font-bold text-red-600 mb-2">لا يوجد اشتراك نشط</h2>
                                    <p className="text-sm text-gray-500 mb-4">
                                        انتهت فترة اشتراكك. يرجى اختيار إحدى الباقات أدناه وتفعيلها للاستمرار في استخدام المنصة.
                                    </p>
                                </>
                            )}
                        </div>
                    </div>

                    {/* Limit: Products */}
                    <div className="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-3">
                                <h3 className="text-gray-500 text-xs font-bold uppercase tracking-wider">حدود المنتجات المتاحة</h3>
                                <span className="text-sm font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {usage.products.max >= 9999 ? 'مفتوح' : `${getPercentage(usage.products.current, usage.products.max)}%`}
                                </span>
                            </div>
                            <h2 className="text-3xl font-extrabold text-gray-900 mb-1">
                                {Math.round(usage.products.current).toLocaleString('en-US')}{' '}
                                <span className="text-sm font-normal text-gray-400">من {getLimitText(usage.products.max)} منتجات</span>
                            </h2>
                            <p className="text-xs text-gray-500 mt-1">المنتجات النشطة التي تم رفعها في متجرك حالياً.</p>
                        </div>
                        {usage.products.max < 9999 && (
                            <div className="mt-4 w-full bg-gray-100 rounded-full h-2">
                                <div
                                    className={`h-2 rounded-full transition-all duration-500 ${
                                        getPercentage(usage.products.current, usage.products.max) > 85 ? 'bg-red-500' : 'bg-indigo-600'
                                    }`}
                                    style={{ width: `${getPercentage(usage.products.current, usage.products.max)}%` }}
                                />
                            </div>
                        )}
                    </div>

                    {/* Limit: Orders */}
                    <div className="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-3">
                                <h3 className="text-gray-500 text-xs font-bold uppercase tracking-wider">حدود الطلبات المتاحة</h3>
                                <span className="text-sm font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {usage.orders.max >= 9999 ? 'مفتوح' : `${getPercentage(usage.orders.current, usage.orders.max)}%`}
                                </span>
                            </div>
                            <h2 className="text-3xl font-extrabold text-gray-900 mb-1">
                                {Math.round(usage.orders.current).toLocaleString('en-US')}{' '}
                                <span className="text-sm font-normal text-gray-400">من {getLimitText(usage.orders.max)} طلبات</span>
                            </h2>
                            <p className="text-xs text-gray-500 mt-1">إجمالي المبيعات والطلبات التي تمت معالجتها بالمنصة.</p>
                        </div>
                        {usage.orders.max < 9999 && (
                            <div className="mt-4 w-full bg-gray-100 rounded-full h-2">
                                <div
                                    className={`h-2 rounded-full transition-all duration-500 ${
                                        getPercentage(usage.orders.current, usage.orders.max) > 85 ? 'bg-red-500' : 'bg-indigo-600'
                                    }`}
                                    style={{ width: `${getPercentage(usage.orders.current, usage.orders.max)}%` }}
                                />
                            </div>
                        )}
                    </div>
                </div>

                {/* Compare pricing plans */}
                <div className="space-y-6">
                    <div className="text-center max-w-xl mx-auto space-y-2 mt-6">
                        <h2 className="text-2xl font-bold text-gray-900">باقات الاشتراك المتاحة</h2>
                        <p className="text-sm text-gray-500">اختر الباقة الأنسب لحجم تجارتك. يمكنك الترقية أو تجديد الاشتراك في أي وقت.</p>

                        {/* Billing Switch */}
                        <div className="inline-flex items-center p-1 bg-gray-100 rounded-xl mt-4 border border-gray-200">
                            <button
                                type="button"
                                onClick={() => setBillingCycle('monthly')}
                                className={`px-4 py-1.5 rounded-lg text-sm font-medium transition-colors ${
                                    billingCycle === 'monthly' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'
                                }`}
                            >
                                دفع شهري
                            </button>
                            <button
                                type="button"
                                onClick={() => setBillingCycle('yearly')}
                                className={`px-4 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 ${
                                    billingCycle === 'yearly' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'
                                }`}
                            >
                                دفع سنوي
                                <span className="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">
                                    وفر 15%+
                                </span>
                            </button>
                        </div>
                    </div>

                    {/* Pricing Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {plans.map((plan) => {
                            const isCurrent = subscription?.plan?.id === plan.id;
                            const price = billingCycle === 'monthly' ? plan.price_monthly : plan.price_yearly;
                            const cycleText = billingCycle === 'monthly' ? 'شهرياً' : 'سنوياً';
                            const parsedLimits = typeof plan.limits === 'string' ? JSON.parse(plan.limits) : plan.limits;
                            const features = parsedLimits?.features || [];

                            return (
                                <div
                                    key={plan.id}
                                    className={`bg-white rounded-3xl p-6 border-2 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-xl ${
                                        isCurrent
                                            ? 'border-indigo-600 ring-4 ring-indigo-50'
                                            : 'border-gray-200 hover:border-indigo-400'
                                    }`}
                                >
                                    <div>
                                        <div className="flex items-center justify-between mb-2">
                                            <h3 className="text-xl font-bold text-gray-900">{plan.name}</h3>
                                            {isCurrent && (
                                                <span className="bg-indigo-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">
                                                    باقتك الحالية
                                                </span>
                                            )}
                                        </div>
                                        <p className="text-sm text-gray-500 min-h-[40px] mb-4">{plan.description}</p>
                                        <div className="mb-6">
                                            <span className="text-4xl font-extrabold text-gray-900">{formatCurrency(price)}</span>
                                            <span className="text-gray-400 text-sm font-semibold mr-1">/ {cycleText}</span>
                                        </div>

                                        {/* Limits list */}
                                        <ul className="space-y-3 text-sm text-gray-600 mb-6 border-t border-gray-100 pt-4">
                                            <li className="flex items-center gap-2.5">
                                                <svg className="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>أقصى عدد منتجات: <b>{getLimitText(parsedLimits?.max_products ?? 0)}</b></span>
                                            </li>
                                            <li className="flex items-center gap-2.5">
                                                <svg className="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>أقصى عدد طلبات: <b>{getLimitText(parsedLimits?.max_orders ?? 0)}</b></span>
                                            </li>
                                            {features.map((feature, i) => (
                                                <li key={i} className="flex items-center gap-2.5">
                                                    <svg className="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>{feature}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>

                                    <button
                                        type="button"
                                        disabled={isCurrent}
                                        onClick={() => openSubscribeModal(plan)}
                                        className={`w-full py-3 rounded-2xl font-bold text-center transition-all ${
                                            isCurrent
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200'
                                                : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg hover:shadow-indigo-200'
                                        }`}
                                    >
                                        {isCurrent ? 'أنت مشترك بهذه الباقة' : 'اشترك الآن'}
                                    </button>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* Receipts History */}
                <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mt-8">
                    <div className="p-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 className="text-lg font-bold text-gray-900">سجل طلبات الاشتراك اليدوية</h3>
                            <p className="text-xs text-gray-500 mt-0.5">تابع حالة طلبات الترقية والتحويلات اليدوية التي قمت برفعها.</p>
                        </div>
                    </div>

                    {receipts.length === 0 ? (
                        <div className="text-center py-12">
                            <svg className="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <p className="text-gray-500 font-medium">لا توجد طلبات اشتراك سابقة</p>
                            <p className="text-gray-400 text-xs mt-1">تظهر هنا طلبات الدفع عبر فودافون كاش أو انستا باي بعد رفع إيصال الدفع.</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-right">
                                <thead>
                                    <tr className="bg-gray-50 border-b border-gray-200">
                                        <th className="px-5 py-3.5 font-bold text-gray-700">الباقة المطلوبة</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">تاريخ الطلب</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">المبلغ المدفوع</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">طريقة التحويل</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">رقم المرجع/التحويل</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">صورة الإيصال</th>
                                        <th className="px-5 py-3.5 font-bold text-gray-700">حالة الطلب</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {receipts.map((receipt) => (
                                        <tr key={receipt.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-5 py-4 font-semibold text-gray-900">{receipt.plan_name}</td>
                                            <td className="px-5 py-4 text-gray-500">{receipt.created_at}</td>
                                            <td className="px-5 py-4 font-bold text-gray-900">{formatCurrency(receipt.amount)}</td>
                                            <td className="px-5 py-4 text-gray-600">
                                                {receipt.payment_method === 'instapay' ? 'InstaPay' : 'Vodafone Cash'}
                                            </td>
                                            <td className="px-5 py-4 text-gray-600 font-mono">{receipt.payment_reference}</td>
                                            <td className="px-5 py-4">
                                                <a
                                                    href={receipt.receipt_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                                                >
                                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    عرض الإيصال
                                                </a>
                                            </td>
                                            <td className="px-5 py-4">
                                                <div className="flex flex-col gap-1">
                                                    {getReceiptStatusBadge(receipt.status)}
                                                    {receipt.status === 'rejected' && receipt.rejection_reason && (
                                                        <span className="text-xs text-red-600 font-medium max-w-[150px] leading-tight">
                                                            السبب: {receipt.rejection_reason}
                                                        </span>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>

            {/* Manual Payment Receipt Form Modal */}
            {showModal && selectedPlan && (
                <div className="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto" dir="rtl">
                    <div className="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative border border-gray-100 overflow-hidden transform transition-all duration-300">
                        {/* Header */}
                        <div className="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                            <div>
                                <h3 className="text-lg font-bold text-gray-900">رفع إيصال الدفع للباقة</h3>
                                <p className="text-xs text-gray-500 mt-0.5">الباقة المطلوبة: <span className="font-bold text-indigo-600">{selectedPlan.name}</span></p>
                            </div>
                            <button
                                onClick={closeSubscribeModal}
                                className="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {/* Approved wallets/payment details */}
                        <div className="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-4 text-sm text-indigo-900 space-y-2">
                            <h4 className="font-bold flex items-center gap-1.5">
                                <svg className="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                بيانات التحويل المعتمدة للمنصة:
                            </h4>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 border-t border-indigo-100/50">
                                <div>
                                    <p className="text-xs text-indigo-500 font-semibold">فودافون كاش (Vodafone Cash)</p>
                                    <p className="text-base font-bold font-mono text-indigo-950">01012345678</p>
                                </div>
                                <div>
                                    <p className="text-xs text-indigo-500 font-semibold">حساب إنستا باي (InstaPay IPN)</p>
                                    <p className="text-base font-bold font-mono text-indigo-950">fastorder@instapay</p>
                                </div>
                            </div>
                            <div className="pt-2 border-t border-indigo-100/30">
                                <p className="text-xs text-indigo-500 font-semibold">اسم صاحب الحساب:</p>
                                <p className="text-sm font-bold text-indigo-950">شركة فاست أوردر للحلول الرقمية</p>
                            </div>
                            <p className="text-xs text-indigo-700 leading-normal pt-1">
                                * يرجى تحويل القيمة الإجمالية للباقة <b>({formatCurrency(billingCycle === 'monthly' ? selectedPlan.price_monthly : selectedPlan.price_yearly)})</b> ثم كتابة رقم التحويل ورفع صورة الإيصال أدناه.
                            </p>
                        </div>

                        {/* Form */}
                        <form onSubmit={handleSubmit} className="space-y-4">
                            {/* Payment Method */}
                            <div>
                                <label className="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">طريقة الدفع المستخدمة:</label>
                                <div className="grid grid-cols-2 gap-3">
                                    <label className={`border rounded-2xl p-3 flex items-center justify-center gap-2 cursor-pointer transition-all ${
                                        data.payment_method === 'vodafone_cash'
                                            ? 'border-indigo-600 bg-indigo-50/50 text-indigo-950 font-bold'
                                            : 'border-gray-200 hover:bg-gray-50 text-gray-600'
                                    }`}>
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="vodafone_cash"
                                            checked={data.payment_method === 'vodafone_cash'}
                                            onChange={(e) => setData('payment_method', e.target.value)}
                                            className="hidden"
                                        />
                                        <span>Vodafone Cash</span>
                                    </label>
                                    <label className={`border rounded-2xl p-3 flex items-center justify-center gap-2 cursor-pointer transition-all ${
                                        data.payment_method === 'instapay'
                                            ? 'border-indigo-600 bg-indigo-50/50 text-indigo-950 font-bold'
                                            : 'border-gray-200 hover:bg-gray-50 text-gray-600'
                                    }`}>
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="instapay"
                                            checked={data.payment_method === 'instapay'}
                                            onChange={(e) => setData('payment_method', e.target.value)}
                                            className="hidden"
                                        />
                                        <span>InstaPay</span>
                                    </label>
                                </div>
                                {errors.payment_method && <p className="text-xs text-red-500 mt-1">{errors.payment_method}</p>}
                            </div>

                            {/* Amount & Reference */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">المبلغ المحول (ج.م):</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={data.amount}
                                        onChange={(e) => setData('amount', e.target.value)}
                                        className="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-bold text-gray-900"
                                    />
                                    {errors.amount && <p className="text-xs text-red-500 mt-1">{errors.amount}</p>}
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                        {data.payment_method === 'vodafone_cash' ? 'رقم المحفظة المحول منها:' : 'اسم المستخدم / كود العملية:'}
                                    </label>
                                    <input
                                        type="text"
                                        placeholder={data.payment_method === 'vodafone_cash' ? 'مثال: 01012345678' : 'مثال: IPN Ref / TXN ID'}
                                        value={data.payment_reference}
                                        onChange={(e) => setData('payment_reference', e.target.value)}
                                        className="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors font-mono"
                                    />
                                    {errors.payment_reference && <p className="text-xs text-red-500 mt-1">{errors.payment_reference}</p>}
                                </div>
                            </div>

                            {/* Receipt File */}
                            <div>
                                <label className="block text-xs font-bold text-gray-700 mb-1.5">صورة إيصال التحويل البنكي:</label>
                                <div className="border-2 border-dashed border-gray-300 rounded-2xl p-4 text-center cursor-pointer hover:border-indigo-400 transition-colors relative bg-gray-50 flex flex-col items-center justify-center min-h-[140px]">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleReceiptChange}
                                        className="absolute inset-0 opacity-0 cursor-pointer"
                                    />
                                    {receiptPreview ? (
                                        <div className="relative w-full max-h-[180px] overflow-hidden rounded-xl">
                                            <img
                                                src={receiptPreview}
                                                alt="Receipt preview"
                                                className="w-full h-auto max-h-[180px] object-contain rounded-xl"
                                            />
                                            <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                <p className="text-white text-xs font-bold">تغيير الصورة</p>
                                            </div>
                                        </div>
                                    ) : (
                                        <>
                                            <svg className="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span className="text-xs font-bold text-indigo-600 hover:text-indigo-800">اضغط لرفع صورة الإيصال</span>
                                            <span className="text-[10px] text-gray-400 mt-1">JPEG, PNG, JPG (الحد الأقصى 2 ميجابايت)</span>
                                        </>
                                    )}
                                </div>
                                {errors.receipt && <p className="text-xs text-red-500 mt-1">{errors.receipt}</p>}
                            </div>

                            {/* Submit & Cancel Buttons */}
                            <div className="flex gap-3 pt-3 border-t border-gray-100">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="flex-1 bg-indigo-600 text-white py-3 rounded-2xl font-bold text-center hover:bg-indigo-700 shadow-lg hover:shadow-indigo-200 transition-all flex items-center justify-center gap-2"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            جاري الإرسال...
                                        </>
                                    ) : (
                                        'إرسال طلب الاشتراك للمراجعة'
                                    )}
                                </button>
                                <button
                                    type="button"
                                    onClick={closeSubscribeModal}
                                    className="px-6 py-3 border border-gray-300 text-gray-600 rounded-2xl font-bold hover:bg-gray-50 transition-colors"
                                >
                                    إلغاء
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
