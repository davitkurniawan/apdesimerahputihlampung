<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CACHE', STORAGEPATH . 'framework/cache');

// Load emergency autoloader
if (file_exists(dirname(__DIR__) . '/xosid_emergency_autoload.php')) {
    require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Boot CodeIgniter
require_once BASEPATH . 'core/Common.php';

$config =& get_config();
$config['subclass_prefix'] = 'MY_';

// Mock CodeIgniter Controller singleton to prevent Loader.php error
require_once BASEPATH . 'core/Controller.php';
class DummyController extends CI_Controller {
    public static $instance;
    public function __construct() {
        self::$instance = $this;
        parent::__construct();
    }
}
$ci = new DummyController();

echo "DummyController initialized!\n";

// Load illuminate_helper
try {
    $ci->load->helper('illuminate');
    echo "illuminate helper loaded successfully!\n";
} catch (Exception $e) {
    echo "Error loading illuminate helper: " . $e->getMessage() . "\n";
}

if (function_exists('ci_route')) {
    echo "ci_route function EXISTS!\n";
    echo "gawai_layanan => " . ci_route('gawai_layanan') . "\n";
    echo "gawai_layanan.datatables => " . ci_route('gawai_layanan.datatables') . "\n";
    echo "gawai_layanan.form => " . ci_route('gawai_layanan.form') . "\n";
} else {
    echo "ci_route function still does not exist.\n";
}
