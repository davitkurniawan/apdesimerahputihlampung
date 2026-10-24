<?php
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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
// Set up error handler to capture the warning and print stack trace
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "========================================\n";
    echo "ERROR/WARNING CAPTURED: $errstr\n";
    echo "File: $errfile | Line: $errline\n";
    echo "SESSION user: ";
    var_dump($_SESSION['user'] ?? 'NOT SET');
    echo "SESSION user_id: ";
    var_dump($_SESSION['user_id'] ?? 'NOT SET');
    echo "Backtrace with args:\n";
    $trace = debug_backtrace(0);
    foreach ($trace as $i => $step) {
        echo "  #$i " . ($step['file'] ?? 'unknown') . ":" . ($step['line'] ?? 'unknown') . " - " . ($step['function'] ?? '') . "\n";
    }
    echo "========================================\n";
    return false; // Continue with standard handling
});
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('kades')) {
    function kades() {
        return \App\Models\RefJabatan::where('id', 1)->first() ?: new class { public $id = 1; public $nama = 'Kepala Desa'; };
    }
}
if (!function_exists('sekdes')) {
    function sekdes() {
        return \App\Models\RefJabatan::where('id', 2)->first() ?: new class { public $id = 2; };
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
            public function user() {
                return $this;
            }
            public function check() {
                return true;
            }
        };
    }
}

if (!function_exists('usia')) {
    function usia($tgl_lahir) {
        return 30;
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (!\Illuminate\Database\Eloquent\Model::getEventDispatcher() && isset($app['events'])) {
    \Illuminate\Database\Eloquent\Model::setEventDispatcher($app['events']);
}

\App\Models\Pamong::retrieved(function($model) {
    echo "RETRIEVED RUNNING FOR: " . $model->pamong_id . "\n";
    $model->setAttribute('id', $model->getKey());
});

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

// Inject connection
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

$queries = [];
if (isset($app['db'])) {
    $app['db']->listen(function($query) use (&$queries) {
        $queries[] = "SQL: " . $query->sql . " | Binds: " . json_encode($query->bindings);
    });
}

try {
    echo "Calling sekdes() directly:\n";
    $s = sekdes();
    echo "sekdes() class: " . (is_object($s) ? get_class($s) : gettype($s)) . "\n";
    echo "sekdes() id: " . var_export($s->id ?? 'no_id', true) . "\n";

    echo "\nQuerying Pamong WITHOUT global scopes:\n";
    $pamong_no_scopes = \App\Models\Pamong::withoutGlobalScopes()->where('jabatan_id', 2)->first();
    if ($pamong_no_scopes) {
        echo "Found WITHOUT global scopes! Name: " . ($pamong_no_scopes->pamong_nama ?: 'empty') . "\n";
        echo "pamong_id: " . var_export($pamong_no_scopes->pamong_id, true) . "\n";
        echo "id: " . var_export($pamong_no_scopes->id, true) . "\n";
    } else {
        echo "NOT found WITHOUT global scopes!\n";
    }

    echo "\nQuerying Pamong WITH global scopes but WITHOUT sekretarisDesa scope:\n";
    $pamong_with_scopes = \App\Models\Pamong::where('jabatan_id', 2)->first();
    if ($pamong_with_scopes) {
        echo "Found WITH global scopes! Name: " . ($pamong_with_scopes->pamong_nama ?: 'empty') . "\n";
    } else {
        echo "NOT found WITH global scopes!\n";
    }

    echo "\nQuerying Pamong using where('jabatan_id', sekdes()):\n";
    $pamong_with_model_obj = \App\Models\Pamong::where('jabatan_id', sekdes())->first();
    if ($pamong_with_model_obj) {
        echo "Found using sekdes() object! Name: " . ($pamong_with_model_obj->pamong_nama ?: 'empty') . "\n";
    } else {
        echo "NOT found using sekdes() object!\n";
    }

    echo "\nQuerying Pamong with kepalaDesa scope:\n";
    $kades_pamong = \App\Models\Pamong::kepalaDesa()->first();
    if ($kades_pamong) {
        echo "Found Kades! Name: " . ($kades_pamong->pamong_nama ?: 'empty') . "\n";
    } else {
        echo "No Pamong found with kepalaDesa scope!\n";
    }

    echo "\nQuerying Pamong with sekretarisDesa scope:\n";
    $pamong = \App\Models\Pamong::sekretarisDesa()->first();
    if ($pamong) {
        echo "Found Sekdes! Name: " . ($pamong->pamong_nama ?: 'empty') . "\n";
    } else {
        echo "No Pamong found with sekretarisDesa scope!\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== EXECUTED SQL QUERIES ===\n";
foreach ($queries as $q) {
    echo $q . "\n";
}

