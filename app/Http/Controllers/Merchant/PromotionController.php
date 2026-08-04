<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PromotionController extends Controller
{
    /**
     * عرض قائمة العروض والتخفيضات في لوحة تحكم التاجر
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $type = trim((string) $request->input('type', ''));

        $promotions = Promotion::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->when($type !== '', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $promotions->getCollection()->transform(function ($promo) {
            $promo->starts_at_formatted = $promo->starts_at ? $promo->starts_at->format('Y-m-d H:i') : null;
            $promo->ends_at_formatted = $promo->ends_at ? $promo->ends_at->format('Y-m-d H:i') : null;
            $promo->type_arabic = $promo->type_name_arabic;
            $promo->products_count = $promo->includedProducts->count();
            return $promo;
        });

        $stats = [
            'total' => Promotion::count(),
            'active' => Promotion::active()->count(),
            'flash_sales' => Promotion::flashSale()->count(),
            'seasonal' => Promotion::seasonal()->count(),
            'clearance' => Promotion::clearance()->count(),
            'bundle' => Promotion::bundle()->count(),
        ];

        return Inertia::render('Merchant/Promotions/Index', [
            'promotions' => $promotions,
            'filters' => [
                'q' => $q,
                'type' => $type,
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * صفحة إنشاء عرض ترويجي جديد
     */
    public function create()
    {
        $products = Product::select('id', 'name', 'price')->get();
        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Merchant/Promotions/Create', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * حفظ العرض الترويجي الجديد
     */
    public function store(StorePromotionRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        Promotion::create($validated);

        return redirect()->route('merchant.promotions.index')
            ->with('success', 'تم إنشاء العرض الترويجي بنجاح ✓');
    }

    /**
     * صفحة تعديل العرض الترويجي
     */
    public function edit(Promotion $promotion)
    {
        $products = Product::select('id', 'name', 'price')->get();
        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Merchant/Promotions/Edit', [
            'promotion' => $promotion,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * تحديث العرض الترويجي
     */
    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : $promotion->is_active;

        $promotion->update($validated);

        return redirect()->route('merchant.promotions.index')
            ->with('success', 'تم تحديث العرض الترويجي بنجاح ✓');
    }

    /**
     * تفعيل / تعطيل حالة العرض الترويجي
     */
    public function toggle(Promotion $promotion)
    {
        $promotion->update([
            'is_active' => !$promotion->is_active
        ]);

        $message = $promotion->is_active ? 'تم تفعيل العرض الترويجي بنجاح ✓' : 'تم تعطيل العرض الترويجي بنجاح ✓';
        return redirect()->route('merchant.promotions.index')->with('success', $message);
    }

    /**
     * حذف العرض الترويجي
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('merchant.promotions.index')
            ->with('success', 'تم حذف العرض الترويجي بنجاح ✓');
    }
}
