<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Test database connection
    $result = DB::select('SELECT COUNT(*) as count FROM users');
    echo "✅ Database connection successful\n";
    echo "Users count: " . $result[0]->count . "\n";
    
    // Test if we can find the unit director
    $user = DB::select('SELECT * FROM users WHERE email = ?', ['unit.director@sdu.edu.ph']);
    if (!empty($user)) {
        echo "✅ Unit Director found in database\n";
        print_r($user[0]);
    } else {
        echo "❌ Unit Director not found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}