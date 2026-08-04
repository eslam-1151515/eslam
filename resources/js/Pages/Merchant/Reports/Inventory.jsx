import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function InventoryReport({ lowStockProducts, movements, allProducts, stats }) {
    const [isAdjustModalOpen, setIsAdjustModalOpen] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(null);
    const [searchQuery, setSearchQuery] = useState('');
    const [typeFilter, setTypeFilter] = useState('all');

    // Form structure for manual stock adjustment
    const { data, setData, post, processing, reset, errors } = useForm({
        product_id: '',
        quantity: '',
        type: 'adjustment',
        description: '',
    });

    const openAdjustModal = (product = null) => {
        reset();
        if (product) {
            setData('product_id', product.id);
            setSelectedProduct(product);
        } else {
            setSelectedProduct(null);
        }
        setIsAdjustModalOpen(true);
    };

    const closeAdjustModal = () => {
        setIsAdjustModalOpen(false);
        reset();
    };

    const handleAdjustSubmit = (e) => {
        e.preventDefault();
        post('/admin/reports/inventory/adjust', {
            onSuccess: () => {
                closeAdjustModal();
            },
        });
    };

    const getMovementTypeBadge = (type) => {
        const types = {
            in: { label: 'شحن وارد', color: 'bg-emerald-50 text-emerald-700 border-emerald-100 border' },
            out: { label: 'مبيعات', color: 'bg-rose-50 text-rose-700 border-rose-100 border' },
            adjustment: { label: 'تعديل يدوي', color: 'bg-amber-50 text-amber-700 border-amber-100 border' },
            return: { label: 'مرتجع', color: 'bg-indigo-50 text-indigo-700 border-indigo-100 border' },
        };
        const t = types[type] || { label: type, color: 'bg-gray-50 text-gray-700 border-gray-100 border' };
        return <span className={`px-2.5 py-1 rounded-lg text-xs font-semibold ${t.color}`}>{t.label}</span>;
    };

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EGP', maximumFractionDigits: 0 }).format(Math.round(value)).replace('EGP', 'ج.م');
    };

    const formatNumber = (value) => {
        return new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(value));
    };

    // Filter movements list if search is active
    const filteredMovements = movements.data.filter((m) => {
        const matchesSearch = searchQuery === '' || 
            (m.product?.name && m.product.name.toLowerCase().includes(searchQuery.toLowerCase())) ||
            (m.description && m.description.toLowerCase().includes(searchQuery.toLowerCase()));
        
        const matchesType = typeFilter === 'all' || m.type === typeFilter;
        
        return matchesSearch && matchesType;
    });

    return (
        <MerchantLayout title="تقرير حركة وإدارة المخزون">
            <Head title="تقرير المخزون المتقدم" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">نظام إدارة وتتبع المخزون المتقدم</h2>
                        <p className="text-sm text-gray-500 mt-1">تتبع حركة المنتجات، القيمة الحالية للمخزون، وتنبيهات النقص الفوري.</p>
                    </div>
                    <button
                        onClick={() => openAdjustModal()}
                        className="flex items-center justify-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition-all shadow-sm self-start sm:self-auto"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        تعديل مخزون يدوي
                    </button>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {/* Valuation */}
                    <div className="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-semibold text-gray-400">إجمالي قيمة المخزون</p>
                                <h3 className="text-xl font-bold text-gray-900 mt-2">{formatCurrency(stats.total_valuation)}</h3>
                            </div>
                            <div className="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {/* Total Stock Items */}
                    <div className="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-semibold text-gray-400">إجمالي كمية القطع</p>
                                <h3 className="text-xl font-bold text-gray-900 mt-2">{formatNumber(stats.total_items)} قطعة</h3>
                            </div>
                            <div className="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {/* Low Stock Warning Count */}
                    <div className="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-semibold text-gray-400">منتجات أوشكت على النفاد</p>
                                <h3 className="text-xl font-bold text-amber-600 mt-2">{formatNumber(lowStockProducts.length)} منتج</h3>
                            </div>
                            <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {/* Out of Stock Count */}
                    <div className="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-semibold text-gray-400">منتجات نفدت بالكامل</p>
                                <h3 className="text-xl font-bold text-rose-600 mt-2">{formatNumber(stats.out_of_stock)} منتج</h3>
                            </div>
                            <div className="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Low Stock Alerts Table */}
                    <div className="lg:col-span-1 bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-4">
                        <div className="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 className="font-bold text-gray-900 flex items-center gap-2">
                                <span className="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                                تنبيهات انخفاض المخزون
                            </h3>
                            <span className="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                {lowStockProducts.length} تنبيهات
                            </span>
                        </div>

                        {lowStockProducts.length === 0 ? (
                            <div className="py-8 text-center text-gray-400 text-sm">
                                🎉 جميع المنتجات بمستويات مخزون آمنة!
                            </div>
                        ) : (
                            <div className="space-y-3.5 max-h-[460px] overflow-y-auto pr-1">
                                {lowStockProducts.map((p) => (
                                    <div key={p.id} className="p-3 rounded-xl border border-gray-100 hover:bg-gray-50/50 transition-all flex items-center justify-between gap-3">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                                {p.image ? (
                                                    <img src={p.image} className="w-full h-full object-cover" alt={p.name} />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center text-gray-400 font-bold">📦</div>
                                                )}
                                            </div>
                                            <div>
                                                <h4 className="text-xs font-bold text-gray-800 line-clamp-1">{p.name}</h4>
                                                <p className="text-[10px] text-gray-400 mt-0.5">الحد المسموح: {p.low_stock_threshold} | السعر: {formatCurrency(p.price)}</p>
                                            </div>
                                        </div>
                                        <div className="text-left flex flex-col items-end gap-1.5">
                                            <span className={`px-2 py-0.5 rounded text-xs font-bold ${p.stock === 0 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-700'}`}>
                                                {p.stock === 0 ? 'نفد' : `${p.stock} قطعة`}
                                            </span>
                                            <button
                                                onClick={() => openAdjustModal(p)}
                                                className="text-[11px] font-bold text-orange-600 hover:text-orange-700 transition-colors"
                                            >
                                                تزويد سريع
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Stock Movements Log */}
                    <div className="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-4">
                        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-3 border-b border-gray-100">
                            <div>
                                <h3 className="font-bold text-gray-900">سجل حركة المخزون (Log)</h3>
                                <p className="text-xs text-gray-400 mt-0.5">العمليات الأخيرة الواردة والصادرة على السلع.</p>
                            </div>

                            {/* Filters */}
                            <div className="flex items-center gap-2">
                                <select
                                    value={typeFilter}
                                    onChange={(e) => setTypeFilter(e.target.value)}
                                    className="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-1 focus:ring-orange-500 bg-white"
                                >
                                    <option value="all">كل الحركات</option>
                                    <option value="in">الوارد فقط</option>
                                    <option value="out">الصادر فقط</option>
                                    <option value="adjustment">التعديلات</option>
                                    <option value="return">المرتجعات</option>
                                </select>
                                <input
                                    type="text"
                                    placeholder="بحث في المنتجات..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-1 focus:ring-orange-500 w-36 sm:w-48 bg-white"
                                />
                            </div>
                        </div>

                        {filteredMovements.length === 0 ? (
                            <div className="py-20 text-center text-gray-400 text-sm">
                                📜 لا يوجد أي حركات مخزون مسجلة مطابقة للبحث.
                            </div>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm text-right text-gray-500">
                                    <thead className="text-xs text-gray-700 bg-gray-50/70 rounded-lg">
                                        <tr>
                                            <th className="px-4 py-3 font-semibold text-gray-700">المنتج</th>
                                            <th className="px-4 py-3 font-semibold text-gray-700">النوع</th>
                                            <th className="px-4 py-3 font-semibold text-gray-700">الكمية</th>
                                            <th className="px-4 py-3 font-semibold text-gray-700">التفاصيل / الملاحظات</th>
                                            <th className="px-4 py-3 font-semibold text-gray-700 text-left">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-100">
                                        {filteredMovements.map((m) => (
                                            <tr key={m.id} className="hover:bg-gray-50/30 transition-colors">
                                                <td className="px-4 py-3.5 font-medium text-gray-800">
                                                    <div className="flex items-center gap-2">
                                                        <div className="w-6 h-6 rounded-md bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                                            {m.product?.main_image_path ? (
                                                                <img src={`/storage/${m.product.main_image_path}`} className="w-full h-full object-cover" alt="" />
                                                            ) : (
                                                                <div className="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">📦</div>
                                                            )}
                                                        </div>
                                                        <span className="line-clamp-1">{m.product?.name || 'منتج محذوف'}</span>
                                                    </div>
                                                </td>
                                                <td className="px-4 py-3.5">{getMovementTypeBadge(m.type)}</td>
                                                <td className="px-4 py-3.5 font-bold text-gray-900">
                                                    <span className={m.type === 'in' || m.type === 'return' ? 'text-emerald-600' : 'text-rose-600'}>
                                                        {m.type === 'in' || m.type === 'return' ? '+' : '-'}{m.quantity}
                                                    </span>
                                                </td>
                                                <td className="px-4 py-3.5 text-xs max-w-[200px] truncate" title={m.description}>
                                                    {m.description || '-'}
                                                </td>
                                                <td className="px-4 py-3.5 text-xs text-gray-400 text-left">
                                                    {new Date(m.created_at).toLocaleDateString('en-US', {
                                                        year: 'numeric',
                                                        month: 'short',
                                                        day: 'numeric',
                                                        hour: '2-digit',
                                                        minute: '2-digit'
                                                    })}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}

                        {/* Pagination Links */}
                        {movements.links && movements.links.length > 3 && (
                            <div className="flex justify-center items-center gap-1.5 pt-4 border-t border-gray-100">
                                {movements.links.map((link, idx) => {
                                    if (link.url === null) return null;
                                    return (
                                        <Link
                                            key={idx}
                                            href={link.url}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={`px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all ${
                                                link.active
                                                    ? 'bg-orange-600 border-orange-600 text-white shadow-sm'
                                                    : 'border-gray-200 text-gray-600 bg-white hover:bg-gray-50'
                                            }`}
                                        />
                                    );
                                })}
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Manual Adjustment Modal */}
            {isAdjustModalOpen && (
                <div className="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity" dir="rtl">
                    <div className="relative bg-white rounded-2xl shadow-xl w-full max-w-md border border-gray-100 p-6 text-right animate-in fade-in zoom-in-95 duration-200">
                        {/* Close button */}
                        <button
                            onClick={closeAdjustModal}
                            className="absolute top-4 left-4 p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <h3 className="text-lg font-bold text-gray-900 pb-3 border-b border-gray-100 mb-5 flex items-center gap-2">
                            🔄 تعديل حركة المخزون يدوياً
                        </h3>

                        <form onSubmit={handleAdjustSubmit} className="space-y-4">
                            {/* Product selection */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5">المنتج المراد تعديله</label>
                                {selectedProduct ? (
                                    <div className="px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50/50 text-sm font-bold text-gray-800 flex items-center justify-between">
                                        <span>{selectedProduct.name}</span>
                                        <span className="text-xs bg-amber-50 text-amber-700 px-2 py-0.5 rounded">المخزون الحالي: {selectedProduct.stock}</span>
                                    </div>
                                ) : (
                                    <select
                                        value={data.product_id}
                                        onChange={(e) => setData('product_id', e.target.value)}
                                        className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white ${
                                            errors.product_id ? 'border-red-400 bg-red-50' : 'border-gray-300'
                                        }`}
                                    >
                                        <option value="">اختر المنتج...</option>
                                        {allProducts.map((p) => (
                                            <option key={p.id} value={p.id}>
                                                {p.name} (المخزون الحالي: {p.stock})
                                            </option>
                                        ))}
                                    </select>
                                )}
                                {errors.product_id && <p className="text-xs text-red-600 mt-1">{errors.product_id}</p>}
                            </div>

                            {/* Type of operation */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5 font-medium">نوع العملية</label>
                                <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    {[
                                        { value: 'in', label: 'وارد (+)' },
                                        { value: 'out', label: 'صادر (-)' },
                                        { value: 'adjustment', label: 'تسوية' },
                                        { value: 'return', label: 'مرتجع (+)' }
                                    ].map((opt) => (
                                        <label
                                            key={opt.value}
                                            className={`flex items-center justify-center p-2.5 rounded-lg border text-xs font-bold cursor-pointer transition-all ${
                                                data.type === opt.value
                                                    ? 'border-orange-500 bg-orange-50 text-orange-700'
                                                    : 'border-gray-200 hover:border-gray-300 text-gray-600 bg-white'
                                            }`}
                                        >
                                            <input
                                                type="radio"
                                                name="type"
                                                value={opt.value}
                                                checked={data.type === opt.value}
                                                onChange={(e) => setData('type', e.target.value)}
                                                className="sr-only"
                                            />
                                            {opt.label}
                                        </label>
                                    ))}
                                </div>
                                {errors.type && <p className="text-xs text-red-600 mt-1">{errors.type}</p>}
                            </div>

                            {/* Quantity */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5 font-medium">الكمية</label>
                                <input
                                    type="number"
                                    value={data.quantity}
                                    onChange={(e) => setData('quantity', e.target.value)}
                                    placeholder="أدخل عدد القطع"
                                    min="1"
                                    required
                                    className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white ${
                                        errors.quantity ? 'border-red-400 bg-red-50' : 'border-gray-300'
                                    }`}
                                />
                                {errors.quantity && <p className="text-xs text-red-600 mt-1">{errors.quantity}</p>}
                            </div>

                            {/* Description */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-1.5 font-medium">السبب / وصف الحركة</label>
                                <textarea
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="مثال: جرد سنوي، توريد شحنة جديدة..."
                                    rows={3}
                                    className={`w-full px-3 py-2.5 rounded-lg border text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white resize-none ${
                                        errors.description ? 'border-red-400 bg-red-50' : 'border-gray-300'
                                    }`}
                                />
                                {errors.description && <p className="text-xs text-red-600 mt-1">{errors.description}</p>}
                            </div>

                            {/* Actions */}
                            <div className="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                                <button
                                    type="button"
                                    onClick={closeAdjustModal}
                                    className="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm transition-colors"
                                >
                                    إلغاء
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg text-sm transition-colors disabled:opacity-60 flex items-center gap-1.5"
                                >
                                    {processing && (
                                        <svg className="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    )}
                                    تأكيد وحفظ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </MerchantLayout>
    );
}
