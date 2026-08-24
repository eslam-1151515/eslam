import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import SuperAdminLayout from '@/Layouts/SuperAdminLayout';

export default function SupportContactsIndex({ contacts }) {
    const { flash } = usePage().props;
    const [editingContact, setEditingContact] = useState(null);
    const [showModal, setShowModal] = useState(false);

    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        type: 'whatsapp',
        title: '',
        phone_number: '',
        whatsapp_message: 'مرحباً، أحتاج مساعدة في نظام أوردر سيف.',
        is_active: true,
        sort_order: 0,
    });

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingContact(null);
        setData({
            type: 'whatsapp',
            title: '',
            phone_number: '',
            whatsapp_message: 'مرحباً، أحتاج مساعدة في نظام أوردر سيف.',
            is_active: true,
            sort_order: 0,
        });
        setShowModal(true);
    };

    const openEditModal = (contact) => {
        clearErrors();
        setEditingContact(contact);
        setData({
            type: contact.type,
            title: contact.title,
            phone_number: contact.phone_number,
            whatsapp_message: contact.whatsapp_message || '',
            is_active: contact.is_active,
            sort_order: contact.sort_order || 0,
        });
        setShowModal(true);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingContact) {
            put(`/support-contacts/${editingContact.id}`, {
                onSuccess: () => {
                    setShowModal(false);
                    reset();
                },
            });
        } else {
            post('/support-contacts', {
                onSuccess: () => {
                    setShowModal(false);
                    reset();
                },
            });
        }
    };

    const handleToggle = (contact) => {
        router.patch(`/support-contacts/${contact.id}/toggle`, {}, { preserveScroll: true });
    };

    const handleDelete = (contact) => {
        if (confirm(`هل أنت متأكد من حذف رقم الدعم "${contact.title}"؟`)) {
            router.delete(`/support-contacts/${contact.id}`, { preserveScroll: true });
        }
    };

    return (
        <SuperAdminLayout>
            <Head title="أرقام الدعم الفني" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">إدارة أرقام الدعم الفني</h1>
                        <p className="text-xs text-gray-500 mt-1">إضافة وتعديل أرقام الواتساب والاتصال الهاتفي التي تظهر للتجار</p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2"
                    >
                        <span>+</span> إضافة رقم جديد
                    </button>
                </div>

                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-800 text-xs font-bold">
                        {flash.success}
                    </div>
                )}

                {/* Grid of Contacts */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {contacts.map((contact) => (
                        <div
                            key={contact.id}
                            className={`bg-white rounded-2xl border p-5 shadow-sm flex flex-col justify-between transition-all ${
                                contact.is_active ? 'border-gray-200 hover:border-indigo-300' : 'border-gray-200 bg-gray-50 opacity-60'
                            }`}
                        >
                            <div>
                                <div className="flex items-center justify-between mb-3">
                                    <span className={`px-2.5 py-1 rounded-full text-[11px] font-extrabold flex items-center gap-1 ${
                                        contact.type === 'whatsapp' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700'
                                    }`}>
                                        {contact.type === 'whatsapp' ? '💬 واتساب' : '📞 اتصال تلفوني'}
                                    </span>

                                    <button
                                        onClick={() => handleToggle(contact)}
                                        className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                                            contact.is_active ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-700'
                                        }`}
                                    >
                                        {contact.is_active ? 'مفعل' : 'معطل'}
                                    </button>
                                </div>

                                <h3 className="font-bold text-gray-900 text-base mb-1">{contact.title}</h3>
                                <p className="text-sm font-mono font-semibold text-indigo-600 dir-ltr text-right mb-3">{contact.phone_number}</p>

                                {contact.type === 'whatsapp' && (
                                    <div className="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs text-gray-600 mb-3">
                                        <span className="font-bold text-gray-500 block mb-0.5">رسالة الواتساب المجهزة:</span>
                                        <p className="line-clamp-2 italic">"{contact.whatsapp_message || 'مرحباً، أحتاج مساعدة.'}"</p>
                                    </div>
                                )}
                            </div>

                            <div className="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <span className="text-[11px] text-gray-400">الترتيب: {contact.sort_order}</span>
                                <div className="flex items-center gap-2">
                                    <button
                                        onClick={() => openEditModal(contact)}
                                        className="px-3 py-1 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 text-gray-700 text-xs font-bold rounded-lg transition-colors"
                                    >
                                        تعديل
                                    </button>
                                    <button
                                        onClick={() => handleDelete(contact)}
                                        className="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg transition-colors"
                                    >
                                        حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Form Modal */}
                {showModal && (
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div className="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                            <div className="flex items-center justify-between border-b pb-3">
                                <h3 className="font-bold text-base text-gray-900">
                                    {editingContact ? 'تعديل رقم الدعم' : 'إضافة رقم دعم جديد'}
                                </h3>
                                <button onClick={() => setShowModal(false)} className="text-gray-400 hover:text-gray-600">✕</button>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">نوع التواصل</label>
                                    <select
                                        value={data.type}
                                        onChange={(e) => setData('type', e.target.value)}
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 bg-white"
                                    >
                                        <option value="whatsapp">💬 واتساب (WhatsApp)</option>
                                        <option value="phone">📞 اتصال تلفوني (Phone Call)</option>
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">اسم/عنوان الرقم (المسؤول)</label>
                                    <input
                                        type="text"
                                        value={data.title}
                                        onChange={(e) => setData('title', e.target.value)}
                                        placeholder="مثال: الدعم الفني الفوري، مبيعات الاشتراكات"
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500"
                                    />
                                    {errors.title && <p className="text-[11px] text-red-600 mt-1">{errors.title}</p>}
                                </div>

                                <div>
                                    <label className="block text-xs font-bold text-gray-700 mb-1">رقم الهاتف</label>
                                    <input
                                        type="text"
                                        value={data.phone_number}
                                        onChange={(e) => setData('phone_number', e.target.value)}
                                        placeholder="مثال: 01012345678"
                                        className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 dir-ltr text-right"
                                    />
                                    {errors.phone_number && <p className="text-[11px] text-red-600 mt-1">{errors.phone_number}</p>}
                                </div>

                                {data.type === 'whatsapp' && (
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1">نص رسالة الواتساب الافتراضية</label>
                                        <textarea
                                            value={data.whatsapp_message}
                                            onChange={(e) => setData('whatsapp_message', e.target.value)}
                                            rows="3"
                                            placeholder="أدخل النص الحاهز للرسالة التي ستفتح للتاجر فور الضغط..."
                                            className="w-full px-3 py-2 border rounded-xl text-xs focus:ring-2 focus:ring-indigo-500"
                                        ></textarea>
                                    </div>
                                )}

                                <div className="grid grid-cols-2 gap-3">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1">ترتيب الظهور</label>
                                        <input
                                            type="number"
                                            value={data.sort_order}
                                            onChange={(e) => setData('sort_order', parseInt(e.target.value) || 0)}
                                            className="w-full px-3 py-2 border rounded-xl text-xs"
                                        />
                                    </div>
                                    <div className="flex items-center pt-5">
                                        <label className="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">
                                            <input
                                                type="checkbox"
                                                checked={data.is_active}
                                                onChange={(e) => setData('is_active', e.target.checked)}
                                                className="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            />
                                            تفعيل ورؤية التاجر للرقم
                                        </label>
                                    </div>
                                </div>

                                <div className="pt-3 border-t flex justify-end gap-2">
                                    <button
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                        className="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50"
                                    >
                                        إلغاء
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md"
                                    >
                                        {editingContact ? 'حفظ التعديلات' : 'إضافة الرقم'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </SuperAdminLayout>
    );
}
