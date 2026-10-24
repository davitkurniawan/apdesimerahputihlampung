<?php
// scratch/inspect_model_relations.php
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

$classes = [
    'App\Models\Config',
    'App\Models\Pamong',
    'App\Models\User',
    'App\Models\Penduduk'
];

foreach ($classes as $className) {
    if (!class_exists($className)) {
        echo "Class $className does not exist.\n";
        continue;
    }
    
    echo "=== RELATIONS FOR $className ===\n";
    $model = new $className();
    $ref = new ReflectionClass($className);
    
    foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->isStatic()) continue;
        if ($method->getNumberOfParameters() > 0) continue;
        
        $name = $method->getName();
        // Ignore standard Eloquent methods
        if (in_array($name, ['getConnection', 'getRelation', 'getRelations', 'getAttributes', 'getMutatedAttributes'])) continue;
        
        try {
            $result = $method->invoke($model);
            if ($result instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                echo " - Method: $name() | Relation type: " . get_class($result) . " | Related model: " . get_class($result->getRelated()) . "\n";
            }
        } catch (\Throwable $e) {
            echo "   [!] Method $name() failed: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
}
