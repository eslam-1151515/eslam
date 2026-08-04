<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Services\ImportExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportExportTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء متجر تجريبي (Tenant)
        $this->tenant = Tenant::create([
            'uuid' => 'test-tenant-uuid',
            'name' => 'متجر التجربة',
            'slug' => 'teststore',
            'is_active' => true,
            'subscription_expires_at' => now()->addMonth(),
        ]);

        // تعيين المتجر الحالي في الجلسة والإعدادات لتفعيل BelongsToTenant
        session(['tenant_id' => $this->tenant->id]);
        config(['tenant.id' => $this->tenant->id]);
        app()->bind(Tenant::class, function () {
            return $this->tenant;
        });

        $this->service = new ImportExportService();
    }

    /**
     * اختبار تصدير المنتجات إلى CSV
     */
    public function test_export_products_generates_valid_csv()
    {
        $category = Category::create([
            'name' => 'ملابس',
            'name_ar' => 'ملابس',
            'main_category' => 'أجهزة عناية شخصية'
        ]);

        Product::create([
            'name' => 'تيشيرت قطن',
            'description' => 'وصف التيشيرت القطن المريح',
            'category_id' => $category->id,
            'price' => 150.00,
            'price_before' => 200.00,
            'price_after' => 150.00,
            'stock' => 30,
            'low_stock_threshold' => 5,
            'shipping_type' => 'free',
            'image_url' => 'https://example.com/tshirt.jpg',
            'sizes' => json_encode(['S', 'M']),
            'colors' => json_encode(['أحمر']),
        ]);

        $csv = $this->service->exportProducts();

        $this->assertStringContainsString("\xEF\xBB\xBF", $csv); // BOM
        $this->assertStringContainsString('اسم المنتج', $csv);
        $this->assertStringContainsString('تيشيرت قطن', $csv);
        $this->assertStringContainsString('ملابس', $csv);
        $this->assertStringContainsString('150', $csv);
    }

    /**
     * اختبار تصدير العملاء إلى CSV
     */
    public function test_export_customers_generates_valid_csv()
    {
        Order::create([
            'reference_number' => '12345',
            'customer_name' => 'أحمد علي',
            'customer_phone' => '01012345678',
            'customer_address' => 'شارع التحرير، القاهرة',
            'governorate' => 'القاهرة',
            'payment_method' => 'cod',
            'shipping_cost' => 50.00,
            'subtotal' => 300.00,
            'total' => 350.00,
            'status' => 'delivered',
            'items' => [
                ['id' => 1, 'name' => 'منتج تجريبي', 'price' => 150.00, 'quantity' => 2]
            ]
        ]);

        $csv = $this->service->exportCustomers();

        $this->assertStringContainsString('اسم العميل', $csv);
        $this->assertStringContainsString('أحمد علي', $csv);
        $this->assertStringContainsString('01012345678', $csv);
        $this->assertStringContainsString('350', $csv);
    }

    /**
     * اختبار تصدير الطلبات إلى CSV
     */
    public function test_export_orders_generates_valid_csv()
    {
        Order::create([
            'reference_number' => '98765',
            'customer_name' => 'محمد سمير',
            'customer_phone' => '01198765432',
            'customer_address' => 'الجيزة',
            'governorate' => 'الجيزة',
            'payment_method' => 'cod',
            'shipping_cost' => 40.00,
            'subtotal' => 200.00,
            'total' => 240.00,
            'status' => 'pending',
            'items' => [
                ['id' => 1, 'name' => 'قميص صوف', 'price' => 200.00, 'quantity' => 1]
            ]
        ]);

        $csv = $this->service->exportOrders();

        $this->assertStringContainsString('الرقم المرجعي', $csv);
        $this->assertStringContainsString('محمد سمير', $csv);
        $this->assertStringContainsString('98765', $csv);
        $this->assertStringContainsString('قيد الانتظار', $csv);
    }

    /**
     * اختبار استيراد منتجات بتنسيق فاست أوردر
     */
    public function test_import_products_fastorder()
    {
        $csvContent = "\xEF\xBB\xBFاسم المنتج,الوصف,القسم,السعر الحالي,السعر قبل الخصم,المخزون,رابط الصورة,المقاسات,الألوان\n";
        $csvContent .= "ساعة يد ذكية,ساعة ذكية مقاومة للماء,ساعات,450.00,600.00,15,https://example.com/watch.jpg,\"M,L\",أسود\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $result = $this->service->importProducts($tempFile, 'fastorder');

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['imported_count']);
        $this->assertEquals(0, $result['failed_count']);

        $product = Product::where('name', 'ساعة يد ذكية')->first();
        $this->assertNotNull($product);
        $this->assertEquals(450.00, $product->price_after);
        $this->assertEquals('ساعات', $product->category->name);
        $this->assertEquals(15, $product->stock);

        unlink($tempFile);
    }

    /**
     * اختبار استيراد منتجات بتنسيق Shopify
     */
    public function test_import_products_shopify()
    {
        $csvContent = "Title,Body (HTML),Type,Variant Price,Variant Compare At Price,Variant Inventory Qty,Image Src\n";
        $csvContent .= "Shopify Shoe,<b>Cool shoes</b>,Footwear,120.00,150.00,8,https://example.com/shoe.jpg\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $result = $this->service->importProducts($tempFile, 'shopify');

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['imported_count']);

        $product = Product::where('name', 'Shopify Shoe')->first();
        $this->assertNotNull($product);
        // التحقق من إزالة وسوم HTML من الوصف
        $this->assertEquals('Cool shoes', $product->description);
        $this->assertEquals(120.00, $product->price_after);
        $this->assertEquals(8, $product->stock);

        unlink($tempFile);
    }

    /**
     * اختبار استيراد منتجات بتنسيق WooCommerce
     */
    public function test_import_products_woocommerce()
    {
        $csvContent = "Name,Description,Categories,Regular price,Sale price,Stock,Images\n";
        $csvContent .= "Woo Product,Woo Description,Gadgets,100.00,80.00,20,https://example.com/woo.jpg\n";

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempFile, $csvContent);

        $result = $this->service->importProducts($tempFile, 'woocommerce');

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['imported_count']);

        $product = Product::where('name', 'Woo Product')->first();
        $this->assertNotNull($product);
        $this->assertEquals(80.00, $product->price_after);
        $this->assertEquals(100.00, $product->price_before);
        $this->assertEquals(20, $product->stock);

        unlink($tempFile);
    }
}
