<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(): Response
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $daysInMonth = $now->daysInMonth;
        $todayDay = $now->day;

        // ==========================================
        // 1. إحصائيات الأيام في الشهر الحالي (للرسم البياني)
        // ==========================================
        $ordersThisMonth = Order::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();

        $chartLabels = [];
        $dailySales = [];
        $dailyOrders = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
            $dayOrders = $ordersThisMonth->filter(fn($order) => $order->created_at->format('Y-m-d') === $dateString);
            
            $chartLabels[] = $day;
            
            if ($day <= $todayDay) {
                // المبيعات نحسبها فقط للطلبات المؤكدة
                $dailySales[] = (float) $dayOrders->whereIn('status', ['confirmed'])->sum('total');
                $dailyOrders[] = $dayOrders->count();
            } else {
                $dailySales[] = 0.0;
                $dailyOrders[] = 0;
            }
        }

        // ==========================================
        // 2. المنتجات الأكثر مبيعاً (Top Selling Products)
        // ==========================================
        $orders = Order::all();
        $productSales = [];

        foreach ($orders as $order) {
            // نحسب فقط للطلبات المؤكدة لضمان دقة المبيعات الفعلية
            if (!in_array($order->status, ['confirmed'])) {
                continue;
            }
            
            $items = $order->items;
            if (is_array($items)) {
                foreach ($items as $item) {
                    $pid = $item['id'] ?? null;
                    if (!$pid) continue;
                    
                    $qty = (int) ($item['quantity'] ?? 0);
                    $price = (float) ($item['price'] ?? 0);
                    $subtotal = $qty * $price;
                    
                    if (!isset($productSales[$pid])) {
                        $productSales[$pid] = [
                            'id' => $pid,
                            'name' => $item['name'] ?? 'منتج غير معروف',
                            'qty' => 0,
                            'revenue' => 0.0,
                        ];
                    }
                    
                    $productSales[$pid]['qty'] += $qty;
                    $productSales[$pid]['revenue'] += $subtotal;
                }
            }
        }

        $topProducts = collect($productSales)
            ->sortByDesc('qty')
            ->take(5)
            ->values()
            ->all();

        // ==========================================
        // 3. توزيع المبيعات والطلبات حسب المحافظات
        // ==========================================
        $governorateStats = Order::select('governorate', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('governorate')
            ->get()
            ->map(fn($item) => [
                'governorate' => $item->governorate ?: 'غير محدد',
                'orders_count' => (int) $item->count,
                'revenue' => round((float) $item->revenue, 2),
            ])
            ->sortByDesc('revenue')
            ->values()
            ->all();

        // ==========================================
        // 4. توزيع الطلبات حسب طرق الدفع
        // ==========================================
        $paymentMethodNames = [
            'instapay' => 'إنستاباي (Instapay)',
            'vodafone_cash' => 'فودافون كاش (Vodafone Cash)',
            'cod' => 'الدفع عند الاستلام (COD)',
        ];

        $paymentStats = Order::select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('payment_method')
            ->get()
            ->map(fn($item) => [
                'method' => $item->payment_method ?: 'cod',
                'method_text' => $paymentMethodNames[strtolower($item->payment_method)] ?? ($item->payment_method ?: 'الدفع عند الاستلام (COD)'),
                'orders_count' => (int) $item->count,
                'revenue' => round((float) $item->revenue, 2),
            ])
            ->sortByDesc('orders_count')
            ->values()
            ->all();

        // ==========================================
        // 5. توزيع الطلبات حسب الحالات
        // ==========================================
        $statusNames = [
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'cancelled' => 'ملغي',
        ];

        $statusStats = Order::whereIn('status', ['pending', 'confirmed', 'cancelled'])
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('status')
            ->get()
            ->map(fn($item) => [
                'status' => $item->status,
                'status_text' => $statusNames[$item->status] ?? $item->status,
                'orders_count' => (int) $item->count,
                'revenue' => round((float) $item->revenue, 2),
            ])
            ->sortByDesc('orders_count')
            ->values()
            ->all();

        // ==========================================
        // 6. بطاقات إحصائية للمؤشرات الرئيسية
        // ==========================================
        $totalOrdersCount = Order::count();
        $confirmedOrdersCount = Order::where('status', 'confirmed')->count();
        
        $totalRevenue = (float) Order::whereIn('status', ['confirmed'])->sum('total');
        
        $avgOrderValue = $totalOrdersCount > 0 
            ? round($totalRevenue / $totalOrdersCount, 2) 
            : 0;

        $topProduct = count($topProducts) > 0 ? $topProducts[0]['name'] : 'لا يوجد';

        return Inertia::render('Merchant/Reports/Index', [
            'chart' => [
                'labels' => $chartLabels,
                'sales'  => $dailySales,
                'orders' => $dailyOrders,
                'month_name' => $now->translatedFormat('F Y'),
            ],
            'topProducts' => $topProducts,
            'governorates' => $governorateStats,
            'payments' => $paymentStats,
            'statuses' => $statusStats,
            'summary' => [
                'total_revenue' => round($totalRevenue, 2),
                'completed_orders' => $confirmedOrdersCount,
                'avg_order_value' => round($avgOrderValue, 2),
                'top_product' => $topProduct,
                'total_orders' => $totalOrdersCount,
            ]
        ]);
    }
}
