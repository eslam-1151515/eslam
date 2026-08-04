import React, { useState, useMemo } from 'react';
import { Head } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

// ========================================================
// مكون كرت الإحصائيات (StatCard)
// ========================================================
function StatCard({ title, value, icon, color }) {
    const colors = {
        indigo: { bg: 'bg-indigo-50/80', border: 'border-indigo-100', iconBg: 'bg-indigo-500', text: 'text-indigo-900', iconText: 'text-indigo-600' },
        emerald: { bg: 'bg-emerald-50/80', border: 'border-emerald-100', iconBg: 'bg-emerald-500', text: 'text-emerald-900', iconText: 'text-emerald-600' },
        amber: { bg: 'bg-amber-50/80', border: 'border-amber-100', iconBg: 'bg-amber-500', text: 'text-amber-900', iconText: 'text-amber-600' },
        sky: { bg: 'bg-sky-50/80', border: 'border-sky-100', iconBg: 'bg-sky-500', text: 'text-sky-900', iconText: 'text-sky-600' },
        rose: { bg: 'bg-rose-50/80', border: 'border-rose-100', iconBg: 'bg-rose-500', text: 'text-rose-900', iconText: 'text-rose-600' },
    };
    const c = colors[color] || colors.indigo;

    return (
        <div className={`p-6 rounded-2xl border ${Math.round(c.bg)} ${c.border} shadow-sm transition-all duration-300 hover:shadow-md`}>
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm font-medium text-gray-500">{title}</p>
                    <h3 className={`text-2xl font-bold ${c.text} mt-2`}>{value}</h3>
                </div>
                <div className={`w-12 h-12 rounded-xl ${c.iconBg} bg-opacity-10 flex items-center justify-center ${c.iconText}`}>
                    {icon}
                </div>
            </div>
        </div>
    );
}

