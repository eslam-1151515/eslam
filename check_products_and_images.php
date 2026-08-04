<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$products = DB::select("SELECT id, name, main_image_path, image_url FROM products LIMIT 10");
echo "PRODUCTS:\n" . json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

$cats = DB::select("SELECT id, name, image_path FROM categories LIMIT 10");
echo "CATEGORIES:\n" . json_encode($cats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// List actual files
$productsDir = 'public/storage/demo/products';
if (is_dir($productsDir)) {
    echo "FILES IN products/: " . implode(', ', scandir($productsDir)) . "\n";
} else {
    echo "Dir $productsDir does not exist\n";
}
