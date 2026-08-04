import React from 'react';
import { Head } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ShippingGateways() {
    return (
        <MerchantLayout title="ربط شركات الشحن">
            <Head title="ربط شركات الشحن - قريباً" />

            <div className="min-h-[70vh] flex flex-col items-center justify-center text-center p-6 bg-white rounded-2xl border border-gray-200 shadow-sm my-4">
                <div className="w-20 h-20 bg-indigo-50 border border-indigo-100 rounded-3xl flex items-center justify-center text-4xl mb-6 shadow-inner">
                    🚚
                </div>
                <span className="px-3.5 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold mb-3">
                    ميزة قادمة
                </span>
                <h2 className="text-3xl font-extrabold text-gray-900 mb-3">ربط شركات الشحن</h2>
                <p className="text-gray-500 max-w-md text-sm leading-relaxed mb-6">
                    نحن نعمل على إتاحة الربط المباشر مع أكبر شركات الشحن (بوسطة، أرامكس، المنسي وغيرها) لإنشاء وتتبع شحناتك بضغطة زر.
                </p>
                <div className="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md">
                    قريباً 🚀
                </div>
            </div>
        </MerchantLayout>
    );
}
