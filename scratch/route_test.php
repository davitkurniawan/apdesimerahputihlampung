<?php
$sess_id = $_COOKIE['ci_session'] ?? '';
if (!$sess_id) { die("No session"); }

require_once '/var/www/html/vendor/autoload.php';
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('LOKASI_CACHE', '/var/www/html/storage/framework/cache');
define('VIEWPATH', '/var/www/html/donjo-app/views/');
define('DESAPATH', '/var/www/html/desa/');
define('STORAGEPATH', '/var/www/html/storage/');
define('MODPATH', '/var/www/html/Modules/');
define('APP_URL', 'http://localhost:8080');

// Boot CodeIgniter
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

class RouteTestController extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $this->load->helper('illuminate');
        echo "anjungan_menu.delete => " . ci_route('anjungan_menu.delete') . "\n";
        try {
            echo "anjungan_menu.delete_id => " . ci_route('anjungan_menu.delete', ['id' => 4]) . "\n";
        } catch (Exception $e) {
            echo "anjungan_menu.delete_id => ERROR: " . $e->getMessage() . "\n";
        }
    }
}

$c = new RouteTestController();
$c->index();
