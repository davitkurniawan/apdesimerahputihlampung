<?php
define('ENVIRONMENT', 'production');
define('VERSION', '2606.0.0');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://master.localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'master.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Include our emergency autoloader to see if it sets config_id = 1
require_once __DIR__ . '/../xosid_emergency_autoload.php';

$classes = [
    'App\Traits\ConfigId',
    'App\Observers\ConfigIdObserver',
    'App\Scopes\ConfigIdScope'
];

foreach ($classes as $class) {
    if (class_exists($class) || trait_exists($class)) {
        echo "Reflecting class/trait: $class\n";
        $ref = new ReflectionClass($class);
        echo "Methods:\n";
        foreach ($ref->getMethods() as $method) {
            echo " - " . $method->getName() . " (Declaring Class: " . $method->getDeclaringClass()->getName() . ")\n";
        }
        echo "Properties:\n";
        foreach ($ref->getProperties() as $prop) {
            echo " - " . $prop->getName() . "\n";
        }
        echo "----------------------------------------\n";
    } else {
        echo "Class/Trait NOT found: $class\n";
    }
}
