<?php
// Define constants
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('FCPATH', __DIR__ . '/../');

// Include base Router
require_once BASEPATH . 'core/Router.php';

// Include MX Router (ionCube encrypted)
require_once APPPATH . 'third_party/MX/Router.php';

if (class_exists('MX_Router')) {
    echo "Successfully loaded MX_Router!\n";
    
    // Now let's define our own MY_Router extending MX_Router
    class MY_Router extends MX_Router {
        public function __construct() {
            parent::__construct();
            echo "MY_Router instantiated successfully!\n";
        }
        
        protected function _set_routing() {
            parent::_set_routing();
            echo "Custom routing is hooked!\n";
        }
    }
    
    echo "Successfully defined MY_Router extending MX_Router!\n";
} else {
    echo "Failed to load MX_Router\n";
}
