<?php
define('ENVIRONMENT', 'production');
define('VERSION', '2606.0.0');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://master.localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'master.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Include our emergency autoloader to see if it sets config_id = 1
require_once __DIR__ . '/../xosid_emergency_autoload.php';

// Let's load the database config
require FCPATH . 'desa/config/database.php';

// Setup Laravel database connection
if (isset($app)) {
    include_once __DIR__ . '/../xosid_database_helpers.php';
    if (class_exists('XOSIDConfigRepository')) {
        $currentConfig = $app->make('config');
        if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
            $newConfig = new XOSIDConfigRepository($currentConfig);
            $app->instance('config', $newConfig);
            $config = $newConfig;
        } else {
            $config = $currentConfig;
        }
    } else {
        $config = $app['config'] ?? null;
    }

    if ($config) {
        $connections = $config->get('database.connections') ?: [];
        $laravel_db = [
            'driver'    => 'mysql',
            'host'      => 'db',
            'port'      => 3306,
            'database'  => 'xosid',
            'username'  => 'root',
            'password'  => 'password',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ];
        $laravel_db['name'] = 'default';
        $connections['default'] = $laravel_db;
        $connections['mysql'] = $laravel_db;
        $connections['opensid'] = $laravel_db;
        $connections['tenant'] = $laravel_db;
        $connections[''] = $laravel_db;
        
        $config->set('database.connections', $connections);
        $config->set('database.default', 'default');
    }

    if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('XOSIDConnectionResolver')) {
        $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        if (!$currentResolver && isset($app['db'])) {
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
            $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        }
        if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
            $newResolver = new XOSIDConnectionResolver($currentResolver);
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
        }
    }
}

// Now check session
echo "SESSION config_id: " . ($_SESSION['config_id'] ?? 'NULL') . "\n";
echo "SESSION id_desa: " . ($_SESSION['id_desa'] ?? 'NULL') . "\n";

// Let's run an Eloquent query on Agama in a transaction
try {
    \Illuminate\Support\Facades\DB::beginTransaction();
    
    echo "Creating a mock Agama model instance:\n";
    $agama = new \App\Models\Agama();
    $agama->nama = 'Test Mock Agama';
    
    // Trigger saving to see the inserted data
    echo "Saving Agama model...\n";
    $agama->save();
    
    echo "Saved Agama config_id: " . $agama->config_id . "\n";
    
    \Illuminate\Support\Facades\DB::rollBack();
} catch (Throwable $e) {
    echo "CATCH: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    try { \Illuminate\Support\Facades\DB::rollBack(); } catch (Throwable $t) {}
}
