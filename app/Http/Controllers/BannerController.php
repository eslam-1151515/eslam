<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * عرض قائمة البانرات
     */
    public function index()
    {
        $banners = Banner::ordered()->get();
        return view('banners.index', compact('banners'));
    }

    /**
     * حفظ بانر جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'url', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.required' => 'صورة البانر مطلوبة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp, gif',
            'image.max' => 'الحد الأقصى لحجم الصورة 5 ميجابايت',
            'link.url' => 'يجب أن يكون الرابط صحيح',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = true;

        Banner::create($validated);

        return redirect()->route('banners.index')->with('status', 'تم إضافة البانر بنجاح');
    }

    /**
     * تحديث بانر
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'url', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp, gif',
            'image.max' => 'الحد الأقصى لحجم الصورة 5 ميجابايت',
            'link.url' => 'يجب أن يكون الرابط صحيح',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $banner->update($validated);

        return redirect()->route('banners.index')->with('status', 'تم تحديث البانر بنجاح');
    }

    /**
     * تبديل حالة التفعيل
     */
    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active,
            'message' => $banner->is_active ? 'تم تفعيل البانر' : 'تم إلغاء تفعيل البانر'
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'تم حذف البانر بنجاح'
        ]);
    }
}
