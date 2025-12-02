<?php
// Manual login test to bypass the web interface

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

echo "=== Manual Login Test ===\n";

// Create a mock request with the correct data
$email = 'unit.director@sdu.edu.ph';
$password = 'sdo123';

echo "Creating mock request with:\n";
echo "Email: $email\n";
echo "Password: $password\n\n";

// Create a request object manually
$request = Request::create('/login', 'POST', [
    'email' => $email,
    'password' => $password,
    '_token' => 'fake_token' // CSRF token
]);

// Manually set the request data
$request->setMethod('POST');
$request->request->add([
    'email' => $email,
    'password' => $password
]);

echo "Request created. Calling AuthController@login...\n";

// Create AuthController instance
$authController = new AuthController();

try {
    $response = $authController->login($request);
    
    echo "Login method executed successfully.\n";
    echo "Response type: " . get_class($response) . "\n";
    
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        
        // Check for errors in the session
        $errors = session('errors');
        if ($errors) {
            echo "Errors found in session:\n";
            foreach ($errors->all() as $error) {
                echo "  - $error\n";
            }
        } else {
            echo "No errors in session.\n";
        }
        
        // Check for success messages
        $message = session('approval_notification');
        if ($message) {
            echo "Success message: $message\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error during login: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";