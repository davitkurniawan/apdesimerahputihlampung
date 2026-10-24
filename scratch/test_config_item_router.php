<?php
// Simulate full CodeIgniter bootstrap
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('FCPATH', __DIR__ . '/../');

require_once BASEPATH . 'core/Controller.php';

// Mock get_config
function &get_config() {
    static $config = [
        'subclass_prefix' => 'MY_'
    ];
    return $config;
}

// Define config_item exactly like in autoload
function config_item($item) {
    static $_config;
    if (empty($_config)) {
        $_config[0] =& get_config();
    }
    
    // Inject MY_Router hijacked class definition when CI_Router is loaded
    if (class_exists('CI_Router', false) && !class_exists('MY_Router', false)) {
        echo "CI_Router detected! Loading MX_Router and defining MY_Router...\n";
        require_once APPPATH . 'third_party/MX/Router.php';
        
        eval('
            class MY_Router extends MX_Router {
                public function __construct() {
                    parent::__construct();
                    echo "MY_Router instantiated successfully!\n";
                }
                protected function _set_routing() {
                    parent::_set_routing();
                    echo "Custom wildcard routes injected!\n";
                }
            }
        ');
    }
    
    return isset($_config[0][$item]) ? $_config[0][$item] : null;
}

// Now include CI Router
require_once BASEPATH . 'core/Router.php';

// CodeIgniter core loader simulation
function &load_class($class, $directory = 'libraries', $param = NULL) {
    static $_classes = array();

    if (isset($_classes[$class])) {
        return $_classes[$class];
    }

    $name = FALSE;
    $subclass_prefix = 'MY_';
    
    // Check if subclass exists in memory or file
    $subclass_name = $subclass_prefix.$class;
    
    // Call config_item to trigger our hijack!
    config_item('subclass_prefix');
    
    if (class_exists($subclass_name, FALSE) || file_exists(APPPATH.'core/'.$subclass_name.'.php')) {
        $name = $subclass_name;
        if (class_exists($name, FALSE) === FALSE) {
            require_once(APPPATH.'core/'.$name.'.php');
        }
    }

    if ($name === FALSE) {
        require_once(BASEPATH.$directory.'/'.$class.'.php');
        $name = 'CI_'.$class;
    }

    $_classes[$class] = new $name($param);
    return $_classes[$class];
}

$router = load_class('Router', 'core');
echo "Instantiated Router class name: " . get_class($router) . "\n";
