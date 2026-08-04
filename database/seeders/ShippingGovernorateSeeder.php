<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingGovernorate;

class ShippingGovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name' => 'القاهرة', 'price' => 100, 'is_active' => true],
            ['name' => 'الجيزة', 'price' => 100, 'is_active' => true],
            ['name' => 'الاسكندرية', 'price' => 100, 'is_active' => true],
            ['name' => 'الدقهلية', 'price' => 100, 'is_active' => true],
            ['name' => 'البحيرة', 'price' => 100, 'is_active' => true],
            ['name' => 'الغربية', 'price' => 100, 'is_active' => true],
            ['name' => 'كفر الشيخ', 'price' => 100, 'is_active' => true],
            ['name' => 'المنوفية', 'price' => 100, 'is_active' => true],
            ['name' => 'القليوبية', 'price' => 100, 'is_active' => true],
            ['name' => 'الشرقية', 'price' => 100, 'is_active' => true],
            ['name' => 'دمياط', 'price' => 100, 'is_active' => true],
            ['name' => 'بورسعيد', 'price' => 100, 'is_active' => true],
            ['name' => 'الاسماعيلية', 'price' => 100, 'is_active' => true],
            ['name' => 'السويس', 'price' => 100, 'is_active' => true],
            ['name' => 'شمال سيناء', 'price' => 100, 'is_active' => true],
            ['name' => 'جنوب سيناء', 'price' => 100, 'is_active' => true],
            ['name' => 'المنيا', 'price' => 100, 'is_active' => true],
            ['name' => 'بني سويف', 'price' => 100, 'is_active' => true],
            ['name' => 'الفيوم', 'price' => 100, 'is_active' => true],
            ['name' => 'أسيوط', 'price' => 100, 'is_active' => true],
            ['name' => 'سوهاج', 'price' => 100, 'is_active' => true],
            ['name' => 'قنا', 'price' => 100, 'is_active' => true],
            ['name' => 'الأقصر', 'price' => 100, 'is_active' => true],
            ['name' => 'أسوان', 'price' => 100, 'is_active' => true],
            ['name' => 'البحر الأحمر', 'price' => 100, 'is_active' => true],
            ['name' => 'الوادي الجديد', 'price' => 100, 'is_active' => true],
            ['name' => 'مطروح', 'price' => 100, 'is_active' => true],
        ];

        foreach ($governorates as $governorate) {
            ShippingGovernorate::updateOrCreate(
                ['name' => $governorate['name']],
                $governorate
            );
        }
    }
}
