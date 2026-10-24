<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define global path helpers
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
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}
if (!function_exists('public_path')) {
    function public_path($path = '') {
        return dirname(__DIR__) . '/public' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('LOKASI_CACHE', STORAGEPATH . 'framework/cache');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://bumidaya.localhost:8080');

if (file_exists(FCPATH . 'xosid_aggressive_patch.php')) {
    include_once FCPATH . 'xosid_aggressive_patch.php';
}
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

require_once FCPATH . 'vendor/autoload.php';

$app = require_once FCPATH . 'bootstrap/app.php';
require_once BASEPATH . 'core/Common.php';

$config =& get_config();
$config['subclass_prefix'] = 'MY_';

require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

class WebDummy extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
}

$ci = new WebDummy();

echo "=== menu_tema() Result ===\n";
if (function_exists('menu_tema')) {
    $res = menu_tema();
    print_r($res);
} else {
    echo "menu_tema() function NOT found!\n";
}
