<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'unit.director@sdu.edu.ph')->first();
if ($user) {
    echo "Password hash: " . $user->password_hash . "\n";
} else {
    echo "User not found\n";
}