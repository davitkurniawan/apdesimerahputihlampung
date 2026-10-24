<?php
// scratch/test_config_id.php

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

\Illuminate\Support\Facades\DB::enableQueryLog();

$cfg = \App\Models\Config::first();
echo "Config Class: " . get_class($cfg) . "\n";
echo "Config ID: " . $cfg->id . "\n";
echo "Config Attributes:\n";
print_r($cfg->getAttributes());

echo "\nExecuted Queries:\n";
print_r(\Illuminate\Support\Facades\DB::getQueryLog());