// ========================================================
// الرسم البياني التفاعلي التلقائي (RTL Interactive SVG Chart)
// ========================================================
function InteractiveChart({ labels, sales, orders, monthName }) {
    const [chartMode, setChartMode] = useState('both'); // both, sales, orders
    const [hoveredIndex, setHoveredIndex] = useState(null);

    const width = 800;
    const height = 320;
    const padding = 45;

    const maxSales = useMemo(() => Math.max(...sales, 100), [sales]);
    const maxOrders = useMemo(() => Math.max(...orders, 10), [orders]);

    const salesPoints = useMemo(() => {
        return sales.map((val, idx) => {
            const x = padding + idx * ((width - 2 * padding) / (labels.length - 1));
            const y = height - padding - (val / maxSales) * (height - 2 * padding);
            return { x, y, val, day: labels[idx] };
        });
    }, [sales, maxSales, labels]);

    const ordersPoints = useMemo(() => {
        return orders.map((val, idx) => {
            const x = padding + idx * ((width - 2 * padding) / (labels.length - 1));
            const y = height - padding - (val / maxOrders) * (height - 2 * padding);
            return { x, y, val, day: labels[idx] };
        });
    }, [orders, maxOrders, labels]);

    const salesPath = useMemo(() => {
        if (salesPoints.length === 0) return '';
        return salesPoints.reduce((acc, p, i) => {
            return i === 0 ? `M ${Math.round(p.x)} ${p.y}` : `${acc} L ${Math.round(p.x)} ${p.y}`;
        }, '');
    }, [salesPoints]);

    const salesAreaPath = useMemo(() => {
        if (salesPoints.length === 0) return '';
        const first = salesPoints[0];
        const last = salesPoints[salesPoints.length - 1];
        return `${salesPath} L ${last.x} ${height - padding} L ${first.x} ${height - padding} Z`;
    }, [salesPoints, salesPath]);

    const ordersPath = useMemo(() => {
        if (ordersPoints.length === 0) return '';
        return ordersPoints.reduce((acc, p, i) => {
            return i === 0 ? `M ${Math.round(p.x)} ${p.y}` : `${acc} L ${Math.round(p.x)} ${p.y}`;
        }, '');
    }, [ordersPoints]);

    const gridLines = [0, 0.25, 0.5, 0.75, 1];

    const formatCurrency = (val) => new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(val)) + ' ج.م';
    const formatNumber = (val) => new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(val));

    return (
        <div className="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 className="text-lg font-bold text-gray-900">تطور المبيعات والطلبات اليومية</h3>
                    <p className="text-sm text-gray-500 mt-1">تقرير بياني لشهر {monthName}</p>
                </div>
                <div className="flex bg-gray-100 p-1 rounded-xl self-start">
                    <button
                        onClick={() => setChartMode('both')}
                        className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${
                            chartMode === 'both' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-950'
                        }`}
                    >
                        الكل
                    </button>
                    <button
                        onClick={() => setChartMode('sales')}
                        className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${
                            chartMode === 'sales' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-950'
                        }`}
                    >
                        المبيعات
                    </button>
                    <button
                        onClick={() => setChartMode('orders')}
                        className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${
                            chartMode === 'orders' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-950'
                        }`}
                    >
                        الطلبات
                    </button>
                </div>
            </div>

            <div className="relative">
                {/* SVG Graph */}
                <svg viewBox={`0 0 ${Math.round(width)} ${height}`} className="w-full h-auto overflow-visible select-none">
                    <defs>
                        <linearGradient id="chartSalesGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stopColor="#4f46e5" stopOpacity="0.25" />
                            <stop offset="100%" stopColor="#4f46e5" stopOpacity="0.0" />
                        </linearGradient>
                        <linearGradient id="chartOrdersGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stopColor="#f59e0b" stopOpacity="0.25" />
                            <stop offset="100%" stopColor="#f59e0b" stopOpacity="0.0" />
                        </linearGradient>
                    </defs>

                    {/* Grid Lines */}
                    {gridLines.map((ratio, i) => {
                        const y = padding + ratio * (height - 2 * padding);
                        return (
                            <line
                                key={i}
                                x1={padding}
                                y1={y}
                                x2={width - padding}
                                y2={y}
                                stroke="#f1f5f9"
                                strokeWidth="1"
                                strokeDasharray="4 4"
                            />
                        );
                    })}

                    {/* Sales Area & Path */}
                    {(chartMode === 'both' || chartMode === 'sales') && (
                        <>
                            <path d={salesAreaPath} fill="url(#chartSalesGrad)" />
                            <path d={salesPath} fill="none" stroke="#4f46e5" strokeWidth="3" strokeLinecap="round" />
                        </>
                    )}

                    {/* Orders Path */}
                    {(chartMode === 'both' || chartMode === 'orders') && (
                        <path d={ordersPath} fill="none" stroke="#f59e0b" strokeWidth="3" strokeLinecap="round" />
                    )}

                    {/* Interactive Hover Guides & Circles */}
                    {labels.map((day, idx) => {
                        const sPt = salesPoints[idx];
                        const oPt = ordersPoints[idx];
                        const x = sPt.x;

                        return (
                            <g key={idx}>
                                {/* Invisible vertical interaction bar */}
                                <rect
                                    x={x - (width - 2 * padding) / (labels.length - 1) / 2}
                                    y={padding}
                                    width={(width - 2 * padding) / (labels.length - 1)}
                                    height={height - 2 * padding}
                                    fill="transparent"
                                    className="cursor-pointer"
                                    onMouseEnter={() => setHoveredIndex(idx)}
                                    onMouseLeave={() => setHoveredIndex(null)}
                                />

                                {/* Vertical Line on hover */}
                                {hoveredIndex === idx && (
                                    <line
                                        x1={x}
                                        y1={padding}
                                        x2={x}
                                        y2={height - padding}
                                        stroke="#cbd5e1"
                                        strokeWidth="1.5"
                                        strokeDasharray="2 2"
                                        className="pointer-events-none"
                                    />
                                )}

                                {/* Sales Circle */}
                                {(chartMode === 'both' || chartMode === 'sales') && hoveredIndex === idx && (
                                    <circle
                                        cx={x}
                                        cy={sPt.y}
                                        r="6"
                                        fill="#4f46e5"
                                        stroke="#ffffff"
                                        strokeWidth="2"
                                        className="pointer-events-none shadow"
                                    />
                                )}

                                {/* Orders Circle */}
                                {(chartMode === 'both' || chartMode === 'orders') && hoveredIndex === idx && (
                                    <circle
                                        cx={x}
                                        cy={oPt.y}
                                        r="6"
                                        fill="#f59e0b"
                                        stroke="#ffffff"
                                        strokeWidth="2"
                                        className="pointer-events-none shadow"
                                    />
                                )}
                            </g>
                        );
                    })}

                    {/* X-Axis Day Labels */}
                    {labels.map((day, idx) => {
                        const sPt = salesPoints[idx];
                        // Show labels every 3 days or first/last to avoid congestion
                        if (day === 1 || day === labels.length || day % 3 === 0) {
                            return (
                                <text
                                    key={idx}
                                    x={sPt.x}
                                    y={height - 15}
                                    textAnchor="middle"
                                    className="text-[10px] font-medium fill-gray-400"
                                >
                                    {formatNumber(day)}
                                </text>
                            );
                        }
                        return null;
                    })}
                </svg>

                {/* Tooltip on hover */}
                {hoveredIndex !== null && (
                    <div
                        className="absolute bg-slate-900 text-white rounded-lg p-2.5 text-xs shadow-lg pointer-events-none transition-all duration-150 border border-slate-800"
                        style={{
                            left: `${(salesPoints[hoveredIndex].x / width) * 100}%`,
                            top: '10px',
                            transform: 'translateX(-50%)',
                        }}
                    >
                        <div className="font-semibold mb-1 text-gray-300">اليوم {labels[hoveredIndex]}</div>
                        <div className="space-y-1">
                            {(chartMode === 'both' || chartMode === 'sales') && (
                                <div className="flex items-center gap-2">
                                    <span className="w-2.5 h-2.5 rounded-full bg-indigo-500" />
                                    <span>المبيعات: {formatCurrency(sales[hoveredIndex])}</span>
                                </div>
                            )}
                            {(chartMode === 'both' || chartMode === 'orders') && (
                                <div className="flex items-center gap-2">
                                    <span className="w-2.5 h-2.5 rounded-full bg-amber-500" />
                                    <span>الطلبات: {formatNumber(orders[hoveredIndex])}</span>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

// ========================================================
// المكون الرئيسي لصفحة التقارير (ReportsIndex)
// ========================================================
export default function ReportsIndex({ chart, topProducts, governorates, payments, statuses, summary }) {
    const formatCurrency = (val) => new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(val)) + ' ج.م';
    const formatNumber = (val) => new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(val));

    return (
        <MerchantLayout title="التقارير والتحليلات">
            <Head title="التقارير والإحصائيات" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* Header */}
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">التقارير والتحليلات</h2>
                    <p className="text-sm text-gray-500 mt-1">تابع أداء متجرك ومبيعاتك بشكل تفصيلي وسهل.</p>
                </div>

                {/* Stats Cards Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <StatCard
                        title="إجمالي المبيعات"
                        value={formatCurrency(summary.total_revenue)}
                        color="indigo"
                        icon={
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        }
                    />
                    <StatCard
                        title="الطلبات المكتملة"
                        value={`${formatNumber(summary.completed_orders)} / ${formatNumber(summary.total_orders)}`}
                        color="emerald"
                        icon={
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        }
                    />
                    <StatCard
                        title="متوسط قيمة الطلب"
                        value={formatCurrency(summary.avg_order_value)}
                        color="amber"
                        icon={
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        }
                    />
                    <StatCard
                        title="المنتج الأكثر مبيعاً"
                        value={summary.top_product}
                        color="sky"
                        icon={
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        }
                    />
                </div>

                {/* Interactive Chart */}
                <InteractiveChart
                    labels={chart.labels}
                    sales={chart.sales}
                    orders={chart.orders}
                    monthName={chart.month_name}
                />

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Top Selling Products */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>🔥</span> المنتجات الأكثر مبيعاً
                        </h3>
                        <div className="overflow-x-auto">
                            <table className="w-full text-right border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-150 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        <th className="pb-3 text-right">المنتج</th>
                                        <th className="pb-3 text-center">الكمية المباعة</th>
                                        <th className="pb-3 text-left">الإيرادات</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 text-sm">
                                    {topProducts.length === 0 ? (
                                        <tr>
                                            <td colSpan="3" className="text-center py-6 text-gray-400 italic">
                                                لا توجد منتجات مباعة بعد.
                                            </td>
                                        </tr>
                                    ) : (
                                        topProducts.map((p, idx) => (
                                            <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="py-3 font-semibold text-gray-800">{p.name}</td>
                                                <td className="py-3 text-center font-bold text-gray-700">{formatNumber(p.qty)}</td>
                                                <td className="py-3 text-left font-bold text-indigo-600">{formatCurrency(p.revenue)}</td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Sales by Governorate */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>📍</span> المبيعات حسب المحافظات
                        </h3>
                        <div className="overflow-x-auto">
                            <table className="w-full text-right border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-150 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        <th className="pb-3 text-right">المحافظة</th>
                                        <th className="pb-3 text-center">عدد الطلبات</th>
                                        <th className="pb-3 text-left">إجمالي المبيعات</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 text-sm">
                                    {governorates.length === 0 ? (
                                        <tr>
                                            <td colSpan="3" className="text-center py-6 text-gray-400 italic">
                                                لا توجد مبيعات مسجلة للمحافظات بعد.
                                            </td>
                                        </tr>
                                    ) : (
                                        governorates.map((gov, idx) => (
                                            <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="py-3 font-semibold text-gray-800">{gov.governorate}</td>
                                                <td className="py-3 text-center font-bold text-gray-700">{formatNumber(gov.orders_count)}</td>
                                                <td className="py-3 text-left font-bold text-emerald-600">{formatCurrency(gov.revenue)}</td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Payment Methods */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>💳</span> توزيع طرق الدفع
                        </h3>
                        <div className="overflow-x-auto">
                            <table className="w-full text-right border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-150 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        <th className="pb-3 text-right">طريقة الدفع</th>
                                        <th className="pb-3 text-center">عدد الطلبات</th>
                                        <th className="pb-3 text-left">إجمالي القيمة</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 text-sm">
                                    {payments.length === 0 ? (
                                        <tr>
                                            <td colSpan="3" className="text-center py-6 text-gray-400 italic">
                                                لا توجد بيانات متاحة لطرق الدفع.
                                            </td>
                                        </tr>
                                    ) : (
                                        payments.map((p, idx) => (
                                            <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="py-3 font-semibold text-gray-800">{p.method_text}</td>
                                                <td className="py-3 text-center font-bold text-gray-700">{formatNumber(p.orders_count)}</td>
                                                <td className="py-3 text-left font-bold text-indigo-600">{formatCurrency(p.revenue)}</td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Order Status Distribution */}
                    <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span>📊</span> حالة الطلبات ونسب التوصيل
                        </h3>
                        <div className="overflow-x-auto">
                            <table className="w-full text-right border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-150 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        <th className="pb-3 text-right">الحالة</th>
                                        <th className="pb-3 text-center">عدد الطلبات</th>
                                        <th className="pb-3 text-left">القيمة المالية</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 text-sm">
                                    {statuses.length === 0 ? (
                                        <tr>
                                            <td colSpan="3" className="text-center py-6 text-gray-400 italic">
                                                لا توجد طلبات بعد.
                                            </td>
                                        </tr>
                                    ) : (
                                        statuses.map((s, idx) => (
                                            <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="py-3 font-semibold text-gray-800">{s.status_text}</td>
                                                <td className="py-3 text-center font-bold text-gray-700">{formatNumber(s.orders_count)}</td>
                                                <td className="py-3 text-left font-bold text-emerald-600">{formatCurrency(s.revenue)}</td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
