const fs = require('fs');
let file = fs.readFileSync('resources/js/Pages/SuperAdmin/Tenants/Show.jsx', 'utf8');

// 1. Add useState, Modal components, etc.
const importsTarget = import { Head, Link, router } from '@inertiajs/react';;
const importsReplacement = import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';;
file = file.replace(importsTarget, importsReplacement);

// 2. Add state inside the component
const componentStartTarget = export default function Show({ tenant, settings }) {
    const toggleStatus = () => {;
const componentStartReplacement = export default function Show({ tenant, settings, plans }) {
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [isWalletModalOpen, setIsWalletModalOpen] = useState(false);
    const [isAssignModalOpen, setIsAssignModalOpen] = useState(false);

    const { data: assignData, setData: setAssignData, post: postAssign, processing: assigning, errors: assignErrors, reset: resetAssign } = useForm({
        plan_id: '',
        ends_at: '',
    });

    const { data: walletData, setData: setWalletData, post: postWallet, processing: walletProcessing, errors: walletErrors, reset: resetWallet } = useForm({
        amount: '',
    });

    const handleAssignSubmit = (e) => {
        e.preventDefault();
        postAssign(route('superadmin.tenants.assign-subscription', tenant.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsAssignModalOpen(false);
                resetAssign();
            },
        });
    };

    const handleWalletSubmit = (e) => {
        e.preventDefault();
        postWallet(route('superadmin.tenants.add-wallet-balance', tenant.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsWalletModalOpen(false);
                resetWallet();
            },
        });
    };

    const handleDelete = () => {
        router.delete(route('superadmin.tenants.destroy', tenant.id), {
            preserveScroll: true,
        });
    };

    const toggleStatus = () => {;
file = file.replace(componentStartTarget, componentStartReplacement);

// 3. Add Wallet Balance to Store Info
const walletInfoTarget =                             <div>
                                <span className="block text-gray-400 font-medium">معرف المتجر UUID</span>
                                <span className="text-xs text-gray-500 font-mono select-all bg-gray-50 p-1.5 rounded block mt-1">
                                    {tenant.uuid}
                                </span>
                            </div>;
const walletInfoReplacement = walletInfoTarget + 
                            <div>
                                <span className="block text-gray-400 font-medium">رصيد المحفظة</span>
                                <div className="flex items-center justify-between gap-2">
                                    <span className="text-emerald-600 font-bold text-lg">{tenant.wallet_balance || '0.00'} ج.م</span>
                                    <button 
                                        onClick={() => setIsWalletModalOpen(true)}
                                        className="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-xs font-semibold hover:bg-indigo-100"
                                    >
                                        إضافة رصيد
                                    </button>
                                </div>
                            </div>;
file = file.replace(walletInfoTarget, walletInfoReplacement);

// 4. Add Delete button and Assign button to Header
const headerTarget =                     <div className="flex items-center gap-3">
                        <button
                            onClick={toggleStatus};
const headerReplacement =                     <div className="flex flex-wrap items-center gap-3">
                        <button
                            onClick={() => setIsAssignModalOpen(true)}
                            className="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg text-sm font-semibold transition-colors"
                        >
                            تعديل الاشتراك
                        </button>
                        <button
                            onClick={() => setIsDeleteModalOpen(true)}
                            className="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-semibold transition-colors"
                        >
                            حذف المتجر
                        </button>
                        <button
                            onClick={toggleStatus};
file = file.replace(headerTarget, headerReplacement);

// 5. Add Modals at the bottom
const modalsTarget =         </SuperAdminLayout>
    );
};
const modalsReplacement = 
            {/* Delete Modal */}
            {isDeleteModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <div className="p-6 border-b border-gray-100">
                            <h3 className="text-lg font-bold text-gray-800">تحذير: حذف المتجر نهائياً</h3>
                        </div>
                        <div className="p-6">
                            <p className="text-gray-600 mb-4">
                                هل أنت متأكد أنك تريد حذف المتجر <strong>{tenant.name}</strong> بجميع بياناته، والمنتجات، والطلبات، وحساب المالك؟
                            </p>
                            <p className="text-red-600 font-semibold text-sm">
                                هذا الإجراء لا يمكن التراجع عنه بأي شكل من الأشكال!
                            </p>
                        </div>
                        <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                            <button
                                onClick={() => setIsDeleteModalOpen(false)}
                                className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors"
                            >
                                إلغاء
                            </button>
                            <button
                                onClick={handleDelete}
                                className="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-md transition-colors"
                            >
                                نعم، احذف المتجر
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Wallet Modal */}
            {isWalletModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <form onSubmit={handleWalletSubmit}>
                            <div className="p-6 border-b border-gray-100">
                                <h3 className="text-lg font-bold text-gray-800">إضافة رصيد للمحفظة</h3>
                            </div>
                            <div className="p-6 space-y-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        المبلغ (جنيه مصري)
                                    </label>
                                    <input
                                        type="number"
                                        required
                                        min="1"
                                        step="1"
                                        value={walletData.amount}
                                        onChange={(e) => setWalletData('amount', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="مثال: 500"
                                    />
                                    {walletErrors.amount && <span className="text-xs text-red-500 mt-1 block">{walletErrors.amount}</span>}
                                </div>
                            </div>
                            <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setIsWalletModalOpen(false);
                                        resetWallet();
                                    }}
                                    className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors"
                                    disabled={walletProcessing}
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={walletProcessing}
                                    className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-md transition-colors disabled:opacity-50"
                                >
                                    {walletProcessing ? 'جاري الإضافة...' : 'إضافة الرصيد'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Assign Subscription Modal */}
            {isAssignModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
                        <form onSubmit={handleAssignSubmit}>
                            <div className="p-6 border-b border-gray-100 flex items-center justify-between">
                                <h3 className="text-lg font-bold text-gray-800">تعديل اشتراك المتجر</h3>
                                <button type="button" onClick={() => setIsAssignModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div className="p-6 space-y-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">اختر الباقة</label>
                                    <select
                                        required
                                        value={assignData.plan_id}
                                        onChange={(e) => setAssignData('plan_id', e.target.value)}
                                        style={{
                                            backgroundImage: 'url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' fill=\\'none\\' viewBox=\\'0 0 20 20\\'%3E%3Cpath stroke=\\'%236B7280\\' stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'m6 8 4 4 4-4\\'/%3E%3C/svg%3E")',
                                            backgroundPosition: 'left 0.75rem center',
                                            backgroundSize: '1.25rem',
                                            backgroundRepeat: 'no-repeat',
                                        }}
                                        className="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm appearance-none"
                                    >
                                        <option value="">-- اختر باقة --</option>
                                        {plans?.map((p) => (
                                            <option key={p.id} value={p.id}>{p.name}</option>
                                        ))}
                                    </select>
                                    {assignErrors.plan_id && <span className="text-xs text-red-500 mt-1 block">{assignErrors.plan_id}</span>}
                                </div>
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">تاريخ انتهاء الاشتراك</label>
                                    <input
                                        type="date"
                                        required
                                        value={assignData.ends_at}
                                        onChange={(e) => setAssignData('ends_at', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                                    />
                                    {assignErrors.ends_at && <span className="text-xs text-red-500 mt-1 block">{assignErrors.ends_at}</span>}
                                </div>
                            </div>
                            <div className="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                                <button type="button" onClick={() => setIsAssignModalOpen(false)} className="px-5 py-2 text-gray-600 hover:bg-gray-200 bg-gray-100 rounded-lg font-semibold transition-colors" disabled={assigning}>
                                    إلغاء
                                </button>
                                <button type="submit" className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow-md transition-colors disabled:opacity-50" disabled={assigning}>
                                    {assigning ? 'جاري الحفظ...' : 'حفظ التعديل'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </SuperAdminLayout>
    );
};
file = file.replace(modalsTarget, modalsReplacement);

fs.writeFileSync('resources/js/Pages/SuperAdmin/Tenants/Show.jsx', file);
