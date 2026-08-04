<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * عرض كل الصور المرفوعة الخاصة بالتاجر
     */
    public function index()
    {
        $mediaItems = [];

        // 1. صور المنتجات الأساسية
        $products = Product::whereNotNull('main_image_path')
            ->where('main_image_path', '!=', '')
            ->get();

        foreach ($products as $product) {
            $path = $product->main_image_path;
            $mediaItems[] = $this->formatMediaItem(
                $path,
                'منتج: ' . $product->name,
                'Product',
                $product->id,
                'main_image_path'
            );
        }

        // 2. صور معرض المنتجات الإضافية
        $productImages = ProductImage::whereIn('product_id', Product::pluck('id'))->get();
        foreach ($productImages as $prodImg) {
            $path = $prodImg->image_path;
            $mediaItems[] = $this->formatMediaItem(
                $path,
                'معرض صور للمنتج: ' . ($prodImg->product->name ?? 'غير معروف'),
                'ProductImage',
                $prodImg->id,
                'image_path'
            );
        }

        // 3. صور البانرات الترويجية
        $banners = Banner::whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->get();

        foreach ($banners as $banner) {
            $path = $banner->image_path;
            $mediaItems[] = $this->formatMediaItem(
                $path,
                'بنر ترويجي: ' . ($banner->title ?? 'بدون عنوان'),
                'Banner',
                $banner->id,
                'image_path'
            );
        }

        // 4. صور تصنيفات المتجر
        $categories = Category::whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->get();

        foreach ($categories as $category) {
            $path = $category->image_path;
            $mediaItems[] = $this->formatMediaItem(
                $path,
                'تصنيف: ' . ($category->name_ar ?? $category->name),
                'Category',
                $category->id,
                'image_path'
            );
        }

        // 5. شعار المتجر والـ favicon من جدول الإعدادات
        $settings = Setting::whereIn('key', ['logo', 'favicon'])
            ->whereNotNull('value')
            ->where('value', '!=', '')
            ->get();

        foreach ($settings as $setting) {
            $path = $setting->value;
            $label = $setting->key === 'logo' ? 'شعار المتجر' : 'أيقونة المتجر Favicon';
            $mediaItems[] = $this->formatMediaItem(
                $path,
                $label,
                'Setting',
                $setting->id,
                'value'
            );
        }

        // ترتيب العناصر من الأحدث للأقدم
        // بما أن الحجم أو تاريخ التعديل ليس متوفراً بسهولة لكل الملفات، يمكننا فقط عرضهم مجمعين
        // أو ترتيبهم حسب وجود الملف
        $mediaItems = array_values(array_filter($mediaItems));

        return Inertia::render('Merchant/Media/Index', [
            'mediaItems' => $mediaItems
        ]);
    }

    /**
     * تنسيق بيانات الصورة لترجع بشكل موحد
     */
    private function formatMediaItem($path, $source, $modelType, $modelId, $field)
    {
        // التحقق من وجود الملف في الـ Storage
        $exists = Storage::disk('public')->exists($path);
        
        // إذا كان الملف غير موجود إطلاقاً، قد نفضل عدم عرضه أو عرضه بحجم 0
        $sizeBytes = 0;
        if ($exists) {
            try {
                $sizeBytes = Storage::disk('public')->size($path);
            } catch (\Exception $e) {
                $sizeBytes = 0;
            }
        }

        // حساب الحجم بشكل مقروء
        $size = $this->formatBytes($sizeBytes);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = basename($path);

        return [
            'url' => asset('storage/' . $path),
            'path' => $path,
            'size' => $size,
            'size_bytes' => $sizeBytes,
            'type' => $extension ? strtolower($extension) : 'unknown',
            'filename' => $filename,
            'source' => $source,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'field' => $field
        ];
    }

    /**
     * تحويل الحجم من البايت إلى صيغة مقروءة
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * حذف الصورة من الهارد ديسك وقاعدة البيانات
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
            'model_type' => ['required', 'string', 'in:Product,ProductImage,Banner,Category,Setting'],
            'model_id' => ['required', 'integer'],
            'field' => ['required', 'string'],
        ]);

        $path = $validated['path'];
        $modelType = $validated['model_type'];
        $modelId = $validated['model_id'];
        $field = $validated['field'];

        // 1. حذف الملف من الـ Storage إذا كان موجوداً
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // 2. تحديث أو حذف السجل في قاعدة البيانات
        switch ($modelType) {
            case 'Product':
                $product = Product::find($modelId);
                if ($product && $product->main_image_path === $path) {
                    $product->update([$field => null]);
                }
                break;

            case 'ProductImage':
                $productImage = ProductImage::find($modelId);
                if ($productImage) {
                    $productImage->delete();
                }
                break;

            case 'Banner':
                $banner = Banner::find($modelId);
                if ($banner && $banner->image_path === $path) {
                    $banner->update([$field => null]);
                }
                break;

            case 'Category':
                $category = Category::find($modelId);
                if ($category && $category->image_path === $path) {
                    $category->update([$field => null]);
                }
                break;

            case 'Setting':
                $setting = Setting::find($modelId);
                if ($setting && $setting->value === $path) {
                    $setting->update([$field => null]);
                }
                break;
        }

        return redirect()->back()->with('success', 'تم حذف الصورة بنجاح ✓');
    }
}
