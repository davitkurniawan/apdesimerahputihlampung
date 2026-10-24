<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Load Composer Autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Boot Laravel
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Laravel Booted ===\n";

if (function_exists('ci_route')) {
    $ref = new ReflectionFunction('ci_route');
    echo "ci_route is defined in:\n";
    echo "File: " . $ref->getFileName() . "\n";
    echo "Line: " . $ref->getStartLine() . "\n";
} else {
    echo "ci_route is not defined at boot.\n";
    // Check all loaded files and loaded functions
    $funcs = get_defined_functions();
    $user_funcs = $funcs['user'];
    $matches = [];
    foreach ($user_funcs as $func) {
        if (strpos($func, 'route') !== false || strpos($func, 'ci_') !== false) {
            $matches[] = $func;
        }
    }
    echo "Matching functions: " . implode(', ', $matches) . "\n";
}
