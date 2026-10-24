<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$router = $app->make('router');
echo "Before manual load count: " . $router->getRoutes()->count() . "\n";

try {
    \Illuminate\Support\Facades\Route::middleware('web')
        ->group(base_path('routes/web.php'));
    echo "Manual load success!\n";
} catch (\Throwable $e) {
    echo "Manual load failed: " . $e->getMessage() . "\n";
}

echo "After manual load count: " . $router->getRoutes()->count() . "\n";
foreach ($router->getRoutes() as $r) {
    echo "  " . $r->uri() . "\n";
}
