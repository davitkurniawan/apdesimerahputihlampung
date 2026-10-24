<?php
// scratch/reflect_identitas_controller.php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';
require_once APPPATH . 'controllers/Identitas_desa.php';

$ref = new ReflectionClass('Identitas_desa');

echo "=== CLASS DETAILS ===\n";
echo "Name: " . $ref->getName() . "\n";
echo "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'NONE') . "\n";

echo "\n=== PROPERTIES ===\n";
foreach ($ref->getProperties() as $prop) {
    echo "Property: " . $prop->getName() . " | Modifiers: " . Reflection::getModifierNames($prop->getModifiers()) . "\n";
}

echo "\n=== METHODS ===\n";
foreach ($ref->getMethods() as $method) {
    if ($method->getDeclaringClass()->getName() === 'Identitas_desa') {
        echo "Method: " . $method->getName() . " | Modifiers: " . Reflection::getModifierNames($method->getModifiers()) . "\n";
    }
}
