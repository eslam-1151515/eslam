import React from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function OrderShow({ order }) {
    const { flash } = usePage().props;

    const handleStatusChange = (newStatus) => {
        router.patch(`/admin/orders/${order.id}/status`, { status: newStatus }, {
            preserveScroll: true,
        });
    };

    const handleCancel = () => {
        if (!confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) return;
        router.patch(`/admin/orders/${order.id}/cancel`, {}, {
            preserveScroll: true,
        });
    };

    const getStatusText = (status) => {
        const statuses = {
            pending: 'في الانتظار',
            confirmed: 'مؤكد',
            shipped: 'في التوصيل',
            delivered: 'تم التسليم',
            cancelled: 'ملغي',
        };
        return statuses[status] || status;
    };

    const getStatusBadgeClass = (status) => {
        const classes = {
            pending: 'bg-yellow-50 text-yellow-700 border-yellow-100',
            confirmed: 'bg-blue-50 text-blue-700 border-blue-100',
            shipped: 'bg-purple-50 text-purple-700 border-purple-100',
            delivered: 'bg-green-50 text-green-700 border-green-100',
            cancelled: 'bg-red-50 text-red-700 border-red-100',
        };
        return classes[status] || 'bg-gray-50 text-gray-700 border-gray-150';
    };

    const formatCurrency = (amount) => {
        return Math.round(Number(amount)).toLocaleString('en-US') + ' ج.م';
    };

    return (
        <MerchantLayout title={`تفاصيل الطلب ${order.reference_number}`}>
            <Head title={`طلب ${order.reference_number}`} />

            <div className="w-full space-y-6">
                {/* Breadcrumb & Navigation */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <nav className="flex items-center gap-2 text-sm text-gray-500">
                        <Link href="/admin/orders" className="hover:text-orange-600 transition-colors">الطلبات</Link>
                        <svg className="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span className="text-gray-800 font-medium">تفاصيل الطلب {order.reference_number}</span>
                    </nav>
                    
                    <div className="flex items-center gap-2">
                        <a
                            href={`/admin/orders/${order.id}/invoice`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm"
                        >
                            <svg className="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            الفاتورة pdf
                        </a>
                    </div>
                </div>

                {/* Status Bar */}
                <div className="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div className="flex items-center gap-3">
                        <span className="text-sm font-semibold text-gray-500">حالة الطلب الحالية:</span>
                        <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border ${getStatusBadgeClass(order.status)}`}>
                            {getStatusText(order.status)}
                        </span>
                    </div>

                    <div className="flex items-center gap-2">
                        <span className="text-xs font-bold text-gray-500">تحديث حالة الطلب:</span>
                        <select
                            value={order.status}
                            onChange={(e) => handleStatusChange(e.target.value)}
                            className="bg-white border border-gray-300 rounded-lg text-xs px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-gray-800"
                        >
                            <option value="pending">في الانتظار</option>
                            <option value="confirmed">مؤكد</option>
                            <option value="shipped">في التوصيل</option>
                            <option value="delivered">تم التسليم</option>
                            <option value="cancelled">ملغي</option>
                        </select>
                    </div>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-lg text-green-800 text-sm font-medium flex items-center gap-2">
                        <span>✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-lg text-red-800 text-sm font-medium flex items-center gap-2">
                        <span>⚠️</span>
                        {flash.error}
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    {/* Left: Items List - takes 3/5 */}
                    <div className="lg:col-span-3 space-y-6">
                        {/* Products Card */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                            <h3 className="font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                                <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                منتجات الطلب
                            </h3>

                            <div className="divide-y divide-gray-100">
                                {order.items.map((item, idx) => (
                                    <div key={idx} className="py-4 flex gap-4 first:pt-0 last:pb-0">
                                        <a
                                            href={`/shop/product.html?id=${item.id}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="w-16 h-16 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0 hover:opacity-80 transition-opacity"
                                        >
                                            {item.image_url ? (
                                                <img
                                                    src={item.image_url}
                                                    alt={item.name}
                                                    className="w-full h-full object-cover"
                                                    onError={(e) => {
                                                        e.currentTarget.onerror = null;
                                                        e.currentTarget.src = 'https://dummyimage.com/150x150/f3f4f6/9ca3af&text=صورة+المنتج';
                                                    }}
                                                />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-xs text-gray-400 font-bold bg-gray-100">صورة</div>
                                            )}
                                        </a>

                                        <div className="flex-1 min-w-0">
                                            <a
                                                href={`/shop/product.html?id=${item.id}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="font-semibold text-gray-900 text-sm hover:text-orange-600 transition-colors block"
                                            >
                                                {item.name}
                                            </a>
                                            <div className="flex flex-wrap gap-2 mt-1">
                                                {item.selectedSize && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                                        مقاس: {item.selectedSize}
                                                    </span>
                                                )}
                                                {item.selectedColor && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                                        لون: {item.selectedColor}
                                                    </span>
                                                )}
                                                {item.options && Object.entries(item.options).map(([k, v]) => v ? (
                                                    <span key={k} className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                                        {k}: {v}
                                                    </span>
                                                ) : null)}
                                                <span className="text-xs text-gray-500 font-medium">الكمية: {item.quantity}</span>
                                            </div>
                                        </div>

                                        <div className="text-left flex-shrink-0">
                                            <p className="font-bold text-gray-900 text-sm">{formatCurrency(item.price)}</p>
                                            <p className="text-xs text-gray-400 mt-0.5">الإجمالي: {formatCurrency(item.total)}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Customer Notes */}
                        {order.notes && (
                            <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-2">
                                <h4 className="font-bold text-gray-900 text-sm">ملاحظات العميل:</h4>
                                <p className="text-sm text-gray-600 bg-gray-50 rounded-lg p-3 border border-gray-100 leading-relaxed">
                                    {order.notes}
                                </p>
                            </div>
                        )}

                        {/* Shipping Integration */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                            <h3 className="font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                                <span className="flex items-center gap-2">
                                    إرسال الشحنة لشركة الشحن
                                    <span className="bg-amber-100 text-amber-800 text-[11px] font-bold px-2 py-0.5 rounded-full border border-amber-200">قريباً</span>
                                </span>
                                <span className="text-xs text-indigo-600 font-normal hidden sm:inline">بوسطة / J&T / البريد</span>
                            </h3>

                            <div className="space-y-3">
                                <p className="text-xs text-gray-500">اختر شركة الشحن لإرسال بيانات الطلب وتوليد البوليسة برقم التتبع:</p>
                                <div className="grid grid-cols-3 gap-3">
                                    <button
                                        type="button"
                                        onClick={() => router.post(`/admin/orders/${order.id}/shipment`, { provider: 'bosta' })}
                                        className="py-2.5 px-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors text-center"
                                    >
                                        📦 بوسطة
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => router.post(`/admin/orders/${order.id}/shipment`, { provider: 'jnt' })}
                                        className="py-2.5 px-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold hover:bg-amber-100 transition-colors text-center"
                                    >
                                        ⚡ J&T Express
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => router.post(`/admin/orders/${order.id}/shipment`, { provider: 'egypt_post' })}
                                        className="py-2.5 px-3 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors text-center"
                                    >
                                        📮 البريد المصري
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Right Sidebar - takes 2/5 */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Customer Details */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                            <h3 className="font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                                <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                بيانات العميل
                            </h3>

                            <div className="space-y-3 text-sm">
                                <div>
                                    <span className="text-xs text-gray-400 block mb-0.5">الاسم:</span>
                                    <span className="font-semibold text-gray-900">{order.customer_name}</span>
                                </div>
                                <div>
                                    <span className="text-xs text-gray-400 block mb-0.5">الهاتف:</span>
                                    <span className="font-mono font-semibold text-gray-900" dir="ltr">{order.customer_phone}</span>
                                </div>
                                <div>
                                    <span className="text-xs text-gray-400 block mb-0.5">المحافظة:</span>
                                    <span className="font-semibold text-gray-900">{order.governorate}</span>
                                </div>
                                <div>
                                    <span className="text-xs text-gray-400 block mb-0.5">العنوان:</span>
                                    <span className="text-gray-700 leading-relaxed block">{order.customer_address}</span>
                                </div>
                            </div>
                        </div>

                        {/* Order Summary */}
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                            <h3 className="font-bold text-gray-900 border-b border-gray-100 pb-3">ملخص الحساب</h3>

                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between text-gray-500">
                                    <span>المجموع الفرعي:</span>
                                    <span className="font-semibold">{formatCurrency(order.subtotal)}</span>
                                </div>
                                <div className="flex justify-between text-gray-500">
                                    <span>تكلفة الشحن:</span>
                                    <span className="font-semibold">{formatCurrency(order.shipping_cost)}</span>
                                </div>
                                <div className="border-t border-gray-100 my-2 pt-2 flex justify-between font-bold text-base text-gray-900">
                                    <span>الإجمالي الكلي:</span>
                                    <span className="text-indigo-600">{formatCurrency(order.total)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}

