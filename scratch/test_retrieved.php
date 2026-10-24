<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Penduduk;

try {
    Penduduk::retrieved(function ($model) {
        $model->setAttribute('idCluster', $model->id_cluster);
    });

    $p = Penduduk::find(999999);
    if ($p) {
        $arr = $p->toArray();
        echo "idCluster key exists in toArray: " . var_export(array_key_exists('idCluster', $arr), true) . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
