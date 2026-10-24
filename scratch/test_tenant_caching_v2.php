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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

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

// Inject connection default/mysql
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database' => 'xosid_tenant_bumidaya',
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

echo "DB_DATABASE env: " . getenv('DB_DATABASE') . "\n";
echo "Resolved config cache path: " . $config->get('cache.stores.file.path') . "\n";
echo "Resolved Cache Store Directory: " . app('cache')->driver('file')->getStore()->getDirectory() . "\n";

// Clear query cache
\App\Models\Config::flushQueryCache();

// Fetch model
$configModel = \App\Models\Config::first();
echo "Village Name: " . ($configModel ? $configModel->nama_desa : 'NULL') . "\n";
