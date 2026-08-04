<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * عرض قائمة التصنيفات مع التصنيفات الرئيسية والفرعية
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $categories = Category::with('parent', 'children')
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . $q . '%';
                $query->where(function ($qq) use ($like) {
                    $qq->where('name', 'like', $like)
                       ->orWhere('name_ar', 'like', $like)
                       ->orWhere('name_en', 'like', $like)
                       ->orWhere('description', 'like', $like);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // التصنيفات الرئيسية للـ dropdown
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name', 'name_ar']);

        $mainCategoryOptions = Category::getMainCategories();

        return Inertia::render('Merchant/Categories/Index', [
            'categories'          => $categories,
            'parentCategories'    => $parentCategories,
            'mainCategoryOptions' => $mainCategoryOptions,
            'filters'             => ['q' => $q],
        ]);
    }

    /**
     * حفظ تصنيف جديد
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = \App\Services\ImageCompressionService::compressAndStore($request->file('image'), 'categories', 'public');
            $validated['image_path'] = $path;
        }

        $validated['name'] = $validated['name_ar'];

        // tenant_id يُعبأ تلقائياً عبر BelongsToTenant trait
        Category::create($validated);

        return redirect()->route('merchant.categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح ✓');
    }

    /**
     * تحديث التصنيف
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
                Storage::disk('public')->delete($category->image_path);
            }
            $path = \App\Services\ImageCompressionService::compressAndStore($request->file('image'), 'categories', 'public');
            $validated['image_path'] = $path;
        }

        $validated['name'] = $validated['name_ar'];

        $category->update($validated);

        return redirect()->route('merchant.categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح ✓');
    }

    /**
     * حذف التصنيف
     */
    public function destroy(Category $category)
    {
        // تحقق أنه لا توجد منتجات مرتبطة
        if ($category->products()->count() > 0) {
            return redirect()->route('merchant.categories.index')
                ->with('error', 'لا يمكن حذف هذا التصنيف لأنه يحتوي على منتجات');
        }

        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('merchant.categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح ✓');
    }
}
