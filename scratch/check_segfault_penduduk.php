<?php
// Test: pamong_nama accessor crash - is it because penduduk relation is the issue?
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}

$GLOBALS['xosid_hooks_registered'] = true;
$GLOBALS['xosid_in_retrieved_callback'] = true;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

$raw = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->where('jabatan_id', 1)
    ->limit(1)
    ->first();

echo "Raw data: pamong_id={$raw->pamong_id}, id_pend={$raw->id_pend}, pamong_nama=" . var_export($raw->pamong_nama, true) . "\n";

// Fetch the penduduk for this pamong
echo "\nFetching penduduk with id={$raw->id_pend}...\n";
$penduduk_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
    ->where('id', $raw->id_pend)
    ->first();
if ($penduduk_raw) {
    echo "Penduduk found: nama=" . $penduduk_raw->nama . "\n";
} else {
    echo "Penduduk NOT FOUND for id_pend={$raw->id_pend}!\n";
}

echo "\n=== Test 1: Create Pamong and preload penduduk relation ===\n";
$pamong = new \App\Models\Pamong();
foreach ((array)$raw as $k => $v) {
    $pamong->setAttribute($k, $v);
}
$pamong->exists = true;
$pamong->syncOriginal();

// Pre-load the penduduk relation so accessor doesn't lazy-load
if ($penduduk_raw) {
    echo "Pre-loading penduduk relation...\n";
    $pend = new \App\Models\Penduduk();
    foreach ((array)$penduduk_raw as $k => $v) {
        $pend->setAttribute($k, $v);
    }
    $pend->exists = true;
    $pend->syncOriginal();
    
    // Also pre-load map/lokasi to avoid further lazy loading
    if (class_exists('App\Models\PendudukMap')) {
        $map = new \App\Models\PendudukMap();
        $map->id = $pend->id;
        $map->config_id = $pend->config_id;
        $map->lat = null;
        $map->lng = null;
        $pend->setRelation('map', $map);
        $pend->setRelation('lokasi', $map);
    }
    
    $pamong->setRelation('penduduk', $pend);
    echo "Penduduk relation set. nama=" . $pend->nama . "\n";
}

echo "\nNow accessing \$pamong->pamong_nama (should use accessor)...\n";
flush();
try {
    $nama = $pamong->pamong_nama;
    echo "SUCCESS: pamong_nama = " . var_export($nama, true) . "\n";
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}

echo "\n=== Test 2: Check Pamong methods via reflection ===\n";
$rc = new ReflectionClass('App\Models\Pamong');
$methods = $rc->getMethods();
$pamong_methods = [];
foreach ($methods as $m) {
    if ($m->getDeclaringClass()->getName() === 'App\Models\Pamong') {
        $pamong_methods[] = $m->getName();
    }
}
sort($pamong_methods);
echo "Pamong's own methods (" . count($pamong_methods) . "):\n";
foreach ($pamong_methods as $name) {
    echo "  $name\n";
}

echo "\n=== Done ===\n";
