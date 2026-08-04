<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * عرض صفحة العروض والتخفيضات (Blade View)
     */
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant') ?? Tenant::first();
        $theme  = $this->getThemeData($tenant);

        $query = Promotion::query()->active();
        if ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }

        if ($request->filled('type') && in_array($request->type, ['flash_sale', 'seasonal', 'clearance', 'bundle'])) {
            $query->where('type', $request->type);
        }

        $promotions = $query->orderBy('ends_at', 'asc')->get();

        // إرفاق المنتجات وحساب الأسعار وتواريخ الانتهاء لكل عرض
        foreach ($promotions as $promo) {
            $products = $promo->includedProducts;
            foreach ($products as $product) {
                $product->discounted_price = $promo->calculateDiscountedPrice($product->price);
                $product->discount_amount = $promo->calculateDiscountAmount($product->price);
                $product->discount_badge = $promo->discount_badge_text;
                
                if ($product->price > 0 && $product->discounted_price < $product->price) {
                    $percentage = round((($product->price - $product->discounted_price) / $product->price) * 100);
                    $product->saved_percentage = $percentage . '%';
                } else {
                    $product->saved_percentage = '0%';
                }
            }
            $promo->setRelation('products_list', $products);
        }

        $flashSales = $promotions->where('type', 'flash_sale');
        $seasonalPromos = $promotions->where('type', 'seasonal');
        $clearancePromos = $promotions->where('type', 'clearance');
        $bundlePromos = $promotions->where('type', 'bundle');

        return view('shop.promotions', compact(
            'tenant',
            'theme',
            'promotions',
            'flashSales',
            'seasonalPromos',
            'clearancePromos',
            'bundlePromos'
        ));
    }

    /**
     * عرض تفاصيل عرض ترويجي محدد (Blade View أو JSON)
     */
    public function show(Request $request, $id)
    {
        $tenant = $request->attributes->get('tenant') ?? Tenant::first();
        $theme  = $this->getThemeData($tenant);

        $promotion = Promotion::query();
        if ($tenant) {
            $promotion->where('tenant_id', $tenant->id);
        }
        $promotion = $promotion->findOrFail($id);

        if (!$promotion->isValid()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'هذا العرض انتهى أو غير متاح حالياً'], 400);
            }
            return redirect()->route('shop.promotions')->with('error', 'هذا العرض غير متاح حالياً');
        }

        $products = $promotion->includedProducts;
        foreach ($products as $product) {
            $product->discounted_price = $promotion->calculateDiscountedPrice($product->price);
            $product->discount_amount = $promotion->calculateDiscountAmount($product->price);
            $product->discount_badge = $promotion->discount_badge_text;
            if ($product->price > 0 && $product->discounted_price < $product->price) {
                $percentage = round((($product->price - $product->discounted_price) / $product->price) * 100);
                $product->saved_percentage = $percentage . '%';
            } else {
                $product->saved_percentage = '0%';
            }
        }
        $promotion->setRelation('products_list', $products);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'promotion' => $promotion,
                'products' => $products,
                'remaining_seconds' => $promotion->remaining_seconds,
            ]);
        }

        return view('shop.promotions', [
            'tenant' => $tenant,
            'theme' => $theme,
            'promotions' => collect([$promotion]),
            'flashSales' => $promotion->type === 'flash_sale' ? collect([$promotion]) : collect(),
            'seasonalPromos' => $promotion->type === 'seasonal' ? collect([$promotion]) : collect(),
            'clearancePromos' => $promotion->type === 'clearance' ? collect([$promotion]) : collect(),
            'bundlePromos' => $promotion->type === 'bundle' ? collect([$promotion]) : collect(),
        ]);
    }

    /**
     * API: جلب العروض النشطة بصيغة JSON (للتطبيقات والـ AJAX)
     */
    public function apiIndex(Request $request)
    {
        $tenant = $request->attributes->get('tenant') ?? Tenant::first();
        
        $query = Promotion::query()->active();
        if ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $promotions = $query->orderBy('ends_at', 'asc')->get()->map(function ($promo) {
            return [
                'id' => $promo->id,
                'name' => $promo->name,
                'type' => $promo->type,
                'type_arabic' => $promo->type_name_arabic,
                'discount_type' => $promo->discount_type,
                'discount_value' => $promo->discount_value,
                'discount_badge' => $promo->discount_badge_text,
                'starts_at' => $promo->starts_at ? $promo->starts_at->toIso8601String() : null,
                'ends_at' => $promo->ends_at ? $promo->ends_at->toIso8601String() : null,
                'remaining_seconds' => $promo->remaining_seconds,
                'banner_image' => $promo->banner_image,
                'products_count' => $promo->includedProducts->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $promotions,
        ]);
    }

    /**
     * API: حساب السعر المخفض لمنتج معين
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'promotion_id' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($request->product_id);
        $tenantId = $product->tenant_id;

        $promotion = null;
        if ($request->filled('promotion_id')) {
            $promotion = Promotion::where('tenant_id', $tenantId)
                ->where('id', $request->promotion_id)
                ->active()
                ->first();
        } else {
            // البحث عن أفضل عرض نشط ينطبق على هذا المنتج
            $activePromos = Promotion::where('tenant_id', $tenantId)->active()->get();
            foreach ($activePromos as $promo) {
                if ($promo->appliesToProduct($product)) {
                    $promotion = $promo;
                    break; // نأخذ أول عرض أو يمكن المفاضلة بينها
                }
            }
        }

        if (!$promotion || !$promotion->appliesToProduct($product)) {
            return response()->json([
                'success' => true,
                'has_promotion' => false,
                'original_price' => (float) $product->price,
                'discounted_price' => (float) $product->price,
                'discount_amount' => 0,
                'badge' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'has_promotion' => true,
            'promotion_id' => $promotion->id,
            'promotion_name' => $promotion->name,
            'promotion_type' => $promotion->type,
            'original_price' => (float) $product->price,
            'discounted_price' => $promotion->calculateDiscountedPrice($product->price),
            'discount_amount' => $promotion->calculateDiscountAmount($product->price),
            'badge' => $promotion->discount_badge_text,
            'remaining_seconds' => $promotion->remaining_seconds,
        ]);
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
