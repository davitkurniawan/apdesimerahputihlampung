<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Load emergency autoloader
if (file_exists(dirname(__DIR__) . '/xosid_emergency_autoload.php')) {
    require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Boot CodeIgniter Core files
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

// Load base controllers if any
if (file_exists(APPPATH . 'core/MY_Controller.php')) {
    require_once APPPATH . 'core/MY_Controller.php';
}

// Load Gawai_layanan controller
try {
    require_once APPPATH . 'controllers/Gawai_layanan.php';
    echo "Gawai_layanan controller loaded successfully!\n";
    
    $ref = new ReflectionClass('Gawai_layanan');
    echo "Class name: " . $ref->getName() . "\n";
    echo "Parent class: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'None') . "\n";
    
    echo "\n=== METHODS ===\n";
    foreach ($ref->getMethods() as $method) {
        if ($method->getDeclaringClass()->getName() === 'Gawai_layanan') {
            echo $method->getName() . " (" . ($method->isPublic() ? 'public' : 'private/protected') . ")\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
