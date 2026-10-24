<?php
// scratch/inspect_kades_query.php
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

if (!function_exists('ci_auth')) {
    function ci_auth() { 
        return new class { 
            public $id = 1; 
            public $id_grup = 1;
            public function user() { return $this; } 
        }; 
    }
}

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}

if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Ensure database connection is Bondo
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database'  => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
foreach (['default', 'mysql', 'opensid', 'tenant', ''] as $name) {
    $config->set("database.connections.{$name}", $laravel_db);
}
$config->set('database.default', 'default');

\Illuminate\Support\Facades\DB::purge();
\Illuminate\Support\Facades\DB::reconnect();

echo "SQL: " . \App\Models\Pamong::kepalaDesa()->toSql() . "\n";
echo "Bindings: " . json_encode(\App\Models\Pamong::kepalaDesa()->getBindings()) . "\n";

// Execute query directly
echo "\n=== INSPECTING KADES ===\n";
$kades = \App\Models\Pamong::where('pamong_id', 27)->first();
echo "Kades: " . ($kades ? json_encode($kades->getAttributes()) : "NULL") . "\n";
if ($kades) {
    $user = $kades->user;
    echo "User: " . ($user ? json_encode($user->getAttributes()) : "NULL") . "\n";
    $pend = $kades->penduduk;
    echo "Penduduk: " . ($pend ? json_encode($pend->getAttributes()) : "NULL") . "\n";
    if ($pend) {
        $map = $pend->map;
        echo "Map: " . ($map ? json_encode($map->getAttributes()) : "NULL") . "\n";
    }
}

echo "\n=== INSPECTING SEKDES ===\n";
$sekdes = \App\Models\Pamong::where('pamong_id', 28)->first();
echo "Sekdes: " . ($sekdes ? json_encode($sekdes->getAttributes()) : "NULL") . "\n";
if ($sekdes) {
    $user = $sekdes->user;
    echo "User: " . ($user ? json_encode($user->getAttributes()) : "NULL") . "\n";
    $pend = $sekdes->penduduk;
    echo "Penduduk: " . ($pend ? json_encode($pend->getAttributes()) : "NULL") . "\n";
    if ($pend) {
        $map = $pend->map;
        echo "Map: " . ($map ? json_encode($map->getAttributes()) : "NULL") . "\n";
    }
}

