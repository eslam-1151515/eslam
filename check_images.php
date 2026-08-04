<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;

echo "--- Categories ---\n";
foreach (Category::all() as $cat) {
    echo "ID: {$cat->id}, Name: {$cat->name}, Image: {$cat->image}\n";
}

echo "\n--- Banners ---\n";
foreach (Banner::all() as $b) {
    echo "ID: {$b->id}, Title: {$b->title}, Image: {$b->image}\n";
}

echo "\n--- Products ---\n";
foreach (Product::take(5)->get() as $p) {
    echo "ID: {$p->id}, Name: {$p->name}, Image: {$p->image}\n";
}
