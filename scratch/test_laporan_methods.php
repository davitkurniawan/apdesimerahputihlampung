<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../xosid_database_helpers.php';
require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

if (!function_exists('geoip_info')) { function geoip_info() { return null; } }
if (!function_exists('setting')) { function setting($name) { return null; } }
if (!function_exists('usia')) { function usia($tgl) { return 0; } }

set_error_handler(function($level, $message, $file, $line) {
    if (strpos($message, 'unlink') !== false) return true;
    echo "ERROR: $message in $file on line $line\n";
    throw new ErrorException($message, 0, $level, $file, $line);
});

$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::login($user);
}

$u = \Illuminate\Support\Facades\Auth::user();
if ($u && $u->pamong && $u->pamong->penduduk) {
    $pend = $u->pamong->penduduk;
    echo "Penduduk Class: " . get_class($pend) . "\n";
    echo "Penduduk idCluster attribute: " . var_export($pend->getAttribute('idCluster'), true) . "\n";
    echo "Penduduk id_cluster attribute: " . var_export($pend->getAttribute('id_cluster'), true) . "\n";
    
    $arr = $pend->toArray();
    echo "Penduduk toArray idCluster: " . var_export($arr['idCluster'] ?? 'NOT_FOUND', true) . "\n";
    echo "Penduduk toArray id_cluster: " . var_export($arr['id_cluster'] ?? 'NOT_FOUND', true) . "\n";
} else {
    echo "User or relations missing!\n";
}

$service = $app->make(\App\Services\LaporanPenduduk::class);

echo "Testing listData...\n";
try {
    $res = $service->listData(1);
    echo "listData(1) success!\n";
} catch (\Throwable $e) {
    echo "listData(1) failed: " . $e->getMessage() . "\n";
}
