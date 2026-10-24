<?php
// scratch/inspect_session_guard.php
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

if (class_exists('App\Services\Auth\SessionGuard')) {
    $ref = new ReflectionClass('App\Services\Auth\SessionGuard');
    echo "Class: App\Services\Auth\SessionGuard\n";
    echo "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'None') . "\n";
    echo "Interfaces: " . implode(', ', array_keys($ref->getInterfaces())) . "\n";
    
    echo "\nMethods:\n";
    foreach ($ref->getMethods() as $method) {
        if ($method->getDeclaringClass()->getName() === 'App\Services\Auth\SessionGuard') {
            echo "  " . $method->getName() . "\n";
        }
    }
    
    echo "\nProperties:\n";
    foreach ($ref->getProperties() as $prop) {
        if ($prop->getDeclaringClass()->getName() === 'App\Services\Auth\SessionGuard') {
            echo "  " . $prop->getName() . "\n";
        }
    }
} else {
    echo "SessionGuard class not found!\n";
}
