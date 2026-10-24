<?php
// scratch/test_config_pamong_relation.php

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

// Set HTTP host early for DB resolution
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Set up error handler to capture the warning and print stack trace
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, 'Attempt to read property') !== false) {
        echo "========================================\n";
        echo "WARNING CAPTURED: $errstr\n";
        echo "File: $errfile | Line: $errline\n";
        echo "SESSION user: ";
        var_dump($_SESSION['user'] ?? 'NOT SET');
        echo "SESSION user_id: ";
        var_dump($_SESSION['user_id'] ?? 'NOT SET');
        echo "Backtrace with args:\n";
        $trace = debug_backtrace(0);
        foreach ($trace as $i => $step) {
            echo "  #$i " . ($step['file'] ?? 'unknown') . ":" . ($step['line'] ?? 'unknown') . " - " . ($step['function'] ?? '') . "\n";
            if (!empty($step['args'])) {
                foreach ($step['args'] as $argIdx => $arg) {
                    echo "    arg[$argIdx]: type=" . gettype($arg) . " ";
                    if (is_scalar($arg)) {
                        var_dump($arg);
                    } elseif (is_object($arg)) {
                        echo "class=" . get_class($arg) . "\n";
                    } else {
                        echo "\n";
                    }
                }
            }
        }
        echo "========================================\n";
    }
    return false; // Continue with standard handling
});

// Mock functions MUST be defined before autoload is required
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public function user() {
                return (object)[
                    'id' => 1,
                    'username' => 'admin',
                    'id_grup' => 1,
                    'grup' => 1,
                    'batasi_wilayah' => 0
                ];
            }
            public function check() {
                return true;
            }
        };
    }
}
if (!function_exists('usia')) {
    function usia($tgl_lahir) { return 30; }
}
if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/donjo-app/helpers/opensid_helper.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
    $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
}
if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
    $newResolver = new XOSIDConnectionResolver($currentResolver);
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
}

// Inject connection details
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database' => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
$connections['default'] = $laravel_db;
$connections['mysql'] = $laravel_db;
$config->set('database.connections', $connections);
$config->set('database.default', 'default');

// Overwrite PHP session user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!class_exists('XosidSessionUser')) {
    class XosidSessionUser implements ArrayAccess, JsonSerializable {
        public $id = 1;
        public $username = 'admin';
        public $id_grup = 1;
        public $grup = 1;
        public $batasi_wilayah = 0;
        public $nama = 'Administrator';

        #[\ReturnTypeWillChange]
        public function offsetExists($offset) {
            return isset($this->$offset);
        }

        #[\ReturnTypeWillChange]
        public function offsetGet($offset) {
            return $this->$offset ?? null;
        }

        #[\ReturnTypeWillChange]
        public function offsetSet($offset, $value) {
            $this->$offset = $value;
        }

        #[\ReturnTypeWillChange]
        public function offsetUnset($offset) {
            unset($this->$offset);
        }

        public function __get($name) {
            return $this->$name ?? null;
        }

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __isset($name) {
            return isset($this->$name);
        }

        public function __toString() {
            return 'admin';
        }

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

// Authenticate user on Laravel side
try {
    if (class_exists('Illuminate\Support\Facades\Auth')) {
        \Illuminate\Support\Facades\Auth::guard('admin')->loginUsingId(1);
        echo "Logged in user in Laravel admin guard. Current Auth::user(): ";
        var_dump(\Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->id : 'NULL');
    }
} catch (\Throwable $e) {
    echo "Error logging in Laravel: " . $e->getMessage() . "\n";
}

// Dump Laravel Session and ci_auth info
echo "========================================\n";
echo "DEBUGGING SESSION & AUTH STATE:\n";
try {
    if (function_exists('session')) {
        echo "Laravel session('user'): ";
        var_dump(session('user'));
        echo "Laravel session('user_id'): ";
        var_dump(session('user_id'));
        echo "Laravel session()->all():\n";
        print_r(session()->all());
    } else {
        echo "Laravel session() function does not exist.\n";
    }
} catch (\Throwable $e) {
    echo "Error querying Laravel session: " . $e->getMessage() . "\n";
}

try {
    if (function_exists('ci_auth')) {
        echo "ci_auth()->user(): ";
        var_dump(ci_auth()->user());
    } else {
        echo "ci_auth() function does not exist.\n";
    }
} catch (\Throwable $e) {
    echo "Error querying ci_auth(): " . $e->getMessage() . "\n";
}
echo "========================================\n";

try {
    $configModel = \App\Models\Config::first();
    if ($configModel) {
        $pamong = $configModel->pamong()->first();
    }
} catch (\Throwable $e) {}
