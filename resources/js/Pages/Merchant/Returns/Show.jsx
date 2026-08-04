import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ReturnShow({ returnRequest }) {
    const { flash } = usePage().props;
    const [notes, setNotes] = useState(returnRequest.notes || '');
    const [actionType, setActionType] = useState(null); // 'approve', 'reject', 'complete'
    const [isSubmitting, setIsSubmitting] = useState(false);

    const formatCurrency = (amount) => {
        return Math.round(Number(amount)).toLocaleString('en-US') + ' ج.م';
    };

    const statusConfig = {
        pending:   { text: '⏳ قيد الانتظار لمراجعة التاجر', badge: 'bg-yellow-50 text-yellow-700 border-yellow-100' },
        approved:  { text: '👍 مقبول مبدئياً - في انتظار استلام المرتجع', badge: 'bg-blue-50 text-blue-700 border-blue-100' },
        completed: { text: '✅ مكتمل - تم استلام المرتجع وتعويض العميل مالياً', badge: 'bg-green-50 text-green-700 border-green-100' },
        rejected:  { text: '❌ مرفوض من التاجر', badge: 'bg-red-50 text-red-700 border-red-100' },
    };

    const handleAction = (e) => {
        e.preventDefault();
        if (actionType === 'reject' && !notes.trim()) {
            alert('يرجى كتابة سبب الرفض في الملاحظات.');
            return;
        }

        setIsSubmitting(true);
        const url = `/admin/returns/${returnRequest.id}/${actionType}`;
        router.post(url, { notes }, {
            onFinish: () => {
                setIsSubmitting(false);
                setActionType(null);
            }
        });
    };

    return (
        <MerchantLayout title={`تفاصيل طلب إرجاع الطلب #${returnRequest.order?.reference_number}`}>
            <Head title={`طلب إرجاع #${returnRequest.order?.reference_number}`} />

            <div className="space-y-6">
                {/* Top Navigation / Breadcrumbs */}
                <div className="flex items-center gap-2 text-sm text-gray-500">
                    <Link href="/admin/returns" className="hover:text-indigo-600">طلبات المرتجعات</Link>
                    <span>/</span>
                    <span className="text-gray-900 font-semibold">تفاصيل طلب الإرجاع</span>
                </div>

                {/* Header Card */}
                <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-3">
                            <h2 className="text-xl font-bold text-gray-900">
                                طلب إرجاع للطلب #{returnRequest.order?.reference_number}
                            </h2>
                            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border ${statusConfig[returnRequest.status]?.badge}`}>
                                {statusConfig[returnRequest.status]?.text}
                            </span>
                        </div>
                        <p className="text-xs text-gray-400 mt-1">تاريخ تقديم الطلب: {returnRequest.created_at}</p>
                    </div>

                    <div className="flex gap-2">
                        <Link
                            href={`/admin/orders/${returnRequest.order?.id}`}
                            className="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs font-bold border border-gray-200 transition-colors"
                        >
                            🔗 عرض الطلب الأصلي
                        </Link>
                    </div>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 text-green-800 rounded-xl border border-green-200 text-sm font-semibold">
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 text-red-800 rounded-xl border border-red-200 text-sm font-semibold">
                        {flash.error}
                    </div>
                )}

                {/* Main Content Split Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left: Return Details & Items (2 cols) */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Returned Items */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 className="text-md font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                                🛍️ المنتجات المطلوب إرجاعها
                            </h3>
                            <div className="divide-y divide-gray-100">
                                {returnRequest.items.map((item, index) => (
                                    <div key={index} className="py-4 flex items-center gap-4">
                                        <img
                                            src={item.image_url || '/shop/placeholder.jpg'}
                                            alt={item.name}
                                            className="w-16 h-16 object-cover rounded-lg border border-gray-100 bg-gray-50"
                                            onError={(e) => { e.target.src = '/shop/placeholder.jpg'; }}
                                        />
                                        <div className="flex-1">
                                            <h4 className="font-semibold text-gray-950">{item.name}</h4>
                                            <div className="text-xs text-gray-500 mt-1 flex flex-wrap gap-2">
                                                {item.selectedSize && <span>المقاس: <strong className="text-gray-700">{item.selectedSize}</strong></span>}
                                                {item.selectedColor && <span>اللون: <strong className="text-gray-700">{item.selectedColor}</strong></span>}
                                                <span>السعر الفردي: <strong className="text-gray-700">{formatCurrency(item.price)}</strong></span>
                                            </div>
                                        </div>
                                        <div className="text-left">
                                            <div className="font-bold text-indigo-600">الكمية المرتجعة: {item.quantity}</div>
                                            <div className="text-xs text-gray-400 mt-0.5">الإجمالي: {formatCurrency(item.price * item.quantity)}</div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="bg-emerald-50 p-4 rounded-xl border border-emerald-100 mt-6 flex justify-between items-center">
                                <span className="font-bold text-emerald-950 text-sm">مبلغ التعويض المستحق للعميل:</span>
                                <span className="text-lg font-black text-emerald-700">{formatCurrency(returnRequest.refund_amount)}</span>
                            </div>
                        </div>

                        {/* Return Reason */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 className="text-md font-bold text-gray-950 mb-3">📝 سبب طلب الإرجاع:</h3>
                            <div className="p-4 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-700 whitespace-pre-line">
                                {returnRequest.reason}
                            </div>
                        </div>
                    </div>

                    {/* Right: Customer Info, Original Order, Actions (1 col) */}
                    <div className="space-y-6">
                        {/* Customer Info Card */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 className="text-md font-bold text-gray-950 border-b border-gray-100 pb-3 mb-4">
                                👤 بيانات العميل
                            </h3>
                            <div className="space-y-3 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-gray-500">الاسم:</span>
                                    <span className="font-semibold text-gray-900">{returnRequest.order?.customer_name}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-500">رقم الهاتف:</span>
                                    <a href={`tel:${returnRequest.order?.customer_phone}`} className="font-semibold text-indigo-600 hover:underline">
                                        {returnRequest.order?.customer_phone}
                                    </a>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-500">البريد الإلكتروني:</span>
                                    <span className="font-semibold text-gray-900">{returnRequest.order?.customer_email || 'لا يوجد'}</span>
                                </div>
                                <div className="border-t border-gray-100 pt-3">
                                    <span className="text-gray-500 block mb-1">العنوان بالتفصيل:</span>
                                    <span className="font-semibold text-gray-900 block bg-gray-50 p-2.5 rounded border border-gray-100 text-xs">
                                        {returnRequest.order?.governorate} - {returnRequest.order?.customer_address}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Order Context Card */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 className="text-md font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                                📊 الطلب الأصلي #{returnRequest.order?.reference_number}
                            </h3>
                            <div className="space-y-3 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-gray-500">حالة الطلب الحالية:</span>
                                    <span className="font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded border border-green-150 text-xs">
                                        {returnRequest.order?.status === 'delivered' ? 'تم التوصيل بنجاح ✓' : returnRequest.order?.status}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-500">إجمالي المنتجات:</span>
                                    <span className="font-semibold text-gray-900">{formatCurrency(returnRequest.order?.subtotal)}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-500">تكلفة الشحن الأصلي:</span>
                                    <span className="font-semibold text-gray-900">{formatCurrency(returnRequest.order?.shipping_cost)}</span>
                                </div>
                                <div className="flex justify-between border-t border-gray-100 pt-3">
                                    <span className="font-bold text-gray-900">إجمالي مدفوعات الطلب:</span>
                                    <span className="font-bold text-indigo-700">{formatCurrency(returnRequest.order?.total)}</span>
                                </div>
                            </div>
                        </div>

                        {/* Actions Card */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 className="text-md font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                                ⚙️ اتخاذ قرار بشأن الطلب
                            </h3>

                            {actionType ? (
                                <form onSubmit={handleAction} className="space-y-4">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">
                                            {actionType === 'reject' ? '❌ سبب الرفض (إلزامي للعميل):' : '📝 ملاحظات إضافية (اختياري):'}
                                        </label>
                                        <textarea
                                            value={notes}
                                            onChange={(e) => setNotes(e.target.value)}
                                            rows="3"
                                            required={actionType === 'reject'}
                                            placeholder="اكتب ملاحظاتك أو أسباب القرار هنا..."
                                            className="w-full text-xs border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                                        />
                                    </div>
                                    <div className="flex gap-2">
                                        <button
                                            type="submit"
                                            disabled={isSubmitting}
                                            className={`flex-1 py-2 rounded-lg text-xs font-bold text-white transition-colors ${
                                                actionType === 'reject' ? 'bg-red-600 hover:bg-red-700' : 
                                                actionType === 'approve' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'
                                            }`}
                                        >
                                            {isSubmitting ? 'جاري الحفظ...' : 'تأكيد القرار'}
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setActionType(null)}
                                            className="px-3 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-xs font-bold"
                                        >
                                            إلغاء
                                        </button>
                                    </div>
                                </form>
                            ) : (
                                <div className="space-y-2.5">
                                    {returnRequest.status === 'pending' && (
                                        <>
                                            <button
                                                onClick={() => setActionType('approve')}
                                                className="w-full py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-bold transition-all shadow-sm"
                                            >
                                                👍 قبول مبدئي للطلب
                                            </button>
                                            <button
                                                onClick={() => setActionType('reject')}
                                                className="w-full py-2.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-xs font-bold transition-all border border-red-100"
                                            >
                                                ❌ رفض طلب الإرجاع
                                            </button>
                                        </>
                                    )}

                                    {returnRequest.status === 'approved' && (
                                        <button
                                            onClick={() => setActionType('complete')}
                                            className="w-full py-2.5 bg-green-600 text-white hover:bg-green-700 rounded-lg text-xs font-bold transition-all shadow-sm"
                                        >
                                            ✅ تأكيد استلام المرتجع وإتمام التعويض
                                        </button>
                                    )}

                                    {(returnRequest.status === 'completed' || returnRequest.status === 'rejected') && (
                                        <div className="p-3 bg-gray-50 border border-gray-150 rounded-lg text-xs text-gray-600">
                                            <div className="font-bold text-gray-800 mb-1.5">ملاحظات القرار المسجلة:</div>
                                            <p className="italic">{returnRequest.notes || 'لا توجد ملاحظات.'}</p>
                                        </div>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}

