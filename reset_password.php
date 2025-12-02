<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container();
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Set up the database connection
$capsule = new Capsule($app);
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'sdu',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Reset password for user with user_id = 2 (Charlie Brown)
$user = Capsule::table('users')->where('user_id', 2)->first();
if ($user) {
    $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
    Capsule::table('users')->where('user_id', 2)->update(['password_hash' => $hashedPassword]);
    echo "Password reset successfully for user: " . $user->full_name . "\n";
} else {
    echo "User not found\n";
}
?>