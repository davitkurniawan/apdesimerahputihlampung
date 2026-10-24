<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('ENVIRONMENT', 'development');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('IS_PREMIUM', true);
define('STATUS_LANGGANAN', 1);
if (!function_exists('resource_path')) { function resource_path($p='') { return FCPATH.'resources'.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('base_path')) { function base_path($p='') { return FCPATH.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('storage_path')) { function storage_path($p='') { return FCPATH.'storage'.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('app_path')) { function app_path($p='') { return FCPATH.'app'.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('config_path')) { function config_path($p='') { return FCPATH.'config'.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('database_path')) { function database_path($p='') { return FCPATH.'app/database'.($p?DIRECTORY_SEPARATOR.$p:''); } }
if (!function_exists('public_path')) { function public_path($p='') { return FCPATH.'public'.($p?DIRECTORY_SEPARATOR.$p:''); } }
require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Boot CodeIgniter minimally to load Mandiri_Controller
require_once BASEPATH . 'core/Common.php';
require_once APPPATH . 'config/constants.php';

// Load CI classes manually
$_SERVER['REQUEST_URI'] = '/index.php/beranda';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// Load CI core
require_once BASEPATH . 'core/Benchmark.php';
require_once BASEPATH . 'core/Hooks.php';
require_once BASEPATH . 'core/Config.php';
require_once BASEPATH . 'core/Router.php';
require_once BASEPATH . 'core/Input.php';
require_once BASEPATH . 'core/Security.php';
require_once BASEPATH . 'core/URI.php';
require_once BASEPATH . 'core/Output.php';
require_once BASEPATH . 'core/Loader.php';
require_once BASEPATH . 'core/Lang.php';
require_once BASEPATH . 'core/Utf8.php';
require_once BASEPATH . 'core/Controller.php';

// Try to find Mandiri_Controller in core overrides
$coreFiles = glob(APPPATH . 'core/*.php');
echo "=== Core Files ===\n";
foreach ($coreFiles as $f) {
    echo "  " . basename($f) . "\n";
}

// Try loading MY_Controller which likely defines Mandiri_Controller
if (file_exists(APPPATH . 'core/MY_Controller.php')) {
    echo "\n=== Loading MY_Controller.php ===\n";
    require_once APPPATH . 'core/MY_Controller.php';
    
    // Now check if Mandiri_Controller exists
    if (class_exists('Mandiri_Controller', false)) {
        echo "Mandiri_Controller FOUND!\n";
        $ref = new ReflectionClass('Mandiri_Controller');
        echo "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'none') . "\n";
        echo "File: " . $ref->getFileName() . "\n";
        
        echo "\nProperties (own):\n";
        foreach ($ref->getProperties() as $prop) {
            if ($prop->getDeclaringClass()->getName() === 'Mandiri_Controller') {
                echo "  \$" . $prop->getName() . "\n";
            }
        }
        
        echo "\nMethods (own):\n";
        foreach ($ref->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === 'Mandiri_Controller') {
                echo "  " . $method->getName() . "()\n";
            }
        }
    } else {
        echo "Mandiri_Controller NOT found after loading MY_Controller\n";
        // Check what classes are defined
        $declared = get_declared_classes();
        $ciClasses = array_filter($declared, function($c) {
            return stripos($c, 'Controller') !== false || stripos($c, 'Mandiri') !== false;
        });
        echo "Controller classes found:\n";
        foreach ($ciClasses as $c) {
            echo "  $c\n";
        }
    }
}
