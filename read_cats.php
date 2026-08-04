<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $mcats = DB::table('main_categories')->get();
    foreach ($mcats as $m) {
        echo "MAIN CAT ID: {$m->id} | Name: {$m->name}\n";
    }
} catch (Exception $e) {
    echo "No main_categories table\n";
}

$cats = App\Models\Category::all();
foreach ($cats as $c) {
    echo "CAT ID: {$c->id} | Name: {$c->name} | Name_AR: {$c->name_ar} | Main: {$c->main_category}\n";
}
