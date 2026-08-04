<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCart;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontCartRecoveryController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /**
     * استعادة السلة المتروكة وتوجيه العميل لصفحة إتمام الطلب
     */
    public function recover(Request $request, string $token)
    {
        $tenant = $request->attributes->get('tenant');
        $tenantId = $tenant?->id;

        $abandonedCart = AbandonedCart::where('recovery_token', $token)
            ->whereNull('recovered_at')
            ->first();

        if (!$abandonedCart) {
            return redirect()->to('/shop')->with('error', 'رابط استعادة السلة غير صالح أو منتهي الصلاحية.');
        }

        // الحصول على سلة المستخدم الحالية
        $cart = $this->cartService->getCart($tenantId);

        // مسح السلة الحالية قبل الاستعادة
        $this->cartService->clearCart($cart);

        // إرجاع العناصر من البيانات المخزنة
        $items = $abandonedCart->cart_data['items'] ?? [];
        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $quantity = $item['quantity'] ?? 1;
            $variantId = $item['product_variant_id'] ?? null;
            
            if ($productId) {
                try {
                    $this->cartService->addItem($cart, $productId, $quantity, $variantId);
                } catch (\Exception $e) {
                    // تخطي المنتجات المحذوفة أو غير المتوفرة
                }
            }
        }

        // إذا كان هناك كود خصم مرتبط بالسلة، نقوم بتطبيقه تلقائياً
        // يمكن حفظ كود الخصم في بيانات السلة أو إرساله كـ query param
        if ($request->has('coupon')) {
            $this->cartService->applyCoupon($cart, $request->query('coupon'), $tenantId);
        }

        // توجيه المستخدم لصفحة إتمام الطلب مباشرة
        return redirect()->route('storefront.checkout');
    }

    /**
     * تتبع البيانات الجزئية أثناء كتابتها في صفحة Checkout لعملاء الزوار (Guest)
     */
    public function trackPartial(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $tenant = $request->attributes->get('tenant');
        $tenantId = $tenant?->id;
        $cart = $this->cartService->getCart($tenantId);

        if ($cart->activeItems()->count() === 0) {
            return response()->json(['success' => false, 'message' => 'السلة فارغة']);
        }

        $email = $request->input('email');
        $phone = $request->input('phone');

        // تحضير بيانات السلة الحالية
        $itemsData = $cart->activeItems->map(fn($item) => [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'name' => $item->product?->name ?? 'منتج غير معروف',
            'price' => (float) $item->price,
            'quantity' => $item->quantity,
            'total' => (float) $item->total,
            'image' => $item->product?->main_image_path
                ? asset('storage/' . $item->product->main_image_path)
                : ($item->product?->image_url ?? null),
        ])->toArray();

        $subtotal = $cart->subtotal;
        $settings = is_array($tenant->settings) ? $tenant->settings : json_decode($tenant->settings ?? '{}', true);
        $taxRate = (float) ($settings['tax_rate'] ?? $settings['tax'] ?? 0);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = round($subtotal + $taxAmount, 2);

        $cartData = [
            'items' => $itemsData,
            'subtotal' => $subtotal,
            'tax' => $taxAmount,
            'tax_rate' => $taxRate,
            'total' => $total,
        ];

        // البحث أو إنشاء سجل سلة متروكة نشط للجلسة الحالية
        $abandonedCart = AbandonedCart::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'session_id' => session()->getId(),
                'recovered_at' => null,
            ],
            [
                'user_id' => auth()->id(),
                'email' => $email ?: null,
                'phone' => $phone ?: null,
                'cart_data' => $cartData,
                'recovery_token' => AbandonedCart::where('session_id', session()->getId())->whereNull('recovered_at')->value('recovery_token') ?? Str::random(40),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ البيانات بنجاح',
            'token' => $abandonedCart->recovery_token,
        ]);
    }
}
