<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::select("SELECT id, name, email, user_type FROM users ORDER BY id");
echo json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
