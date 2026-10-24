<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ENVIRONMENT', 'development');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

define('LOKASI_CACHE', STORAGEPATH . 'framework/cache');

if (file_exists('/var/www/html/xosid_emergency_autoload.php')) {
    require_once '/var/www/html/xosid_emergency_autoload.php';
}

require_once '/var/www/html/vendor/autoload.php';
require_once BASEPATH . 'core/Common.php';

$config =& get_config();
$config['subclass_prefix'] = 'MY_';

require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

// Let's load the Menu controller
require_once APPPATH . 'controllers/Menu.php';

// Use reflection to inspect the class
$ref = new ReflectionClass('Menu');
echo "=== METHODS IN MENU CONTROLLER ===\n";
foreach ($ref->getMethods() as $method) {
    if ($method->getDeclaringClass()->getName() === 'Menu') {
        echo "Method: " . $method->getName() . "\n";
        foreach ($method->getParameters() as $param) {
            echo "  Param: " . $param->getName() . "\n";
        }
    }
}
