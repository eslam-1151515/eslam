<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus.
     */
    public function index()
    {
        $menus = Menu::latest()->get()->map(function ($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'location' => $menu->location,
                'items' => $menu->items ?? [],
                'is_active' => (bool) $menu->is_active,
                'created_at' => $menu->created_at->format('Y-m-d H:i'),
            ];
        });

        // Fetch categories for menu item selector
        $categories = Category::select('id', 'name', 'name_ar', 'name_en')->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name_ar ?: $cat->name,
                'name_en' => $cat->name_en ?: $cat->name,
                'slug' => $cat->id, // store uses id or slug
            ];
        });

        // Fetch products for menu item selector
        $products = Product::select('id', 'name')->get()->map(function ($prod) {
            return [
                'id' => $prod->id,
                'name' => $prod->name,
            ];
        });

        // Static pages defined for storefront
        $staticPages = [
            ['name' => 'الرئيسية (Home)', 'url' => '/'],
            ['name' => 'المنتجات (Products)', 'url' => '/products.html'],
            ['name' => 'التصنيفات (Categories)', 'url' => '/categories.html'],
            ['name' => 'سلة المشتريات (Cart)', 'url' => '/cart.html'],
            ['name' => 'تتبع الطلب (Order Tracking)', 'url' => '/tracking.html'],
            ['name' => 'المفضلة (Wishlist)', 'url' => '/wishlist.html'],
            ['name' => 'تواصل معنا (Contact)', 'url' => '/contact.html'],
        ];

        return Inertia::render('Merchant/Menus/Index', [
            'menus' => $menus,
            'categories' => $categories,
            'products' => $products,
            'staticPages' => $staticPages,
        ]);
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->input('is_active', true);

        // If the location is header or footer or sidebar, ensure only one active menu per location
        if ($validated['is_active'] && in_array($validated['location'], ['header', 'footer', 'sidebar'])) {
            Menu::where('location', $validated['location'])->update(['is_active' => false]);
        }

        Menu::create($validated);

        return redirect()->back()->with('success', 'تم إنشاء القائمة بنجاح ✓');
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->input('is_active', $menu->is_active);

        // If the location is header or footer or sidebar and we are activating this menu, deactivate others
        if ($validated['is_active'] && in_array($validated['location'], ['header', 'footer', 'sidebar'])) {
            Menu::where('location', $validated['location'])
                ->where('id', '!=', $menu->id)
                ->update(['is_active' => false]);
        }

        $menu->update($validated);

        return redirect()->back()->with('success', 'تم تحديث القائمة بنجاح ✓');
    }

    /**
     * Toggle active state of the menu.
     */
    public function toggle(Menu $menu)
    {
        $newStatus = !$menu->is_active;

        if ($newStatus && in_array($menu->location, ['header', 'footer', 'sidebar'])) {
            Menu::where('location', $menu->location)
                ->where('id', '!=', $menu->id)
                ->update(['is_active' => false]);
        }

        $menu->update(['is_active' => $newStatus]);

        return redirect()->back()->with('success', $newStatus ? 'تم تفعيل القائمة ✓' : 'تم إلغاء تفعيل القائمة ✓');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->back()->with('success', 'تم حذف القائمة بنجاح ✓');
    }
}
