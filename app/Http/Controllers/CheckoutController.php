<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingGovernorate;
use App\Http\Requests\StoreCheckoutOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * عرض صفحة الـ Checkout (Blade)
     */
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $theme  = $this->getThemeData($tenant);

        return view('shop.checkout', compact('tenant', 'theme'));
    }

    /**
     * معالجة تأكيد الطلب (JSON API)
     */
    public function store(StoreCheckoutOrderRequest $request)
    {
        // التحقق من طلب فحص الكوبون فقط عبر AJAX
        if ($request->input('action') === 'check_coupon' || $request->has('check_coupon_only')) {
            $couponCode = $request->input('coupon_code') ?? $request->input('code');
            $orderValue = (float) $request->input('subtotal', 0);

            $tenantId = optional($request->attributes->get('tenant'))->id;
            $couponQuery = \App\Models\Coupon::where('code', $couponCode)->active();
            if ($tenantId) {
                $couponQuery->where('tenant_id', $tenantId);
            }
            $coupon = $couponQuery->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير صحيح أو منتهي الصلاحية',
                ], 400);
            }

            if (!$coupon->isValidForOrder($orderValue)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير صالح أو لا يستوفي الحد الأدنى لقيمة الطلب',
                ], 400);
            }

            $discount = $coupon->calculateDiscount($orderValue);

            return response()->json([
                'success'  => true,
                'message'  => 'تم تطبيق كوبون الخصم بنجاح',
                'discount' => $discount,
                'code'     => $coupon->code,
                'type'     => $coupon->type,
                'value'    => $coupon->value,
            ]);
        }

        try {
            $validated = $request->validated();

            $governorate = ShippingGovernorate::findOrFail($validated['governorate_id']);

            if (!$governorate->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'المحافظة المختارة غير متاحة حالياً',
                ], 400);
            }

            $subtotal          = 0;
            $orderItems        = [];
            $hasNonFreeShipping = false;

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['id']);

                if ($product && ($product->shipping_type ?? 'free') !== 'free') {
                    $hasNonFreeShipping = true;
                }

                // التحقق من المخزون
                if ($product && $product->stock < $item['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => "المنتج \"{$product->name}\" غير متوفر بالكمية المطلوبة. المتاح: {$product->stock}",
                    ], 422);
                }

                $itemTotal  = (float) $item['price'] * (int) $item['qty'];
                $subtotal  += $itemTotal;

                $options = $item['options'] ?? null;
                $pieces  = $item['piecesSelections'] ?? null;

                // إذا كانت خيارات قطع متعددة موجودة بدون تجميع الـ options
                if (is_array($pieces) && count($pieces) > 1) {
                    $optKeys = [];
                    foreach ($pieces as $p) {
                        if (!empty($p['options']) && is_array($p['options'])) {
                            foreach (array_keys($p['options']) as $k) {
                                $optKeys[$k] = true;
                            }
                        }
                    }
                    if (!empty($optKeys)) {
                        $options = is_array($options) ? $options : [];
                        foreach (array_keys($optKeys) as $k) {
                            if (empty($options[$k]) || !str_contains((string)$options[$k], ' | ')) {
                                $combinedOpts = [];
                                foreach ($pieces as $idx => $p) {
                                    $val = $p['options'][$k] ?? '-';
                                    $combinedOpts[] = 'ق' . ($idx + 1) . ': ' . $val;
                                }
                                $options[$k] = implode(' | ', $combinedOpts);
                            }
                        }
                    }
                }

                $orderItems[] = [
                    'id'               => $item['id'],
                    'name'             => $item['name'],
                    'price'            => (float) $item['price'],
                    'quantity'         => (int) $item['qty'],
                    'total'            => $itemTotal,
                    'image'            => $product?->main_image_path
                                            ? asset('storage/' . $product->main_image_path)
                                            : ($product?->image_url ?? null),
                    'selectedSize'     => $item['selectedSize']  ?? null,
                    'selectedColor'    => $item['selectedColor'] ?? null,
                    'options'          => $options,
                    'piecesSelections' => $pieces,
                ];
            }

            // حساب الخصم إذا تم استخدام كوبون
            $discount   = 0;
            $couponCode = $validated['coupon_code'] ?? null;
            if ($couponCode) {
                $tenantId = optional($request->attributes->get('tenant'))->id;
                $couponQuery = \App\Models\Coupon::where('code', $couponCode)->active();
                if ($tenantId) {
                    $couponQuery->where('tenant_id', $tenantId);
                }
                $coupon = $couponQuery->first();
                if ($coupon && $coupon->isValidForOrder($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $coupon->increment('uses_count');
                }
            }

            $shippingCost = $hasNonFreeShipping ? (float) $governorate->price : 0;
            $total        = max(0, $subtotal - $discount + $shippingCost);

            $notes = $validated['notes'] ?? '';
            if ($discount > 0) {
                $notes .= ($notes ? "\n" : "") . "[كوبون خصم: {$couponCode} | الخصم: {$discount} ج.م]";
            }

            DB::beginTransaction();

            $order = Order::createWithReference([
                'tenant_id'        => optional($request->attributes->get('tenant'))->id,
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'],
                'governorate'      => $governorate->name,
                'payment_method'   => $validated['payment_method'] ?? 'cod',
                'shipping_cost'    => $shippingCost,
                'items'            => $orderItems,
                'subtotal'         => $subtotal,
                'total'            => $total,
                'status'           => 'pending',
                'notes'            => $notes ?: null,
            ]);

            // تحديث حالة السلة المتروكة إلى مستردة (Recovered)
            try {
                $tenantId = optional($request->attributes->get('tenant'))->id;
                \App\Models\AbandonedCart::where('tenant_id', $tenantId)
                    ->whereNull('recovered_at')
                    ->where(function ($query) use ($validated) {
                        $query->where('session_id', session()->getId())
                            ->orWhere('email', $validated['customer_email'] ?? '___none___')
                            ->orWhere('phone', $validated['customer_phone'] ?? '___none___')
                            ->orWhere(function ($q) {
                                if (auth()->check()) {
                                    $q->where('user_id', auth()->id());
                                }
                            });
                    })
                    ->update(['recovered_at' => now()]);
            } catch (\Exception $e) {
                \Log::warning('Failed to mark abandoned cart as recovered: ' . $e->getMessage());
            }

            // تقليل المخزون
            foreach ($validated['items'] as $item) {
                $prod = Product::find($item['id']);
                if ($prod) {
                    $pieces = OrderController::parsePieceSelections($item);
                    $deductedQty = 0;

                    if (!empty($pieces)) {
                        $itemQty = (int) ($item['qty'] ?? 1);
                        foreach ($pieces as $piece) {
                            $pSize  = $piece['size'] ?? null;
                            $pColor = $piece['color'] ?? null;
                            $pOpts  = is_array($piece['options'] ?? null) ? $piece['options'] : [];
                            $prod->decrementVariantStock($itemQty, $pSize, $pColor, $pOpts);
                            $deductedQty += $itemQty;
                        }
                    } else {
                        $selectedSize  = $item['selectedSize']  ?? null;
                        $selectedColor = $item['selectedColor'] ?? null;
                        $options       = is_array($item['options'] ?? null) ? $item['options'] : [];
                        $itemQty       = (int) ($item['qty'] ?? 1);

                        $prod->decrementVariantStock($itemQty, $selectedSize, $selectedColor, $options);
                        $deductedQty   = $itemQty;
                    }

                    // تسجيل حركة المخزون (صادر)
                    $optDesc = '';
                    if (!empty($item['options']) && is_array($item['options'])) {
                        foreach ($item['options'] as $optK => $optV) {
                            if ($optV) {
                                $optDesc .= " | {$optK}: {$optV}";
                            }
                        }
                    }

                    \App\Models\StockMovement::create([
                        'tenant_id'   => $prod->tenant_id,
                        'product_id'  => $prod->id,
                        'quantity'    => $deductedQty,
                        'type'        => 'out',
                        'description' => "مبيعات الطلب رقم #{$order->reference_number}"
                            . (!empty($item['selectedSize'])  ? " | مقاس: {$item['selectedSize']}"  : '')
                            . (!empty($item['selectedColor']) ? " | لون: {$item['selectedColor']}" : '')
                            . $optDesc,
                    ]);

                    // إرسال تنبيه في حال انخفاض المخزون عن الحد المحدد
                    if ($prod->stock <= $prod->low_stock_threshold) {
                        $tenant = \App\Models\Tenant::find($order->tenant_id);
                        if ($tenant && $tenant->owner) {
                            try {
                                $tenant->owner->notify(new \App\Notifications\LowStockNotification($prod, $prod->stock));
                            } catch (\Exception $e) {
                                // لا نعطل العملية بسبب فشل إرسال التنبيه
                            }
                        }
                    }
                }
            }

            DB::commit();

            // Webhook
            try {
                \App\Services\WebhookSender::trigger('order.created', $order->toArray(), $order->tenant_id);
            } catch (\Throwable $e) {
                // لا نوقف الطلب بسبب فشل الـ webhook
            }

            return response()->json([
                'success'          => true,
                'message'          => 'تم إنشاء طلبك بنجاح',
                'reference_number' => $order->reference_number,
                'redirect'         => '/order-success/' . $order->reference_number . '?clear_cart=1',
                'data'             => [
                    'reference_number' => $order->reference_number,
                    'total'            => $order->total,
                    'order_id'         => $order->id,
                    'discount'         => $discount,
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CheckoutController::store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلبك، يرجى المحاولة مرة أخرى.',
            ], 500);
        }
    }

    /**
     * صفحة نجاح الطلب (Blade)
     */
    public function success(Request $request, string $referenceNumber)
    {
        $tenant = $request->attributes->get('tenant');

        $query = Order::where('reference_number', $referenceNumber);
        if ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }
        $order = $query->firstOrFail();

        $theme = $this->getThemeData($tenant);

        return view('shop.order-success', compact('tenant', 'theme', 'order'));
    }

    /**
     * استخراج بيانات الثيم من إعدادات الـ tenant
     */
    private function getThemeData($tenant): array
    {
        if (!$tenant) {
            return [
                'primary_color'   => '#6c63ff',
                'secondary_color' => '#ff6584',
                'font_family'     => 'Cairo',
            ];
        }

        $settings = is_array($tenant->settings)
            ? $tenant->settings
            : json_decode($tenant->settings ?? '{}', true);

        return [
            'primary_color'   => $settings['primary_color']   ?? '#6c63ff',
            'secondary_color' => $settings['secondary_color'] ?? '#ff6584',
            'font_family'     => $settings['font_family']     ?? 'Cairo',
        ];
    }
}
