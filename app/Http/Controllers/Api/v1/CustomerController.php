<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء الفريدين (مجمّعين من الطلبات)
     */
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $search = $request->input('search', '');

        $query = Order::where('tenant_id', $tenant->id)
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

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('last_order_at')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'meta' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
            ],
        ]);
    }

    /**
     * عرض تفاصيل عميل بناءً على رقم هاتفه
     */
    public function show(Request $request, $phone): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $customerData = Order::where('tenant_id', $tenant->id)
            ->where('customer_phone', $phone)
            ->select(
                'customer_phone',
                DB::raw('MAX(customer_name) as customer_name'),
                DB::raw('MAX(customer_address) as customer_address'),
                DB::raw('MAX(governorate) as governorate'),
                DB::raw('COUNT(id) as orders_count'),
                DB::raw('SUM(total) as total_spent'),
                DB::raw('MAX(created_at) as last_order_at')
            )
            ->groupBy('customer_phone')
            ->first();

        if (!$customerData) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على العميل.',
            ], 404);
        }

        $orders = Order::where('tenant_id', $tenant->id)
            ->where('customer_phone', $phone)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => $customerData,
                'orders' => $orders,
            ],
        ]);
    }

    /**
     * API لا يدعم إنشاء عميل بشكل مباشر (العملاء يُنشأون من الطلبات)
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'العملاء يُنشأون تلقائياً عند إنشاء الطلبات. استخدم POST /api/v1/orders لإنشاء طلب جديد.',
        ], 422);
    }

    /**
     * API لا يدعم تحديث العملاء مباشرة
     */
    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'تحديث بيانات العملاء غير مدعوم مباشرةً. قم بتحديث الطلبات المرتبطة بهم.',
        ], 422);
    }

    /**
     * API لا يدعم حذف العملاء مباشرة
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'حذف العملاء غير مدعوم. يمكنك حذف طلباتهم بدلاً من ذلك.',
        ], 422);
    }
}
