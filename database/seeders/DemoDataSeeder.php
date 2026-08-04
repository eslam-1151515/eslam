<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['name' => 'إلكترونيات', 'description' => 'هواتف، لابتوبات، وإكسسوارات'],
            ['name' => 'ملابس', 'description' => 'رجالي، حريمي، وأطفال'],
            ['name' => 'أدوات منزلية', 'description' => 'مطبخ وتنظيم المنزل'],
        ];

        foreach ($cats as $c) {
            $category = Category::firstOrCreate(['name' => $c['name']], ['description' => $c['description']]);
            // Create a couple of demo products per category
            Product::firstOrCreate([
                'name' => $c['name'].' - منتج 1',
            ], [
                'category_id' => $category->id,
                'description' => 'وصف تجريبي للمنتج',
                'price' => 99.99,
                'stock' => 10,
                'image_url' => null,
            ]);

            Product::firstOrCreate([
                'name' => $c['name'].' - منتج 2',
            ], [
                'category_id' => $category->id,
                'description' => 'وصف تجريبي آخر',
                'price' => 149.50,
                'stock' => 5,
                'image_url' => null,
            ]);
        }
    }
}
