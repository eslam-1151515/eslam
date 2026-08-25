<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRecommendation;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Setting;
use App\Models\ShippingGovernorate;
use App\Models\StockMovement;
use App\Models\AbandonedCart;
use App\Models\BlacklistRecord;
use App\Models\StoreRating;
use App\Models\Review;
use App\Models\Menu;
use App\Models\Banner;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\CacheService;

class SeedDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ordersaif:seed-demo {--tenant=demo : The subdomain slug for the demo store} {--force : Force delete existing demo data for this tenant}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'توليد بيانات تجريبية شاملة وواقعية للمنصة والمتاجر (تينانت) لتسهيل العرض والاختبار';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $slug = $this->option('tenant') ?: 'demo';
        $force = $this->option('force');

        $this->info("🚀 البدء في توليد البيانات التجريبية الشاملة للمتجر: '{$slug}'...");

        // 1. إيجاد أو إنشاء المستخدم التاجر المالك
        $owner = User::where('email', 'merchant@demo.com')->first();
        if (!$owner) {
            $owner = User::create([
                'name' => 'التاجر التجريبي',
                'email' => 'merchant@demo.com',
                'password' => Hash::make('password'),
                'user_type' => 'merchant',
                'is_active' => true,
            ]);
            $this->info("✅ تم إنشاء حساب تاجر تجريبي: merchant@demo.com / password");
        } else {
            $this->info("ℹ️ تم استخدام حساب التاجر الحالي: merchant@demo.com");
        }

        // 2. فحص التينانت (المتجر)
        $tenant = Tenant::where('slug', $slug)->first();
        
        if ($tenant && $force) {
            $this->warn("⚠️ تم العثور على المتجر '{$slug}'، وسيتم تنظيف جميع بياناته القديمة بسبب تفعيل خيار --force...");
            
            // تهيئة الجلسة للسماح بالوصول الكامل دون قيود النطاق
            session(['tenant_id' => $tenant->id]);
            config(['tenant.id' => $tenant->id]);

            // حذف الحركات والسجلات الفرعية بالتفصيل
            StockMovement::where('tenant_id', $tenant->id)->delete();
            ProductRecommendation::where('tenant_id', $tenant->id)->delete();
            Review::where('tenant_id', $tenant->id)->delete();
            StoreRating::where('tenant_id', $tenant->id)->delete();
            OrderReturn::where('tenant_id', $tenant->id)->delete();
            Order::where('tenant_id', $tenant->id)->delete();
            
            ProductImage::whereHas('product', function ($q) use ($tenant) {
                $q->where('tenant_id', $tenant->id);
            })->delete();
            
            Product::where('tenant_id', $tenant->id)->delete();
            Category::where('tenant_id', $tenant->id)->delete();
            AbandonedCart::where('tenant_id', $tenant->id)->delete();
            BlacklistRecord::where('tenant_id', $tenant->id)->delete();
            Menu::where('tenant_id', $tenant->id)->delete();
            Banner::where('tenant_id', $tenant->id)->delete();
            Setting::where('tenant_id', $tenant->id)->delete();
            ShippingGovernorate::where('tenant_id', $tenant->id)->delete();
            Subscription::where('tenant_id', $tenant->id)->delete();
            
            $this->info("🧹 تم تنظيف جميع السجلات السابقة بنجاح.");
        }

        if (!$tenant) {
            $tenant = Tenant::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'متجر فاست أوردر التجريبي',
                'slug' => $slug,
                'logo' => 'demo/logo.png',
                'favicon' => 'demo/favicon.png',
                'email' => 'store@demo.com',
                'phone' => '01146520922',
                'owner_id' => $owner->id,
                'subscription_status' => 'active',
                'trial_ends_at' => null,
                'subscription_ends_at' => Carbon::now()->addYear(),
                'is_active' => true,
                'theme_id' => 'default',
            ]);
            $this->info("✅ تم إنشاء تينانت جديد بنجاح باسم: 'متجر فاست أوردر التجريبي' وسلاغ: '{$slug}'");
        } else {
            $this->info("ℹ️ تم استخدام تينانت الحالي ذو سلاغ: '{$slug}'");
        }

        // ربط المستخدم بالمتجر
        DB::table('tenant_users')->updateOrInsert(
            ['tenant_id' => $tenant->id, 'user_id' => $owner->id],
            [
                'role' => 'manager',
                'permissions' => json_encode(['*']),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        $owner->update(['tenant_id' => $tenant->id]);

        // تهيئة سياق التينانت النشط للتطبيق بالكامل لضمان عمل Scopes و Mutators
        session(['tenant_id' => $tenant->id]);
        config(['tenant.id' => $tenant->id]);

        // 3. إعداد الاشتراك (Subscription)
        $plan = SubscriptionPlan::where('slug', 'pro')->first();
        if ($plan) {
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'billing_cycle' => 'yearly',
                'price' => $plan->price_yearly,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addYear(),
            ]);
            $this->info("✅ تم إعداد اشتراك سنوي نشط في 'الباقة الاحترافية' للمتجر.");
        }

        // 4. توليد إعدادات المتجر بالكامل (Store Settings)
        $this->info("⚙️ توليد إعدادات المتجر الكاملة...");
        $settings = [
            'store_name' => 'متجر فاست أوردر التجريبي',
            'phone' => '01146520922',
            'whatsapp' => '201146520922',
            'facebook_page' => 'https://facebook.com/demo.store',
            'facebook_pixel_id' => '1234567890',
            'tiktok_pixel_id' => '9876543210',
            'google_analytics_id' => 'UA-123456-1',
            'primary_color' => '#4f46e5',
            'secondary_color' => '#64748b',
            'accent_color' => '#f59e0b',
            'font_family' => 'Cairo',
            'active_theme' => 'default',
            'ghost_blocker_enabled' => '1',
            'phone_verification_enabled' => '1',
            'phone_verification_min_order' => '150',
            'blocked_emails' => 'spammer@example.com, fake@fake.com',
            'homepage_best_offers_limit' => '4',
            'homepage_latest_products_limit' => '4',
            'live_chat_provider' => 'whatsapp',
            'live_chat_whatsapp_number' => '201146520922',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $val, 'group' => 'general', 'type' => 'string']
            );
        }

        Setting::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => 'homepage_sections'],
            [
                'value' => json_encode([
                    ['id' => 'hero_slider', 'enabled' => true, 'title' => 'البانر الإعلاني', 'title_en' => 'Hero Slider'],
                    ['id' => 'featured_categories', 'enabled' => true, 'title' => 'الأقسام المميزة', 'title_en' => 'Featured Categories'],
                    ['id' => 'best_offers', 'enabled' => true, 'title' => 'أفضل العروض والخصومات', 'title_en' => 'Best Offers & Discounts'],
                    ['id' => 'latest_products', 'enabled' => true, 'title' => 'أحدث المنتجات', 'title_en' => 'Latest Products']
                ], JSON_UNESCAPED_UNICODE),
                'group' => 'homepage',
                'type' => 'json'
            ]
        );

        // 5. توليد أسعار الشحن للمحافظات (Shipping Rates)
        $this->info("🚚 توليد خطط الشحن للمحافظات...");
        $govs = [
            ['name' => 'القاهرة', 'price' => 45, 'is_active' => true],
            ['name' => 'الجيزة', 'price' => 45, 'is_active' => true],
            ['name' => 'الاسكندرية', 'price' => 55, 'is_active' => true],
            ['name' => 'الدقهلية', 'price' => 60, 'is_active' => true],
            ['name' => 'البحيرة', 'price' => 60, 'is_active' => true],
            ['name' => 'الغربية', 'price' => 60, 'is_active' => true],
            ['name' => 'المنوفية', 'price' => 60, 'is_active' => true],
            ['name' => 'القليوبية', 'price' => 50, 'is_active' => true],
            ['name' => 'الشرقية', 'price' => 60, 'is_active' => true],
            ['name' => 'بورسعيد', 'price' => 65, 'is_active' => true],
            ['name' => 'الاسماعيلية', 'price' => 65, 'is_active' => true],
            ['name' => 'السويس', 'price' => 65, 'is_active' => true],
            ['name' => 'المنيا', 'price' => 75, 'is_active' => true],
            ['name' => 'بني سويف', 'price' => 70, 'is_active' => true],
            ['name' => 'الفيوم', 'price' => 70, 'is_active' => true],
            ['name' => 'أسيوط', 'price' => 80, 'is_active' => true],
            ['name' => 'سوهاج', 'price' => 80, 'is_active' => true],
            ['name' => 'قنا', 'price' => 85, 'is_active' => true],
            ['name' => 'الأقصر', 'price' => 90, 'is_active' => true],
            ['name' => 'أسوان', 'price' => 95, 'is_active' => true],
            ['name' => 'البحر الأحمر', 'price' => 90, 'is_active' => true],
            ['name' => 'مطروح', 'price' => 85, 'is_active' => true],
        ];

        foreach ($govs as $gov) {
            ShippingGovernorate::create([
                'tenant_id' => $tenant->id,
                'name' => $gov['name'],
                'price' => $gov['price'],
                'is_active' => $gov['is_active'],
            ]);
        }

        // 6. توليد الأقسام (Categories)
        $this->info("📁 إنشاء الأقسام التجريبية...");
        $categoriesData = [
            [
                'name' => 'مستحضرات تجميل',
                'name_ar' => 'مستحضرات تجميل',
                'name_en' => 'Cosmetics',
                'description' => 'أفضل منتجات التجميل والعناية بالبشرة والشعر المصنوعة من مكونات طبيعية وآمنة',
                'image_path' => 'demo/categories/cosmetics.png',
                'main_category' => null,
            ],
            [
                'name' => 'أجهزة عناية شخصية',
                'name_ar' => 'أجهزة عناية شخصية',
                'name_en' => 'Personal Care Devices',
                'description' => 'أجهزة تصفيف الشعر، ماكينات الحلاقة والعناية بالجسم للرجال والنساء',
                'image_path' => 'demo/categories/personal_care.png',
                'main_category' => 'اجهزة عناية شخصية',
            ],
            [
                'name' => 'إلكترونيات واكسسوارات',
                'name_ar' => 'إلكترونيات واكسسوارات',
                'name_en' => 'Electronics & Accessories',
                'description' => 'اكسسوارات الهواتف، الساعات الذكية، الشواحن وسماعات البلوتوث اللاسلكية',
                'image_path' => 'demo/categories/electronics.png',
                'main_category' => 'اجهزة منزلية صغيرة',
            ],
            [
                'name' => 'أدوات مطبخ مبتكرة',
                'name_ar' => 'أدوات مطبخ مبتكرة',
                'name_en' => 'Innovative Kitchen Tools',
                'description' => 'أدوات تسهل عليك الطهي وتنظيم المطبخ بذكاء وسرعة',
                'image_path' => 'demo/categories/kitchen.png',
                'main_category' => 'اجهزة مطبخ',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[] = Category::create(array_merge($catData, ['tenant_id' => $tenant->id]));
        }

        // تحديث إعدادات الأقسام الرئيسية والمميزة بالمتجر
        $mainCatNames = collect($categoriesData)->pluck('name')->toArray();
        Setting::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => 'main_categories'],
            ['value' => json_encode($mainCatNames, JSON_UNESCAPED_UNICODE), 'group' => 'general', 'type' => 'json']
        );

        $featuredCatIds = collect($categories)->take(3)->pluck('id')->toArray();
        Setting::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => 'homepage_featured_categories'],
            ['value' => json_encode($featuredCatIds), 'group' => 'homepage', 'type' => 'json']
        );

        // 7. توليد المنتجات الشاملة مع صور افتراضية (Products & Images)
        $this->info("🛍️ توليد المنتجات الشاملة والخيارات (الأحجام والألوان)...");
        $products = [];

        // منتجات مستحضرات التجميل
        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[0]->id,
            'name' => 'سيروم الهيالورونيك أسيد الطبيعي',
            'description' => 'سيروم مركز يحتوي على الهيالورونيك أسيد النقي وفيتامين B5 لترطيب وتنعيم البشرة وعلاج التجاعيد البسيطة. مناسب لجميع أنواع البشرة.',
            'price' => 199.00,
            'price_before' => 280.00,
            'price_after' => 199.00,
            'stock' => 50,
            'low_stock_threshold' => 10,
            'shipping_type' => 'free',
            'image_url' => '/images/demo/products/serum.jpg',
            'main_image_path' => 'demo/products/serum.jpg',
        ]);

        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[0]->id,
            'name' => 'كريم مرطب للعناية الفائقة',
            'description' => 'كريم مرطب يومي غني بزبده الشيا وزيت الأرجان، يساعد في حماية حاجز البشرة الطبيعي ويوفر ترطيبًا يدوم لمدة ٢٤ ساعة كاملة.',
            'price' => 120.00,
            'price_before' => 180.00,
            'price_after' => 120.00,
            'stock' => 100,
            'low_stock_threshold' => 15,
            'shipping_type' => 'governorate',
            'image_url' => '/images/demo/products/moisturizer.jpg',
            'main_image_path' => 'demo/products/moisturizer.jpg',
        ]);

        // منتجات أجهزة العناية
        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[1]->id,
            'name' => 'ماكينة حلاقة وتشذيب ذكية قابلة للشحن',
            'description' => 'ماكينة حلاقة رجالية احترافية متعددة الاستخدامات مع شاشة ديجيتال لعرض مستوى البطارية، شفرات حادة مقاومة للصدأ، وتدعم الشحن السريع USB.',
            'price' => 450.00,
            'price_before' => 600.00,
            'price_after' => 450.00,
            'stock' => 25,
            'low_stock_threshold' => 5,
            'shipping_type' => 'free',
            'image_url' => '/images/demo/products/shaver.jpg',
            'main_image_path' => 'demo/products/shaver.jpg',
            'sizes' => json_encode(['قياسي']),
            'colors' => json_encode(['أسود', 'فضي']),
            'variants_stock' => json_encode(['قياسي-أسود' => 15, 'قياسي-فضي' => 10])
        ]);

        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[1]->id,
            'name' => 'مصفف ومجفف شعر احترافي 2 في 1',
            'description' => 'فرشاة مصفف ومجفف الشعر السيراميكية الاحترافية بقوة ١٢٠٠ واط، تعمل على فرد وتجفيف وتصفيف الشعر في نصف الوقت مع تقنية الأيونات السالبة لمنع التقصف.',
            'price' => 699.00,
            'price_before' => 999.00,
            'price_after' => 699.00,
            'stock' => 3, // كمية منخفضة للتنبيهات
            'low_stock_threshold' => 5,
            'shipping_type' => 'free',
            'image_url' => '/images/demo/products/hair_styler.jpg',
            'main_image_path' => 'demo/products/hair_styler.jpg',
        ]);

        // منتجات الإلكترونيات
        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[2]->id,
            'name' => 'ساعة ذكية رياضية مقاومة للماء',
            'description' => 'ساعة ذكية بشاشة أموليد ملونة، تدعم تتبع ضربات القلب ومستوى الأكسجين، مقاومة للمياه بتصنيف IP68، مع بطارية تدوم حتى ١٠ أيام متواصلة.',
            'price' => 550.00,
            'price_before' => 800.00,
            'price_after' => 550.00,
            'stock' => 40,
            'low_stock_threshold' => 8,
            'shipping_type' => 'free',
            'image_url' => '/images/demo/products/smartwatch.jpg',
            'main_image_path' => 'demo/products/smartwatch.jpg',
            'colors' => json_encode(['أسود', 'كحلي', 'رمادي']),
            'price_tiers' => json_encode([
                ['quantity' => 3, 'price' => 500.00],
                ['quantity' => 5, 'price' => 450.00],
            ])
        ]);

        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[2]->id,
            'name' => 'سماعات بلوتوث لاسلكية عازلة للضوضاء',
            'description' => 'سماعات أذن لاسلكية مزودة بتقنية إلغاء الضوضاء النشطة ANC، صوت ستيريو نقي وواضح، مع علبة شحن ذكية تدوم حتى ٣٠ ساعة تشغيل.',
            'price' => 350.00,
            'price_before' => 499.00,
            'price_after' => 350.00,
            'stock' => 60,
            'low_stock_threshold' => 10,
            'shipping_type' => 'governorate',
            'image_url' => '/images/demo/products/earbuds.jpg',
            'main_image_path' => 'demo/products/earbuds.jpg',
        ]);

        // منتجات المطبخ
        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[3]->id,
            'name' => 'قطاعة خضروات متعددة الوظائف 9 في 1',
            'description' => 'أداة المطبخ المتكاملة لتقطيع وتشريح وبشر الخضروات والفواكه بسهولة، تأتي مع ٧ شفرات مختلفة قابلة للاستبدال وسلة غسيل مدمجة.',
            'price' => 180.00,
            'price_before' => 250.00,
            'price_after' => 180.00,
            'stock' => 15,
            'low_stock_threshold' => 5,
            'shipping_type' => 'governorate',
            'image_url' => '/images/demo/products/chopper.jpg',
            'main_image_path' => 'demo/products/chopper.jpg',
        ]);

        $products[] = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $categories[3]->id,
            'name' => 'ميزان مطبخ رقمي عالي الدقة',
            'description' => 'ميزان مطبخ حساس ديجيتال يقيس من ١ جرام حتى ١٠ كيلو جرام، مناسب لوصفات الحلويات والطهي بدقة، شاشة إل سي دي وإيقاف تشغيل تلقائي.',
            'price' => 99.00,
            'price_before' => 150.00,
            'price_after' => 99.00,
            'stock' => 0, // منتج نفذ مخزونه لاختبار حالات النفاذ
            'low_stock_threshold' => 3,
            'shipping_type' => 'governorate',
            'image_url' => '/images/demo/products/scale.jpg',
            'main_image_path' => 'demo/products/scale.jpg',
        ]);

        // إضافة صور فرعية لبعض المنتجات
        foreach ($products as $p) {
            ProductImage::create([
                'product_id' => $p->id,
                'image_path' => $p->main_image_path
            ]);
        }

        // 8. إعداد عروض Upsell/Cross-sell
        $this->info("🎯 إنشاء عروض البيع البديل والبيع المتبادل (Upsell / Cross-sell)...");
        // ربط السيروم بالكريم المرطب
        ProductRecommendation::create([
            'tenant_id' => $tenant->id,
            'product_id' => $products[0]->id,
            'recommended_id' => $products[1]->id,
            'type' => 'cross-sell'
        ]);
        ProductRecommendation::create([
            'tenant_id' => $tenant->id,
            'product_id' => $products[1]->id,
            'recommended_id' => $products[0]->id,
            'type' => 'upsell'
        ]);
        // ربط الساعة الذكية بالسماعات
        ProductRecommendation::create([
            'tenant_id' => $tenant->id,
            'product_id' => $products[4]->id,
            'recommended_id' => $products[5]->id,
            'type' => 'cross-sell'
        ]);
        ProductRecommendation::create([
            'tenant_id' => $tenant->id,
            'product_id' => $products[5]->id,
            'recommended_id' => $products[4]->id,
            'type' => 'upsell'
        ]);

        // 9. توليد رصيد أول المدة وسجلات المخزون (Stock Movements)
        $this->info("📦 تسجيل حركات المخزون الافتتاحية للمنتجات...");
        foreach ($products as $p) {
            if ($p->stock > 0) {
                StockMovement::create([
                    'tenant_id' => $tenant->id,
                    'product_id' => $p->id,
                    'quantity' => $p->stock,
                    'type' => 'in',
                    'description' => 'مخزون افتتاحي عند إعداد النظام التجريبي',
                ]);
            }
        }

        // 10. توليد طلبات تجريبية بحالات مختلفة لتعبئة إحصائيات الداشبورد (Orders)
        $this->info("📈 توليد طلبات شراء تجريبية عبر آخر 35 يوماً...");
        $customers = [
            ['name' => 'أحمد رأفت', 'phone' => '01012345678', 'address' => 'شارع التحرير، الدقي', 'notes' => 'يرجى الاتصال قبل التسليم'],
            ['name' => 'منى محمود', 'phone' => '01122334455', 'address' => 'محرم بك، الإسكندرية', 'notes' => 'التسليم بعد الساعة ٥ مساءً'],
            ['name' => 'محمد إبراهيم', 'phone' => '01234567890', 'address' => 'حي الجامعة، المنصورة', 'notes' => null],
            ['name' => 'sara hassan', 'phone' => '01555443322', 'address' => 'شارع الهرم، الجيزة', 'notes' => 'الباب الخلفي'],
            ['name' => 'أشرف عبد الفتاح', 'phone' => '01099887766', 'address' => 'منطقة السادات، أسيوط', 'notes' => null],
            ['name' => 'فاطمة الزهراء', 'phone' => '01222333444', 'address' => 'شارع الجيش، طنطا', 'notes' => 'برج التوحيد الشقة 4'],
            ['name' => 'كريم عبد العزيز', 'phone' => '01144556677', 'address' => 'شارع التسعين، التجمع الخامس', 'notes' => 'الدور الثاني'],
            ['name' => 'ندى علي', 'phone' => '01511223344', 'address' => 'الجمهورية، السويس', 'notes' => null],
            ['name' => 'طارق حامد', 'phone' => '01077665544', 'address' => 'حوض الأشراف، الزقازيق', 'notes' => 'الدور الأرضي بجوار الصيدلية'],
            ['name' => 'رنا أحمد', 'phone' => '01244332211', 'address' => 'شارع الجلاء، بورسعيد', 'notes' => null],
        ];

        $allGovernorates = ShippingGovernorate::where('tenant_id', $tenant->id)->get();
        $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        
        $ordersCount = 28;
        
        for ($i = 0; $i < $ordersCount; $i++) {
            // توزيع تواريخ الطلبات بالتاريخ:
            // 8 طلبات في الشهر السابق (بين 30 و38 يوماً مضت) لدعم حساب مقارنات التغير
            // 20 طلباً في الشهر الحالي (بين 0 و29 يوماً مضت)
            // نضمن وجود طلبات يومية في آخر 7 أيام للرسم البياني للداشبورد
            if ($i < 8) {
                $daysAgo = rand(30, 38);
                $status = $i < 6 ? 'delivered' : 'cancelled'; // أغلب طلبات الشهر الماضي ناجحة
            } else {
                $daysAgo = $i - 8; // يضمن وجود طلبات تغطي آخر 20 يوماً بالتحديد (من اليوم وحتى 19 يوم مضت)
                if ($daysAgo < 7) {
                    $status = rand(0, 4) > 1 ? 'delivered' : 'shipped'; // طلبات الأسبوع الحالي أغلبها بين التوصيل والنجاح
                } else {
                    // حالات عشوائية للطلبات الأخرى
                    $status = $statuses[rand(0, 4)];
                }
            }

            $orderDate = Carbon::now()->subDays($daysAgo)->subHours(rand(1, 23))->subMinutes(rand(1, 59));
            $customer = $customers[array_rand($customers)];
            $gov = $allGovernorates->isEmpty() ? null : $allGovernorates->random();
            $shippingCost = $gov ? $gov->price : 50;
            $governorateName = $gov ? $gov->name : 'القاهرة';

            // اختيار من 1 إلى 3 منتجات عشوائية
            $numItems = rand(1, 3);
            $selectedProducts = collect($products)->filter(fn($p) => $p->stock > 0)->random(min($numItems, count($products)));
            
            $orderItems = [];
            $subtotal = 0;

            foreach ($selectedProducts as $prod) {
                $qty = rand(1, 2);
                $price = (float) $prod->price;
                $itemSubtotal = $price * $qty;
                
                $orderItems[] = [
                    'id' => $prod->id,
                    'name' => $prod->name,
                    'price' => $price,
                    'quantity' => $qty,
                    'size' => rand(0, 1) ? 'M' : 'S',
                    'color' => rand(0, 1) ? 'أسود' : 'أبيض'
                ];
                
                $subtotal += $itemSubtotal;

                // تحديث سجلات حركة المخزون لتلك الطلبات غير الملغية
                if ($status !== 'cancelled') {
                    StockMovement::create([
                        'tenant_id' => $tenant->id,
                        'product_id' => $prod->id,
                        'quantity' => -$qty,
                        'type' => 'out',
                        'description' => "مبيعات - طلب رقم مرجعي #{$daysAgo}-{$i}",
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }
            }

            $total = $subtotal + $shippingCost;

            // إنشاء الطلب بشكل مباشر وتجنب كتابة حقول timestamps تلقائياً للتلاعب بالتاريخ
            $order = new Order();
            $order->tenant_id = $tenant->id;
            $order->reference_number = Order::generateReferenceNumber();
            $order->customer_name = $customer['name'];
            $order->customer_phone = $customer['phone'];
            $order->customer_address = $customer['address'];
            $order->governorate = $governorateName;
            $order->payment_method = 'cod';
            $order->shipping_cost = $shippingCost;
            $order->items = $orderItems;
            $order->subtotal = $subtotal;
            $order->total = $total;
            $order->status = $status;
            $order->notes = $customer['notes'];
            
            $order->timestamps = false;
            $order->created_at = $orderDate;
            $order->updated_at = $orderDate;
            $order->save();
        }
        $this->info("✅ تم توليد وتوزيع 28 طلب شراء بحالات وتواريخ مختلفة.");

        // 11. توليد طلبات استرجاع (Order Returns)
        $this->info("↩️ توليد طلبات مرتجعة...");
        $deliveredOrders = Order::where('tenant_id', $tenant->id)->where('status', 'delivered')->get();
        if ($deliveredOrders->count() >= 2) {
            $o1 = $deliveredOrders[0];
            $items1 = $o1->items;
            if (count($items1) > 0) {
                $retItem = $items1[0];
                OrderReturn::create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $o1->id,
                    'items' => json_encode([$retItem]),
                    'reason' => 'المنتج تالف أو به عيب صناعة في الخامات الأساسية',
                    'status' => 'completed',
                    'refund_amount' => $retItem['price'] * $retItem['quantity'],
                    'notes' => 'تم استلام المنتج التالف وإرجاع القيمة لحساب العميل البنكي بنجاح.',
                ]);
                
                // حركة مخزون مرتجع
                StockMovement::create([
                    'tenant_id' => $tenant->id,
                    'product_id' => $retItem['id'],
                    'quantity' => $retItem['quantity'],
                    'type' => 'return',
                    'description' => "مرتجع - طلب رقم #{$o1->reference_number}",
                ]);
            }

            $o2 = $deliveredOrders[1];
            $items2 = $o2->items;
            if (count($items2) > 0) {
                $retItem2 = $items2[0];
                OrderReturn::create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $o2->id,
                    'items' => json_encode([$retItem2]),
                    'reason' => 'المقاس غير مناسب ومقاس أصغر مطلوب',
                    'status' => 'pending',
                    'refund_amount' => $retItem2['price'] * $retItem2['quantity'],
                    'notes' => 'العميل ينتظر مندوب شركة الشحن لاستلام المرتجع.',
                ]);
            }
        }

        // 12. توليد تقييمات المتجر ومراجعات المنتجات (Reviews & Store Ratings)
        $this->info("⭐ توليد مراجعات للمنتجات وتقييمات للمتجر...");
        $reviewsData = [
            [
                'rating' => 5,
                'title' => 'سيروم رائع جداً!',
                'body' => 'استخدمته لمدة أسبوعين ونصف والنتيجة مذهلة، نعومة وترطيب غير طبيعي للبشرة. أنصح به بشدة وسأشتريه مجدداً بالتأكيد.',
                'helpful_count' => 12,
                'is_approved' => true,
                'is_verified_purchase' => true,
                'merchant_reply' => 'سعداء جداً بسماع ذلك عميلتنا العزيزة! رضاكم هو هدفنا دائماً وسيروم الهيالورونيك هو الأكثر مبيعاً لدينا.',
                'replied_at' => now()->subDays(5),
            ],
            [
                'rating' => 4,
                'title' => 'ممتاز ولكن التوصيل تأخر قليلاً',
                'body' => 'المنتج أصلي وجودته عالية جداً، ولكن شركة التوصيل استغرقت ٤ أيام بدلاً من يومين للتسليم للقاهرة.',
                'helpful_count' => 5,
                'is_approved' => true,
                'is_verified_purchase' => true,
                'merchant_reply' => 'نعتذر عن التأخير الخارج عن إرادتنا عميلنا العزيز، وسنعمل مع شركة الشحن لتحسين وقت التسليم مستقبلاً.',
                'replied_at' => now()->subDays(3),
            ],
            [
                'rating' => 5,
                'title' => 'جودة ممتازة وسعر مناسب',
                'body' => 'أفضل ماكينة حلاقة استخدمتها في هذه فئة السعرية، البطارية تدوم طويلاً والشفرات حادة ولطيفة على البشرة.',
                'helpful_count' => 8,
                'is_approved' => true,
                'is_verified_purchase' => true,
            ]
        ];

        if (count($products) >= 3) {
            Review::create(array_merge($reviewsData[0], [
                'tenant_id' => $tenant->id,
                'product_id' => $products[0]->id,
            ]));
            Review::create(array_merge($reviewsData[1], [
                'tenant_id' => $tenant->id,
                'product_id' => $products[0]->id,
            ]));
            Review::create(array_merge($reviewsData[2], [
                'tenant_id' => $tenant->id,
                'product_id' => $products[2]->id,
            ]));
        }

        $storeRatingsData = [
            [
                'rating_products' => 5,
                'rating_shipping' => 4,
                'rating_service' => 5,
                'comment' => 'تجربة شراء ممتازة وسهلة من المتجر، والخدمة سريعة والدعم متعاون للغاية.',
                'is_visible' => true,
            ],
            [
                'rating_products' => 4,
                'rating_shipping' => 5,
                'rating_service' => 4,
                'comment' => 'المنتجات وصلت مغلفة بشكل ممتاز والتوصيل سريع جداً.',
                'is_visible' => true,
            ]
        ];

        if ($deliveredOrders->count() >= 2) {
            StoreRating::create(array_merge($storeRatingsData[0], [
                'tenant_id' => $tenant->id,
                'order_id' => $deliveredOrders[0]->id,
                'user_id' => $owner->id,
            ]));
            StoreRating::create(array_merge($storeRatingsData[1], [
                'tenant_id' => $tenant->id,
                'order_id' => $deliveredOrders[1]->id,
                'user_id' => $owner->id,
            ]));
        }

        // 13. توليد سلات متروكة (Abandoned Carts)
        $this->info("🛒 توليد سلات تسوق متروكة ونشطة...");
        if (count($products) >= 3) {
            // سلة متروكة نشطة (لم تُسترجع بعد)
            AbandonedCart::create([
                'tenant_id' => $tenant->id,
                'email' => 'abandoned1@example.com',
                'phone' => '01099998888',
                'session_id' => Str::random(40),
                'cart_data' => [
                    'items' => [
                        [
                            'id' => $products[0]->id,
                            'name' => $products[0]->name,
                            'price' => (float)$products[0]->price,
                            'quantity' => 2,
                        ],
                        [
                            'id' => $products[1]->id,
                            'name' => $products[1]->name,
                            'price' => (float)$products[1]->price,
                            'quantity' => 1,
                        ]
                    ],
                    'subtotal' => (float)($products[0]->price * 2 + $products[1]->price),
                    'total' => (float)($products[0]->price * 2 + $products[1]->price),
                ],
                'recovery_token' => Str::random(32),
                'recovery_email_sent_at' => Carbon::now()->subDays(1),
                'recovered_at' => null,
                'created_at' => Carbon::now()->subDays(2),
            ]);

            // سلة متروكة تم استرجاعها بنجاح
            AbandonedCart::create([
                'tenant_id' => $tenant->id,
                'email' => 'abandoned2@example.com',
                'phone' => '01177776666',
                'session_id' => Str::random(40),
                'cart_data' => [
                    'items' => [
                        [
                            'id' => $products[2]->id,
                            'name' => $products[2]->name,
                            'price' => (float)$products[2]->price,
                            'quantity' => 1,
                        ]
                    ],
                    'subtotal' => (float)$products[2]->price,
                    'total' => (float)$products[2]->price,
                ],
                'recovery_token' => Str::random(32),
                'recovery_email_sent_at' => Carbon::now()->subHours(12),
                'recovered_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(18),
            ]);
        }

        // 14. توليد سجلات القائمة السوداء (Blacklist Records)
        $this->info("🚫 توليد سجلات حظر للقائمة السوداء...");
        BlacklistRecord::create([
            'tenant_id' => $tenant->id,
            'type' => 'phone',
            'value' => '01299990000',
            'reason' => 'عميل وهمي يقوم بطلب منتجات بشكل متكرر ويرفض الاستلام عند تواصل المندوب معه',
        ]);
        BlacklistRecord::create([
            'tenant_id' => $tenant->id,
            'type' => 'email',
            'value' => 'spammer@example.com',
            'reason' => 'بريد إلكتروني وهمي يرسل رسائل بريد مؤذية متكررة عبر صفحة اتصل بنا',
        ]);

        // 15. توليد البانرات الإعلانية (Banners)
        $this->info("🖼️ توليد البانرات الإعلانية...");
        Banner::create([
            'tenant_id' => $tenant->id,
            'title' => 'عروض الصيف المذهلة - خصومات تصل إلى ٥٠٪',
            'image_path' => 'demo/banners/summer_sale.png',
            'link' => '/offers',
            'order' => 1,
            'is_active' => true,
        ]);
        Banner::create([
            'tenant_id' => $tenant->id,
            'title' => 'احصل على شحن مجاني عند الشراء بأكثر من ٥٠٠ جنيه',
            'image_path' => 'demo/banners/free_shipping.png',
            'link' => '/products',
            'order' => 2,
            'is_active' => true,
        ]);

        // 16. توليد القوائم (Menus)
        $this->info("🔗 توليد قوائم التصفح الرئيسية...");
        Menu::create([
            'tenant_id' => $tenant->id,
            'name' => 'القائمة الرئيسية العلويّة',
            'location' => 'header',
            'items' => [
                ['title_ar' => 'الرئيسية', 'title_en' => 'Home', 'type' => 'link', 'value' => '/'],
                ['title_ar' => 'كل المنتجات', 'title_en' => 'All Products', 'type' => 'link', 'value' => '/products'],
                ['title_ar' => 'مستحضرات التجميل', 'title_en' => 'Cosmetics', 'type' => 'category', 'value' => $categories[0]->id],
                ['title_ar' => 'عروضنا', 'title_en' => 'Offers', 'type' => 'link', 'value' => '/offers'],
                ['title_ar' => 'اتصل بنا', 'title_en' => 'Contact Us', 'type' => 'link', 'value' => '/contact'],
            ],
            'is_active' => true,
        ]);

        Menu::create([
            'tenant_id' => $tenant->id,
            'name' => 'قائمة روابط التذييل',
            'location' => 'footer',
            'items' => [
                ['title_ar' => 'سياسة الاستبدال والاسترجاع', 'title_en' => 'Refund Policy', 'type' => 'link', 'value' => '/pages/refund-policy'],
                ['title_ar' => 'سياسة الخصوصية', 'title_en' => 'Privacy Policy', 'type' => 'link', 'value' => '/pages/privacy-policy'],
                ['title_ar' => 'الشروط والأحكام', 'title_en' => 'Terms & Conditions', 'type' => 'link', 'value' => '/pages/terms-and-conditions'],
            ],
            'is_active' => true,
        ]);

        // 17. مسح وتحديث ذاكرة الكاش بالكامل للمتجر
        $this->info("⚡ تحديث ومسح ذاكرة الكاش الخاصة بالمتجر...");
        CacheService::invalidateAll($tenant->id);

        $this->info("✨ تم الانتهاء من ملء البيانات التجريبية للمتجر '{$slug}' بالكامل وبنجاح! ✨");
        
        return Command::SUCCESS;
    }
}
