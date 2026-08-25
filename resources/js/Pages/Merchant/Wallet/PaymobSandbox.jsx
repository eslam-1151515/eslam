import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function PaymobSandbox({ receipt }) {
    const [processing, setProcessing] = useState(false);

    const handleAction = (action) => {
        setProcessing(true);
        router.post(route('merchant.wallet.paymob.sandbox.complete'), {
            receipt_id: receipt.id,
            action: action,
        });
    };

    return (
        <MerchantLayout title="محاكاة الدفع عبر Paymob (Sandbox)">
            <Head title="محاكاة الدفع الإلكتروني" />

            <div className="max-w-xl mx-auto py-8 space-y-6" dir="rtl">
                {/* Sandbox Banner */}
                <div className="bg-gradient-to-r from-indigo-900 to-purple-900 rounded-3xl p-6 text-white shadow-xl text-center space-y-3 relative overflow-hidden">
                    <div className="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-full text-xs font-bold border border-white/30">
                        <span>🧪</span>
                        <span>بيئة الاختبار التجريبية (Paymob Sandbox)</span>
                    </div>

                    <h1 className="text-2xl font-black">بوابة الدفع الإلكتروني التجريبية</h1>
                    <p className="text-xs text-indigo-200 leading-relaxed max-w-md mx-auto">
                        يتم تشغيل هذا الوضع لأن مفاتيح Paymob المباشرة لم تُدخل بعد بالسوبر أدمن. يمكنك محاكاة نجاح الدفع أو فشله لاختبار إضافة الرصيد اللحظية.
                    </p>
                </div>

                {/* Transaction Summary Card */}
                <div className="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-4">
                    <div className="border-b border-gray-100 pb-3 flex items-center justify-between">
                        <span className="text-xs font-bold text-gray-500">تفاصيل العملية</span>
                        <span className="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                            #{receipt.reference_code}
                        </span>
                    </div>

                    <div className="space-y-2.5 text-xs">
                        <div className="flex items-center justify-between text-gray-600">
                            <span>المبلغ المطلوب شحنه:</span>
                            <span className="text-base font-black text-gray-900">{receipt.amount} ج.م</span>
                        </div>

                        <div className="flex items-center justify-between text-gray-600">
                            <span>طريقة الدفع المختارة:</span>
                            <span className="font-bold text-indigo-700">
                                {receipt.method === 'wallet' ? '📱 محفظة إلكترونية (كاش)' : '💳 بطاقة بنكية (فيزا / ماستركارد)'}
                            </span>
                        </div>
                    </div>

                    <div className="pt-4 border-t border-gray-100 space-y-3">
                        <button
                            type="button"
                            disabled={processing}
                            onClick={() => handleAction('success')}
                            className="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer"
                        >
                            <span>✅</span>
                            <span>محاكاة دفع ناجح (إضافة الرصيد فوراً في المحفظة)</span>
                        </button>

                        <button
                            type="button"
                            disabled={processing}
                            onClick={() => handleAction('fail')}
                            className="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer"
                        >
                            <span>❌</span>
                            <span>محاكاة فشل الدفع / رفض البنك</span>
                        </button>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
