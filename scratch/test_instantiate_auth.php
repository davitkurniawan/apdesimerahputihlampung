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
define('VIEWPATH', APPPATH . 'views/');

if (file_exists(APPPATH . 'config/constants.php')) {
    require_once APPPATH . 'config/constants.php';
}

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

try {
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    require_once APPPATH . 'controllers/Mandiri.php';
    
    $file = APPPATH . 'controllers/fmandiri/AuthenticatedSessionController.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "File not found\n";
        exit;
    }
    
    echo "Instantiating AuthenticatedSessionController...\n";
    $ci = new AuthenticatedSessionController();
    echo "Successfully instantiated AuthenticatedSessionController!\n";
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
