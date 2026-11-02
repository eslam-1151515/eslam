<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $newDescription = implode("\n", [
            'الطبقية كابتشينو',
            'شيفون و دانتيل راقي بتفاصيل فخمة',
            'خامة خفيفة ومريحة',
            'متوفرة بألوان مميزة تناسب كل الأذواق',
            'المقاس ٧٥سم ف ١٧٥ سم',
            'مناسبه لجميع الاعمار',
            'الخصومات لفتره محدوده',
            'معاينة قبل الاستلام',
            'توصيل لجميع محافظات مصر',
        ]);

        $product = Product::find(57);
        if ($product) {
            $product->update([
                'description' => $newDescription,
            ]);
        }

        // In case there is also product 61 which is a copy of 57 on the same store
        $product61 = Product::find(61);
        if ($product61 && $product && $product61->tenant_id === $product->tenant_id) {
            $product61->update([
                'description' => $newDescription,
            ]);
        }

        Cache::flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
