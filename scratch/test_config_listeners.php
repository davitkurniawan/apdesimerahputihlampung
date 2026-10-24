<?php
// scratch/test_config_listeners.php

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

if (!\Illuminate\Database\Eloquent\Model::getEventDispatcher() && isset($app['events'])) {
    \Illuminate\Database\Eloquent\Model::setEventDispatcher($app['events']);
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

$dispatcher = \App\Models\Config::getEventDispatcher();
if ($dispatcher) {
    echo "Eloquent event dispatcher found!\n";
    $listeners = $dispatcher->getListeners("eloquent.retrieved: App\Models\Config");
    echo "Retrieved listeners for Config: " . count($listeners) . "\n";
    foreach ($listeners as $idx => $listener) {
        if (is_array($listener)) {
            echo "  Listener $idx: Array (" . (is_object($listener[0]) ? get_class($listener[0]) : gettype($listener[0])) . ", {$listener[1]})\n";
        } else if (is_object($listener)) {
            // Check if it is a Closure
            $ref = new ReflectionFunction($listener);
            echo "  Listener $idx: Closure in File {$ref->getFileName()} on Line {$ref->getStartLine()}\n";
        } else {
            echo "  Listener $idx: " . gettype($listener) . "\n";
        }
    }
} else {
    echo "No Eloquent event dispatcher found.\n";
}

$cfg = \App\Models\Config::first();
echo "Config ID after fetch: " . $cfg->id . "\n";
