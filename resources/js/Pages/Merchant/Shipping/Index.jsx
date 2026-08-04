import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

/* ===== Shared Toggle Switch (Moved Outside) ===== */
const ToggleSwitch = ({ checked, onChange }) => (
    <label className="relative inline-flex items-center cursor-pointer" onClick={(e) => { e.preventDefault(); onChange(); }}>
        <input type="checkbox" readOnly checked={checked} className="sr-only peer" />
        <div className={`w-11 h-6 rounded-full peer transition-all duration-200 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all ${
            checked
                ? 'bg-indigo-600 after:translate-x-full after:border-white'
                : 'bg-gray-200'
        }`} />
    </label>
);

/* ===== Modal wrapper (Moved Outside) ===== */
const Modal = ({ title, onClose, children }) => (
    <div
        className="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50"
        onClick={(e) => { if (e.target === e.currentTarget) onClose(); }}
    >
        <div className="relative top-20 mx-auto border-0 shadow-2xl rounded-2xl bg-white max-w-sm animate-in fade-in zoom-in-95 duration-200">
            {/* Gradient header — like backup */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 rounded-t-2xl flex items-center justify-between">
                <h3 className="text-base font-bold">{title}</h3>
                <button
                    onClick={onClose}
                    className="w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors"
                >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            {children}
        </div>
    </div>
);

/* ===== Shared form body (Moved Outside) ===== */
const GovForm = ({ data, setData, errors, processing, onSubmit, onClose, submitLabel }) => (
    <form onSubmit={onSubmit} className="p-4 space-y-4">
        {/* Name */}
        <div>
            <label className="block text-sm font-semibold text-gray-700 mb-1">اسم المحافظة</label>
            <input
                type="text"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                placeholder="مثال: القاهرة"
                className="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-sm"
                required
            />
            {errors.name && <p className="text-red-600 text-xs mt-1">{errors.name}</p>}
        </div>

        {/* Price */}
        <div>
            <label className="block text-sm font-semibold text-gray-700 mb-1">سعر الشحن (جنيه)</label>
            <input
                type="number"
                value={data.price}
                onChange={(e) => setData('price', e.target.value)}
                placeholder="مثال: 50"
                min="0"
                step="1"
                className="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-sm"
                required
            />
            {errors.price && <p className="text-red-600 text-xs mt-1">{errors.price}</p>}
        </div>

        {/* Buttons */}
        <div className="flex gap-2 pt-1">
            <button
                type="submit"
                disabled={processing}
                style={{ background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)', color: 'white' }}
                className="flex-1 font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 disabled:opacity-60 flex items-center justify-center gap-2"
            >
                {processing && (
                    <svg className="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                )}
                {submitLabel}
            </button>
            <button
                type="button"
                onClick={onClose}
                style={{ background: 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)', color: 'white' }}
                className="flex-1 font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200"
            >
                إلغاء
            </button>
        </div>
    </form>
);

export default function ShippingIndex({ governorates }) {
    const { flash } = usePage().props;

    const [editingGov, setEditingGov]       = useState(null);
    const [showEditModal, setShowEditModal] = useState(false);
    const [showAddModal, setShowAddModal]   = useState(false);

    /* ===== Form: Edit ===== */
    const {
        data: editData, setData: setEditData,
        put, processing: editProcessing,
        errors: editErrors, reset: editReset, clearErrors: editClearErrors,
    } = useForm({ name: '', price: '', is_active: true });

    /* ===== Form: Add ===== */
    const {
        data: addData, setData: setAddData,
        post, processing: addProcessing,
        errors: addErrors, reset: addReset, clearErrors: addClearErrors,
    } = useForm({ name: '', price: '', is_active: true });

    /* ===== Handlers ===== */
    const openEditModal = (gov) => {
        editClearErrors();
        setEditingGov(gov);
        setEditData({ name: gov.name, price: gov.price, is_active: gov.is_active });
        setShowEditModal(true);
    };

    const openAddModal = () => {
        addClearErrors();
        addReset();
        setShowAddModal(true);
    };

    const handleToggle = (gov) => {
        router.patch(`/admin/shipping/${gov.id}/toggle`, {}, { preserveScroll: true });
    };

    const handleEditSubmit = (e) => {
        e.preventDefault();
        put(`/admin/shipping/${editingGov.id}`, {
            onSuccess: () => { setShowEditModal(false); editReset(); },
            preserveScroll: true,
        });
    };

    const handleAddSubmit = (e) => {
        e.preventDefault();
        post('/admin/shipping', {
            onSuccess: () => { setShowAddModal(false); addReset(); },
            preserveScroll: true,
        });
    };

    const handleDelete = (gov) => {
        if (!confirm(`هل تريد حذف محافظة "${gov.name}"؟`)) return;
        router.delete(`/admin/shipping/${gov.id}`, { preserveScroll: true });
    };

    return (
        <MerchantLayout title="إدارة أسعار الشحن">
            <Head title="إدارة أسعار الشحن" />

            <div className="space-y-4">
                {/* ===== Header ===== */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 className="text-xl font-semibold text-gray-900">محافظات الشحن</h2>
                        <p className="text-xs text-gray-500 mt-0.5">يمكنك تعديل أسعار الشحن وحالة التفعيل لكل محافظة</p>
                    </div>

                    <button
                        onClick={openAddModal}
                        className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة محافظة
                    </button>
                </div>

                {/* ===== Flash Messages ===== */}
                {flash?.success && (
                    <div className="p-3 bg-green-100 text-green-700 rounded-lg text-sm font-medium flex items-center gap-2">
                        ✓ {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-3 bg-red-100 text-red-700 rounded-lg text-sm font-medium flex items-center gap-2">
                        ⚠️ {flash.error}
                    </div>
                )}

                {/* ===== Table ===== */}
                <div className="bg-white shadow-sm rounded-lg p-3 border border-gray-100">
                    <div className="overflow-x-auto">
                        <table className="w-full text-center text-sm align-middle">
                            <thead>
                                <tr className="border-b bg-gray-50">
                                    <th className="py-3 px-3 text-center text-sm font-bold">#</th>
                                    <th className="py-3 px-3 text-center text-sm font-bold">التحكم</th>
                                    <th className="py-3 px-3 text-center text-sm font-bold">المحافظة</th>
                                    <th className="py-3 px-3 text-center text-sm font-bold">سعر الشحن</th>
                                    <th className="py-3 px-3 text-center text-sm font-bold">الحالة</th>
                                    <th className="py-3 px-3 text-center text-sm font-bold">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                {governorates.length > 0 ? (
                                    governorates.map((gov, i) => (
                                        <tr
                                            key={gov.id}
                                            className={`border-b ${i % 2 === 1 ? 'bg-gray-50' : ''} ${!gov.is_active ? 'opacity-60' : ''} hover:bg-indigo-50/30 transition-colors`}
                                        >
                                            {/* # */}
                                            <td className="py-3 px-3 text-sm text-gray-500">{i + 1}</td>

                                            {/* Toggle */}
                                            <td className="py-3 px-3">
                                                <ToggleSwitch
                                                    checked={gov.is_active}
                                                    onChange={() => handleToggle(gov)}
                                                />
                                            </td>

                                            {/* Name */}
                                            <td className="py-3 px-3">
                                                <div className="font-medium text-gray-900 text-sm">{gov.name}</div>
                                            </td>

                                            {/* Price */}
                                            <td className="py-3 px-3">
                                                <div className="text-gray-900 text-sm">{Math.round(gov.price)} جنيه</div>
                                            </td>

                                            {/* Status text */}
                                            <td className="py-3 px-3">
                                                <span className={`text-sm font-medium ${gov.is_active ? 'text-green-600' : 'text-red-600'}`}>
                                                    {gov.is_active ? 'متاح' : 'غير متاح'}
                                                </span>
                                            </td>

                                            {/* Actions */}
                                            <td className="py-3 px-3">
                                                <div className="flex items-center justify-center gap-1.5">
                                                    <button
                                                        onClick={() => openEditModal(gov)}
                                                        className="px-3 py-1.5 text-sm bg-white text-indigo-700 hover:text-white hover:bg-indigo-700 border border-indigo-700 rounded shadow-sm transition-all"
                                                    >
                                                        تعديل
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(gov)}
                                                        className="px-3 py-1.5 text-sm bg-white text-red-600 hover:text-white hover:bg-red-600 border border-red-400 rounded shadow-sm transition-all"
                                                    >
                                                        حذف
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="py-10 text-center text-gray-500 text-sm">
                                            <div className="flex flex-col items-center gap-2">
                                                <span className="text-3xl opacity-30">📍</span>
                                                <p>لا توجد محافظات مضافة حتى الآن</p>
                                                <button
                                                    onClick={openAddModal}
                                                    className="text-indigo-600 hover:underline text-sm font-semibold mt-1"
                                                >
                                                    + أضف محافظة الآن
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Footer count */}
                    {governorates.length > 0 && (
                        <p className="mt-2 text-xs text-gray-400 text-center">
                            {governorates.length} محافظة — {governorates.filter(g => g.is_active).length} مفعلة
                        </p>
                    )}
                </div>
            </div>

            {/* ===== Edit Modal ===== */}
            {showEditModal && (
                <Modal title={`تعديل شحن ${editingGov?.name}`} onClose={() => setShowEditModal(false)}>
                    <GovForm
                        data={editData}
                        setData={setEditData}
                        errors={editErrors}
                        processing={editProcessing}
                        onSubmit={handleEditSubmit}
                        onClose={() => setShowEditModal(false)}
                        submitLabel="حفظ التغييرات"
                    />
                </Modal>
            )}

            {/* ===== Add Modal ===== */}
            {showAddModal && (
                <Modal title="إضافة محافظة جديدة" onClose={() => setShowAddModal(false)}>
                    <GovForm
                        data={addData}
                        setData={setAddData}
                        errors={addErrors}
                        processing={addProcessing}
                        onSubmit={handleAddSubmit}
                        onClose={() => setShowAddModal(false)}
                        submitLabel="إضافة المحافظة"
                    />
                </Modal>
            )}
        </MerchantLayout>
    );
}
