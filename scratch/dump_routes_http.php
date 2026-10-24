<?php
if (!function_exists('base_url')) {
    function base_url() {
        return 'http://localhost:8080/';
    }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return __DIR__ . '/../storage' . ($path ? '/' . ltrim($path, '/') : '');
    }
}
if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', 'index.php');
if (!defined('BASEPATH'))
    define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL'))
    define('APP_URL', 'http://localhost:8080');
define('LOKASI_CACHE', dirname(__DIR__) . '/storage/framework/cache');

// Load Composer Autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Boot Laravel via HTTP Kernel
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== Laravel Registered Routes (HTTP Boot) ===\n";
$routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
foreach ($routeCollection as $value) {
    echo "URI: " . $value->uri() . " | Name: " . $value->getName() . " | Action: " . $value->getActionName() . "\n";
}
