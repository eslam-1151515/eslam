<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
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

        // فلتر حالة الطباعة
        if ($request->filled('print_status')) {
            if ($request->print_status === 'printed') {
                $query->where('is_printed', true);
            } elseif ($request->print_status === 'unprinted') {
                $query->where('is_printed', false);
            }
        }

        // فلتر حالة الشحن
        if ($request->filled('shipping_status')) {
            if ($request->shipping_status === 'shipped') {
                $query->whereHas('shipment');
            } elseif ($request->shipping_status === 'unshipped') {
                $query->whereDoesntHave('shipment');
            }
        }

        // الفلاتر السريعة
        if ($request->filled('quick_filter')) {
            if ($request->quick_filter === 'ready_to_ship') {
                $query->where('status', 'confirmed')->whereDoesntHave('shipment');
            } elseif ($request->quick_filter === 'unprinted_confirmed') {
                $query->where('status', 'confirmed')->where('is_printed', false);
            }
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
            'fake'      => (clone $statsQuery)->where('status', 'fake')->count(),
        ];

        $fulfillmentCounts = [
            'ready_to_ship'       => (clone $statsQuery)->where('status', 'confirmed')->whereDoesntHave('shipment')->count(),
            'unprinted_confirmed' => (clone $statsQuery)->where('status', 'confirmed')->where('is_printed', false)->count(),
            'printed'             => (clone $statsQuery)->where('is_printed', true)->count(),
            'shipped'             => (clone $statsQuery)->whereHas('shipment')->count(),
        ];

        // حساب المجموع الإجمالي للطلبات المفلترة (باستثناء الملغية والوهمية)
        $totalAmount = (clone $query)->whereNotIn('status', ['cancelled', 'fake'])->sum('total');

        $perPage = min(100, max(10, (int) $request->input('per_page', 10)));

        $orders = $query->with(['shipment'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // قائمة المنتجات للفلتر
        $productsList = Product::orderBy('name')->get(['id', 'name']);

        // بوابات الشحن المفعلة
        $providerNames = [
            'jnt' => 'J&T Express',
            'bosta' => 'بوسطة (Bosta)',
            'aramex' => 'أرامكس (Aramex)',
        ];
        $activeShippingGateways = \App\Models\ShippingGateway::where('is_active', true)
            ->get(['id', 'provider'])
            ->map(function ($gateway) use ($providerNames) {
                return [
                    'id' => $gateway->id,
                    'provider' => $gateway->provider,
                    'name' => $providerNames[$gateway->provider] ?? strtoupper($gateway->provider),
                ];
            });

        $tenant = app(\App\Models\Tenant::class);

        $activeSub = $tenant->subscriptions()->where('status', 'active')->latest()->first();
        $isCommission = $activeSub && ($activeSub->plan?->slug === 'commission' || str_contains($activeSub->plan?->name ?? '', 'عمولة'));

        $isSubscriptionExpired = false;
        if (!$isCommission) {
            if ($tenant->subscription_status === 'expired' || ($tenant->subscription_ends_at && $tenant->subscription_ends_at->isPast())) {
                $isSubscriptionExpired = true;
            }
        }

        $filters = array_merge(
            $request->only(['search', 'status', 'date_from', 'date_to', 'product_id', 'print_status', 'shipping_status', 'quick_filter']),
            ['per_page' => $perPage]
        );

        return Inertia::render('Merchant/Orders/Index', [
            'orders'                 => $orders,
            'totalAmount'            => round((float) $totalAmount, 2),
            'statusCounts'           => $statusCounts,
            'fulfillmentCounts'      => $fulfillmentCounts,
            'productsList'           => $productsList,
            'shippingGateways'       => $activeShippingGateways,
            'wallet_balance'         => (float) ($tenant->wallet_balance ?? 0),
            'isSubscriptionExpired'  => $isSubscriptionExpired,
            'filters'                => $filters,
        ]);
    }

    /**
     * فتح الأوردر (اليدوي — للأوردرات القديمة المقفولة بعد شحن المحفظة)
     * الأوردرات الجديدة تُفتح تلقائياً عند الإنشاء في CheckoutController
     */
    public function unlock(Order $order)
    {
        if ($order->is_unlocked) {
            return redirect()->route('merchant.orders.show', $order->id);
        }

        $tenant = app(\App\Models\Tenant::class);
        $fee    = 2;

        if (($tenant->wallet_balance ?? 0) < $fee) {
            return redirect()->route('merchant.orders.index')
                ->with('insufficient_balance', 'رصيد المحفظة غير كافٍ. يرجى شحن المحفظة لعرض تفاصيل الطلب.');
        }

        $tenant->decrement('wallet_balance', $fee);
        \App\Models\WalletTransaction::create([
            'tenant_id'   => $tenant->id,
            'amount'      => $fee,
            'type'        => 'debit',
            'description' => 'رسوم الطلب رقم (' . $order->reference_number . ')',
        ]);
        $order->update(['is_unlocked' => true, 'unlocked_at' => now()]);

        return redirect()->route('merchant.orders.show', $order->id);
    }

    /**
     * عرض تفاصيل الطلب
     * الطلبات الجديدة تُفتح تلقائياً عند الإنشاء.
     * الطلبات القديمة المقفولة: لو في رصيد → خصم وفتح. لو لا → رجوع بـ flash.
     */
    public function show(Order $order)
    {
        if (!$order->is_unlocked) {
            $tenant = app(\App\Models\Tenant::class);
            $fee    = 2;

            if (($tenant->wallet_balance ?? 0) < $fee) {
                return redirect()->route('merchant.orders.index')
                    ->with('insufficient_balance', 'رصيد المحفظة غير كافٍ. يرجى شحن المحفظة لعرض تفاصيل الطلب.');
            }

            $tenant->decrement('wallet_balance', $fee);
            \App\Models\WalletTransaction::create([
                'tenant_id'   => $tenant->id,
                'amount'      => $fee,
                'type'        => 'debit',
                'description' => 'رسوم الطلب رقم (' . $order->reference_number . ')',
            ]);
            $order->update(['is_unlocked' => true, 'unlocked_at' => now()]);
        }

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

        $activeShippingGateways = \App\Models\ShippingGateway::withoutGlobalScopes()
            ->where('tenant_id', $order->tenant_id)
            ->where('is_active', true)
            ->pluck('provider')
            ->toArray();

        $productsList = Product::orderBy('name')
            ->get(['id', 'name', 'price', 'main_image_path', 'colors', 'sizes', 'stock'])
            ->map(function ($p) {
                return [
                    'id'               => $p->id,
                    'name'             => $p->name,
                    'price'            => (float) $p->price,
                    'image_url'        => Product::resolveImageUrl($p->main_image_path) ?: 'https://dummyimage.com/150x150/f3f4f6/9ca3af&text=منتج',
                    'colors'           => is_array($p->colors) ? $p->colors : (json_decode($p->colors ?? '[]', true) ?: []),
                    'sizes'            => is_array($p->sizes) ? $p->sizes : (json_decode($p->sizes ?? '[]', true) ?: []),
                    'options'          => [],
                    'stock'            => $p->stock,
                ];
            });

        $governoratesList = [
            'القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'البحر الأحمر', 'البحيرة',
            'الفيوم', 'الغربية', 'الإسماعيلية', 'المنوفية', 'المنيا', 'القليوبية',
            'الوادي الجديد', 'السويس', 'أسوان', 'أسيوط', 'بني سويف', 'بورسعيد',
            'دمياط', 'الشرقية', 'جنوب سيناء', 'كفر الشيخ', 'مطروح', 'الأقصر',
            'قنا', 'شمال سيناء', 'سوهـاج'
        ];

        return Inertia::render('Merchant/Orders/Show', [
            'order' => [
                'id'               => $order->id,
                'reference_number' => $order->reference_number,
                'customer_name'    => $order->customer_name,
                'customer_phone'   => $order->customer_phone,
                'customer_email'   => $order->customer_email,
                'customer_address' => $order->customer_address,
                'governorate'      => $order->governorate,
                'payment_method'   => $order->payment_method,
                'payment_status'   => $order->payment_status,
                'transaction_id'   => $order->transaction_id,
                'total'            => $order->total,
                'subtotal'         => $order->subtotal,
                'shipping_cost'    => $order->shipping_cost,
                'status'           => $order->status,
                'is_unlocked'      => (bool) $order->is_unlocked,
                'items'            => $items,
                'notes'            => $order->notes,
                'whatsapp_status'      => $order->whatsapp_status,
                'whatsapp_sent_at'     => $order->whatsapp_sent_at ? \Carbon\Carbon::parse($order->whatsapp_sent_at)->format('Y-m-d H:i') : null,
                'whatsapp_response_at' => $order->whatsapp_response_at ? \Carbon\Carbon::parse($order->whatsapp_response_at)->format('Y-m-d H:i') : null,
                'whatsapp_message_id'  => $order->whatsapp_message_id,
                'created_at'           => $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') : null,
            ],
            'productsList'             => $productsList,
            'governoratesList'         => $governoratesList,
            'active_shipping_gateways' => $activeShippingGateways,
        ]);
    }

    /**
     * تعديل بيانات الطلب بالكامل (العميل، المنتجات، الكميات، الشحن)
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:50',
            'customer_address' => 'required|string|max:500',
            'governorate'      => 'nullable|string|max:100',
            'shipping_cost'    => 'required|numeric|min:0',
            'notes'            => 'nullable|string|max:2000',
            'items'            => 'required|array|min:1',
            'items.*.name'     => 'required|string|max:255',
            'items.*.price'    => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'customer_name.required'    => 'اسم العميل مطلوب.',
            'customer_phone.required'   => 'رقم هاتف العميل مطلوب.',
            'customer_address.required' => 'عنوان العميل مطلوب.',
            'items.required'            => 'يجب أن يحتوي الطلب على منتج واحد على الأقل.',
            'items.min'                 => 'يجب أن يحتوي الطلب على منتج واحد على الأقل.',
        ]);

        $subtotal = 0;
        $formattedItems = [];

        foreach ($request->input('items', []) as $item) {
            $price = round((float) ($item['price'] ?? 0), 2);
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $itemTotal = round($price * $qty, 2);
            $subtotal += $itemTotal;

            $formattedItems[] = [
                'id'            => $item['id'] ?? null,
                'name'          => $item['name'] ?? 'منتج',
                'price'         => $price,
                'quantity'      => $qty,
                'total'         => $itemTotal,
                'selectedColor' => $item['selectedColor'] ?? $item['color'] ?? null,
                'selectedSize'  => $item['selectedSize'] ?? $item['size'] ?? null,
                'options'       => $item['options'] ?? null,
                'image_url'     => $item['image_url'] ?? $item['image'] ?? null,
            ];
        }

        $shippingCost = round((float) $request->input('shipping_cost', 0), 2);
        $discount = round((float) ($order->discount ?? 0), 2);
        $total = max(0, round($subtotal + $shippingCost - $discount, 2));

        $order->update([
            'customer_name'    => trim($validated['customer_name']),
            'customer_phone'   => trim($validated['customer_phone']),
            'customer_address' => trim($validated['customer_address']),
            'governorate'      => trim($validated['governorate'] ?? $order->governorate),
            'shipping_cost'    => $shippingCost,
            'subtotal'         => $subtotal,
            'total'            => $total,
            'items'            => $formattedItems,
            'notes'            => $request->filled('notes') ? trim($request->notes) : $order->notes,
        ]);

        return redirect()->back()->with('success', 'تم تعديل بيانات الطلب والمنتجات بنجاح ✓');
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled,fake',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // لو تحولت الحالة إلى ملغي أو طلب وهمي ولم تكن كذلك من قبل → استرجاع المخزون
        if (in_array($newStatus, ['cancelled', 'fake']) && !in_array($oldStatus, ['cancelled', 'fake'])) {
            $this->restoreOrderStock($order);
        }

        $updateData = ['status' => $newStatus];
        if ($request->filled('notes')) {
            $updateData['notes'] = ($order->notes ? $order->notes . "\n" : '') . $request->notes;
        }

        $order->update($updateData);

        // التحويل التلقائي لشركة الشحن عند تأكيد الطلب
        if (in_array($newStatus, ['confirmed', 'shipped']) && $oldStatus !== $newStatus) {
            $this->handleAutoDispatchShipping($order);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح ✓');
    }

    /**
     * معالجة التحويل التلقائي لشركة الشحن
     */
    protected function handleAutoDispatchShipping(Order $order): void
    {
        try {
            $enabled = (bool) \App\Models\Setting::get('auto_dispatch_shipping', false);
            if (!$enabled) return;

            $exists = \App\Models\Shipment::where('order_id', $order->id)->exists();
            if ($exists) return;

            $provider = \App\Models\Setting::get('auto_dispatch_provider', 'bosta');
            $shippingManager = new \App\Services\Shipping\ShippingManager();
            $shipment = $shippingManager->createShipment($order, $provider);

            if ($shipment && $order->status !== 'shipped') {
                $order->update(['status' => 'shipped']);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Auto dispatch to shipping failed for Order #{$order->id}: " . $e->getMessage());
        }
    }

    /**
     * إلغاء الطلب
     */
    public function cancel(Order $order)
    {
        if ($order->status !== 'cancelled') {
            $this->restoreOrderStock($order);
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->back()->with('success', 'تم إلغاء الطلب واسترجاع المخزون بنجاح ✓');
    }

    /**
     * استرجاع كميات المخزون للطلب الملغي
     */
    protected function restoreOrderStock(Order $order): void
    {
        if (empty($order->items) || !is_array($order->items)) {
            return;
        }

        foreach ($order->items as $item) {
            $product = Product::find($item['id'] ?? null);
            if ($product) {
                $qty = (int) ($item['quantity'] ?? $item['qty'] ?? 1);
                $selectedSize  = $item['selectedSize']  ?? null;
                $selectedColor = $item['selectedColor'] ?? null;
                $options       = is_array($item['options'] ?? null) ? $item['options'] : [];

                $product->incrementVariantStock($qty, $selectedSize, $selectedColor, $options);

                StockMovement::create([
                    'tenant_id'   => $product->tenant_id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'type'        => 'in',
                    'description' => 'استرجاع مخزون بسبب إلغاء الطلب #' . ($order->reference_number ?: $order->id),
                ]);
            }
        }
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
     * تصدير الطلبات المفتوحة فقط كـ CSV / PDF
     */
    public function export(Request $request)
    {
        $tenant = app(\App\Models\Tenant::class);
        $query  = Order::query();

        // إذا كان رصيد المحفظة غير كافٍ، قم بتصدير المفتوحة فقط
        if (($tenant->wallet_balance ?? 0) < 2) {
            $query->where('is_unlocked', true);
        }

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
     * عرض فاتورة الطلب للطباعة (للطلبات المفتوحة)
     */
    public function invoice(Order $order)
    {
        if (!$order->is_unlocked) {
            $tenant = app(\App\Models\Tenant::class);
            if (($tenant->wallet_balance ?? 0) < 2) {
                return redirect()->route('merchant.orders.index')->with('insufficient_balance', 'برجاء الشحن لرؤية وطباعة تفاصيل الأوردر (رصيدك الحالي غير كافٍ، المطلوب 2 ج.م).');
            }
            $tenant->decrement('wallet_balance', 2);
            \App\Models\WalletTransaction::create([
                'tenant_id'   => $tenant->id,
                'amount'      => 2,
                'type'        => 'debit',
                'description' => 'رسوم فتح ومعاينة تفاصيل الطلب رقم (' . $order->reference_number . ')',
            ]);
            $order->update(['is_unlocked' => true, 'unlocked_at' => now()]);
        }

        // تعليم الطلب كـ مطبوع
        if (!$order->is_printed) {
            $order->update(['is_printed' => true, 'printed_at' => now()]);
        }

        $order->items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?: 'Store';
        $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
            ?: (\App\Models\Setting::where('key', 'whatsapp')->value('value')
            ?: (auth()->user()?->phone ?: ''));

        return view('orders.invoice', compact('order', 'storeName', 'storePhone'));
    }

    /**
     * طباعة فواتير مجمعة للطلبات المحددة
     */
    public function bulkPrint(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)
            ->with(['shipment'])
            ->orderBy('id', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'لم يتم العثور على أي طلبات محددة للطباعة.');
        }

        // تحديث حالة الطباعة للطلبات المحددة
        Order::whereIn('id', $orders->pluck('id'))->update([
            'is_printed' => true,
            'printed_at' => now(),
        ]);

        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') 
            ?: (app(\App\Models\Tenant::class)->name ?: 'Store');
        $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
            ?: (\App\Models\Setting::where('key', 'whatsapp')->value('value')
            ?: (auth()->user()?->phone ?: ''));

        return view('orders.bulk-invoice', compact('orders', 'storeName', 'storePhone'));
    }

    /**
     * إرسال الطلبات المحددة لشركة الشحن
     */
    public function bulkShip(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer',
            'provider' => 'nullable|string',
        ]);

        $provider = $request->provider ?: \App\Models\Setting::get('auto_dispatch_provider', 'jnt');
        
        $orders = Order::whereIn('id', $request->order_ids)->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'لم يتم تحديد أي طلبات.'], 422);
        }

        $shippingManager = new \App\Services\Shipping\ShippingManager();
        $dispatched = 0;
        $failed = 0;
        $errors = [];

        foreach ($orders as $order) {
            // تحديث حالة الطلب إلى "مع شركة الشحن" فوراً
            $order->update(['status' => 'shipped']);

            // التحقق إذا كانت الشحنة مرسلة مسبقاً عبر API
            $exists = \App\Models\Shipment::where('order_id', $order->id)->exists();
            if ($exists) {
                $dispatched++;
                continue;
            }

            try {
                $shipment = $shippingManager->createShipment($order, $provider);
                if ($shipment) {
                    $dispatched++;
                } else {
                    $dispatched++; // تم تحويل الحالة حتى لو مفيش API شحن
                }
            } catch (\Throwable $e) {
                $dispatched++; // الحالة اتغيرت حتى لو API فشل
                $errors[] = "طلب #{$order->reference_number}: " . $e->getMessage();
            }
        }

        $msg = $failed > 0
            ? "تم إرسال {$dispatched} طلب بنجاح، وفشل {$failed} طلب."
            : "تم إرسال جميع الطلبات المحددة ({$dispatched}) لشركة الشحن بنجاح ✓";

        if ($request->wantsJson()) {
            return response()->json([
                'success'          => true,
                'dispatched_count' => $dispatched,
                'failed_count'     => $failed,
                'errors'           => $errors,
                'message'          => $msg,
            ]);
        }

        return redirect()->back()
            ->with($failed > 0 && $dispatched === 0 ? 'error' : 'success', $msg)
            ->with('shipping_errors', $errors);
    }

    /**
     * تحديث حالة الطلبات المحددة دفعة واحدة
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer',
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled,fake',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $newStatus = $request->status;

        foreach ($orders as $order) {
            if ($newStatus === 'cancelled' || $newStatus === 'fake') {
                if ($order->status !== 'cancelled' && $order->status !== 'fake') {
                    $this->restoreOrderStock($order);
                }
            }
            $order->update(['status' => $newStatus]);
        }

        $statusTitles = [
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'shipped' => 'مع شركة الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            'fake' => 'طلب وهمي',
        ];

        return redirect()->back()->with('success', "تم تحديث حالة {$orders->count()} طلب إلى '{$statusTitles[$newStatus]}' بنجاح ✓");
    }

    /**
     * تصدير الطلبات المحددة كملف Excel / CSV
     */
    public function bulkExport(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)
            ->with(['shipment'])
            ->orderBy('id', 'desc')
            ->get();

        $fileName = 'orders_export_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'رقم الطلب',
                'الرقم المرجعي',
                'اسم العميل',
                'رقم الهاتف',
                'المحافظة',
                'العنوان',
                'المنتجات',
                'المجموع',
                'سعر الشحن',
                'الإجمالي',
                'الحالة',
                'حالة الطباعة',
                'شركة الشحن',
                'رقم البوليصة (Tracking)',
                'ملاحظات',
                'تاريخ الإنشاء',
            ]);

            $statusTitles = [
                'pending' => 'في الانتظار',
                'confirmed' => 'مؤكد',
                'shipped' => 'مع شركة الشحن',
                'delivered' => 'تم التسليم',
                'cancelled' => 'ملغي',
                'fake' => 'طلب وهمي',
            ];

            foreach ($orders as $order) {
                $itemsArray = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
                $itemsStr = collect($itemsArray)->map(function ($item) {
                    $name = $item['name'] ?? $item['product_name'] ?? 'منتج';
                    $qty = $item['quantity'] ?? 1;
                    $size = !empty($item['size']) ? " [{$item['size']}]" : '';
                    $color = !empty($item['color']) ? " [{$item['color']}]" : '';
                    return "{$name} x{$qty}{$size}{$color}";
                })->implode(' | ');

                fputcsv($file, [
                    $order->id,
                    $order->reference_number,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->governorate,
                    $order->customer_address,
                    $itemsStr,
                    $order->subtotal ?? 0,
                    $order->shipping_cost ?? 0,
                    $order->total ?? 0,
                    $statusTitles[$order->status] ?? $order->status,
                    $order->is_printed ? 'تمت الطباعة' : 'غير مطبوع',
                    $order->shipment ? strtoupper($order->shipment->provider) : 'لم يُرسل',
                    $order->shipment ? $order->shipment->tracking_number : '',
                    $order->notes ?? '',
                    $order->created_at?->format('Y/m/d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * تحميل فاتورة الطلب كملف PDF (للطلبات المفتوحة)
     */
    public function downloadInvoice(Order $order)
    {
        if (!$order->is_unlocked) {
            $tenant = app(\App\Models\Tenant::class);
            if (($tenant->wallet_balance ?? 0) < 2) {
                return redirect()->route('merchant.orders.index')->with('insufficient_balance', 'برجاء الشحن لرؤية وتحميل تفاصيل الأوردر (رصيدك الحالي غير كافٍ، المطلوب 2 ج.م).');
            }
            $tenant->decrement('wallet_balance', 2);
            \App\Models\WalletTransaction::create([
                'tenant_id'   => $tenant->id,
                'amount'      => 2,
                'type'        => 'debit',
                'description' => 'رسوم فتح ومعاينة تفاصيل الطلب رقم (' . $order->reference_number . ')',
            ]);
            $order->update(['is_unlocked' => true, 'unlocked_at' => now()]);
        }

        $order->items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?: 'Store';
        $storePhone = \App\Models\Setting::where('key', 'phone')->value('value')
            ?: (\App\Models\Setting::where('key', 'whatsapp')->value('value')
            ?: (auth()->user()?->phone ?: ''));

        $pdf = \PDF::loadView('orders.invoice', compact('order', 'storeName', 'storePhone'));
        return $pdf->download('invoice-' . $order->reference_number . '.pdf');
    }
}
