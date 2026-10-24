<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

// Find the model using each of the Penduduk model classes
foreach (['App\Models\Penduduk', 'App\Models\PendudukSaja', 'App\Models\PendudukHidup'] as $cls) {
    if (class_exists($cls)) {
        $p = $cls::find(999999);
        if ($p) {
            echo "Class: $cls\n";
            echo "  id_cluster: " . var_export($p->id_cluster, true) . "\n";
            echo "  idCluster attribute: " . var_export($p->getAttribute('idCluster'), true) . "\n";
            echo "  idCluster array key: " . var_export($p['idCluster'], true) . "\n";
            $arr = $p->toArray();
            echo "  idCluster in toArray: " . var_export(array_key_exists('idCluster', $arr), true) . "\n";
        }
    }
}
