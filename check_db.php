<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Tables in database:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableArray = (array)$table;
        echo "- " . reset($tableArray) . "\n";
    }
    
    // Check if users table exists and query the specified emails
    if (Schema::hasTable('users')) {
        echo "\nChecking users table for emails:\n";
        $emails = ['superadmin@example.com', 'merchant@demo.com'];
        foreach ($emails as $email) {
            $user = DB::table('users')->where('email', $email)->first();
            if ($user) {
                echo "User: {$email} exists. ID: {$user->id}, Password Hash: {$user->password}\n";
            } else {
                echo "User: {$email} does NOT exist.\n";
            }
        }
    } else {
        echo "\n'users' table does not exist.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
