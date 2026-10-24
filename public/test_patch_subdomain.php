<?php
// Mock request() helper
if (!function_exists('request')) {
    function request() {
        return Illuminate\Http\Request::capture();
    }
}

// Mock storage_path() helper
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return '/var/www/html/storage/' . ($path ? ltrim($path, '/') : '');
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

// --- APPLY THE RESOLVER PATCH ---
if (isset($app)) {
    $config = $app['config'] ?? null;
    if ($config) {
        $connections = $config->get('database.connections') ?: [];
        $defaultConfig = $connections['default'] ?? null;
        if ($defaultConfig) {
            foreach (['', 'mysql', 'opensid', 'tenant'] as $name) {
                if (empty($connections[$name])) {
                    $connections[$name] = $defaultConfig;
                }
            }
            $config->set('database.connections', $connections);
        }
        if (!$config->get('database.default')) {
            $config->set('database.default', 'default');
        }
    }

    if (class_exists('Illuminate\Database\Eloquent\Model')) {
        $currentResolver = Illuminate\Database\Eloquent\Model::getConnectionResolver();
        if ($currentResolver) {
            $newResolver = new class($currentResolver) implements Illuminate\Database\ConnectionResolverInterface {
                protected $resolver;
                public function __construct($resolver) {
                    $this->resolver = $resolver;
                }
                public function connection($name = null) {
                    if ($name === '' || $name === null) {
                        $name = 'default';
                    }
                    try {
                        return $this->resolver->connection($name);
                    } catch (\Throwable $e) {
                        try {
                            return $this->resolver->connection('default');
                        } catch (\Throwable $e2) {
                            return $this->resolver->connection();
                        }
                    }
                }
                public function getDefaultConnection() {
                    return $this->resolver->getDefaultConnection() ?: 'default';
                }
                public function setDefaultConnection($name) {
                    $this->resolver->setDefaultConnection($name);
                }
            };
            Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
        }
    }
}

// Mock CodeIgniter dependencies
class CI_Controller {
    public static $instance;
    public function __construct() {
        self::$instance = $this;
        $this->load = new stdClass();
        $this->config = new stdClass();
        
        // Mock CodeIgniter database driver
        $this->db = new stdClass();
        $this->db->database = 'xosid_tenant_gunungsugih-gunungsugih';
        $this->db->dbprefix = '';
        $this->db->dbcollat = 'utf8mb4_general_ci';
    }
    public static function &get_instance() {
        return self::$instance;
    }
}

// Mock DB helper function if it calls db_connect()
if (!class_exists('CI_DB', false)) {
    class CI_DB {}
}

// Load MY_Controller
require_once APPPATH . 'core/MY_Controller.php';

// Load AuthenticatedSessionController
require_once APPPATH . 'controllers/auth/AuthenticatedSessionController.php';

echo "Instantiating AuthenticatedSessionController with patch...\n";
$controller = new AuthenticatedSessionController();
echo "Success!\n";
?>
