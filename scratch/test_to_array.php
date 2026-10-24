<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = new \App\Models\Penduduk();
$ref = new ReflectionClass($p);

if ($ref->hasMethod('toArray')) {
    echo "toArray is defined in: " . $ref->getMethod('toArray')->getDeclaringClass()->getName() . "\n";
} else {
    echo "toArray is not defined in Penduduk or parents\n";
}
