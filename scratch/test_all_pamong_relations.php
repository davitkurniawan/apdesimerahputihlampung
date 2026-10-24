<?php
// scratch/test_all_pamong_relations.php

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', 'index.php');
if (!defined('BASEPATH'))
    define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL'))
    define('APP_URL', 'http://localhost:8080');
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);
if (!defined('LOKASI_CONFIG_DESA'))
    define('LOKASI_CONFIG_DESA', 'desa/config/');

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
if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
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

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
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
function get_instance() {
    static $instance = null;
    if ($instance === null) {
        $instance = new XOSIDMockCIInstance();
    }
    return $instance;
}

// Ensure dummy resident 999999 exists in Bondo tenant DB before testing
try {
    $db = $app['db'];
    if ($db) {
        $db->statement("INSERT INTO tweb_penduduk (id, config_id, nama, nik, status_dasar, tanggallahir, created_by) SELECT 999999, 1, 'Dummy Pamong', '0000000000000000', 1, '1980-01-01', 1 WHERE NOT EXISTS (SELECT 1 FROM tweb_penduduk WHERE id = 999999)");
        $db->statement("INSERT INTO tweb_penduduk_map (id, config_id, lat, lng) SELECT 999999, 1, NULL, NULL WHERE NOT EXISTS (SELECT 1 FROM tweb_penduduk_map WHERE id = 999999)");
        echo "Dummy resident 999999 verified/inserted in DB.\n";
    }
} catch (\Throwable $dbErr) {
    echo "DB prep error: " . $dbErr->getMessage() . "\n";
}

// Register retrieved listener for Pamong model to inject dummy penduduk and map relations
if (class_exists('App\Models\Pamong')) {
    if (!\App\Models\Pamong::getEventDispatcher()) {
        \App\Models\Pamong::setEventDispatcher(new \Illuminate\Events\Dispatcher());
        echo "Manually registered Event Dispatcher on App\\Models\\Pamong.\n";
    }
    echo "App\\Models\\Pamong class exists, registering retrieved listener...\n";
    \App\Models\Pamong::retrieved(function($model) {
        echo "Retrieved event fired for Pamong ID: " . $model->pamong_id . " | id_pend: " . json_encode($model->id_pend) . "\n";
        if (!array_key_exists('id_pend', $model->getAttributes())) {
            $model->setAttribute('id_pend', null);
        }
        if (empty($model->id_pend)) {
            echo "  -> id_pend is empty, rewriting to 999999\n";
            $model->id_pend = 999999;
            $model->setAttribute('id_pend', 999999);
        }
    });
} else {
    echo "App\\Models\\Pamong class DOES NOT EXIST in registration scope!\n";
}

echo "Event Dispatcher status: " . (\App\Models\Pamong::getEventDispatcher() ? "REGISTERED (class=" . get_class(\App\Models\Pamong::getEventDispatcher()) . ")" : "NULL") . "\n";

// Fetch all pamong
$pamongs = \App\Models\Pamong::all();
echo "Total pamongs loaded: " . count($pamongs) . "\n\n";

foreach ($pamongs as $idx => $p) {
    echo "Pamong #$idx: ID=" . $p->pamong_id . " | Nama='" . $p->pamong_nama . "' | NIK='" . $p->pamong_nik . "' | id_pend='" . ($p->id_pend ?? 'NULL') . "'\n";
    try {
        $pend = $p->penduduk;
        echo "  -> Penduduk relation: " . ($pend ? "FOUND (Nama='" . $pend->nama . "', NIK='" . $pend->nik . "', ID=" . $pend->id . ")" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "    -> Map relation: " . ($map ? "FOUND" : "NULL") . "\n";
            if ($map) {
                $map->makeVisible('lat');
                echo "      -> makeVisible on map succeeded!\n";
            }
        }
    } catch (\Throwable $e) {
        echo "  -> ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
