<?php
// scratch/test_config_queries.php
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

// Mock get_instance early
if (!function_exists('get_instance')) {
    class DummyCI {
        public $db;
        public function __construct() {
            $this->db = new class {
                public $database = 'xosid_tenant_bondo-bangsri';
            };
        }
    }
    function &get_instance() {
        static $ci = null;
        if ($ci === null) {
            $ci = new DummyCI();
        }
        return $ci;
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        echo "[setting() called: key='{$key}', default='{$default}']\n";
        if ($key === 'kades_jabatan_id') return 27; // or whatever
        return $default;
    }
}

$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Enable query logging
\Illuminate\Support\Facades\DB::connection()->enableQueryLog();

$c = \App\Models\Config::first();
echo "Config Loaded: " . ($c ? "YES" : "NO") . "\n";

echo "\n--- Calling pamong(1) ---\n";
try {
    \Illuminate\Support\Facades\DB::connection()->flushQueryLog();
    $res = $c->pamong(1);
    echo "Result: " . ($res ? "FOUND (ID={$res->pamong_id})" : "NULL") . "\n";
    print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- Calling pamong(27) ---\n";
try {
    \Illuminate\Support\Facades\DB::connection()->flushQueryLog();
    $res = $c->pamong(27);
    echo "Result: " . ($res ? "FOUND (ID={$res->pamong_id})" : "NULL") . "\n";
    print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- Calling pamong() ---\n";
try {
    \Illuminate\Support\Facades\DB::connection()->flushQueryLog();
    $res = $c->pamong();
    echo "Result: " . ($res ? "FOUND (ID={$res->pamong_id})" : "NULL") . "\n";
    print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- Accessing nama_kepala_desa ---\n";
try {
    \Illuminate\Support\Facades\DB::connection()->flushQueryLog();
    $res = $c->nama_kepala_desa;
    echo "Result: " . json_encode($res) . "\n";
    print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
