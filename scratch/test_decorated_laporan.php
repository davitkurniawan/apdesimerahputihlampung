<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../index.php';
xosid_configure_laravel_app_decorators($app);

if (!function_exists('geoip_info')) { function geoip_info() { return null; } }
if (!function_exists('setting')) { function setting($name) { return null; } }

set_error_handler(function($level, $message, $file, $line) {
    if (strpos($message, 'unlink') !== false) return true;
    echo "ERROR: $message in $file on line $line\n";
    throw new ErrorException($message, 0, $level, $file, $line);
});

// Auth login will run through the decorated Auth manager
$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::login($user);
}

// Let's verify what Auth::user()->pamong->penduduk returns now
$u = \Illuminate\Support\Facades\Auth::user();
if ($u && $u->pamong && $u->pamong->penduduk) {
    $pend = $u->pamong->penduduk;
    echo "Penduduk class: " . get_class($pend) . "\n";
    echo "id_cluster: " . var_export($pend->id_cluster, true) . "\n";
    echo "idCluster: " . var_export($pend->idCluster, true) . "\n";
    echo "idCluster key in array: " . var_export($pend['idCluster'], true) . "\n";
} else {
    echo "Mock relations failed to load!\n";
}

$service = $app->make(\App\Services\LaporanPenduduk::class);
echo "Testing listData...\n";
try {
    $res = $service->listData(1);
    echo "listData(1) success!\n";
} catch (\Throwable $e) {
    echo "listData(1) failed: " . $e->getMessage() . "\n";
}
