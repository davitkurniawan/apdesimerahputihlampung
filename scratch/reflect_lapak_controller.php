<?php
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
    
    // Load Lapak controller
    if (file_exists(APPPATH . 'controllers/fmandiri/Lapak.php')) {
        require_once APPPATH . 'controllers/fmandiri/Lapak.php';
    }
    
    // Load Produk controller
    if (file_exists(APPPATH . 'controllers/fmandiri/Produk.php')) {
        require_once APPPATH . 'controllers/fmandiri/Produk.php';
    }
    
    // Check classes defined
    $classes = get_declared_classes();
    
    foreach (['Lapak', 'Produk'] as $c_name) {
        $found_class = null;
        foreach ($classes as $c) {
            if (strcasecmp($c, $c_name) === 0) {
                $found_class = $c;
                break;
            }
        }
        
        if (!$found_class) {
            echo "Class $c_name not found in declared classes\n";
            continue;
        }
        
        echo "\n=== CLASS: $found_class ===\n";
        $reflector = new ReflectionClass($found_class);
        echo "Parent class: " . ($reflector->getParentClass() ? $reflector->getParentClass()->getName() : 'none') . "\n";
        
        echo "=== METHODS ===\n";
        foreach ($reflector->getMethods() as $method) {
            if ($method->class === $found_class) {
                echo $method->getName() . " - " . ($method->isPublic() ? 'public' : 'private/protected') . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
