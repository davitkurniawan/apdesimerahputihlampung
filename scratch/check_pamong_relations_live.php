<?php
// scratch/check_pamong_relations_live.php

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
define('FORCE_HTTPS', false);
define('LOKASI_LOGO_DESA', 'desa/logo/');

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';
define('APP_URL', 'http://localhost:8080');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}
if (!function_exists('ci_auth')) {
    function ci_auth() { 
        return new class { 
            public $id = 1; 
            public $id_grup = 1;
            public function user() { return $this; } 
        }; 
    }
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

require_once dirname(__DIR__) . '/vendor/autoload.php';
include_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/donjo-app/helpers/opensid_helper.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    if (class_exists('XOSIDMySqlConnection')) {
        foreach (['mysql', 'default', 'opensid', 'tenant', ''] as $conn_name) {
            $app['db']->extend($conn_name, function($config, $name) {
                $connector = new \Illuminate\Database\Connectors\MySqlConnector;
                $pdo = $connector->connect($config);
                if (class_exists('XOSIDPDOStatement')) {
                    $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, ['XOSIDPDOStatement', []]);
                }
                return new XOSIDMySqlConnection($pdo, $config['database'], $config['prefix'] ?? '', $config);
            });
        }
    }
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Emulate CodeIgniter database connection config
class XOSIDMockCIDB {
    public $database = 'xosid_tenant_bondo-bangsri';
    public $hostname = 'db';
    public $username = 'root';
    public $password = 'password';
    public $dbcollat = 'utf8mb4_general_ci';
    public $port = 3306;
}
class XOSIDMockCIInstance {
    public $db;
    public function __construct() {
        $this->db = new XOSIDMockCIDB();
    }
}
if (!function_exists('get_instance')) {
    function get_instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new XOSIDMockCIInstance();
        }
        return $instance;
    }
}

// Register retrieved listener for Pamong model to inject dummy penduduk and map relations
if (class_exists('App\Models\Pamong')) {
    if (!\App\Models\Pamong::getEventDispatcher()) {
        \App\Models\Pamong::setEventDispatcher(new \Illuminate\Events\Dispatcher());
    }
    \App\Models\Pamong::retrieved(function($model) {
        if (!array_key_exists('id_pend', $model->getAttributes())) {
            $model->setAttribute('id_pend', null);
        }
        if (empty($model->id_pend)) {
            $model->id_pend = 999999;
            $model->setAttribute('id_pend', 999999);
        }
    });
}

// Check Config
$cfg = \App\Models\Config::first();
echo "Config: " . ($cfg ? "Found (id=" . $cfg->id . ")" : "NULL") . "\n";
if ($cfg) {
    echo "Config pamong_id (Kades ID): " . $cfg->pamong_id . "\n";
    echo "Config pamong_id_sekdes (Sekdes ID): " . $cfg->pamong_id_sekdes . "\n";
    
    // Call getNamaKepalaDesaAttribute
    try {
        echo "Nama Kepala Desa (via accessor): " . $cfg->nama_kepala_desa . "\n";
    } catch (\Throwable $e) {
        echo "Nama Kepala Desa accessor error: " . $e->getMessage() . "\n";
    }
}

// Check Kades Pamong
try {
    $kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
    echo "Kades Pamong: " . ($kades ? "Found (id=" . $kades->pamong_id . ", nama=" . $kades->pamong_nama . ", id_pend=" . $kades->id_pend . ")" : "NULL") . "\n";
    if ($kades) {
        $pend = $kades->penduduk;
        echo "  Penduduk: " . ($pend ? "Found (id=" . $pend->id . ", nama=" . $pend->nama . ")" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "    Map: " . ($map ? "Found (id=" . $map->id . ")" : "NULL") . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "Kades check error: " . $e->getMessage() . "\n";
}

// Check Sekdes Pamong
try {
    $sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
    echo "Sekdes Pamong: " . ($sekdes ? "Found (id=" . $sekdes->pamong_id . ", nama=" . $sekdes->pamong_nama . ", id_pend=" . $sekdes->id_pend . ")" : "NULL") . "\n";
    if ($sekdes) {
        $pend = $sekdes->penduduk;
        echo "  Penduduk: " . ($pend ? "Found (id=" . $pend->id . ", nama=" . $pend->nama . ")" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "    Map: " . ($map ? "Found (id=" . $map->id . ")" : "NULL") . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "Sekdes check error: " . $e->getMessage() . "\n";
}
