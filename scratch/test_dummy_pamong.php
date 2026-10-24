<?php
// scratch/test_dummy_pamong.php
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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? '/' . $path : '');
    }
}
if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public $username = 'admin';
            public $id_grup = 1;
            public $grup = 1;
            public $batasi_wilayah = 0;
            public function user() { return $this; }
            public function check() { return true; }
        };
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dummy = xosid_get_dummy_pamong(999991, 'Kepala Desa Dummy', 1);

echo "Dummy class: " . get_class($dummy) . "\n";
echo "Dummy pamong_nama: " . $dummy->pamong_nama . "\n";
echo "Dummy nama: " . $dummy->nama . "\n";

try {
    $pend = $dummy->penduduk;
    echo "Dummy penduduk: " . ($pend ? "FOUND (class=" . get_class($pend) . ", nama=" . $pend->nama . ")" : "NULL") . "\n";
    if ($pend) {
        $map = $pend->map;
        echo "  -> map: " . ($map ? "FOUND (class=" . get_class($map) . ")" : "NULL") . "\n";
        if ($map) {
            $map->makeVisible('lat');
            echo "    -> makeVisible('lat') succeeded!\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
