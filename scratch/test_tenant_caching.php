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

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost:8080/' . $path;
    }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) {
        return 'Desa';
    }
}
if (!function_exists('kades')) {
    function kades() {
        return 'Kepala Desa';
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Helper to simulate request for a subdomain
function test_subdomain_boot($subdomain_host, $db_name) {
    // Override HTTP_HOST and server variables
    $_SERVER['HTTP_HOST'] = $subdomain_host;
    $_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    // Clear global variables from previous runs
    global $subdomain, $database_name;
    $subdomain = null;
    $database_name = null;
    
    // Require emergency autoloader to run early-boot config
    require dirname(__DIR__) . '/xosid_emergency_autoload.php';
    
    // Boot Laravel
    $app = require dirname(__DIR__) . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Apply database resolver patch (like index.php does)
    include_once dirname(__DIR__) . '/xosid_database_helpers.php';
    if (class_exists('XOSIDConfigRepository')) {
        $currentConfig = $app->make('config');
        if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
            $newConfig = new XOSIDConfigRepository($currentConfig);
            $app->instance('config', $newConfig);
        }
    }
    
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
    
    // Dynamically inject connections
    $config = $app['config'];
    $connections = $config->get('database.connections') ?: [];
    $laravel_db = [
        'driver'    => 'mysql',
        'host'      => 'db',
        'port'      => 3306,
        'database'  => $db_name,
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
    
    // Fetch Config Model
    $configModel = \App\Models\Config::first();
    return [
        'resolved_db' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName(),
        'cache_path' => app('cache')->driver('file')->getStore()->getDirectory(),
        'village_name' => $configModel ? $configModel->nama_desa : 'NULL',
        'village_code' => $configModel ? $configModel->kode_desa : 'NULL',
    ];
}

echo "--- RUNNING TENANT CACHE ISOLATION TEST ---\n";

$bumidaya = test_subdomain_boot('bumidaya.localhost:8080', 'xosid_tenant_bumidaya');
echo "Bumi Daya:\n";
print_r($bumidaya);
echo "\n";

// Clear instance of app to simulate new request
Illuminate\Container\Container::setInstance(null);

$bondo = test_subdomain_boot('bondo-bangsri.localhost:8080', 'xosid_tenant_bondo-bangsri');
echo "Bondo:\n";
print_r($bondo);
echo "\n";
