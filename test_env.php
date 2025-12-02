<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "SESSION_DRIVER: " . $_ENV['SESSION_DRIVER'] . "\n";
echo "Actual value: " . env('SESSION_DRIVER', 'database') . "\n";

// Check if we can access the config
$app = require_once 'bootstrap/app.php';
$config = $app['config'];

echo "Config session.driver: " . $config->get('session.driver') . "\n";