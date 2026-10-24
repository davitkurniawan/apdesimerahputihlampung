<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ref = new ReflectionClass(\App\Models\Penduduk::class);
foreach ($ref->getMethods() as $m) {
    $name = $m->getName();
    if (strpos(strtolower($name), 'array') !== false || strpos(strtolower($name), 'attribute') !== false) {
        echo "Method: $name declared in: " . $m->getDeclaringClass()->getName() . "\n";
    }
}
