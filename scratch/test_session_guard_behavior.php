<?php
// scratch/test_session_guard_behavior.php
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
\Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "--- TEST 1: user_id is integer 1 ---\n";
$_SESSION['user_id'] = 1;
\Illuminate\Support\Facades\DB::connection()->enableQueryLog();
\Illuminate\Support\Facades\DB::connection()->flushQueryLog();

$guard = \Illuminate\Support\Facades\Auth::guard('admin');
if (method_exists($guard, 'forgetUser')) {
    $guard->forgetUser();
}
$user = $guard->user();
echo "User resolved: " . ($user ? "FOUND (ID=" . $user->id . ", Username=" . $user->username . ")" : "NULL") . "\n";
print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());

echo "\n--- TEST 2: user_id is XosidSessionUser ---\n";
if (method_exists($guard, 'forgetUser')) {
    $guard->forgetUser();
}
$_SESSION['user_id'] = new XosidSessionUser();
\Illuminate\Support\Facades\DB::connection()->flushQueryLog();
$user2 = $guard->user();
echo "User resolved: " . ($user2 ? "FOUND (ID=" . $user2->id . ", Username=" . $user2->username . ")" : "NULL") . "\n";
print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
