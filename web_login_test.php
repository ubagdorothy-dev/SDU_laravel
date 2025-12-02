<?php
// Simple web login test

// This simulates what happens when we submit the login form
// We'll manually call the AuthController login method

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

// Create a mock request
$request = Request::create('/login', 'POST', [
    'email' => 'unit.director@sdu.edu.ph',
    'password' => 'sdo123'
]);

// Validate the request (this is what Laravel does automatically)
try {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    echo "✅ Request validation passed\n";
    print_r($validated);
} catch (Exception $e) {
    echo "❌ Request validation failed: " . $e->getMessage() . "\n";
    exit;
}

// Create AuthController instance
$authController = new AuthController();

// Call the login method
try {
    echo "Calling login method...\n";
    $response = $authController->login($request);
    
    echo "Response type: " . get_class($response) . "\n";
    
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "✅ Login returned redirect response\n";
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        
        // Check for session data
        $sessionData = session()->all();
        echo "Session data:\n";
        print_r($sessionData);
    } else {
        echo "Unexpected response type\n";
    }
} catch (Exception $e) {
    echo "❌ Login method failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}