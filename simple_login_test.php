<?php
// Simple login test to debug authentication issues

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== Simple Login Test ===\n";

// Test credentials
$email = 'unit.director@sdu.edu.ph';
$password = 'sdo123';

echo "Testing with email: $email\n";
echo "Password: $password\n\n";

// 1. Check if user exists in database
echo "1. Checking if user exists in database...\n";
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found in database\n";
    exit;
}

echo "✅ User found:\n";
echo "   ID: {$user->user_id}\n";
echo "   Email: {$user->email}\n";
echo "   Role: {$user->role}\n";
echo "   Approved: " . ($user->is_approved ? 'Yes' : 'No') . "\n";
echo "   Password hash: {$user->password_hash}\n\n";

// 2. Test password verification
echo "2. Testing password verification...\n";
if (password_verify($password, $user->password_hash)) {
    echo "✅ Password verification successful\n\n";
} else {
    echo "❌ Password verification failed\n";
    echo "Stored hash: {$user->password_hash}\n";
    echo "Password entered: $password\n";
    exit;
}

// 3. Test Auth::attempt
echo "3. Testing Auth::attempt...\n";
$credentials = ['email' => $email, 'password' => $password];

if (Auth::attempt($credentials)) {
    echo "✅ Auth::attempt successful\n";
    $loggedInUser = Auth::user();
    echo "   Logged in user ID: {$loggedInUser->user_id}\n";
    echo "   Logged in user email: {$loggedInUser->email}\n";
    echo "   Logged in user role: {$loggedInUser->role}\n";
    echo "   Logged in user approved: " . ($loggedInUser->is_approved ? 'Yes' : 'No') . "\n";
    
    // Test if we can access the session
    echo "\n4. Testing session access...\n";
    try {
        session()->put('test_key', 'test_value');
        $sessionValue = session()->get('test_key');
        echo "✅ Session working - stored value: $sessionValue\n";
    } catch (Exception $e) {
        echo "❌ Session error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Auth::attempt failed\n";
    
    // Check for any validation errors
    echo "\nChecking for validation errors...\n";
    // This would normally be available in a request context
    echo "No validation errors detected in this test context.\n";
}

echo "\n=== Test Complete ===\n";