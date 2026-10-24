<?php
// Mock request() helper used by ThrottleRequests middleware
if (!function_exists('request')) {
    function request() {
        return Illuminate\Http\Request::capture();
    }
}

set_exception_handler(function($e) {
    header('Content-Type: text/plain');
    echo "Exception Class: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace with Arguments:\n";
    
    $trace = $e->getTrace();
    foreach ($trace as $i => $frame) {
        $file = $frame['file'] ?? 'unknown';
        $line = $frame['line'] ?? 'unknown';
        $class = $frame['class'] ?? '';
        $type = $frame['type'] ?? '';
        $func = $frame['function'] ?? 'unknown';
        
        echo "#$i $file($line): $class$type$func(";
        if (isset($frame['args'])) {
            $args = [];
            foreach ($frame['args'] as $arg) {
                if (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_array($arg)) {
                    $args[] = 'Array';
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = $arg ? 'true' : 'false';
                } else {
                    $args[] = var_export($arg, true);
                }
            }
            echo implode(', ', $args);
        }
        echo ")\n";
    }
    exit;
});

$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

chdir('/var/www/html/');

// Boot hybrid stack constants
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://gunungsugih-gunungsugih.localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_nuclear_boot.php';
require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Mock CodeIgniter core class dependencies
class CI_Controller {
    public static $instance;
    public function __construct() {
        self::$instance = $this;
        $this->load = new stdClass();
        $this->config = new stdClass();
    }
    public static function &get_instance() {
        return self::$instance;
    }
}

// Load MY_Controller
require_once APPPATH . 'core/MY_Controller.php';

// Load AuthenticatedSessionController
require_once APPPATH . 'controllers/auth/AuthenticatedSessionController.php';

echo "Instantiating AuthenticatedSessionController...\n";
$controller = new AuthenticatedSessionController();
echo "Success!\n";
?>
