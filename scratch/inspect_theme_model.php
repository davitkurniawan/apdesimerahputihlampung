<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

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

try {
    $configModel = \App\Models\Config::first();
    echo "Config pamong_id: " . var_export($configModel->pamong_id, true) . "\n";
    if ($configModel->pamong_id) {
        $pamong = \App\Models\Pamong::find($configModel->pamong_id);
        echo "Pamong record for ID " . $configModel->pamong_id . ": " . ($pamong ? 'FOUND' : 'NOT FOUND') . "\n";
        if ($pamong) {
            print_r($pamong->getAttributes());
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
