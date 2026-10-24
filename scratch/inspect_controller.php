<?php
// scratch/inspect_controller.php
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

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Load CodeIgniter classes so we can load the controller
require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';

// Try to include the controller file
require_once APPPATH . 'controllers/Identitas_desa.php';

$ref = new ReflectionClass('Identitas_desa');
echo "Class name: " . $ref->getName() . "\n";
echo "Parent class: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'none') . "\n";

echo "\nProperties:\n";
foreach ($ref->getProperties() as $p) {
    echo "  " . $p->getName() . " (" . implode(', ', Reflection::getModifierNames($p->getModifiers())) . ")\n";
}

echo "\nMethods:\n";
foreach ($ref->getMethods() as $m) {
    echo "  " . $m->getName() . " (" . implode(', ', Reflection::getModifierNames($m->getModifiers())) . ")\n";
}
