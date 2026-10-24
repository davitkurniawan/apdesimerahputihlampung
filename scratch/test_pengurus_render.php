<?php
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
if (!defined('LOKASI_CONFIG_DESA')) define('LOKASI_CONFIG_DESA', 'desa/config/');
if (!defined('FCPATH')) define('FCPATH', dirname(__DIR__) . '/');
if (!defined('BASEPATH')) define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', dirname(__DIR__) . '/donjo-app/');
if (!defined('VIEWPATH')) define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH')) define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH')) define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH')) define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL')) define('APP_URL', 'http://bumidaya.localhost:8080');

if (!function_exists('resource_path')) { function resource_path($path = '') { return FCPATH . 'resources/' . ltrim($path, '/'); } }
if (!function_exists('storage_path')) { function storage_path($path = '') { return FCPATH . 'storage/' . ltrim($path, '/'); } }
if (!function_exists('config_path')) { function config_path($path = '') { return FCPATH . 'config/' . ltrim($path, '/'); } }
if (!function_exists('app_path')) { function app_path($path = '') { return FCPATH . 'app/' . ltrim($path, '/'); } }
if (!function_exists('public_path')) { function public_path($path = '') { return FCPATH . 'public/' . ltrim($path, '/'); } }
if (!function_exists('base_path')) { function base_path($path = '') { return FCPATH . ltrim($path, '/'); } }
if (!function_exists('base_url')) { function base_url($path = '') { return APP_URL . '/' . ltrim($path, '/'); } }
if (!function_exists('site_url')) { function site_url($path = '') { return APP_URL . '/index.php/' . ltrim($path, '/'); } }
if (!function_exists('ci_route')) { function ci_route($path = '') { return APP_URL . '/index.php/' . ltrim($path, '/'); } }
if (!function_exists('can')) { function can($a, $b) { return true; } }

require_once dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

include_once dirname(__DIR__) . '/xosid_database_helpers.php';
if (isset($app)) {
    $laravel_db = [
        'driver'    => 'mysql',
        'host'      => 'db',
        'port'      => 3306,
        'database'  => 'xosid_tenant_bumidaya',
        'username'  => 'root',
        'password'  => 'password',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'prefix'    => '',
        'strict'    => true,
        'engine'    => null,
    ];
    $config = $app['config'];
    $connections = $config->get('database.connections') ?: [];
    $connections['default'] = $laravel_db;
    $connections['mysql'] = $laravel_db;
    $config->set('database.connections', $connections);
    $config->set('database.default', 'default');

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

$app->make('view')->share('errors', new \Illuminate\Support\ViewErrorBag);

$data = [
    'controller' => 'pengurus',
    'status' => [
        '1' => 'Aktif',
        '2' => 'Dihapus'
    ],
    'default_status' => '1',
    'jabatanKadesSekdes' => [1, 2],
    'pemerintah' => 'Pemerintah Desa'
];

try {
    echo "Attempting to render view:\n";
    $output = $app->make('view')->make('admin.pengurus.index', $data)->render();
    echo "SUCCESS! Rendered length: " . strlen($output) . "\n";
    echo "Sample output contains stats container: " . (strpos($output, 'stats-container') !== false ? 'YES' : 'NO') . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
}
