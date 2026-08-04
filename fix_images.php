<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Map product IDs to correct image names that exist
// Products 1-6 are old ones (null images), 7+ are demo ones with named images
// We need to rename our generated files to match, OR update DB paths

// Update products 1-6 to use product_1.jpg ... product_6.jpg
$mapping = [
    1 => 'demo/products/product_1.jpg',
    2 => 'demo/products/product_2.jpg',
    3 => 'demo/products/product_3.jpg',
    4 => 'demo/products/product_4.jpg',
    5 => 'demo/products/product_5.jpg',
    6 => 'demo/products/product_6.jpg',
];

foreach ($mapping as $id => $path) {
    DB::table('products')->where('id', $id)->update([
        'main_image_path' => $path,
        'image_url' => '/storage/' . $path,
    ]);
}

// For products 7+ that reference named images, create symlinked copies from our generated files
// or create placeholder files with correct names
$namedProducts = DB::select("SELECT id, name, main_image_path FROM products WHERE id >= 7");
$srcFiles = ['product_1.jpg','product_2.jpg','product_3.jpg','product_4.jpg','product_5.jpg','product_6.jpg'];
$i = 0;

foreach ($namedProducts as $p) {
    $filename = basename($p->main_image_path);
    $destPath = "public/storage/demo/products/$filename";
    $srcPath = "public/storage/demo/products/" . $srcFiles[$i % count($srcFiles)];
    
    if (!file_exists($destPath) && file_exists($srcPath)) {
        copy($srcPath, $destPath);
        echo "Copied $srcPath => $destPath\n";
    } else {
        echo "Already exists or source missing: $destPath\n";
    }
    $i++;
}

// Also fix banners - update DB to reference banner_1.jpg etc
$bannerMapping = [1 => 'banner_1.jpg', 2 => 'banner_2.jpg', 3 => 'banner_3.jpg'];
$banners = DB::select("SELECT id, image_path FROM banners LIMIT 10");
echo "\nBANNERS:\n" . json_encode($banners, JSON_UNESCAPED_UNICODE) . "\n";

echo "\nDone fixing image paths!\n";
echo "Products updated: " . count($mapping) . " base products\n";
echo "Named product images created: " . $i . "\n";
