<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', dirname(__DIR__).'/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__).'/donjo-app/');
// Mock CI enough to load Penduduk
class CI_Controller {}
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';
if (file_exists(APPPATH . 'controllers/Penduduk.php')) {
    require_once APPPATH . 'controllers/Penduduk.php';
    echo "METHODS:\n";
    print_r(get_class_methods('Penduduk'));
}
