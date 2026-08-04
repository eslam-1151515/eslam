import React from 'react';
import { Head } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function SupportIndex({ contacts }) {
    return (
        <MerchantLayout title="الدعم الفني">
            <Head title="الدعم الفني والخدمة المباشرة" />

            <div className="space-y-8 max-w-5xl mx-auto py-4">
                {/* Hero Header */}
                <div className="relative rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 overflow-hidden shadow-xl border border-white/10">
                    <div className="relative z-10 max-w-2xl space-y-3">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-300 text-xs font-bold">
                            🎧 خدمة العملاء والدعم الفني المباشر
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-black leading-tight text-white">
                            نحن هنا لمساعدتك في نجاح متجرك ومبيعاتك
                        </h1>
                        <p className="text-sm text-gray-300 leading-relaxed">
                            اختر طريقة التواصل المناسبة لك، فريق الدعم الفني جاهز للرد على استفساراتك ومساعدتك في أي خطوة فوراً.
                        </p>
                    </div>
                </div>

                {/* Contacts List */}
                <div className="space-y-4">
                    <h2 className="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span>📲</span> وسائل التواصل المتاحة:
                    </h2>

                    {contacts.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {contacts.map((contact) => {
                                const isWhatsapp = contact.type === 'whatsapp';
                                return (
                                    <div
                                        key={contact.id}
                                        className={`rounded-3xl border-2 p-6 transition-all duration-200 flex flex-col justify-between shadow-sm hover:shadow-md bg-white ${
                                            isWhatsapp
                                                ? 'border-emerald-200 hover:border-emerald-400 hover:bg-emerald-50/20'
                                                : 'border-blue-200 hover:border-blue-400 hover:bg-blue-50/20'
                                        }`}
                                    >
                                        <div className="space-y-4">
                                            <div className="flex items-center justify-between">
                                                <div className={`w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm ${
                                                    isWhatsapp ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600'
                                                }`}>
                                                    {isWhatsapp ? '💬' : '📞'}
                                                </div>
                                                <span className={`px-3 py-1 rounded-full text-xs font-extrabold ${
                                                    isWhatsapp ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'
                                                }`}>
                                                    {isWhatsapp ? 'محادثه واتساب' : 'اتصال هاتفي'}
                                                </span>
                                            </div>

                                            <div>
                                                <h3 className="font-extrabold text-lg text-gray-900">{contact.title}</h3>
                                                <p className="text-sm font-mono font-bold text-gray-500 mt-1 dir-ltr text-right">
                                                    {contact.phone_number}
                                                </p>
                                            </div>
                                        </div>

                                        <div className="mt-6 pt-4 border-t border-gray-100">
                                            <a
                                                href={contact.action_url}
                                                target={isWhatsapp ? "_blank" : "_self"}
                                                rel="noopener noreferrer"
                                                className={`w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-sm font-extrabold text-white transition-all shadow-md active:scale-95 ${
                                                    isWhatsapp
                                                        ? 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-emerald-600/20'
                                                        : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-blue-600/20'
                                                }`}
                                            >
                                                {isWhatsapp ? (
                                                    <>
                                                        <span>تواصل عبر الواتساب الفوري</span>
                                                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.762.459 3.481 1.332 5.001l-1.416 5.169 5.305-1.385c1.464.797 3.119 1.217 4.767 1.217h.004c5.506 0 9.99-4.478 9.99-9.985 0-2.668-1.039-5.176-2.926-7.062a9.924 9.924 0 00-7.066-2.939z" />
                                                        </svg>
                                                    </>
                                                ) : (
                                                    <>
                                                        <span>اتصال تلفوني مباشر</span>
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                        </svg>
                                                    </>
                                                )}
                                            </a>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="bg-white p-8 rounded-3xl border border-gray-200 text-center space-y-3">
                            <div className="text-4xl">🎧</div>
                            <h3 className="font-bold text-base text-gray-800">لا تتوفر أرقام دعم حالياً</h3>
                            <p className="text-xs text-gray-500">سيتم إضافة أرقام الدعم الفني قريباً من قِبل إدارة النظام.</p>
                        </div>
                    )}
                </div>
            </div>
        </MerchantLayout>
    );
}
