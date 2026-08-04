<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'الباقة الأساسية',
                'slug' => 'basic',
                'description' => 'مثالية للمتاجر الناشئة والصغيرة',
                'price_monthly' => 100.00,
                'price_yearly' => 1000.00,
                'trial_days' => 14,
                'limits' => [
                    'max_products' => 50,
                    'max_orders' => 200,
                    'features' => [
                        'دعم فني عبر البريد الإلكتروني',
                        'تقارير مبيعات أساسية',
                        'نطاق فرعي مجاني (subdomain)',
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => 'الباقة الاحترافية',
                'slug' => 'pro',
                'description' => 'الخيار الأفضل للمتاجر المتوسطة والآخذة في النمو',
                'price_monthly' => 250.00,
                'price_yearly' => 2500.00,
                'trial_days' => 14,
                'limits' => [
                    'max_products' => 500,
                    'max_orders' => 1000,
                    'features' => [
                        'دعم فني ذو أولوية عبر الواتساب',
                        'تقارير مبيعات متقدمة',
                        'ربط بيكسل فيسبوك وتيك توك',
                        'دعم نطاق مخصص (custom domain)',
                    ]
                ],
                'is_active' => true,
            ],
            [
                'name' => 'الباقة غير المحدودة',
                'slug' => 'enterprise',
                'description' => 'للمتاجر الكبيرة والشركات الضخمة ذات الاحتياجات المخصصة',
                'price_monthly' => 500.00,
                'price_yearly' => 5000.00,
                'trial_days' => 14,
                'limits' => [
                    'max_products' => 9999, // unlimited representation
                    'max_orders' => 9999, // unlimited representation
                    'features' => [
                        'مدير حساب مخصص ودعم على مدار الساعة',
                        'لوحة تحكم مخصصة بالكامل للتقارير والتحليلات',
                        'ربط غير محدود للمنصات الإعلانية ومحركات الدفع',
                        'دعم كامل لنطاقات مخصصة وتصميمات مخصصة',
                    ]
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
