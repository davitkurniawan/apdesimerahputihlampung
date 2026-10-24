<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Boot Laravel via index.php bootstrap logic
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);

require_once dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists(dirname(__DIR__) . '/xosid_emergency_autoload.php')) {
    require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
}

if (file_exists(dirname(__DIR__) . '/bootstrap/app.php')) {
    $app = require_once dirname(__DIR__) . '/bootstrap/app.php';
}

echo "=== APP OBJECT ===\n";
echo "Class: " . get_class($app) . "\n";

// Test direct registration of View Composer
try {
    if (class_exists('\Illuminate\Support\Facades\View')) {
        echo "View Facade exists.\n";
        \Illuminate\Support\Facades\View::composer('*', function($view) {
            echo "Composer called!\n";
        });
        echo "Successfully registered global view composer directly!\n";
    } else {
        echo "View Facade does NOT exist.\n";
    }
} catch (\Throwable $e) {
    echo "ERROR registering composer directly: " . $e->getMessage() . "\n";
}


if (isset($app) && method_exists($app, 'laravel')) {
    echo "\n=== LARAVEL INTERNALS ===\n";
    try {
        $laravel = $app->laravel();
        echo "Laravel Class: " . get_class($laravel) . "\n";
        $lar_methods = get_class_methods($laravel);
        sort($lar_methods);
        foreach ($lar_methods as $m) {
            echo " - $m\n";
        }
    } catch (\Throwable $e) {
        echo "Error calling laravel(): " . $e->getMessage() . "\n";
    }
}
