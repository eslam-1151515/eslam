<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BannerController extends Controller
{
    /**
     * عرض قائمة البانرات
     */
    public function index()
    {
        $banners = Banner::ordered()->get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'link' => $banner->link,
                'order' => $banner->order,
                'is_active' => (bool) $banner->is_active,
                'image_path' => $banner->image_path,
                'image_url' => $banner->image_path ? asset('storage/' . $banner->image_path) : null,
            ];
        });

        return Inertia::render('Merchant/Banners/Index', [
            'banners' => $banners,
        ]);
    }

    /**
     * حفظ بانر جديد
     */
    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = true;

        Banner::create($validated);

        return redirect()->route('banners.index')->with('success', 'تم إضافة البانر بنجاح ✓');
    }

    /**
     * تحديث بانر
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('success', 'تم تحديث البانر بنجاح ✓');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return redirect()->route('banners.index')->with('success', $banner->is_active ? 'تم تفعيل البانر ✓' : 'تم إلغاء تفعيل البانر ✓');
    }

    /**
     * حذف بانر
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'تم حذف البانر بنجاح ✓');
    }
}
