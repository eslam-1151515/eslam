import React, { useState } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function BlacklistIndex({ records, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.search || '');
    const [typeFilter, setTypeFilter] = useState(filters?.type || '');

    // Form for adding a new blacklisted record
    const { data, setData, post, processing, errors, reset } = useForm({
        type: 'phone',
        value: '',
        reason: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/blacklist', {
            onSuccess: () => {
                reset('value', 'reason');
            },
        });
    };

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/blacklist', { search, type: typeFilter }, { preserveState: true });
    };

    const handleReset = () => {
        setSearch('');
        setTypeFilter('');
        router.get('/admin/blacklist', {}, { replace: true });
    };

    const handleDelete = (id) => {
        if (confirm('هل أنت متأكد من إزالة هذه القيمة من القائمة السوداء؟ سيتمكن هذا العميل من الطلب مجدداً.')) {
            router.delete(`/admin/blacklist/${id}`);
        }
    };

    const getTypeBadge = (type) => {
        switch (type) {
            case 'ip':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                        🌐 عنوان IP
                    </span>
                );
            case 'phone':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                        📞 رقم هاتف
                    </span>
                );
            case 'email':
                return (
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                        ✉️ بريد إلكتروني
                    </span>
                );
            default:
                return type;
        }
    };

    return (
        <MerchantLayout title="منع الطلبات الوهمية">
            <Head title="القائمة السوداء وحظر الاحتيال" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* Header */}
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">منع الطلبات الوهمية (القائمة السوداء)</h2>
                    <p className="text-sm text-gray-500 mt-1">
                        حماية متجرك من الاحتيال والسبام عبر إدراج عناوين الـ IP، أرقام الهواتف، أو البريد الإلكتروني للمزعجين في القائمة السوداء.
                    </p>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-red-100 rounded-full text-red-600 text-xs">⚠️</span>
                        {flash.error}
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Add new blacklisted value panel */}
                    <div className="lg:col-span-1 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm h-fit">
                        <h3 className="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span>🚫</span> حظر قيمة جديدة
                        </h3>

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">نوع الحظر</label>
                                <select
                                    value={data.type}
                                    onChange={(e) => setData('type', e.target.value)}
                                    className="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all cursor-pointer"
                                >
                                    <option value="phone">رقم هاتف (Phone)</option>
                                    <option value="ip">عنوان IP (IP Address)</option>
                                    <option value="email">بريد إلكتروني (Email)</option>
                                </select>
                                {errors.type && <p className="text-xs text-red-600 mt-1">{errors.type}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">القيمة المراد حظرها</label>
                                <input
                                    type="text"
                                    value={data.value}
                                    onChange={(e) => setData('value', e.target.value)}
                                    placeholder={
                                        data.type === 'phone' 
                                            ? 'مثال: 01146520922' 
                                            : data.type === 'ip' 
                                                ? 'مثال: 197.34.201.55' 
                                                : 'مثال: spammer@dummy.com'
                                    }
                                    className="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono"
                                    dir="ltr"
                                />
                                {errors.value && <p className="text-xs text-red-600 mt-1">{errors.value}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-1.5">سبب الحظر (اختياري)</label>
                                <textarea
                                    value={data.reason}
                                    onChange={(e) => setData('reason', e.target.value)}
                                    placeholder="مثال: طلبات وهمية متكررة وعدم استلام الطرود"
                                    rows="3"
                                    className="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                />
                                {errors.reason && <p className="text-xs text-red-600 mt-1">{errors.reason}</p>}
                            </div>

                             <button
                                 type="submit"
                                 disabled={processing}
                                 className="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 transition-all cursor-pointer"
                             >
                                 {processing ? 'جاري الإضافة...' : 'حظر القيمة'}
                             </button>
                         </form>
                     </div>
 
                     {/* Records List Column */}
                     <div className="lg:col-span-2 space-y-6">
                         {/* Search & Filter Bar */}
                         <div className="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                             <form onSubmit={handleSearch} className="flex flex-col sm:flex-row gap-3">
                                 <div className="flex-1 relative">
                                     <input
                                         type="text"
                                         placeholder="ابحث عن قيمة محظورة..."
                                         value={search}
                                         onChange={(e) => setSearch(e.target.value)}
                                         className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                     />
                                     <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                         <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                         </svg>
                                     </div>
                                 </div>
 
                                 <div className="w-full sm:w-48">
                                     <select
                                         value={typeFilter}
                                         onChange={(e) => setTypeFilter(e.target.value)}
                                         className="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all cursor-pointer"
                                     >
                                         <option value="">كل الأنواع</option>
                                         <option value="phone">📞 رقم هاتف</option>
                                         <option value="ip">🌐 عنوان IP</option>
                                         <option value="email">✉️ بريد إلكتروني</option>
                                     </select>
                                 </div>
 
                                 <div className="flex gap-2">
                                     <button
                                         type="submit"
                                         className="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold transition-all cursor-pointer shadow-sm"
                                     >
                                         بحث
                                     </button>
                                     <button
                                         type="button"
                                         onClick={handleReset}
                                         className="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all cursor-pointer"
                                     >
                                         إعادة تعيين
                                     </button>
                                 </div>
                             </form>
                         </div>
 
                         {/* Records Table */}
                         <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                             <div className="overflow-x-auto">
                                 <table className="w-full text-right border-collapse">
                                     <thead>
                                         <tr className="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                             <th className="px-6 py-3">القيمة المحظورة</th>
                                             <th className="px-6 py-3">النوع</th>
                                             <th className="px-6 py-3">السبب</th>
                                             <th className="px-6 py-3">تاريخ الحظر</th>
                                             <th className="px-6 py-3 text-left">الإجراءات</th>
                                         </tr>
                                     </thead>
                                     <tbody className="divide-y divide-gray-150 text-sm">
                                         {records.data.length === 0 ? (
                                             <tr>
                                                 <td colSpan="5" className="text-center py-10 text-gray-500 font-medium">
                                                     لا توجد قيم محظورة حالياً.
                                                 </td>
                                             </tr>
                                         ) : (
                                             records.data.map((record) => (
                                                 <tr key={record.id} className="hover:bg-gray-50 transition-colors">
                                                     <td className="px-6 py-4 font-mono font-bold text-gray-900" dir="ltr">
                                                         {record.value}
                                                     </td>
                                                     <td className="px-6 py-4">
                                                         {getTypeBadge(record.type)}
                                                     </td>
                                                     <td className="px-6 py-4 text-gray-600 max-w-xs truncate" title={record.reason}>
                                                         {record.reason || <span className="text-gray-400 italic">بدون سبب</span>}
                                                     </td>
                                                     <td className="px-6 py-4 text-gray-500">
                                                         {new Date(record.created_at).toLocaleDateString('en-US', {
                                                             year: 'numeric',
                                                             month: 'short',
                                                             day: 'numeric'
                                                         })}
                                                     </td>
                                                     <td className="px-6 py-4 text-left">
                                                         <button
                                                             onClick={() => handleDelete(record.id)}
                                                             className="text-red-600 hover:text-red-900 font-semibold text-xs border border-red-100 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-colors cursor-pointer"
                                                         >
                                                             إزالة الحظر
                                                         </button>
                                                     </td>
                                                 </tr>
                                             ))
                                         )}
                                     </tbody>
                                 </table>
                             </div>
 
                             {/* Pagination */}
                             {records.links.length > 3 && (
                                 <div className="p-4 bg-gray-50 border-t border-gray-250 flex items-center justify-center gap-1.5">
                                     {records.links.map((link, idx) => (
                                         <button
                                             key={idx}
                                             onClick={() => link.url && router.get(link.url, { search, type: typeFilter }, { preserveState: true })}
                                             disabled={!link.url || link.active}
                                             className={`px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all ${
                                                 link.active
                                                     ? 'bg-orange-600 text-white border-orange-600'
                                                     : link.url
                                                         ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                                         : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'
                                             }`}
                                             dangerouslySetInnerHTML={{ __html: link.label }}
                                         />
                                     ))}
                                 </div>
                             )}
                         </div>
                     </div>
                 </div>
             </div>
         </MerchantLayout>
     );
 }