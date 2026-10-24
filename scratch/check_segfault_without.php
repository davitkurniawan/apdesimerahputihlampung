<?php
// Test: Override $with on Pamong to prevent eager-load segfault
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

\Illuminate\Database\Eloquent\Model::unsetEventDispatcher();

echo "=== Check Penduduk \$with ===\n";
$pend = new \App\Models\Penduduk();
$rp = new ReflectionClass(get_class($pend));
$withProp = $rp->getProperty('with');
$withProp->setAccessible(true);
$withVal = $withProp->getValue($pend);
echo "Penduduk \$with = " . var_export($withVal, true) . "\n";

echo "\n=== Test: Pamong::without('penduduk','jabatan')->where()->get() ===\n";
flush();
try {
    $result = \App\Models\Pamong::without(['penduduk', 'jabatan'])
        ->where('jabatan_id', 1)
        ->get();
    echo "SUCCESS: Got " . count($result) . " results\n";
    if (count($result) > 0) {
        $p = $result[0];
        echo "  pamong_id=" . $p->pamong_id . "\n";
        echo "  Accessing pamong_nama (will trigger lazy load)...\n";
        flush();
        $nama = $p->pamong_nama;
        echo "  pamong_nama = " . var_export($nama, true) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Check Jabatan model ===\n";
$jabFile = '/var/www/html/app/Models/Jabatan.php';
if (file_exists($jabFile)) {
    $head = file_get_contents($jabFile, false, null, 0, 200);
    echo "Jabatan file head: " . (strpos($head, 'ionCube') !== false ? 'IonCube-encoded' : 'Plain PHP') . "\n";
    
    if (strpos($head, 'ionCube') === false) {
        echo "Content:\n" . file_get_contents($jabFile) . "\n";
    }
} else {
    echo "Jabatan model file not found!\n";
    // Check if it's a RefJabatan or similar
    echo "Looking for jabatan-related models...\n";
    foreach (glob('/var/www/html/app/Models/*abatan*') as $f) {
        echo "  Found: $f\n";
    }
    foreach (glob('/var/www/html/app/Models/Ref*') as $f) {
        echo "  Ref model: $f\n";
    }
}

echo "\nDONE!\n";
