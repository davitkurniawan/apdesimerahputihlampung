<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('ENVIRONMENT', 'development');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('DESAPATH', __DIR__ . '/../desa/');
define('STORAGEPATH', __DIR__ . '/../storage/');
define('MODPATH', __DIR__ . '/../Modules/');
define('FCPATH', __DIR__ . '/../');

if (file_exists(APPPATH . 'config/constants.php')) {
    require_once APPPATH . 'config/constants.php';
}

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';
require_once BASEPATH . 'core/Router.php';

try {
    // Load config and URI classes
    $CFG =& load_class('Config', 'core');
    $URI =& load_class('URI', 'core');
    
    // Simulate URI
    $URI->uri_string = 'layanan-mandiri/masuk';
    $URI->segments = [1 => 'layanan-mandiri', 2 => 'masuk'];
    
    // Include routes
    $route = [];
    if (file_exists(APPPATH . 'config/routes.php')) {
        include APPPATH . 'config/routes.php';
    }
    if (file_exists(APPPATH . 'config/routes_neural.php')) {
        include APPPATH . 'config/routes_neural.php';
    }
    
    echo "=== SIMULATING ROUTER ===\n";
    if (file_exists(APPPATH . 'core/MY_Router.php')) {
        require_once APPPATH . 'core/MY_Router.php';
        $router = new MY_Router();
    } else {
        $router = new CI_Router();
    }
    
    $router->routes = $route;
    
    echo "Loaded route keys (first 10):\n";
    print_r(array_slice(array_keys($router->routes), 0, 10));
    
    // Search for layanan-mandiri routes
    $matched_keys = [];
    foreach (array_keys($router->routes) as $k) {
        if (strpos($k, 'layanan-mandiri') !== false) {
            $matched_keys[] = $k;
        }
    }
    echo "Matched layanan-mandiri keys:\n";
    print_r($matched_keys);
    
    // Run route parser
    $ref = new ReflectionClass($router);
    
    $method = $ref->getMethod('_parse_routes');
    $method->setAccessible(true);
    $method->invoke($router);
    
    echo "Parsed Directory: " . $router->directory . "\n";
    echo "Parsed Class: " . $router->class . "\n";
    echo "Parsed Method: " . $router->method . "\n";
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
