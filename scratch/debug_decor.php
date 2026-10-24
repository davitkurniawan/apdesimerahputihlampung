<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$events = $app['events'];
$events->listen("eloquent.retrieved: App\Models\Penduduk", function ($model) {
    echo "DUMMY LISTEN\n";
});

$ref = new ReflectionClass($events);
$prop = $ref->getProperty('listeners');
$prop->setAccessible(true);
$keys = array_keys($prop->getValue($events));
echo "Keys: " . json_encode($keys) . "\n";
