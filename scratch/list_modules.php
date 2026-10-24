<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

if ($app->bound('modules')) {
    $modules = $app->make('modules')->all();
    echo "Modules found: " . count($modules) . "\n";
    foreach ($modules as $name => $module) {
        echo "  - $name: Enabled=" . ($module->isEnabled() ? 'YES' : 'NO') . " Path=" . $module->getPath() . "\n";
    }
} else {
    echo "Modules service not bound!\n";
}
echo "Done!\n";
