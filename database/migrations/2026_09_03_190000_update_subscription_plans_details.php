<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Free plan
        DB::table('subscription_plans')->where('slug', 'free')->update([
            'name' => 'الباقة التجريبية المجانية',
            'description' => '100 ج.م رصيد هدية افتتاحي في المحفظة لتجربة المنصة واستقبال الطلبات مجاناً، مدة مفتوحة بدون حد أقصى للأيام.',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'trial_days' => 0,
            'is_active' => true,
            'limits' => json_encode([
                'max_products' => 9999,
                'max_orders'   => 9999,
                'features'     => [
                    '100 ج.م رصيد هدية افتتاحي في المحفظة',
                    'متجر إلكتروني فائق السرعة ومتكامل',
                    'مدة مفتوحة بدون انتهاء تجربة 7 أيام',
                    'تجربة فتح ومعاينة الطلبات مجاناً',
                    '0% عمولة على المبيعات',
                    'ميزة الأوتوكونفرم تتفعل بعد أول شحن فعلي للمحفظة',
                    'دعم فني عبر الواتس اب أو الاتصال',
                ]
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // 2. Monthly plan
        DB::table('subscription_plans')->where('slug', 'monthly')->update([
            'name' => 'الباقة الشهرية الشاملة',
            'description' => 'اشتراك 4000 ج.م شهرياً بدون أي حد أقصى للطلبات أو المنتجات و0% عمولة، مع ميزة الأوتوكونفرم بـ 1ج لكل رسالة.',
            'price_monthly' => 4000,
            'price_yearly' => 40000,
            'trial_days' => 0,
            'is_active' => true,
            'limits' => json_encode([
                'max_products' => 9999,
                'max_orders'   => 9999,
                'features'     => [
                    'فتح وإدارة غير محدودة لجميع الأوردرات والمنتجات',
                    '0% عمولة على المبيعات (بدون خصم 2ج على الأوردر)',
                    'ميزة تأكيد الطلبات عبر الواتساب (1ج لكل رسالة من المحفظة)',
                    'فتح جميع الثيمات الاحترافية والتخصيصات',
                    'سيرفرات فائقة السرعة وأولوية معالجة',
                    'دعم فني عبر الواتس اب أو الاتصال 24/7',
                ]
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // 3. Yearly plan - deactivate completely
        DB::table('subscription_plans')->where('slug', 'yearly')->update([
            'is_active' => false,
        ]);

        // 4. Commission plan
        DB::table('subscription_plans')->where('slug', 'commission')->update([
            'name' => 'باقة الدفع لكل أوردر (2ج)',
            'description' => 'بدون أي اشتراك شهري ثابت! اشحن محفظتك بـ فودافون كاش أو إنستاباي وادفع 2 ج.م فقط عند فتح ومعاينة الأوردر.',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'trial_days' => 0,
            'is_active' => true,
            'limits' => json_encode([
                'max_products' => 9999,
                'max_orders'   => 9999,
                'features'     => [
                    '0ج اشتراك شهري ثابت',
                    'خصم 2 ج.م فقط عند فتح ومعاينة كل أوردر',
                    'شحن فوري بـ فودافون كاش وإنستاباي',
                    'منتجات وثيمات وتصميمات غير محدودة',
                    'ميزة تأكيد الطلبات عبر الواتساب (1ج لكل رسالة)',
                    'دعم فني عبر الواتس اب أو الاتصال',
                ]
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // Update trial tenants to open period without 7 days expiration
        DB::table('tenants')->where('subscription_status', 'trial')->update([
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
        ]);

        $freePlanId = DB::table('subscription_plans')->where('slug', 'free')->value('id');
        if ($freePlanId) {
            DB::table('subscriptions')->where('plan_id', $freePlanId)->update([
                'ends_at' => null,
                'trial_ends_at' => null,
            ]);
        }
    }

    public function down(): void
    {
    }
};
