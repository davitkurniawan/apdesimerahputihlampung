<?php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/penduduk/datatables';

chdir('/var/www/html/');

define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://bondo-bangsri.localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_nuclear_boot.php';
require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Mock CodeIgniter core dependencies
class CI_Controller {
    public static $instance;
    public $load;
    public $config;
    public function __construct() {
        self::$instance = $this;
        $this->load = new CI_Loader();
        $this->config = new CI_Config();
    }
    public static function &get_instance() {
        return self::$instance;
    }
}

function &get_instance() {
    return CI_Controller::get_instance();
}

class CI_Loader {
    public function model($model_name) {
        $name = basename($model_name);
        require_once BASEPATH . 'core/Model.php';
        require_once APPPATH . 'core/MY_Model.php';
        require_once APPPATH . 'models/' . $model_name . '.php';
        $class = ucfirst($name);
        CI_Controller::get_instance()->$name = new $class();
    }
}

class CI_Config {
    public function item($key) {
        return '';
    }
}

$controller = new CI_Controller();
$controller->load->model('Penduduk_model');

$ref = new ReflectionClass('Penduduk_model');
echo "Methods of Penduduk_model:\n";
foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    if ($method->getDeclaringClass()->getName() === 'Penduduk_model') {
        echo "- " . $method->getName() . "\n";
    }
}
