<?php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

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
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';

// Define some basic CI functions and constants that core files might expect
if (!function_exists('get_config')) {
    function &get_config() {
        $config = array();
        return $config;
    }
}
if (!function_exists('load_class')) {
    function &load_class($class, $directory = 'core', $param = NULL) {
        static $_classes = array();
        return $_classes[$class];
    }
}

// Require CodeIgniter core files
require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';
require_once APPPATH . 'controllers/Identitas_desa.php';

try {
    $ref = new ReflectionClass('Identitas_desa');
    echo "Class: " . $ref->getName() . "\n";
    echo "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'None') . "\n";
    echo "Methods:\n";
    foreach ($ref->getMethods() as $method) {
        if ($method->getDeclaringClass()->getName() === $ref->getName()) {
            echo "  " . $method->getName() . "()\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
