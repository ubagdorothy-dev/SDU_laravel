<?php
// Simple debug script to test login process

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Simulate a login request
$email = 'unit.director@sdu.edu.ph';
$password = 'sdo123';

echo "Debugging login process...\n";
echo "Email: $email\n";
echo "Password: $password\n";

// Check if user exists
$user = \App\Models\User::where('email', $email)->first();
if (!$user) {
    echo "❌ User not found in database\n";
    exit;
}

echo "✅ User found in database\n";
echo "User ID: " . $user->user_id . "\n";
echo "Role: " . $user->role . "\n";
echo "Approved: " . ($user->is_approved ? 'Yes' : 'No') . "\n";
echo "Password hash: " . $user->password_hash . "\n";

// Test password verification
if (\Illuminate\Support\Facades\Hash::check($password, $user->password_hash)) {
    echo "✅ Password verification successful\n";
} else {
    echo "❌ Password verification failed\n";
    exit;
}

// Test Auth::attempt with the credentials
$credentials = ['email' => $email, 'password' => $password];
echo "Attempting Auth::attempt...\n";

if (Auth::attempt($credentials)) {
    echo "✅ Auth::attempt successful\n";
    $loggedInUser = Auth::user();
    echo "Logged in user ID: " . $loggedInUser->user_id . "\n";
    echo "Logged in user email: " . $loggedInUser->email . "\n";
    
    // Check if user is approved
    if ($loggedInUser->is_approved) {
        echo "✅ User is approved\n";
    } else {
        echo "❌ User is not approved\n";
        Auth::logout();
    }
} else {
    echo "❌ Auth::attempt failed\n";
}