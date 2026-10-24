<?php
// Define constants needed by OpenSID controllers on load
if (!defined('APPPATH')) define('APPPATH', '/var/www/html/donjo-app/');
if (!defined('BASEPATH')) define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');

// Load parents manually only if they don't exist
if (!class_exists('CI_Controller')) {
    require_once BASEPATH . 'core/Controller.php';
}
if (!class_exists('Controller')) {
    @require_once APPPATH . 'core/Controller.php';
}
if (!class_exists('MY_Controller')) {
    @require_once APPPATH . 'core/MY_Controller.php';
}
if (!class_exists('Admin_Controller')) {
    @require_once APPPATH . 'core/Admin_Controller.php';
}

require_once APPPATH . 'controllers/Wilayah.php';

try {
    $ref = new ReflectionClass('Wilayah');
    echo "Class Wilayah details:\n";
    echo "Parent: " . $ref->getParentClass()->getName() . "\n";
    
    echo "\nMethods:\n";
    foreach ($ref->getMethods() as $method) {
        if ($method->getDeclaringClass()->getName() === 'Wilayah') {
            echo "  " . $method->getName() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
