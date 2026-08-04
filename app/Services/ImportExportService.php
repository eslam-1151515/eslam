<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportExportService
{
    /**
     * تصدير المنتجات إلى صيغة CSV
     */
    public function exportProducts(): string
    {
        $products = Product::with('category')->get();

        $headers = [
            'الرقم (ID)',
            'اسم المنتج',
            'الوصف',
            'التصنيف (Category)',
            'السعر الحالي',
            'السعر قبل الخصم',
            'المخزون',
            'حد المخزون المنخفض',
            'نوع الشحن',
            'رابط الصورة',
            'المقاسات',
            'الألوان',
            'تاريخ الإنشاء'
        ];

        $rows = [];
        foreach ($products as $product) {
            $sizes = $product->sizes;
            if (is_string($sizes)) {
                $decoded = json_decode($sizes, true);
                if (is_array($decoded)) {
                    $sizes = implode(',', $decoded);
                }
            } elseif (is_array($sizes)) {
                $sizes = implode(',', $sizes);
            }

            $colors = $product->colors;
            if (is_string($colors)) {
                $decoded = json_decode($colors, true);
                if (is_array($decoded)) {
                    $colors = implode(',', $decoded);
                }
            } elseif (is_array($colors)) {
                $colors = implode(',', $colors);
            }

            $rows[] = [
                $product->id,
                $product->name,
                $product->description,
                $product->category ? $product->category->name : '',
                $product->price_after,
                $product->price_before,
                $product->stock,
                $product->low_stock_threshold,
                $product->shipping_type,
                $product->image_url ?? $product->main_image_path,
                $sizes,
                $colors,
                $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : ''
            ];
        }

        return $this->generateCsvContent($headers, $rows);
    }

    /**
     * تصدير العملاء إلى صيغة CSV (مجمعين من الطلبات بالهاتف)
     */
    public function exportCustomers(): string
    {
        $customers = Order::query()
            ->select(
                'customer_phone',
                DB::raw('MAX(customer_name) as customer_name'),
                DB::raw('MAX(customer_address) as customer_address'),
                DB::raw('MAX(governorate) as governorate'),
                DB::raw('COUNT(id) as orders_count'),
                DB::raw('SUM(total) as total_spent'),
                DB::raw('MAX(created_at) as last_order_at')
            )
            ->groupBy('customer_phone')
            ->orderBy('last_order_at', 'desc')
            ->get();

        $headers = [
            'اسم العميل',
            'رقم الهاتف',
            'العنوان',
            'المحافظة',
            'عدد الطلبات',
            'إجمالي المبيعات',
            'تاريخ آخر طلب'
        ];

        $rows = [];
        foreach ($customers as $customer) {
            $rows[] = [
                $customer->customer_name,
                $customer->customer_phone,
                $customer->customer_address,
                $customer->governorate,
                $customer->orders_count,
                round((float) $customer->total_spent, 2),
                $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('Y-m-d H:i:s') : ''
            ];
        }

        return $this->generateCsvContent($headers, $rows);
    }

    /**
     * تصدير الطلبات إلى صيغة CSV
     */
    public function exportOrders(): string
    {
        $orders = Order::latest()->get();

        $headers = [
            'الرقم المرجعي',
            'اسم العميل',
            'رقم الهاتف',
            'العنوان',
            'المحافظة',
            'طريقة الدفع',
            'تكلفة الشحن',
            'المجموع الفرعي',
            'الإجمالي',
            'حالة الطلب',
            'ملاحظات',
            'المنتجات المطلوبة',
            'تصنيفات المنتجات',
            'تاريخ الطلب'
        ];

        $rows = [];
        foreach ($orders as $order) {
            // تفاصيل المنتجات
            $productNames = [];
            $categoriesList = [];
            
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    $qty = $item['quantity'] ?? 1;
                    $name = $item['name'] ?? 'منتج غير معروف';
                    $price = $item['price'] ?? 0;
                    $opt = [];
                    if (!empty($item['size'])) $opt[] = "مقاس: " . $item['size'];
                    if (!empty($item['color'])) $opt[] = "لون: " . $item['color'];
                    
                    $optStr = count($opt) ? " (" . implode(' - ', $opt) . ")" : "";
                    $productNames[] = "{$name} x {$qty} [{$price} ج.م]{$optStr}";
                    
                    // جلب تصنيف المنتج لو أمكن
                    if (isset($item['id'])) {
                        $p = Product::with('category')->find($item['id']);
                        if ($p && $p->category) {
                            $categoriesList[] = $p->category->name;
                        }
                    }
                }
            }

            $categoriesList = array_unique($categoriesList);

            $rows[] = [
                $order->reference_number,
                $order->customer_name,
                $order->customer_phone,
                $order->customer_address,
                $order->governorate,
                $order->payment_method === 'cod' ? 'الدفع عند الاستلام' : $order->payment_method,
                $order->shipping_cost,
                $order->subtotal,
                $order->total,
                $this->translateStatus($order->status),
                $order->notes,
                implode(' | ', $productNames),
                implode(', ', $categoriesList),
                $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : ''
            ];
        }

        return $this->generateCsvContent($headers, $rows);
    }

    /**
     * استيراد المنتجات من ملف CSV
     */
    public function importProducts(string $filePath, string $source): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("ملف الاستيراد غير موجود.");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("تعذر فتح ملف الاستيراد.");
        }

        // قراءة السطر الأول (العناوين)
        // دعم قراءة التنسيقات بترميز UTF-8 وإزالة الـ BOM إن وجد
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $headers = fgetcsv($handle, 0, ',');
        // إذا كان هناك مشاكل بالفاصلة العادية، نحاول الفاصلة المنقوطة
        if (count($headers) <= 1) {
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") {
                fread($handle, 3);
            }
            $headers = fgetcsv($handle, 0, ';');
        }

        if (!$headers) {
            fclose($handle);
            throw new \Exception("الملف فارغ أو يحتوي على عناوين غير صالحة.");
        }

        $map = $this->getHeaderMap($headers, $source);

        if (!isset($map['name'])) {
            fclose($handle);
            throw new \Exception("لم نتمكن من العثور على عمود اسم المنتج. يرجى التحقق من الملف المرفوع.");
        }

        $importedCount = 0;
        $failedCount = 0;
        $errors = [];
        $lineNum = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, count($headers) > 1 ? ',' : ';')) !== false) {
                $lineNum++;
                // تخطي السطور الفارغة
                if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                    continue;
                }

                // موازنة الصف مع الرأس للتفادي من الأخطاء
                if (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                }

                try {
                    $productName = trim($row[$map['name']] ?? '');
                    if (empty($productName)) {
                        $failedCount++;
                        $errors[] = "السطر {$lineNum}: اسم المنتج فارغ.";
                        continue;
                    }

                    // الوصف
                    $description = isset($map['description']) ? trim($row[$map['description']] ?? '') : null;
                    if ($description !== null) {
                        // تنظيف أكواد HTML لو كان المصدر Shopify
                        if ($source === 'shopify') {
                            $description = strip_tags($description);
                        }
                    }

                    // القسم / التصنيف
                    $categoryName = isset($map['category']) ? trim($row[$map['category']] ?? '') : '';
                    $categoryId = null;
                    if (!empty($categoryName)) {
                        $categoryId = $this->resolveCategory($categoryName);
                    } else {
                        $categoryId = $this->resolveCategory('غير مصنف');
                    }

                    // معالجة الأسعار
                    $priceBefore = 0.0;
                    $priceAfter = 0.0;

                    if ($source === 'woocommerce') {
                        $regularPrice = isset($map['price_before']) ? $this->cleanPrice($row[$map['price_before']] ?? '') : 0.0;
                        $salePrice = isset($map['price_after']) ? $this->cleanPrice($row[$map['price_after']] ?? '') : 0.0;
                        
                        if ($salePrice > 0) {
                            $priceAfter = $salePrice;
                            $priceBefore = $regularPrice;
                        } else {
                            $priceAfter = $regularPrice;
                            $priceBefore = 0.0;
                        }
                    } else {
                        $priceAfter = isset($map['price_after']) ? $this->cleanPrice($row[$map['price_after']] ?? '') : 0.0;
                        $priceBefore = isset($map['price_before']) ? $this->cleanPrice($row[$map['price_before']] ?? '') : 0.0;
                    }

                    // الكمية والمخزون
                    $stock = isset($map['stock']) ? (int)trim($row[$map['stock']] ?? '0') : 10;
                    $lowStockThreshold = isset($map['low_stock_threshold']) ? (int)trim($row[$map['low_stock_threshold']] ?? '5') : 5;

                    // نوع الشحن
                    $shippingType = isset($map['shipping_type']) ? trim(strtolower($row[$map['shipping_type']] ?? '')) : 'free';
                    if (!in_array($shippingType, ['free', 'governorate'])) {
                        $shippingType = 'free';
                    }

                    // الصور
                    $imageUrl = isset($map['image_url']) ? trim($row[$map['image_url']] ?? '') : null;

                    // المقاسات والألوان
                    $sizes = [];
                    $colors = [];
                    if (isset($map['sizes']) && !empty($row[$map['sizes']])) {
                        $sizes = array_map('trim', explode(',', $row[$map['sizes']]));
                    }
                    if (isset($map['colors']) && !empty($row[$map['colors']])) {
                        $colors = array_map('trim', explode(',', $row[$map['colors']]));
                    }

                    // حفظ المنتج
                    Product::create([
                        'name' => $productName,
                        'description' => $description,
                        'category_id' => $categoryId,
                        'price' => $priceAfter,
                        'price_before' => $priceBefore,
                        'price_after' => $priceAfter,
                        'stock' => $stock,
                        'low_stock_threshold' => $lowStockThreshold,
                        'shipping_type' => $shippingType,
                        'image_url' => $imageUrl,
                        'sizes' => count($sizes) ? json_encode(array_values($sizes), JSON_UNESCAPED_UNICODE) : null,
                        'colors' => count($colors) ? json_encode(array_values($colors), JSON_UNESCAPED_UNICODE) : null,
                    ]);

                    $importedCount++;
                } catch (\Exception $ex) {
                    $failedCount++;
                    $errors[] = "السطر {$lineNum}: " . $ex->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }

        fclose($handle);

        return [
            'success' => true,
            'imported_count' => $importedCount,
            'failed_count' => $failedCount,
            'errors' => array_slice($errors, 0, 50) // إرجاع أول 50 خطأ فقط لتجنب الضخامة
        ];
    }

    /**
     * تنظيف وتحويل السعر إلى رقم عشري
     */
    private function cleanPrice($val): float
    {
        $val = trim((string)$val);
        $val = preg_replace('/[^\d\.]/', '', $val);
        return empty($val) ? 0.0 : (float)$val;
    }

    /**
     * جلب أو إنشاء القسم بشكل تلقائي
     */
    private function resolveCategory(string $categoryName): int
    {
        $categoryName = trim($categoryName);
        if (empty($categoryName)) {
            $categoryName = 'غير مصنف';
        }

        $category = Category::where('name', $categoryName)
            ->orWhere('name_ar', $categoryName)
            ->orWhere('name_en', $categoryName)
            ->first();

        if (!$category) {
            $category = Category::create([
                'name' => $categoryName,
                'name_ar' => $categoryName,
                'name_en' => $categoryName,
                'main_category' => 'مستحضرات تجميل' // تصنيف رئيسي افتراضي
            ]);
        }

        return $category->id;
    }

    /**
     * موازنة ومطابقة عناوين الأعمدة بناءً على المصدر
     */
    private function getHeaderMap(array $headers, string $source): array
    {
        $map = [];
        $normalizedHeaders = array_map(function($h) {
            return trim(strtolower((string)$h));
        }, $headers);

        $schema = [
            'name' => [
                'name', 'title', 'الاسم', 'اسم المنتج', 'product name', 'post_title', 'product_name'
            ],
            'description' => [
                'description', 'body', 'body (html)', 'الوصف', 'وصف المنتج', 'post_content', 'short description'
            ],
            'category' => [
                'category', 'type', 'categories', 'القسم', 'التصنيف', 'قسم'
            ],
            'price_after' => [
                'price', 'variant price', 'sale price', 'price_after', 'السعر', 'السعر الحالي', 'السعر بعد الخصم', 'sale_price'
            ],
            'price_before' => [
                'price_before', 'variant compare at price', 'regular price', 'regular_price', 'السعر قبل الخصم', 'السعر الأصلي'
            ],
            'stock' => [
                'stock', 'quantity', 'variant inventory qty', 'inventory', 'الكمية', 'المخزون', 'العدد', 'manage_stock'
            ],
            'low_stock_threshold' => [
                'low_stock_threshold', 'threshold', 'حد المخزون المنخفض', 'حد الأدنى للمخزون'
            ],
            'shipping_type' => [
                'shipping_type', 'shipping', 'نوع الشحن'
            ],
            'image_url' => [
                'image_url', 'image', 'images', 'image src', 'رابط الصورة', 'الصورة', 'رابط الصورة الرئيسية', 'images_url'
            ],
            'sizes' => [
                'sizes', 'size', 'المقاسات', 'المقاس'
            ],
            'colors' => [
                'colors', 'color', 'الألوان', 'اللون'
            ]
        ];

        foreach ($schema as $field => $aliases) {
            foreach ($normalizedHeaders as $index => $header) {
                if (in_array($header, $aliases)) {
                    $map[$field] = $index;
                    break;
                }
            }
        }

        // موازنات خاصة حسب المصدر
        if ($source === 'shopify') {
            if (!isset($map['name']) && in_array('title', $normalizedHeaders)) {
                $map['name'] = array_search('title', $normalizedHeaders);
            }
            if (!isset($map['description']) && in_array('body (html)', $normalizedHeaders)) {
                $map['description'] = array_search('body (html)', $normalizedHeaders);
            }
            if (!isset($map['price_after']) && in_array('variant price', $normalizedHeaders)) {
                $map['price_after'] = array_search('variant price', $normalizedHeaders);
            }
            if (!isset($map['price_before']) && in_array('variant compare at price', $normalizedHeaders)) {
                $map['price_before'] = array_search('variant compare at price', $normalizedHeaders);
            }
            if (!isset($map['stock']) && in_array('variant inventory qty', $normalizedHeaders)) {
                $map['stock'] = array_search('variant inventory qty', $normalizedHeaders);
            }
            if (!isset($map['image_url']) && in_array('image src', $normalizedHeaders)) {
                $map['image_url'] = array_search('image src', $normalizedHeaders);
            }
        } elseif ($source === 'woocommerce') {
            if (!isset($map['name']) && in_array('name', $normalizedHeaders)) {
                $map['name'] = array_search('name', $normalizedHeaders);
            }
            if (!isset($map['price_before']) && in_array('regular price', $normalizedHeaders)) {
                $map['price_before'] = array_search('regular price', $normalizedHeaders);
            }
            if (!isset($map['price_after']) && in_array('sale price', $normalizedHeaders)) {
                $map['price_after'] = array_search('sale price', $normalizedHeaders);
            }
        }

        return $map;
    }

    /**
     * توليد محتوى ملف CSV بترميز UTF-8 مع BOM
     */
    private function generateCsvContent(array $headers, array $rows): string
    {
        $output = fopen('php://temp', 'r+');
        
        // كتابة BOM لدعم العربية في Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        fputcsv($output, $headers);
        
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    /**
     * ترجمة حالة الطلب للغة العربية
     */
    private function translateStatus(string $status): string
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي'
        ];

        return $statuses[$status] ?? $status;
    }
}
