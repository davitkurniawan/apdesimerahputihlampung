<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

// Defer to load routeCodeIgniter
try {
    require __DIR__ . '/../bootstrap/routeCodeIgniter_encoded.php';
} catch (\Throwable $e) {
    echo "Boot threw: " . $e->getMessage() . "\n";
}

$router = $app->make('router');
echo "Routes registered:\n";
foreach ($router->getRoutes() as $r) {
    if (strpos($r->uri(), 'buku') !== false) {
        echo "  " . $r->uri() . " -> " . $r->getName() . "\n";
    }
}
