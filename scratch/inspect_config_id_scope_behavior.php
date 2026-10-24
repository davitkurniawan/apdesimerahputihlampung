<?php
// scratch/inspect_config_id_scope_behavior.php

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
define('APP_URL', 'http://localhost:8080');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null) {
        return 'Desa';
    }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public function user() {
                return $this;
            }
        };
    }
}
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate Bondo environment
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

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

// Let's call apply on ConfigIdScope directly
$model = new App\Models\Pamong;
$builder = $model->newQueryWithoutScopes();
$scope = new App\Scopes\ConfigIdScope;

echo "Calling apply() on ConfigIdScope...\n";
$scope->apply($builder, $model);

echo "Query SQL: " . $builder->toSql() . "\n";
echo "Query Bindings: " . json_encode($builder->getBindings()) . "\n";

echo "Running App\\Models\\Config::first() check:\n";
try {
    $cfg = \App\Models\Config::first();
    echo "  Config: " . ($cfg ? json_encode($cfg->toArray()) : 'NULL') . "\n";
    echo "  Calling \$cfg->pamong():\n";
    $rel = $cfg->pamong();
    echo "  Returned class: " . (is_object($rel) ? get_class($rel) : gettype($rel)) . "\n";
} catch (\Throwable $e) {
    echo "  Config check failed: " . $e->getMessage() . "\n";
}


echo "Running Pamong::kepalaDesa() query:\n";
$kades_query = \App\Models\Pamong::kepalaDesa();
echo "  SQL: " . $kades_query->toSql() . "\n";
echo "  Bindings: " . json_encode($kades_query->getBindings()) . "\n";
try {
    $kades = $kades_query->first();
    echo "  Result: " . ($kades ? json_encode($kades->toArray()) : 'NULL') . "\n";
} catch (\Throwable $e) {
    echo "  Query failed: " . $e->getMessage() . "\n";
}

echo "Running Pamong::sekretarisDesa() query:\n";
$sekdes_query = \App\Models\Pamong::sekretarisDesa();
echo "  SQL: " . $sekdes_query->toSql() . "\n";
echo "  Bindings: " . json_encode($sekdes_query->getBindings()) . "\n";
try {
    $sekdes = $sekdes_query->first();
    echo "  Result: " . ($sekdes ? json_encode($sekdes->toArray()) : 'NULL') . "\n";
} catch (\Throwable $e) {
    echo "  Query failed: " . $e->getMessage() . "\n";
}
