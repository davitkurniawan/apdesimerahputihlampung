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

$CFG =& load_class('Config', 'core');
$URI =& load_class('URI', 'core');

// Test 1: siteman
$URI->uri_string = 'siteman';
$URI->segments = [1 => 'siteman'];

$route = [];
if (file_exists(APPPATH . 'config/routes.php')) {
    include APPPATH . 'config/routes.php';
}
if (file_exists(APPPATH . 'config/routes_neural.php')) {
    include APPPATH . 'config/routes_neural.php';
}

if (file_exists(APPPATH . 'core/MY_Router.php')) {
    require_once APPPATH . 'core/MY_Router.php';
    $router = new MY_Router();
} else {
    $router = new CI_Router();
}

$router->routes = $route;

$ref = new ReflectionClass($router);
$method = $ref->getMethod('_parse_routes');
$method->setAccessible(true);
$method->invoke($router);

echo "=== URI: siteman ===\n";
echo "Parsed Directory: " . $router->directory . "\n";
echo "Parsed Class: " . $router->class . "\n";
echo "Parsed Method: " . $router->method . "\n\n";

// Test 2: siteman/auth
$URI->uri_string = 'siteman/auth';
$URI->segments = [1 => 'siteman', 2 => 'auth'];

if (file_exists(APPPATH . 'core/MY_Router.php')) {
    $router2 = new MY_Router();
} else {
    $router2 = new CI_Router();
}
$router2->routes = $route;
$method2 = $ref->getMethod('_parse_routes');
$method2->setAccessible(true);
$method2->invoke($router2);

echo "=== URI: siteman/auth ===\n";
echo "Parsed Directory: " . $router2->directory . "\n";
echo "Parsed Class: " . $router2->class . "\n";
echo "Parsed Method: " . $router2->method . "\n\n";
