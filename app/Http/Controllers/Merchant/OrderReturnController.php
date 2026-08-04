<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Http\Requests\ApproveOrderReturnRequest;
use App\Http\Requests\RejectOrderReturnRequest;
use App\Http\Requests\CompleteOrderReturnRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderReturnController extends Controller
{
    /**
     * عرض قائمة طلبات الإرجاع للتاجر
     */
    public function index(Request $request): Response
    {
        $query = OrderReturn::query()->with(['order', 'user']);

        // البحث ببيانات العميل أو رقم الطلب
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($oq) use ($search) {
                    $oq->where('customer_name', 'like', "%{$search}%")
                       ->orWhere('customer_phone', 'like', "%{$search}%")
                       ->orWhere('reference_number', 'like', "%{$search}%");
                });
            });
        }

        // الفلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'total'     => OrderReturn::count(),
            'pending'   => OrderReturn::where('status', 'pending')->count(),
            'approved'  => OrderReturn::where('status', 'approved')->count(),
            'rejected'  => OrderReturn::where('status', 'rejected')->count(),
            'completed' => OrderReturn::where('status', 'completed')->count(),
        ];

        return Inertia::render('Merchant/Returns/Index', [
            'returns'      => $returns,
            'statusCounts' => $statusCounts,
            'filters'      => $request->only(['search', 'status']),
        ]);
    }

    /**
     * عرض تفاصيل طلب الإرجاع
     */
    public function show(OrderReturn $orderReturn): Response
    {
        $orderReturn->load(['order', 'user']);
        
        // جلب الصور للمنتجات المرتجعة
        $items = collect($orderReturn->items)->map(function ($item) {
            $product = Product::find($item['id']);
            if ($product) {
                $item['image_url'] = $product->main_image_path ? asset('storage/' . $product->main_image_path) : ($product->image_url ?: null);
            }
            return $item;
        });

        return Inertia::render('Merchant/Returns/Show', [
            'returnRequest' => [
                'id' => $orderReturn->id,
                'order' => $orderReturn->order,
                'user' => $orderReturn->user,
                'items' => $items,
                'reason' => $orderReturn->reason,
                'status' => $orderReturn->status,
                'refund_amount' => $orderReturn->refund_amount,
                'notes' => $orderReturn->notes,
                'created_at' => $orderReturn->created_at?->format('Y-m-d H:i'),
            ]
        ]);
    }

    /**
     * الموافقة المبدئية على طلب الإرجاع
     */
    public function approve(ApproveOrderReturnRequest $request, OrderReturn $orderReturn)
    {
        $validated = $request->validated();

        $orderReturn->update([
            'status' => 'approved',
            'notes' => $request->notes,
        ]);

        // Webhook trigger
        try {
            \App\Services\WebhookSender::trigger('order_return.approved', $orderReturn->toArray(), $orderReturn->tenant_id);
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', 'تمت الموافقة المبدئية على طلب الإرجاع ✓');
    }

    /**
     * رفض طلب الإرجاع مع ذكر السبب
     */
    public function reject(RejectOrderReturnRequest $request, OrderReturn $orderReturn)
    {
        $validated = $request->validated();

        $orderReturn->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        // Webhook trigger
        try {
            \App\Services\WebhookSender::trigger('order_return.rejected', $orderReturn->toArray(), $orderReturn->tenant_id);
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', 'تم رفض طلب الإرجاع بنجاح ✓');
    }

    /**
     * تأكيد استلام المرتجعات وإتمام عملية التعويض المالي للمشتري وإعادة المنتجات للمخزون
     */
    public function complete(CompleteOrderReturnRequest $request, OrderReturn $orderReturn)
    {
        $validated = $request->validated();

        if ($orderReturn->status === 'completed') {
            return redirect()->back()->with('error', 'طلب الإرجاع مكتمل بالفعل.');
        }

        $orderReturn->load('order');

        try {
            DB::beginTransaction();

            // 1. إعادة المخزون وتسجيل حركات المخزن
            $items = $orderReturn->items;
            foreach ($items as $item) {
                // زيادة مخزون المنتج الرئيسي
                $product = Product::find($item['id']);
                if ($product) {
                    $product->increment('stock', $item['quantity']);

                    // تسجيل حركة المخزن
                    StockMovement::create([
                        'tenant_id' => $orderReturn->tenant_id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'type' => 'return',
                        'description' => "إرجاع منتجات الطلب #" . ($orderReturn->order->reference_number ?: $orderReturn->order->id),
                    ]);
                }
            }

            // 2. تحديث حالة طلب المرتجع إلى مكتمل
            $orderReturn->update([
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            // 3. كتابة ملاحظة في الطلب الأصلي وتحديث حالته إذا لزم الأمر
            $order = $orderReturn->order;
            if ($order) {
                $returnDetailsNote = collect($items)->map(function ($it) {
                    return "{$it['name']} × {$it['quantity']}";
                })->implode(', ');

                $newNotes = ($order->notes ? $order->notes . "\n" : "") . 
                            "[مرتجع مكتمل: تم إرجاع ({$returnDetailsNote}) وتعويض العميل بمبلغ {$orderReturn->refund_amount} ج.م]";
                
                $order->update([
                    'notes' => $newNotes
                ]);
            }

            DB::commit();

            // Webhook trigger
            try {
                \App\Services\WebhookSender::trigger('order_return.completed', $orderReturn->toArray(), $orderReturn->tenant_id);
            } catch (\Throwable $e) {}

            return redirect()->route('merchant.returns.index')->with('success', 'تم تأكيد الاستلام وإعادة المخزون وتعويض العميل مالياً بنجاح ✓');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to complete order return: " . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إتمام عملية الإرجاع: ' . $e->getMessage());
        }
    }
}
