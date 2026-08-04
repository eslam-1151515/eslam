<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboardData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 1800, function () {
            // حساب التاريخ الحالي والشهر الماضي
            $now = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();
            
            // إحصائيات الطلبات
            $totalOrders = Order::count();
            $completedOrders = Order::where('status', 'confirmed')->count();
            $cancelledOrders = Order::where('status', 'cancelled')->count();
            $pendingOrders = Order::where('status', 'pending')->count();
            
            // إحصائيات المبيعات
            $totalSales = Order::whereIn('status', ['confirmed'])->sum('total');
            
            // حساب الإحصائيات للشهر الحالي والماضي لحساب النسب المئوية
            $currentMonthOrders = Order::whereMonth('created_at', $now->month)
                                      ->whereYear('created_at', $now->year)
                                      ->count();
            
            $lastMonthOrders = Order::whereMonth('created_at', $lastMonth->month)
                                   ->whereYear('created_at', $lastMonth->year)
                                   ->count();
            
            $currentMonthSales = Order::whereIn('status', ['confirmed'])
                                      ->whereMonth('created_at', $now->month)
                                      ->whereYear('created_at', $now->year)
                                      ->sum('total');
                                      
            $lastMonthSales = Order::whereIn('status', ['confirmed'])
                                   ->whereMonth('created_at', $lastMonth->month)
                                   ->whereYear('created_at', $lastMonth->year)
                                   ->sum('total');
            
            $currentMonthCompleted = Order::where('status', 'confirmed')
                                         ->whereMonth('created_at', $now->month)
                                         ->whereYear('created_at', $now->year)
                                         ->count();
                                         
            $lastMonthCompleted = Order::where('status', 'confirmed')
                                       ->whereMonth('created_at', $lastMonth->month)
                                       ->whereYear('created_at', $lastMonth->year)
                                       ->count();
                                       
            $currentMonthCancelled = Order::where('status', 'cancelled')
                                         ->whereMonth('created_at', $now->month)
                                         ->whereYear('created_at', $now->year)
                                         ->count();
                                         
            $lastMonthCancelled = Order::where('status', 'cancelled')
                                       ->whereMonth('created_at', $lastMonth->month)
                                       ->whereYear('created_at', $lastMonth->year)
                                       ->count();
            
            // حساب النسب المئوية للتغيير
            $ordersPercentageChange = $this->calculatePercentageChange($lastMonthOrders, $currentMonthOrders);
            $salesPercentageChange = $this->calculatePercentageChange($lastMonthSales, $currentMonthSales);
            $completedPercentageChange = $this->calculatePercentageChange($lastMonthCompleted, $currentMonthCompleted);
            $cancelledPercentageChange = $this->calculatePercentageChange($lastMonthCancelled, $currentMonthCancelled);
            
            // جلب المبيعات اليومية لآخر 12 شهر في استعلام واحد مُحسّن لتجنب N+1 Queries
            $startDateSales = Carbon::now()->subMonths(11)->startOfMonth();
            $dailySalesRaw = Order::whereIn('status', ['confirmed'])
                ->where('created_at', '>=', $startDateSales)
                ->selectRaw('DATE(created_at) as date_only, SUM(total) as daily_total')
                ->groupBy('date_only')
                ->pluck('daily_total', 'date_only')
                ->toArray();

            // بيانات المبيعات الشهرية للرسم البياني (آخر 12 شهر)
            $monthlySales = [];
            $monthlyLabels = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $year = $date->year;
                $month = $date->month;
                
                $monthSum = 0;
                foreach ($dailySalesRaw as $dateStr => $total) {
                    $parts = explode('-', $dateStr);
                    if (count($parts) === 3 && (int)$parts[0] === $year && (int)$parts[1] === $month) {
                        $monthSum += $total;
                    }
                }
                
                $monthlySales[] = (float)$monthSum;
                $monthlyLabels[] = $date->translatedFormat('F'); // أسماء الشهور بالعربية
            }
            
            // بيانات المبيعات الأسبوعية (آخر 7 أسابيع)
            $weeklySales = [];
            $weeklyLabels = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                
                $weekSum = 0;
                $currentDate = clone $startOfWeek;
                while ($currentDate->lte($endOfWeek)) {
                    $weekSum += $dailySalesRaw[$currentDate->toDateString()] ?? 0;
                    $currentDate->addDay();
                }
                
                $weeklySales[] = (float)$weekSum;
                $weeklyLabels[] = 'أسبوع ' . ($i + 1);
            }
            
            // بيانات المبيعات اليومية (آخر 7 أيام)
            $dailySales = [];
            $dailyLabels = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateString = $date->toDateString();
                
                $dailySales[] = (float) ($dailySalesRaw[$dateString] ?? 0.0);
                $dailyLabels[] = $date->translatedFormat('l'); // أسماء الأيام بالعربية
            }
            
            // إحصائيات حالة الطلبات للرسم البياني الدائري
            $orderStatusStats = [
                'delivered' => $completedOrders,
                'pending' => $pendingOrders,
                'confirmed' => Order::where('status', 'confirmed')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'cancelled' => $cancelledOrders
            ];
            
            // حساب النسب المئوية لحالة الطلبات
            $totalStatusOrders = array_sum($orderStatusStats);
            $orderStatusPercentages = [];
            
            if ($totalStatusOrders > 0) {
                $orderStatusPercentages = [
                    'delivered' => round(($orderStatusStats['delivered'] / $totalStatusOrders) * 100, 1),
                    'processing' => round((($orderStatusStats['pending'] + $orderStatusStats['confirmed'] + $orderStatusStats['shipped']) / $totalStatusOrders) * 100, 1),
                    'cancelled' => round(($orderStatusStats['cancelled'] / $totalStatusOrders) * 100, 1)
                ];
            } else {
                $orderStatusPercentages = ['delivered' => 0, 'processing' => 0, 'cancelled' => 0];
            }
            
            // المنتجات الأكثر مبيعاً (من بيانات الطلبات)
            $topProducts = $this->getTopSellingProducts();

            return compact(
                'totalOrders',
                'completedOrders', 
                'cancelledOrders',
                'totalSales',
                'ordersPercentageChange',
                'salesPercentageChange',
                'completedPercentageChange',
                'cancelledPercentageChange',
                'monthlySales',
                'monthlyLabels',
                'weeklySales',
                'weeklyLabels',
                'dailySales',
                'dailyLabels',
                'orderStatusPercentages',
                'topProducts'
            );
        });
        
        // أحدث الطلبات (تُجلب ديناميكياً بدون كاش)
        $recentOrders = Order::latest()
                            ->take(6)
                            ->get()
                            ->map(function ($order) {
                                return [
                                    'id' => $order->id,
                                    'reference_number' => $order->reference_number,
                                    'customer_name' => $order->customer_name,
                                    'total' => $order->total,
                                    'status' => $order->status,
                                    'created_at' => $order->created_at,
                                    'status_text' => $this->getStatusText($order->status)
                                ];
                            });
        
        return view('dashboard', array_merge($dashboardData, compact('recentOrders')));
    }
    
    /**
     * حساب النسبة المئوية للتغيير
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }
    
    /**
     * الحصول على نص حالة الطلب
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'shipped' => 'في التوصيل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ];
        
        return $statusTexts[$status] ?? $status;
    }
    
    /**
     * الحصول على المنتجات الأكثر مبيعاً
     */
    private function getTopSellingProducts()
    {
        $topProducts = [];
        
        // جلب الطلبات المؤكدة لآخر 90 يوم فقط لتقليص استخدام الذاكرة والأداء
        $orders = Order::whereIn('status', ['confirmed'])
                      ->where('created_at', '>=', Carbon::now()->subDays(90))
                      ->get();
        
        $productSales = [];
        
        // تحليل بيانات الطلبات لحساب المبيعات
        foreach ($orders as $order) {
            $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    $productId = $item['id'] ?? null;
                    $quantity = $item['quantity'] ?? 0;
                    $price = $item['price'] ?? 0;
                    
                    if ($productId) {
                        if (!isset($productSales[$productId])) {
                            $productSales[$productId] = [
                                'quantity' => 0,
                                'revenue' => 0,
                                'name' => $item['name'] ?? 'منتج غير معروف'
                            ];
                        }
                        
                        $productSales[$productId]['quantity'] += $quantity;
                        $productSales[$productId]['revenue'] += ($quantity * $price);
                    }
                }
            }
        }
        
        // ترتيب المنتجات حسب الإيرادات
        uasort($productSales, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        
        // أخذ أفضل 3 منتجات
        $topProducts = array_slice($productSales, 0, 3, true);
        
        // تنسيق البيانات للعرض مع جلب صور المنتجات
        $formattedProducts = [];
        $rank = 1;
        
        foreach ($topProducts as $productId => $data) {
            // جلب بيانات المنتج من قاعدة البيانات
            $product = Product::find($productId);
            $image = null;
            $shopUrl = '#';
            
            if ($product) {
                // الحصول على الصورة الرئيسية للمنتج
                if ($product->main_image_path) {
                    $image = asset('storage/' . $product->main_image_path);
                }
                
                // رابط المنتج في المتجر
                $shopUrl = '/shop/#product-' . $product->id;
            }
            
            $formattedProducts[] = [
                'id' => $productId,
                'rank' => $rank,
                'name' => $data['name'],
                'revenue' => $data['revenue'],
                'quantity' => $data['quantity'],
                'formatted_revenue' => number_format($data['revenue']) . ' جنيه',
                'image' => $image,
                'shop_url' => $shopUrl
            ];
            $rank++;
        }
        
        // في حالة عدم وجود بيانات، إرجاع بيانات افتراضية
        if (empty($formattedProducts)) {
            $formattedProducts = [
                [
                    'id' => null,
                    'rank' => 1,
                    'name' => 'لا توجد مبيعات حتى الآن',
                    'revenue' => 0,
                    'quantity' => 0,
                    'formatted_revenue' => '0 جنيه',
                    'image' => null,
                    'shop_url' => '#'
                ]
            ];
        }
        
        return $formattedProducts;
    }
}