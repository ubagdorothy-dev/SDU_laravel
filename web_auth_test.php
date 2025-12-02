<?php
// Web authentication test that simulates the actual login process

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\AuthController;

echo "=== Web Authentication Test ===\n";

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

// 2. Test password verification directly
echo "2. Testing password verification directly...\n";
if (password_verify($password, $user->password_hash)) {
    echo "✅ Direct password verification successful\n\n";
} else {
    echo "❌ Direct password verification failed\n";
    exit;
}

// 3. Test Auth::attempt with email and password
echo "3. Testing Auth::attempt with email and password...\n";
$credentials = ['email' => $email, 'password' => $password];

if (Auth::attempt($credentials)) {
    echo "✅ Auth::attempt with email/password successful\n";
    Auth::logout();
} else {
    echo "❌ Auth::attempt with email/password failed\n";
}

// 4. Test Auth::attempt with email and password_hash
echo "\n4. Testing Auth::attempt with email and password_hash...\n";
$credentials2 = ['email' => $email, 'password_hash' => $password];

if (Auth::attempt($credentials2)) {
    echo "✅ Auth::attempt with email/password_hash successful\n";
    Auth::logout();
} else {
    echo "❌ Auth::attempt with email/password_hash failed\n";
}

// 5. Test User model authentication methods
echo "\n5. Testing User model authentication methods...\n";
echo "   getAuthIdentifierName(): " . $user->getAuthIdentifierName() . "\n";
echo "   getAuthIdentifier(): " . $user->getAuthIdentifier() . "\n";
echo "   getAuthPassword(): " . $user->getAuthPassword() . "\n";

// 6. Test retrieving user by credentials manually
echo "\n6. Testing manual credential retrieval...\n";
$retrievedUser = User::where('email', $email)->first();
if ($retrievedUser) {
    echo "✅ User retrieved by email\n";
    echo "   Stored password_hash: {$retrievedUser->password_hash}\n";
    echo "   Password verification: " . (password_verify($password, $retrievedUser->password_hash) ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "❌ Could not retrieve user by email\n";
}

echo "\n=== Test Complete ===\n";