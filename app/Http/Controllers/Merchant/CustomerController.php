<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء الفريدين للتاجر
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->input('search', ''));

        // تجميع العملاء الفريدين بناءً على رقم الهاتف
        $query = Order::query()
            ->select(
                'customer_phone',
                DB::raw('MAX(customer_name) as customer_name'),
                DB::raw('MAX(customer_address) as customer_address'),
                DB::raw('MAX(governorate) as governorate'),
                DB::raw('COUNT(id) as orders_count'),
                DB::raw('SUM(total) as total_spent'),
                DB::raw('MAX(created_at) as last_order_at')
            )
            ->groupBy('customer_phone');

        // تطبيق فلتر البحث بالاسم أو الهاتف
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // الترتيب بآخر طلب أولاً
        $customers = $query->orderBy('last_order_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // تهيئة وتنسيق المخرجات
        $customers->getCollection()->transform(function ($customer) {
            $customer->total_spent = round((float) $customer->total_spent, 2);
            $customer->orders_count = (int) $customer->orders_count;
            $customer->last_order_at = $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('Y-m-d H:i') : null;
            return $customer;
        });

        return Inertia::render('Merchant/Customers/Index', [
            'customers' => $customers,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    /**
     * جلب تفاصيل العميل وقائمة طلباته السابقة (كـ JSON لتغذية الـ Modal)
     */
    public function show($phone)
    {
        // جلب أحدث بيانات العميل
        $customer = Order::where('customer_phone', $phone)
            ->latest()
            ->first(['customer_name', 'customer_phone', 'customer_address', 'governorate']);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'العميل غير موجود'
            ], 404);
        }

        // جلب الطلبات السابقة
        $orders = Order::where('customer_phone', $phone)
            ->latest()
            ->get(['id', 'reference_number', 'total', 'status', 'created_at']);

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'reference_number' => $order->reference_number,
                'total' => round((float) $order->total, 2),
                'status' => $order->status,
                'status_text' => $this->statusText($order->status),
                'status_color' => $this->statusColor($order->status),
                'created_at' => $order->created_at ? $order->created_at->format('Y-m-d H:i') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'orders' => $formattedOrders
        ]);
    }

    /**
     * ترجمة حالة الطلب للغة العربية
     */
    private function statusText(string $status): string
    {
        return match ($status) {
            'pending'   => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'shipped'   => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
            default     => $status,
        };
    }

    /**
     * ألوان حالات الطلب
     */
    private function statusColor(string $status): string
    {
        return match ($status) {
            'pending'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'shipped'   => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default     => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }
}
