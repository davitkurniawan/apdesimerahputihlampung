<?php
// Define CodeIgniter constants to allow loading
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('FCPATH', __DIR__ . '/../');

require_once BASEPATH . 'core/Controller.php';

// Include parent class first
require_once BASEPATH . 'core/Router.php';

// Define MY_Router inside memory
class MY_Router extends CI_Router {
    public function __construct() {
        echo "MY_Router successfully loaded from MEMORY!\n";
    }
}

// Now call CI core load_class
function &load_class($class, $directory = 'libraries', $param = NULL) {
    static $_classes = array();

    if (isset($_classes[$class])) {
        return $_classes[$class];
    }

    $name = FALSE;

    // Look for subclass first
    $subclass_prefix = 'MY_';
    if (file_exists(APPPATH.'core/'.$subclass_prefix.$class.'.php')) {
        $name = $subclass_prefix.$class;
        if (class_exists($name, FALSE) === FALSE) {
            require_once(APPPATH.'core/'.$name.'.php');
        }
    }

    if ($name === FALSE) {
        // Load original
        require_once(BASEPATH.$directory.'/'.$class.'.php');
        $name = 'CI_'.$class;
    }

    $_classes[$class] = new $name($param);
    return $_classes[$class];
}

$router = load_class('Router', 'core');
echo "Instantiated Router class name: " . get_class($router) . "\n";
