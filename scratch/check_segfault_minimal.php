<?php
// Minimal segfault test - bypass all retrieved hooks
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

// Disable ALL retrieved hooks by pre-setting the guard
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

echo "=== TEST 1: Config::first() with hooks DISABLED ===\n";
$desa = \App\Models\Config::first();
if (!$desa) {
    echo "Config model not found!\n";
    exit;
}
echo "Config ID: " . $desa->id . "\n";
echo "Config Nama Desa: " . $desa->nama_desa . "\n";
echo "Relations loaded: " . implode(', ', array_keys($desa->getRelations())) . "\n";

echo "\n=== TEST 2: Pamong::where(jabatan_id,1)->first() with hooks DISABLED ===\n";
$kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
echo "Kades: " . ($kades ? "ID=" . $kades->pamong_id . " nama=" . $kades->pamong_nama : "NULL") . "\n";

echo "\n=== TEST 3: Access \$desa->pamong relation directly ===\n";
try {
    $pamong = $desa->pamong;
    echo "pamong: " . ($pamong ? get_class($pamong) . " ID=" . $pamong->pamong_id : "NULL") . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST 4: makeVisible on Pamong ===\n";
if ($kades) {
    try {
        $kades->makeVisible('pamong_nip');
        echo "makeVisible succeeded!\n";
    } catch (\Throwable $e) {
        echo "makeVisible FAILED: " . $e->getMessage() . "\n";
    }
}

echo "\n=== TEST 5: Re-enable hooks and fetch Config again ===\n";
$GLOBALS['xosid_in_retrieved_callback'] = false;
try {
    $desa2 = \App\Models\Config::first();
    echo "Config fetched again with hooks: ID=" . $desa2->id . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== All tests completed without segfault! ===\n";
