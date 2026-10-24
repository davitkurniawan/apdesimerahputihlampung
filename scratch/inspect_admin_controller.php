<?php
// inspect_admin_controller.php
header('Content-Type: text/plain');

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bumidaya.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    if (!defined('BASEPATH')) define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
    if (!defined('APPPATH')) define('APPPATH', '/var/www/html/donjo-app/');
    
    // We need to load CI core classes first so Admin_Controller can be loaded
    require_once BASEPATH . 'core/Controller.php';
    
    // Load MY_Controller and Admin_Controller
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    
    $ref_my = new ReflectionClass('MY_Controller');
    echo "MY_Controller methods:\n";
    foreach ($ref_my->getMethods() as $m) {
        echo "  " . $m->getName() . " (" . $m->getDeclaringClass()->getName() . ")\n";
    }
    
    $ref_admin = new ReflectionClass('Admin_Controller');
    echo "\nAdmin_Controller methods:\n";
    foreach ($ref_admin->getMethods() as $m) {
        echo "  " . $m->getName() . " (" . $m->getDeclaringClass()->getName() . ")\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
