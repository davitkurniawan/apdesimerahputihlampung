<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Manually configure tenant database connection
$config = $app->make('config');
$config->set('database.connections.mysql.database', 'xosid_tenant_demo');
$config->set('database.connections.mysql.username', 'root');
$config->set('database.connections.mysql.password', 'password');
$config->set('database.connections.mysql.host', 'db');
$config->set('database.connections.mysql.port', 3306);
$config->set('database.default', 'mysql');

// Force reconnect
\Illuminate\Support\Facades\DB::purge('mysql');
\Illuminate\Support\Facades\DB::reconnect('mysql');

$router = $app->make('router');
echo "Initial routes count: " . $router->getRoutes()->count() . "\n";

// Execute all booted callbacks
$ref = new ReflectionClass($app);
$prop = $ref->getProperty('bootedCallbacks');
$prop->setAccessible(true);
$callbacks = $prop->getValue($app);
foreach ($callbacks as $callback) {
    try {
        $callback($app);
    } catch (\Throwable $e) {}
}

echo "After booted callbacks routes count: " . $router->getRoutes()->count() . "\n";
foreach ($router->getRoutes() as $r) {
    if (strpos($r->uri(), 'buku') !== false) {
        echo "  " . $r->uri() . " -> " . $r->getName() . "\n";
    }
}
