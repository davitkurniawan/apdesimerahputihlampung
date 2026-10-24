<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

if (!function_exists('usia')) { function usia($tgl) { return 0; } }

$dispatcher = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
echo "Is dispatcher same as app['events']? " . var_export($dispatcher === $app['events'], true) . "\n";

$p = \App\Models\Pamong::where('pamong_id', 999999)->first();
if (!$p) {
    $p = new \App\Models\Pamong();
    $p->pamong_id = 999999;
    $p->id_pend = 999999;
    $p->config_id = 1;
    $p->setAttribute('pamong_id', 999999);
    $p->setAttribute('id_pend', 999999);
    $p->setAttribute('config_id', 1);
}

$pend = $p->penduduk;
if ($pend) {
    echo "Relation loaded: " . get_class($pend) . " ID " . $pend->id . "\n";
    $arr = $pend->toArray();
    echo "array_key_exists idCluster: " . var_export(array_key_exists('idCluster', $arr), true) . "\n";
}
