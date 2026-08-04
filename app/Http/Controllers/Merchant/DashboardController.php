<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $tenantId = session()->get('tenant_id') ?? config('tenant.id') ?? 0;
        $tenant = \App\Models\Tenant::find($tenantId);

        $dashboardData = \App\Services\CacheService::getDashboardStats($tenantId, function () use ($tenant) {
            $now = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            // ==========================================
            // إحصائيات أساسية للمتجر الحالي (BelongsToTenant يصفّي تلقائياً)
            // ==========================================
            $totalOrders    = Order::count();
            $pendingOrders  = Order::where('status', 'pending')->count();
            $completedOrders = Order::where('status', 'confirmed')->count();
            $cancelledOrders = Order::where('status', 'cancelled')->count();

            $totalRevenue = Order::whereIn('status', ['confirmed'])
                ->sum('total');

            $avgOrderValue = $totalOrders > 0
                ? Order::whereIn('status', ['confirmed'])->avg('total')
                : 0;

            $activeProducts = Product::where('stock', '>', 0)->count();
            $totalProducts  = Product::count();

            // ==========================================
            // نسب التغيير مقارنةً بالشهر الماضي
            // ==========================================
            $currentMonthOrders = Order::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)->count();

            $lastMonthOrders = Order::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)->count();

            $currentMonthRevenue = Order::whereIn('status', ['confirmed'])
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)->sum('total');

            $lastMonthRevenue = Order::whereIn('status', ['confirmed'])
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)->sum('total');

            $ordersChange  = $this->percentageChange($lastMonthOrders, $currentMonthOrders);
            $revenueChange = $this->percentageChange($lastMonthRevenue, $currentMonthRevenue);

            // ==========================================
            // بيانات الرسم البياني (آخر 7 أيام) - استعلام واحد مُحسّن
            // ==========================================
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $dailyRevenues = Order::whereIn('status', ['confirmed'])
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date_only, SUM(total) as daily_total')
                ->groupBy('date_only')
                ->pluck('daily_total', 'date_only')
                ->toArray();

            $chartLabels = [];
            $chartData   = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateString = $date->toDateString();
                $chartLabels[] = $date->format('d/m');
                $chartData[]   = (float) ($dailyRevenues[$dateString] ?? 0.0);
            }

            $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
                ?: \App\Models\Setting::where('key', 'whatsapp')->value('value');

            return [
                'stats' => [
                    'total_orders'            => $totalOrders,
                    'pending_orders'          => $pendingOrders,
                    'completed_orders'        => $completedOrders,
                    'cancelled_orders'        => $cancelledOrders,
                    'total_revenue'           => round((float) $totalRevenue, 2),
                    'avg_order_value'         => round((float) $avgOrderValue, 2),
                    'active_products'         => $activeProducts,
                    'total_products'          => $totalProducts,
                    'orders_change'           => $ordersChange,
                    'revenue_change'          => $revenueChange,
                    'wallet_balance'          => $tenant ? round((float) $tenant->wallet_balance, 2) : 0,
                    'store_phone'             => $storePhone,
                ],
                'chart' => [
                    'labels' => $chartLabels,
                    'data'   => $chartData,
                ],
            ];
        });

        // ==========================================
        // آخر 5 طلبات (تُجلب بدون كاش لضمان تحديثها الفوري في لوحة التحكم)
        // ==========================================
        $recentOrders = Order::latest()
            ->take(5)
            ->get()
            ->map(fn($order) => [
                'id'               => $order->id,
                'reference_number' => $order->reference_number,
                'customer_name'    => $order->customer_name,
                'total'            => $order->total,
                'status'           => $order->status,
                'status_text'      => $this->statusText($order->status),
                'created_at'       => $order->created_at?->format('Y-m-d h:i A'),
                'created_at_date'  => $order->created_at?->format('Y-m-d'),
                'created_at_time'  => $order->created_at?->format('h:i A'),
            ]);

        return Inertia::render('Merchant/Dashboard', [
            'stats'        => $dashboardData['stats'],
            'recentOrders' => $recentOrders,
            'chart'        => $dashboardData['chart'],
        ]);
    }

    private function percentageChange($old, $new): float
    {
        if ($old == 0) {
            return $new > 0 ? 100.0 : 0.0;
        }
        return round((($new - $old) / $old) * 100, 1);
    }

    private function statusText(string $status): string
    {
        return match ($status) {
            'pending'   => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'shipped'   => 'في التوصيل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            default     => $status,
        };
    }

    private function statusColor(string $status): string
    {
        return match ($status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'shipped'   => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }
}
