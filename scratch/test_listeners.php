<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

use App\Models\Penduduk;

$dispatcher = Penduduk::getEventDispatcher();
if ($dispatcher) {
    $ref = new ReflectionClass($dispatcher);
    if ($ref->hasProperty('listeners')) {
        $prop = $ref->getProperty('listeners');
        $prop->setAccessible(true);
        echo "Registered events:\n";
        print_r(array_keys($prop->getValue($dispatcher)));
    }
}
