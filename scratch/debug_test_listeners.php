<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

$events = $app['events'];
echo "Listeners keys count: " . count($events->getListeners('eloquent.retrieved: App\Models\Penduduk')) . "\n";
