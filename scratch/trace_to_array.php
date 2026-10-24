<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!function_exists('usia')) { function usia($tgl) { return 0; } }

$p = \App\Models\Penduduk::find(999999);
$p->setAttribute('idCluster', 123);

echo "Attributes keys: " . json_encode(array_keys($p->getAttributes())) . "\n";
echo "AttributesToArray keys: " . json_encode(array_keys($p->attributesToArray())) . "\n";
