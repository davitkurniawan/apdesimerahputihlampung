<?php
header('Content-Type: text/plain');

define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

require __DIR__.'/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Laravel default database connection: " . config('database.default') . "\n\n";
echo "Laravel connections:\n";
print_r(config('database.connections'));
?>
