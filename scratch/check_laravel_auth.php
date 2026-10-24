<?php
// scratch/check_laravel_auth.php
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

class XOSIDMockIdentitas {
    public $id = 1;
    public function toArray() { return ['id' => 1]; }
}

if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}

if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return STORAGEPATH . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xosid_config_id_scope_hijack.php';

try {
    @include_once __DIR__ . '/../donjo-app/helpers/opensid_helper.php';
} catch (\Throwable $e) {}

// Start session and set mock user session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['config_id'] = 1;
$_SESSION['user_id'] = 1;
$_SESSION['user'] = 1;
$_SESSION['username'] = 'admin';

include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Trigger DB Config Sync dynamically (simulating CodeIgniter database initialization)
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

// Injects request instance
if (class_exists('Illuminate\Http\Request')) {
    $app->instance('request', \Illuminate\Http\Request::capture());
}

if (!function_exists('AmbilFoto')) {
    function AmbilFoto($f) { return $f; }
}

// Now trigger connection resolver
if (class_exists('Illuminate\Support\Facades\Auth')) {
    // Force connection to trigger sync
    $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    echo "RESOLVED LARAVEL DB NAME: " . $dbName . "\n";
    
    // Login
    \Illuminate\Support\Facades\Auth::guard('admin')->loginUsingId(1);
    $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
    echo "AUTH USER: " . ($user ? get_class($user) . " (id=" . $user->id . ", pamong_id=" . var_export($user->pamong_id, true) . ")" : 'NULL') . "\n";
    if ($user) {
        $pamong = $user->pamong;
        echo "USER PAMONG: " . ($pamong ? get_class($pamong) . " (id=" . $pamong->pamong_id . ", nama=" . $pamong->pamong_nama . ")" : 'NULL') . "\n";
        if ($pamong) {
            echo "USER PAMONG RELATION loaded user? " . ($pamong->relationLoaded('user') ? 'YES' : 'NO') . "\n";
            echo "USER PAMONG USER: " . ($pamong->user ? get_class($pamong->user) . " (id=" . $pamong->user->id . ")" : 'NULL') . "\n";
            $pend = $pamong->penduduk;
            echo "USER PAMONG PENDUDUK: " . ($pend ? get_class($pend) . " (id=" . $pend->id . ", nama=" . $pend->nama . ")" : 'NULL') . "\n";
            if ($pend) {
                $map = $pend->map;
                echo "USER PAMONG PENDUDUK MAP: " . ($map ? get_class($map) : 'NULL') . "\n";
            }
        }
    }
}
