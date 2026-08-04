<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * عرض قائمة الطلبات للتاجر مع البحث والفلترة
     */
    public function index(Request $request): Response
    {
        $query = Order::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // فلتر التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // فلتر الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلتر المنتج
        if ($request->filled('product_id')) {
            $pid = (int) $request->product_id;
            $query->where(function ($q) use ($pid) {
                $q->where('items', 'like', "%\"id\":{$pid},%")
                  ->orWhere('items', 'like', "%\"id\": {$pid},%")
                  ->orWhere('items', 'like', "%\"id\":{$pid}}%")
                  ->orWhere('items', 'like', "%\"id\": {$pid}}%");
            });
        }

        // إحصائيات الحالة (من نفس الفلتر بدون status filter)
        $statsQuery = Order::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('product_id')) {
            $pid = (int) $request->product_id;
            $statsQuery->where(function ($q) use ($pid) {
                $q->where('items', 'like', "%\"id\":{$pid},%")
                  ->orWhere('items', 'like', "%\"id\": {$pid},%")
                  ->orWhere('items', 'like', "%\"id\":{$pid}}%")
                  ->orWhere('items', 'like', "%\"id\": {$pid}}%");
            });
        }

        $statusCounts = [
            'total'     => $statsQuery->count(),
            'pending'   => (clone $statsQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $statsQuery)->where('status', 'confirmed')->count(),
            'shipped'   => (clone $statsQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $statsQuery)->where('status', 'delivered')->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
        ];

        // حساب المجموع الإجمالي للطلبات المفلترة
        $totalAmount = (clone $query)->sum('total');

        $orders = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // قائمة المنتجات للفلتر
        $productsList = Product::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Merchant/Orders/Index', [
            'orders'       => $orders,
            'totalAmount'  => round((float) $totalAmount, 2),
            'statusCounts' => $statusCounts,
            'productsList' => $productsList,
            'filters'      => $request->only(['search', 'status', 'date_from', 'date_to', 'product_id']),
        ]);
    }

    /**
     * عرض تفاصيل الطلب
     */
    public function show(Order $order): Response
    {
        $items = collect($order->items)->map(function ($item) {
            $product = Product::find($item['id'] ?? null);
            $rawPath = null;

            if ($product) {
                $rawPath = $product->main_image_path ?: $product->image_url;
            }

            if (!$rawPath && !empty($item['image'])) {
                $rawPath = $item['image'];
            }

            if (!$rawPath && !empty($item['image_url'])) {
                $rawPath = $item['image_url'];
            }

            $item['image_url'] = Product::resolveImageUrl($rawPath) ?: 'https://dummyimage.com/150x150/f3f4f6/9ca3af&text=صورة+المنتج';
            return $item;
        });

        return Inertia::render('Merchant/Orders/Show', [
            'order' => [
                'id'               => $order->id,
                'reference_number' => $order->reference_number,
                'customer_name'    => $order->customer_name,
                'customer_phone'   => $order->customer_phone,
                'customer_email'   => $order->customer_email,
                'customer_address' => $order->customer_address,
                'governorate'      => $order->governorate,
                'total'            => $order->total,
                'subtotal'         => $order->subtotal,
                'shipping_cost'    => $order->shipping_cost,
                'status'           => $order->status,
                'items'            => $items,
                'notes'            => $order->notes,
                'created_at'       => $order->created_at?->format('Y-m-d H:i'),
            ]
        ]);
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح ✓');
    }

    /**
     * إلغاء الطلب
     */
    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح ✓');
    }

    /**
     * حذف الطلب
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('merchant.orders.index')->with('success', 'تم حذف الطلب بنجاح ✓');
    }

    /**
     * تصدير الطلبات كـ CSV
     */
    public function export(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        if ($request->input('format') === 'pdf') {
            return view('orders.export_print', compact('orders'));
        }

        $filename = 'orders_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($file, ['#', 'الرقم المرجعي', 'العميل', 'الهاتف', 'المحافظة', 'العنوان', 'المنتجات', 'المجموع الفرعي', 'الشحن', 'الإجمالي', 'ملاحظات', 'التاريخ']);

            foreach ($orders as $order) {
                $items = collect($order->items)->map(function($i) {
                    $name = $i['name'] ?? '';
                    $qty = $i['quantity'] ?? 1;
                    $size = isset($i['selectedSize']) ? ' - مقاس: ' . $i['selectedSize'] : '';
                    $color = isset($i['selectedColor']) ? ' - لون: ' . $i['selectedColor'] : '';
                    return "{$name} x{$qty}{$size}{$color}";
                })->implode(' | ');

                fputcsv($file, [
                    $order->id,
                    $order->reference_number,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->governorate,
                    $order->customer_address,
                    $items,
                    $order->subtotal ?? 0,
                    $order->shipping_cost ?? 0,
                    $order->total ?? 0,
                    $order->notes ?? '',
                    $order->created_at?->format('Y/m/d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * عرض فاتورة الطلب للطباعة
     */
    public function invoice(Order $order)
    {
        $order->items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?: 'Store';
        $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
            ?: (\App\Models\Setting::where('key', 'whatsapp')->value('value')
            ?: (auth()->user()?->phone ?: ''));

        return view('orders.invoice', compact('order', 'storeName', 'storePhone'));
    }

    /**
     * تحميل فاتورة الطلب كملف PDF
     */
    public function downloadInvoice(Order $order)
    {
        $order->items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?: 'Store';
        $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
            ?: (\App\Models\Setting::where('key', 'whatsapp')->value('value')
            ?: (auth()->user()?->phone ?: ''));

        $pdf = \PDF::loadView('orders.invoice', compact('order', 'storeName', 'storePhone'));
        return $pdf->download('invoice-' . $order->reference_number . '.pdf');
    }
}
