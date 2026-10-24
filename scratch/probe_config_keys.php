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
define('APP_URL', 'http://demo.localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
require_once __DIR__ . '/../xosid_emergency_autoload.php';
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
            'database'  => 'xosid_tenant_demo',
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

// Make sure event dispatcher is booted
\Illuminate\Database\Eloquent\Model::setEventDispatcher($app['events']);

// We will test several config keys
$keys = [
    'config_id',
    'config.id',
    'config.config_id',
    'xosid.config_id',
    'tenant.config_id',
    'tenant.id',
    'app.config_id',
    'database.config_id',
];

foreach ($keys as $key) {
    echo "PROBING KEY: $key\n";
    
    // Clear other keys
    foreach ($keys as $k) {
        $config->set($k, null);
    }
    
    // Set current key to 1
    $config->set($key, 1);
    
    try {
        \Illuminate\Support\Facades\DB::beginTransaction();
        
        $theme = new \App\Models\Theme();
        $theme->nama = 'Probe Theme';
        $theme->slug = 'probe-' . str_replace('.', '-', $key);
        $theme->path = 'storage/app/themes/probe';
        $theme->save();
        
        echo " -> Success! Saved config_id = " . ($theme->config_id ?? 'NULL') . "\n";
        \Illuminate\Support\Facades\DB::rollBack();
    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        if (strpos($msg, 'insert into') !== false) {
            // Extract SQL query
            preg_match('/(insert into.*)/i', $msg, $m);
            echo " -> Query: " . ($m[1] ?? $msg) . "\n";
        } else {
            echo " -> Error: " . $msg . "\n";
        }
        try { \Illuminate\Support\Facades\DB::rollBack(); } catch (\Throwable $t) {}
    }
}
