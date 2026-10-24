<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Penduduk;

Penduduk::retrieved(function ($model) {
    $model->setAttribute('idCluster', $model->id_cluster);
});

$p = Penduduk::find(999999);
if ($p) {
    echo "Found penduduk!\n";
    echo "Attributes:\n";
    print_r($p->getAttributes());
}
