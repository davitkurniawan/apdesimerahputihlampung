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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

// Boot CodeIgniter
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

class DummyController extends CI_Controller {
    public static $instance;
    public function __construct() {
        self::$instance = $this;
        parent::__construct();
    }
}
function &get_instance() {
    return DummyController::$instance;
}
$ci = new DummyController();

echo "Booting CodeIgniter database...\n";
$ci->load->database();

echo "Loading identitas_desa_model...\n";
$ci->load->model('identitas_desa_model');

$data = $ci->identitas_desa_model->get_data();
echo "get_data() class/type: " . gettype($data) . "\n";
if (is_object($data)) {
    echo "Class: " . get_class($data) . "\n";
    echo "Properties of \$data:\n";
    print_r(array_keys((array)$data));
    
    echo "\n\$data->pamong class/type: " . gettype($data->pamong) . "\n";
    if (is_object($data->pamong)) {
        echo "Class of pamong: " . get_class($data->pamong) . "\n";
    }
    
    echo "\n\$data->sekdes class/type: " . gettype($data->sekdes) . "\n";
    if (is_object($data->sekdes)) {
        echo "Class of sekdes: " . get_class($data->sekdes) . "\n";
    }
    
    echo "\n\$data->pamong_sekdes class/type: " . gettype($data->pamong_sekdes) . "\n";
    if (is_object($data->pamong_sekdes)) {
        echo "Class of pamong_sekdes: " . get_class($data->pamong_sekdes) . "\n";
    }
} else {
    print_r($data);
}
