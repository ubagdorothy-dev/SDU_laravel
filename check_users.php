<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Show table structure
    echo "Users table structure:\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n";
    
    // Check if the unit director user exists
    $stmt = $pdo->prepare("SELECT user_id, full_name, email, role, office_code, is_approved, password FROM users WHERE email = ?");
    $stmt->execute(['unit.director@sdu.edu.ph']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "User found:\n";
        print_r($user);
        
        // Test password
        $testPassword = 'sdo123';
        if (password_verify($testPassword, $user['password'])) {
            echo "\nPassword is correct!\n";
        } else {
            echo "\nPassword is incorrect. Current hash: " . $user['password'] . "\n";
            echo "Expected hash for 'sdo123': " . password_hash('sdo123', PASSWORD_DEFAULT) . "\n";
        }
    } else {
        echo "User not found in database\n";
        
        // List all users
        echo "\nAll users in database:\n";
        $stmt = $pdo->query("SELECT user_id, full_name, email, role, office_code, is_approved FROM users");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}