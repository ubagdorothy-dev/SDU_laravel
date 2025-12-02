<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test authentication for Unit Director
$email = 'unit.director@sdu.edu.ph';
$password = 'sdo123';

echo "Testing authentication for Unit Director ($email)\n";

// Try to authenticate
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    echo "✅ Authentication successful!\n";
    $user = Auth::user();
    echo "User ID: " . $user->user_id . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Approved: " . ($user->is_approved ? 'Yes' : 'No') . "\n";
    
    if ($user->is_approved) {
        echo "✅ User is approved. Should be able to access dashboard.\n";
    } else {
        echo "❌ User is not approved. Will be logged out.\n";
    }
} else {
    echo "❌ Authentication failed!\n";
    
    // Check if user exists
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        echo "User exists in database.\n";
        echo "Stored password hash: " . $user->password_hash . "\n";
        echo "Password verification: " . (Hash::check($password, $user->password_hash) ? '✅ PASS' : '❌ FAIL') . "\n";
        echo "User approved: " . ($user->is_approved ? 'Yes' : 'No') . "\n";
        echo "User role: " . $user->role . "\n";
    } else {
        echo "User does not exist in database.\n";
    }
}