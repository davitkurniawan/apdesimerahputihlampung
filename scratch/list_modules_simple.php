<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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
