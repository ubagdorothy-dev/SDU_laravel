<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Update the Unit Director user
$user = User::find(1);
if ($user) {
    $user->email = 'director@sdu.edu.ph';
    $user->password_hash = Hash::make('sdo123');
    $user->save();
    echo "User updated successfully\n";
    echo "Email: " . $user->email . "\n";
    echo "Password hash: " . $user->password_hash . "\n";
} else {
    echo "User not found\n";
}