<?php
// Test: Pamong get() with no event dispatcher at all
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

// Check dispatcher state 
$disp = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
echo "Dispatcher: " . ($disp ? get_class($disp) : 'NULL') . "\n";

// Ensure NO event dispatcher exists so fireModelEvent in newFromBuilder is a no-op
\Illuminate\Database\Eloquent\Model::unsetEventDispatcher();
$disp2 = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
echo "After unset: " . ($disp2 ? get_class($disp2) : 'NULL') . "\n";

echo "\n=== Test: Pamong::where('jabatan_id', 1)->get() with NO event dispatcher ===\n";
flush();
try {
    $result = \App\Models\Pamong::where('jabatan_id', 1)->get();
    echo "SUCCESS: Got " . count($result) . " results\n";
    if (count($result) > 0) {
        $p = $result[0];
        echo "  pamong_id=" . $p->pamong_id . "\n";
        echo "  Accessing pamong_nama...\n";
        flush();
        $nama = $p->pamong_nama;
        echo "  pamong_nama = " . var_export($nama, true) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Done ===\n";
