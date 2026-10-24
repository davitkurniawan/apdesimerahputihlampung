<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('ENVIRONMENT', 'development');

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

try {
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    require_once APPPATH . 'controllers/Mandiri.php';
    
    // Load AuthenticatedSessionController
    $file = APPPATH . 'controllers/fmandiri/auth/AuthenticatedSessionController.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "File not found\n";
        exit;
    }
    
    $classes = get_declared_classes();
    $found_class = null;
    foreach ($classes as $c) {
        if (stripos($c, 'AuthenticatedSession') !== false) {
            $found_class = $c;
            break;
        }
    }
    
    if (!$found_class) {
        echo "Class not found in declared classes\n";
        exit;
    }
    
    echo "Class name: " . $found_class . "\n";
    $reflector = new ReflectionClass($found_class);
    echo "Parent class: " . ($reflector->getParentClass() ? $reflector->getParentClass()->getName() : 'none') . "\n";
    
    echo "\n=== METHODS ===\n";
    foreach ($reflector->getMethods() as $method) {
        if ($method->class === $found_class) {
            echo $method->getName() . " - " . ($method->isPublic() ? 'public' : 'private/protected') . "\n";
        }
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
