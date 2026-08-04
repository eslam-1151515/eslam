<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryReportController extends Controller
{
    /**
     * عرض تقرير المخزون وحركته
     */
    public function index(Request $request): Response
    {
        // 1. قائمة المنتجات التي أوشكت على النفاد
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->get(['id', 'name', 'stock', 'low_stock_threshold', 'price_after', 'main_image_path'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'stock' => $p->stock,
                    'low_stock_threshold' => $p->low_stock_threshold,
                    'price' => $p->price_after,
                    'image' => $p->main_image_path ? asset('storage/' . $p->main_image_path) : null,
                ];
            });

        // 2. سجل حركة المخزون
        $movements = StockMovement::with('product:id,name,main_image_path')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // 3. إجمالي قيمة المخزون الحالية (Stock valuation)
        $totalItems = Product::sum('stock');
        $totalValuation = Product::selectRaw('SUM(stock * price_after) as total_value')->value('total_value') ?? 0;
        $totalProductsCount = Product::count();
        $outOfStockCount = Product::where('stock', 0)->count();

        // قائمة بجميع المنتجات للاستخدام في نافذة التعديل اليدوي
        $allProducts = Product::orderBy('name')->get(['id', 'name', 'stock', 'price_after']);

        return Inertia::render('Merchant/Reports/Inventory', [
            'lowStockProducts' => $lowStockProducts,
            'movements'        => $movements,
            'allProducts'      => $allProducts,
            'stats' => [
                'total_items'      => (int) $totalItems,
                'total_valuation'  => round((float) $totalValuation, 2),
                'total_products'   => $totalProductsCount,
                'out_of_stock'     => $outOfStockCount,
            ]
        ]);
    }

    /**
     * تعديل يدوي للمخزون من صفحة التقارير
     */
    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'type'        => 'required|in:in,out,adjustment,return',
            'description' => 'nullable|string|max:500',
        ], [
            'product_id.required'  => 'المنتج مطلوب',
            'product_id.exists'    => 'المنتج المحدد غير موجود',
            'quantity.required'    => 'الكمية مطلوبة',
            'quantity.integer'     => 'الكمية يجب أن تكون رقماً صحيحاً',
            'quantity.min'         => 'الكمية يجب أن تكون 1 على الأقل',
            'type.required'        => 'نوع العملية مطلوب',
            'type.in'              => 'نوع العملية غير صحيح',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $oldStock = $product->stock;
        $qty = abs($validated['quantity']);

        // تعديل المخزون
        if ($validated['type'] === 'in' || $validated['type'] === 'return') {
            $product->increment('stock', $qty);
        } elseif ($validated['type'] === 'out') {
            if ($product->stock < $qty) {
                return redirect()->back()->with('error', 'الكمية المراد سحبها أكبر من الكمية المتوفرة حالياً!');
            }
            $product->decrement('stock', $qty);
        } else {
            // adjustment
            $product->stock = max(0, $product->stock + $request->input('quantity'));
            $product->save();
        }

        $newStock = $product->stock;

        // تسجيل حركة المخزون
        StockMovement::create([
            'product_id'  => $product->id,
            'quantity'    => abs($newStock - $oldStock),
            'type'        => $validated['type'],
            'description' => $validated['description'] ?? 'تعديل يدوي للمخزون من صفحة التقارير',
        ]);

        return redirect()->back()->with('success', 'تم تعديل المخزون بنجاح ✓');
    }
}
