<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!function_exists('usia')) { function usia($tgl) { return 0; } }

use App\Models\Penduduk;

$p = Penduduk::find(999999);
$p->setAttribute('idCluster', $p->id_cluster ?? 0);
$p->setAttribute('id_cluster', $p->id_cluster ?? 0);

$arr = $p->toArray();
echo "idCluster value in toArray: " . var_export($arr['idCluster'], true) . "\n";
echo "id_cluster value in toArray: " . var_export($arr['id_cluster'], true) . "\n";
