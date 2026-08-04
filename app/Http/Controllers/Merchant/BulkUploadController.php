<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\BulkUploadRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BulkUploadController extends Controller
{
    /**
     * عرض صفحة الرفع الجماعي للمنتجات
     */
    public function show()
    {
        return Inertia::render('Merchant/Products/BulkUpload');
    }

    /**
     * توليد وتحميل ملف CSV كقالب جاهز للرفع الجماعي للمنتجات
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // الأعمدة المطلوبة
        $columns = ['name', 'description', 'price', 'price_before', 'stock', 'sku', 'category_name', 'sizes', 'colors'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // إضافة UTF-8 BOM ليتعرف عليه إكسل ويدعم الحروف العربية بشكل صحيح
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // كتابة العناوين الرئيسية
            fputcsv($file, $columns);
            
            // كتابة سطرين تجريبيين للتوضيح للتاجر
            fputcsv($file, [
                'تيشيرت كاجوال قطن',
                'تيشيرت مريح وعالي الجودة مصنوع من القطن الفاخر مناسب لجميع المناسبات',
                '150.00',
                '200.00',
                '100',
                'TSH-COT-001',
                'ملابس رجالي',
                'S, M, L, XL',
                'أحمر, أزرق, أسود'
            ]);
            
            fputcsv($file, [
                'حذاء جري رياضي',
                'حذاء رياضي خفيف ومريح بتصميم عصري يناسب الجري والمشي الطويل',
                '350.00',
                '400.00',
                '50',
                'SH-RUN-002',
                'أحذية رياضية',
                '41, 42, 43, 44',
                'أسود, رمادي, أبيض'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * معالجة الملف المرفوع واستيراد المنتجات
     */
    public function import(BulkUploadRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($extension !== 'csv') {
            return back()->with('error', 'يجب أن يكون الملف بصيغة CSV فقط (مفصول بفاصلة)');
        }

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        
        if (!$handle) {
            return back()->with('error', 'تعذر فتح وقراءة الملف المرفوع');
        }

        // إزالة الـ BOM إذا كان موجوداً
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // قراءة السطر الأول للحصول على العناوين
        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'الملف فارغ أو غير صالح');
        }

        // تنظيف أسماء الأعمدة من المسافات وعلامات الاقتباس
        $header = array_map(function($h) {
            return trim(str_replace(['"', "'"], '', $h));
        }, $header);

        // التحقق من وجود الأعمدة الأساسية
        $requiredHeaders = ['name', 'price', 'stock', 'category_name'];
        $missingHeaders = [];
        foreach ($requiredHeaders as $req) {
            if (!in_array($req, $header)) {
                $missingHeaders[] = $req;
            }
        }

        if (count($missingHeaders) > 0) {
            fclose($handle);
            return back()->with('error', 'الملف يفتقد الأعمدة الإلزامية التالية: ' . implode(', ', $missingHeaders));
        }

        $importedCount = 0;
        $ignoredCount = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rowNumber++;

            // تجاهل السطور الفارغة تماماً
            if (count(array_filter($row)) === 0) {
                $ignoredCount++;
                continue;
            }

            // تسوية طول الصف مع عدد العناوين
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            } elseif (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }

            $data = array_combine($header, $row);
            
            // تنظيف الحقول من المسافات الزائدة
            $data = array_map('trim', $data);

            $rowErrors = [];

            // التحقق من اسم المنتج
            if (empty($data['name'])) {
                $rowErrors[] = 'اسم المنتج مطلوب';
            }

            // التحقق من سعر المنتج
            if (!isset($data['price']) || $data['price'] === '') {
                $rowErrors[] = 'السعر مطلوب';
            } elseif (!is_numeric($data['price']) || $data['price'] < 0) {
                $rowErrors[] = 'السعر يجب أن يكون رقماً أكبر من أو يساوي 0';
            }

            // التحقق من المخزون
            if (!isset($data['stock']) || $data['stock'] === '') {
                $rowErrors[] = 'الكمية (المخزون) مطلوبة';
            } elseif (!is_numeric($data['stock']) || $data['stock'] < 0) {
                $rowErrors[] = 'الكمية يجب أن تكون عدداً صحيحاً أكبر من أو يساوي 0';
            }

            // التحقق من القسم
            if (empty($data['category_name'])) {
                $rowErrors[] = 'اسم القسم مطلوب';
            }

            // إذا وجدنا أخطاء في السطر، نقوم بتسجيلها وتجاوز السطر
            if (count($rowErrors) > 0) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $data['name'] ?: 'منتج بدون اسم',
                    'messages' => $rowErrors
                ];
                $ignoredCount++;
                continue;
            }

            try {
                // مطابقة اسم القسم أو إنشاؤه
                $categoryName = $data['category_name'];
                $category = Category::where('name', $categoryName)
                    ->orWhere('name_ar', $categoryName)
                    ->first();

                if (!$category) {
                    $mainCategories = Category::getMainCategories();
                    $defaultMain = count($mainCategories) > 0 ? $mainCategories[0] : 'أخرى';
                    
                    $category = Category::create([
                        'name' => $categoryName,
                        'name_ar' => $categoryName,
                        'main_category' => $defaultMain,
                    ]);
                }

                // تحويل المقاسات والألوان المفصولة بفاصلة لمصفوفات JSON
                $sizes = null;
                if (!empty($data['sizes'])) {
                    $sizesArr = array_filter(array_map('trim', explode(',', $data['sizes'])));
                    if (count($sizesArr) > 0) {
                        $sizes = json_encode(array_values($sizesArr), JSON_UNESCAPED_UNICODE);
                    }
                }

                $colors = null;
                if (!empty($data['colors'])) {
                    $colorsArr = array_filter(array_map('trim', explode(',', $data['colors'])));
                    if (count($colorsArr) > 0) {
                        $colors = json_encode(array_values($colorsArr), JSON_UNESCAPED_UNICODE);
                    }
                }

                $price = (float) $data['price'];
                $priceBefore = !empty($data['price_before']) && is_numeric($data['price_before']) ? (float) $data['price_before'] : 0.0;

                // إنشاء المنتج (يُربط بالـ tenant_id تلقائياً عبر BelongsToTenant)
                Product::create([
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'] ?: null,
                    'price' => $price,
                    'price_after' => $price,
                    'price_before' => $priceBefore,
                    'stock' => (int) $data['stock'],
                    'sku' => $data['sku'] ?: null,
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'shipping_type' => 'governorate', // الافتراضي
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $rowNumber,
                    'name' => $data['name'] ?: 'منتج بدون اسم',
                    'messages' => ['فشل في الحفظ بقاعدة البيانات: ' . $e->getMessage()]
                ];
                $ignoredCount++;
            }
        }

        fclose($handle);

        return back()->with('import_result', [
            'success' => true,
            'imported' => $importedCount,
            'ignored' => $ignoredCount,
            'errors' => $errors
        ]);
    }
}
