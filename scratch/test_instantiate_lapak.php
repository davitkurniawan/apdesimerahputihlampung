<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('ENVIRONMENT', 'development');
define('APP_URL', 'http://localhost:8080');
define('FCPATH', __DIR__ . '/../');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);
define('SELF', 'index.php');

require_once __DIR__ . '/../vendor/autoload.php';
require_once APPPATH . 'config/constants.php';
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

try {
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    require_once APPPATH . 'controllers/Mandiri.php';
    
    if (file_exists(APPPATH . 'controllers/fmandiri/Lapak.php')) {
        require_once APPPATH . 'controllers/fmandiri/Lapak.php';
    } else {
        echo "Lapak.php not found\n";
        exit;
    }
    
    echo "Instantiating Lapak controller...\n";
    // We need to bootstrap CI_Controller instance
    $ci = new Lapak();
    echo "Successfully instantiated Lapak controller!\n";
    
} catch (Throwable $e) {
    echo "CATCHED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
