<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('FCPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('ENVIRONMENT', 'production');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('DESAPATH', __DIR__ . '/../desa/');
define('STORAGEPATH', __DIR__ . '/../storage/');
define('MODPATH', __DIR__ . '/../Modules/');
define('LOKASI_CONFIG_DESA', 'desa/config/');

// Load emergency autoloader
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

require_once BASEPATH . 'core/Common.php';

// Force load MY_Controller and Admin_Controller
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';

echo "=== REFLECTING Admin_Controller ===\n";
$ref = new ReflectionClass('Admin_Controller');

echo "Parent Class: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'NONE') . "\n\n";

echo "Properties:\n";
foreach ($ref->getProperties() as $prop) {
    echo "  " . $prop->getName() . "\n";
}
echo "\n";

echo "Methods:\n";
foreach ($ref->getMethods() as $method) {
    echo "  " . $method->getName() . " (declaring class: " . $method->getDeclaringClass()->getName() . ")\n";
}
