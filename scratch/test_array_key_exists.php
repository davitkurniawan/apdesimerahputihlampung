<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!function_exists('usia')) { function usia($tgl) { return 0; } }

use App\Models\Penduduk;

$p = Penduduk::find(999999);
$p->setAttribute('idCluster', null);

$arr = $p->toArray();
echo "array_key_exists for idCluster (null): " . var_export(array_key_exists('idCluster', $arr), true) . "\n";
