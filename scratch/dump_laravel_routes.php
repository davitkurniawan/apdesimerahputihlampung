<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define global path helpers in global namespace first
if (!function_exists('base_url')) {
    function base_url() {
        return 'http://localhost:8080/';
    }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

// Define CodeIgniter and global path constants
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('LOKASI_CACHE', dirname(__DIR__) . '/storage/framework/cache');

// Define other global path helpers in global namespace
if (!function_exists('resource_path')) {
    function resource_path($path = '') {
        return dirname(__DIR__) . '/resources' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '') {
        return dirname(__DIR__) . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('app_path')) {
    function app_path($path = '') {
        return dirname(__DIR__) . '/app' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('config_path')) {
    function config_path($path = '') {
        return dirname(__DIR__) . '/config' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('database_path')) {
    function database_path($path = '') {
        return dirname(__DIR__) . '/app/database' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('public_path')) {
    function public_path($path = '') {
        return dirname(__DIR__) . '/public' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

// Boot Laravel
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Laravel Registered Routes ===\n";
$routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
foreach ($routeCollection as $value) {
    echo "URI: " . $value->uri() . " | Name: " . $value->getName() . " | Action: " . $value->getActionName() . "\n";
}
