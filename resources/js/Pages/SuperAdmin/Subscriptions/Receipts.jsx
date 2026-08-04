import React, { useState } from 'react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Receipts({ receipts, tenants, plans, filters }) {
    const safeFilters = filters || {};
    const [statusFilter, setStatusFilter] = useState(safeFilters.status || '');
    const [selectedReceipt, setSelectedReceipt] = useState(null);
    const [rejectionReason, setRejectionReason] = useState('');
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [processing, setProcessing] = useState(false);

    // Modal state for attaching receipt
    const [showAttachModal, setShowAttachModal] = useState(false);
    const [attachData, setAttachData] = useState({
        tenant_id: '',
        plan_id: '',
        amount: '',
        payment_method: '',
        payment_reference: '',
        receipt_file: null,
    });
    const [attachErrors, setAttachErrors] = useState({});
    const [attaching, setAttaching] = useState(false);

    const handleStatusFilterChange = (newStatus) => {
        setStatusFilter(newStatus);
        router.get(
            route('superadmin.subscriptions.receipts'),
            { status: newStatus },
            { preserveState: true, replace: true }
        );
    };

    const handleApprove = (id) => {
        if (confirm('هل أنت متأكد من الموافقة على هذا الإيصال وتفعيل الاشتراك؟')) {
            setProcessing(true);
            router.post(
                route('superadmin.subscriptions.receipts.approve', id),
                {},
                {
                    preserveScroll: true,
                    onFinish: () => setProcessing(false),
                }
            );
        }
    };

    const openRejectModal = (receipt) => {
        setSelectedReceipt(receipt);
        setRejectionReason('');
        setShowRejectModal(true);
    };

    const closeRejectModal = () => {
        setShowRejectModal(false);
        setSelectedReceipt(null);
        setRejectionReason('');
    };

    const handleReject = (e) => {
        e.preventDefault();
        if (!rejectionReason.trim()) {
            alert('يرجى كتابة سبب الرفض');
            return;
        }

        setProcessing(true);
        router.post(
            route('superadmin.subscriptions.receipts.reject', selectedReceipt.id),
            { rejection_reason: rejectionReason },
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeRejectModal();
                },
                onFinish: () => setProcessing(false),
            }
        );
    };

    const handleFileChange = (e) => {
        setAttachData({
            ...attachData,
            receipt_file: e.target.files[0]
        });
    };

    const handleAttachSubmit = (e) => {
        e.preventDefault();
        setAttaching(true);
        router.post(
            route('superadmin.subscriptions.receipts.store'),
            attachData,
            {
                forceFormData: true,
                onError: (errs) => {
                    setAttachErrors(errs);
                    setAttaching(false);
                },
                onSuccess: () => {
                    setShowAttachModal(false);
                    setAttachData({
                        tenant_id: '',
                        plan_id: '',
                        amount: '',
                        payment_method: '',
                        payment_reference: '',
                        receipt_file: null,
                    });
                    setAttachErrors({});
                    setAttaching(false);
                },
            }
        );
    };

    const getPaymentMethodLabel = (method) => {
        const methods = {
            instapay: 'إنستاباي (InstaPay)',
            vodafone_cash: 'فودافون كاش (Vodafone Cash)',
            bank_transfer: 'تحويل بنكي',
            cash: 'نقدي',
        };
        return methods[method] || method;
    };

    return (
        <SuperAdminLayout>
            <Head title="إيصالات الدفع والاشتراكات - لوحة التحكم" />

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {/* Header Section */}
                <div className="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 className="text-xl font-bold text-gray-800">إيصالات الدفع اليدوية</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            مراجعة طلبات الدفع اليدوية المقدمة من أصحاب المتاجر والموافقة عليها أو رفضها.
                        </p>
                    </div>
                    <button
                        onClick={() => setShowAttachModal(true)}
                        className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center justify-center gap-2 self-start md:self-auto"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إرفاق إيصال دفع
                    </button>
                </div>

                {/* Filter Section */}
                <div className="p-6 bg-gray-50/50 border-b border-gray-100 flex flex-wrap gap-2">
                    <button
                        onClick={() => handleStatusFilterChange('')}
                        className={`px-4 py-2 text-xs font-bold rounded-lg border transition-all ${
                            statusFilter === ''
                                ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                        }`}
                    >
                        كل الطلبات
                    </button>
                    <button
                        onClick={() => handleStatusFilterChange('pending')}
                        className={`px-4 py-2 text-xs font-bold rounded-lg border transition-all ${
                            statusFilter === 'pending'
                                ? 'bg-amber-500 text-white border-amber-500 shadow-sm'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                        }`}
                    >
                        قيد المراجعة (المعلقة)
                    </button>
                    <button
                        onClick={() => handleStatusFilterChange('approved')}
                        className={`px-4 py-2 text-xs font-bold rounded-lg border transition-all ${
                            statusFilter === 'approved'
                                ? 'bg-emerald-500 text-white border-emerald-500 shadow-sm'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                        }`}
                    >
                        المقبولة
                    </button>
                    <button
                        onClick={() => handleStatusFilterChange('rejected')}
                        className={`px-4 py-2 text-xs font-bold rounded-lg border transition-all ${
                            statusFilter === 'rejected'
                                ? 'bg-rose-500 text-white border-rose-500 shadow-sm'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                        }`}
                    >
                        المرفوضة
                    </button>
                </div>

                {/* Table Section */}
                <div className="overflow-x-auto">
                    <table className="w-full text-right border-collapse">
                        <thead>
                            <tr className="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                                <th className="px-6 py-4">المتجر</th>
                                <th className="px-6 py-4">الخطة المطلوبة</th>
                                <th className="px-6 py-4">المبلغ المدفوع</th>
                                <th className="px-6 py-4">طريقة الدفع / المرجع</th>
                                <th className="px-6 py-4">تاريخ التقديم</th>
                                <th className="px-6 py-4">الإيصال</th>
                                <th className="px-6 py-4">الحالة</th>
                                <th className="px-6 py-4 text-left">العمليات</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 text-sm">
                            {receipts.data && receipts.data.length > 0 ? (
                                receipts.data.map((receipt) => (
                                    <tr key={receipt.id} className="hover:bg-gray-50/50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div>
                                                <Link 
                                                    href={route('superadmin.tenants.show', receipt.tenant?.id || 0)}
                                                    className="font-bold text-gray-800 hover:text-indigo-600 transition-colors"
                                                >
                                                    {receipt.tenant?.name || 'متجر غير معروف'}
                                                </Link>
                                                <span className="block text-xs text-gray-400 mt-0.5" dir="ltr">
                                                    {receipt.tenant?.email}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="font-semibold text-gray-800">
                                                {receipt.plan?.name || 'خطة غير معروفة'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 font-bold text-indigo-600">
                                            {Math.round(parseFloat(receipt.amount)).toLocaleString('en-US')} ج.م
                                        </td>
                                        <td className="px-6 py-4">
                                            <div>
                                                <span className="font-semibold text-gray-700 block text-xs">
                                                    {getPaymentMethodLabel(receipt.payment_method)}
                                                </span>
                                                {receipt.payment_reference && (
                                                    <span className="font-mono text-xs text-gray-400 select-all block mt-0.5">
                                                        المرجع: {receipt.payment_reference}
                                                    </span>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-xs text-gray-500">
                                            {new Date(receipt.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </td>
                                        <td className="px-6 py-4">
                                            {receipt.receipt_path ? (
                                                <a
                                                    href={`/storage/${receipt.receipt_path}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 underline transition-colors"
                                                >
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    عرض الملف
                                                </a>
                                            ) : (
                                                <span className="text-xs text-gray-400">لا يوجد ملف</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4">
                                            {receipt.status === 'pending' && (
                                                <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                    قيد المراجعة
                                                </span>
                                            )}
                                            {receipt.status === 'approved' && (
                                                <div>
                                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        مقبول
                                                    </span>
                                                    {receipt.approved_by && (
                                                        <span className="block text-[10px] text-gray-400 mt-1">
                                                            بواسطة: {receipt.approved_by?.name || 'المدير'}
                                                        </span>
                                                    )}
                                                </div>
                                            )}
                                            {receipt.status === 'rejected' && (
                                                <div>
                                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                                        مرفوض
                                                    </span>
                                                    {receipt.rejection_reason && (
                                                        <span className="block text-[10px] text-rose-500 font-medium max-w-[150px] truncate mt-1" title={receipt.rejection_reason}>
                                                            السبب: {receipt.rejection_reason}
                                                        </span>
                                                    )}
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-left">
                                            {receipt.status === 'pending' ? (
                                                <div className="flex items-center justify-end gap-2">
                                                    <button
                                                        onClick={() => handleApprove(receipt.id)}
                                                        disabled={processing}
                                                        className="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-md text-xs font-bold transition-colors disabled:opacity-50"
                                                    >
                                                        قبول
                                                    </button>
                                                    <button
                                                        onClick={() => openRejectModal(receipt)}
                                                        disabled={processing}
                                                        className="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-md text-xs font-bold transition-colors disabled:opacity-50"
                                                    >
                                                        رفض
                                                    </button>
                                                </div>
                                            ) : (
                                                <span className="text-xs text-gray-400 font-semibold">-</span>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="8" className="px-6 py-12 text-center text-gray-400">
                                        لا توجد إيصالات دفع مطابقة للتصفية حالياً.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination Section */}
                {receipts.links && receipts.links.length > 3 && (
                    <div className="p-6 border-t border-gray-100 flex items-center justify-between">
                        <div className="text-xs text-gray-500">
                            عرض {receipts.from || 0} إلى {receipts.to || 0} من إجمالي {receipts.total || 0} طلب
                        </div>
                        <div className="flex items-center gap-1">
                            {receipts.links.map((link, idx) => (
                                <Link
                                    key={idx}
                                    href={link.url || '#'}
                                    disabled={!link.url}
                                    className={`px-3 py-1.5 border text-xs font-medium rounded-md transition-all ${
                                        link.active
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'
                                    } ${!link.url ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''}`}
                                    dangerouslySetInnerHTML={{
                                        __html: link.label
                                            .replace('Previous', 'السابق')
                                            .replace('Next', 'التالي')
                                    }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>

            {/* Rejection Modal */}
            {showRejectModal && selectedReceipt && (
                <div className="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        {/* Background overlay */}
                        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onClick={closeRejectModal}></div>

                        {/* Modal panel */}
                        <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div className="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div>
                                <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 text-rose-600">
                                    <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div className="mt-3 text-center sm:mt-5">
                                    <h3 className="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                        رفض إيصال دفع المشترك
                                    </h3>
                                    <div className="mt-2">
                                        <p className="text-sm text-gray-500">
                                            المتجر: <span className="font-bold text-gray-700">{selectedReceipt.tenant?.name}</span> | الخطة: <span className="font-bold text-gray-700">{selectedReceipt.plan?.name}</span>
                                        </p>
                                        <p className="text-xs text-gray-400 mt-1">
                                            يرجى تقديم سبب واضح للرفض ليتمكن صاحب المتجر من قراءته وتصحيح الخطأ أو المحاولة مجدداً.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form onSubmit={handleReject} className="mt-5">
                                <div>
                                    <label htmlFor="rejection_reason" className="block text-sm font-semibold text-gray-700 mb-2">
                                        سبب الرفض:
                                    </label>
                                    <textarea
                                        id="rejection_reason"
                                        rows="3"
                                        required
                                        value={rejectionReason}
                                        onChange={(e) => setRejectionReason(e.target.value)}
                                        placeholder="مثال: صورة الإيصال غير واضحة، أو المبلغ المحول لا يتطابق مع قيمة الاشتراك المطلوبة..."
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                                    ></textarea>
                                </div>

                                <div className="mt-6 flex flex-row-reverse gap-2">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-bold text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:text-sm disabled:opacity-50"
                                    >
                                        تأكيد الرفض
                                    </button>
                                    <button
                                        type="button"
                                        onClick={closeRejectModal}
                                        className="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}

            {/* Attach Receipt Modal */}
            {showAttachModal && (
                <div className="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        {/* Background overlay */}
                        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onClick={() => setShowAttachModal(false)}></div>

                        {/* Modal panel */}
                        <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div className="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div>
                                <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 text-indigo-600">
                                    <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div className="mt-3 text-center sm:mt-5">
                                    <h3 className="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                        إرفاق إيصال دفع جديد
                                    </h3>
                                    <p className="text-xs text-gray-400 mt-1">
                                        تسجيل عملية دفع يدوية لمتجر وتحديد الباقة والمبلغ المحول.
                                    </p>
                                </div>
                            </div>

                            <form onSubmit={handleAttachSubmit} className="mt-5 space-y-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        المتجر
                                    </label>
                                    <select
                                        required
                                        value={attachData.tenant_id}
                                        onChange={(e) => setAttachData({ ...attachData, tenant_id: e.target.value })}
                                        style={{
                                            backgroundImage: `url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E")`,
                                            backgroundPosition: 'left 0.75rem center',
                                            backgroundSize: '1.25rem',
                                            backgroundRepeat: 'no-repeat',
                                        }}
                                        className="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none text-right"
                                    >
                                        <option value="">اختر المتجر...</option>
                                        {(tenants || []).map((tenant) => (
                                            <option key={tenant.id} value={tenant.id}>
                                                {tenant.name} ({tenant.slug}.{typeof window !== 'undefined' ? window.location.host.replace('app.', '') : 'fastorder.localhost'})
                                            </option>
                                        ))}
                                    </select>
                                    {attachErrors.tenant_id && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.tenant_id}</span>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        خطة الاشتراك (الباقة)
                                    </label>
                                    <select
                                        required
                                        value={attachData.plan_id}
                                        onChange={(e) => {
                                            const selectedPlan = (plans || []).find(p => String(p.id) === String(e.target.value));
                                            setAttachData({
                                                ...attachData,
                                                plan_id: e.target.value,
                                                amount: selectedPlan ? selectedPlan.price_monthly : ''
                                            });
                                        }}
                                        style={{
                                            backgroundImage: `url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E")`,
                                            backgroundPosition: 'left 0.75rem center',
                                            backgroundSize: '1.25rem',
                                            backgroundRepeat: 'no-repeat',
                                        }}
                                        className="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none text-right"
                                    >
                                        <option value="">اختر الباقة...</option>
                                        {(plans || []).map((plan) => (
                                            <option key={plan.id} value={plan.id}>
                                                {plan.name} ({Math.round(parseFloat(plan.price_monthly)).toLocaleString('en-US')} ج.م / شهر)
                                            </option>
                                        ))}
                                    </select>
                                    {attachErrors.plan_id && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.plan_id}</span>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        المبلغ المدفوع (ج.م)
                                    </label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        required
                                        value={attachData.amount}
                                        onChange={(e) => setAttachData({ ...attachData, amount: e.target.value })}
                                        placeholder="مثال: 500"
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-right"
                                    />
                                    {attachErrors.amount && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.amount}</span>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        طريقة الدفع
                                    </label>
                                    <select
                                        required
                                        value={attachData.payment_method}
                                        onChange={(e) => setAttachData({ ...attachData, payment_method: e.target.value })}
                                        style={{
                                            backgroundImage: `url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E")`,
                                            backgroundPosition: 'left 0.75rem center',
                                            backgroundSize: '1.25rem',
                                            backgroundRepeat: 'no-repeat',
                                        }}
                                        className="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none text-right"
                                    >
                                        <option value="">اختر طريقة الدفع...</option>
                                        <option value="instapay">إنستاباي (InstaPay)</option>
                                        <option value="vodafone_cash">فودافون كاش (Vodafone Cash)</option>
                                        <option value="bank_transfer">تحويل بنكي</option>
                                        <option value="cash">نقدي</option>
                                    </select>
                                    {attachErrors.payment_method && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.payment_method}</span>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        الرقم المرجعي للمعاملة (اختياري)
                                    </label>
                                    <input
                                        type="text"
                                        value={attachData.payment_reference}
                                        onChange={(e) => setAttachData({ ...attachData, payment_reference: e.target.value })}
                                        placeholder="رقم العملية أو المرجع البنكي..."
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-right"
                                    />
                                    {attachErrors.payment_reference && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.payment_reference}</span>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        صورة إيصال الدفع (jpg, png, jpeg)
                                    </label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleFileChange}
                                        className="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm file:ml-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all text-right"
                                    />
                                    {attachErrors.receipt_file && (
                                        <span className="text-xs text-rose-500 mt-1 block">{attachErrors.receipt_file}</span>
                                    )}
                                </div>

                                <div className="mt-6 flex flex-row-reverse gap-2">
                                    <button
                                        type="submit"
                                        disabled={attaching}
                                        className="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm disabled:opacity-50"
                                    >
                                        {attaching ? 'جاري الإرسال...' : 'إرفاق الإيصال'}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setShowAttachModal(false);
                                            setAttachErrors({});
                                        }}
                                        className="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </SuperAdminLayout>
    );
}

