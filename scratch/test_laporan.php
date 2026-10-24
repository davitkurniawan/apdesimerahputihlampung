<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Load database helpers
require_once __DIR__ . '/../xosid_database_helpers.php';

// Define dummy functions for CodeIgniter / PHP extensions missing in test CLI
if (!function_exists('geoip_info')) {
    function geoip_info() { return null; }
}
if (!function_exists('setting')) {
    function setting($name) { return null; }
}

// Register error handler to show exactly where the exception is thrown
set_error_handler(function($level, $message, $file, $line) {
    if (strpos($message, 'unlink') !== false) return true; // ignore session unlink warning
    echo "ERROR caught in handler: $message in $file on line $line\n";
    throw new ErrorException($message, 0, $level, $file, $line);
});

// Mock logged-in user 1
$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::login($user);
    echo "Logged in as user: " . $user->username . "\n";
}

try {
    $service = $app->make(\App\Services\LaporanPenduduk::class);
    echo "LaporanPenduduk instantiated successfully!\n";
    
    $ref = new ReflectionClass($service);
    echo "Methods:\n";
    foreach ($ref->getMethods() as $m) {
        echo "  " . $m->getName() . "\n";
    }
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
