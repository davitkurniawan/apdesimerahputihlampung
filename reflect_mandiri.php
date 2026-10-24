<?php
require_once 'vendor/autoload.php';
define('BASEPATH', __DIR__ . '/vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/donjo-app/');
define('ENVIRONMENT', 'development');

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

try {
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    require_once APPPATH . 'controllers/Mandiri.php';
    $reflector = new ReflectionClass('Mandiri');
    foreach ($reflector->getMethods() as $method) {
        if ($method->class === 'Mandiri') {
            echo $method->getName() . " - " . ($method->isPublic() ? 'public' : 'private/protected') . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
