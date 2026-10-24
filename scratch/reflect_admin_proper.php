<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

// 1. Load Composer autoloader
require_once FCPATH . 'vendor/autoload.php';

// 2. Load emergency autoloader
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

require_once BASEPATH . 'core/Common.php';
require_once APPPATH . 'core/MY_Controller.php';

$ref = new ReflectionClass('MY_Controller');
echo "Parent Class: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'NONE') . "\n\n";
echo "Properties:\n";
foreach ($ref->getProperties() as $prop) {
    echo "  " . $prop->getName() . "\n";
}
echo "Methods:\n";
foreach ($ref->getMethods() as $method) {
    echo "  " . $method->getName() . "\n";
}
