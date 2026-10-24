<?php
// scratch/test_relation.php
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

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($app['db'])) {
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

// Fetch resident 33225 and its relations
$resident = \App\Models\Penduduk::find(33225);
if ($resident) {
    echo "Found resident: " . $resident->nama . "\n";
    
    // Check map relation
    try {
        $map = $resident->map;
        echo "Map relation: " . ($map ? "FOUND (class=" . get_class($map) . ")" : "NULL") . "\n";
        if ($map) {
            $map->makeVisible('lat');
            echo "makeVisible on map succeeded!\n";
        }
    } catch (\Throwable $e) {
        echo "Map relation error: " . $e->getMessage() . "\n";
    }

    // Check lokasi relation
    try {
        $lokasi = $resident->lokasi;
        echo "Lokasi relation: " . ($lokasi ? "FOUND (class=" . get_class($lokasi) . ")" : "NULL") . "\n";
        if ($lokasi) {
            $lokasi->makeVisible('lat');
            echo "makeVisible on lokasi succeeded!\n";
        }
    } catch (\Throwable $e) {
        echo "Lokasi relation error: " . $e->getMessage() . "\n";
    }
    
    // Check other relations (e.g. wilayah, keluarga, etc.)
    foreach (['wilayah', 'keluarga', 'kk', 'pekerjaan', 'agama', 'pendidikan'] as $rel) {
        try {
            if (method_exists($resident, $rel)) {
                $val = $resident->$rel;
                echo "Relation '$rel': " . ($val ? "FOUND" : "NULL") . "\n";
            } else {
                echo "Method '$rel' does not exist on Resident class!\n";
            }
        } catch (\Throwable $e) {
            echo "Relation '$rel' error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "Resident 33225 NOT FOUND!\n";
}
