<?php
// scratch/test_identitas_queries.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bypass CLI detection in CodeIgniter
if (!function_exists('is_cli')) {
    function is_cli() {
        return false;
    }
}

// Intercept show_404 to see who triggers it
if (!function_exists('show_404')) {
    function show_404($page = '', $log_error = TRUE) {
        echo "\n\n========================================\n";
        echo "show_404() CALLED! Page: $page\n";
        echo "Backtrace:\n";
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo "========================================\n\n";
        exit;
    }
}

if (!function_exists('redirect')) {
    function redirect($uri = '', $method = 'auto', $code = NULL) {
        echo "REDIRECT CALLED TO: $uri\n";
        exit;
    }
}

// Define environment
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('VIEWPATH', APPPATH . 'views/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['PATH_INFO'] = '/identitas_desa';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = '/var/www/html/index.php';
$_SERVER['PHP_SELF'] = '/index.php/identitas_desa';

// We must set up session authentication
if (session_status() === PHP_SESSION_NONE) {
    session_name('PHPSESSID_bondo-bangsri');
    session_start();
}

// Inject user session variables
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

if (!class_exists('XosidSessionUser')) {
    class XosidSessionUser implements ArrayAccess, JsonSerializable {
        public $id = 1;
        public $username = 'admin';
        public $id_grup = 1;
        public $grup = 1;
        public $batasi_wilayah = 0;
        public $nama = 'Administrator';
        public $foto = 'user.png';
        public $email = 'admin@desa.id';
        public $email_verified_at = '2026-01-01 00:00:00';
        public $notif_telegram = 0;
        public $id_telegram = '';
        public $userGrup;
        public function __construct() {
            $this->userGrup = (object)['nama' => 'Administrator'];
        }

        #[\ReturnTypeWillChange]
        public function offsetExists($offset) { return isset($this->$offset); }
        #[\ReturnTypeWillChange]
        public function offsetGet($offset) { return $this->$offset ?? null; }
        #[\ReturnTypeWillChange]
        public function offsetSet($offset, $value) { $this->$offset = $value; }
        #[\ReturnTypeWillChange]
        public function offsetUnset($offset) { unset($this->$offset); }
        public function __get($name) { return $this->$name ?? null; }
        public function __set($name, $value) { $this->$name = $value; }
        public function __isset($name) { return isset($this->$name); }
        public function __toString() { return 'admin'; }
        #[\ReturnTypeWillChange]
        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'id_grup' => $this->id_grup,
                'grup' => $this->grup,
                'batasi_wilayah' => $this->batasi_wilayah,
                'nama' => $this->nama
            ];
        }
    }
}

$userObj = new XosidSessionUser();
$_SESSION['user'] = $userObj;
$_SESSION['user_id'] = $userObj;
$_SESSION['username'] = $userObj;
$_SESSION['nama'] = 'Administrator';
$_SESSION['siteman'] = 1;
$_SESSION['grup'] = 1;
$_SESSION['admin_level'] = 1;
$_SESSION['sesi'] = 'aktif';

// Register shutdown function to dump Laravel queries and errors
register_shutdown_function(function() {
    echo "\n\n=== SHUTDOWN TRACE ===\n";
    if (class_exists('Illuminate\Support\Facades\DB')) {
        try {
            $queries = \Illuminate\Support\Facades\DB::getQueryLog();
            echo "Laravel Eloquent Queries Executed: " . count($queries) . "\n";
            foreach ($queries as $q) {
                echo "  SQL: {$q['query']} | Binds: " . json_encode($q['bindings']) . " | Time: {$q['time']}ms\n";
            }
        } catch (\Throwable $e) {
            echo "Error getting query log: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Laravel DB class not loaded.\n";
    }
    
    $error = error_get_last();
    if ($error) {
        echo "Last PHP Error:\n";
        print_r($error);
    }
});

// Enable query logging early
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

\Illuminate\Support\Facades\DB::enableQueryLog();

// Set up custom error handler to get full backtrace on Error
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, 'makeVisible') !== false || $errno === E_ERROR || $errno === E_USER_ERROR) {
        echo "\n\n========================================\n";
        echo "ERROR INTERCEPTED: $errstr\n";
        echo "File: $errfile on line $errline\n";
        echo "Backtrace:\n";
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo "========================================\n";
    }
    return false;
});

// Now boot CodeIgniter
echo "Booting CodeIgniter with route to identitas_desa...\n";
require_once BASEPATH . 'core/Common.php';
$CFG =& load_class('Config', 'core');
$CFG->set_item('enable_hooks', FALSE);

require_once BASEPATH . 'core/CodeIgniter.php';
