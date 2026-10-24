<?php
// PHP Script to dump all CodeIgniter routes

namespace App\Traits {
    trait Upload {}
}

namespace {
    // Define CI constants
    define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
    define('APPPATH', '/var/www/html/donjo-app/');
    define('VIEWPATH', APPPATH . 'views/');
    define('FCPATH', '/var/www/html/');

    // Load CI Router core files
    require_once BASEPATH . 'core/Controller.php';

    // Mock Admin_Controller
    class Admin_Controller extends CI_Controller {
        public function __construct() {
            parent::__construct();
        }
    }

    // Load config
    $config_file = APPPATH . 'config/config.php';
    
    // Stub global functions
    if (!function_exists('get_config')) {
        function get_config() {
            return [];
        }
    }
    
    // Load routes
    $route = [];
    $routes_file = APPPATH . 'config/routes.php';
    if (file_exists($routes_file)) {
        require_once $routes_file;
    }
    
    // Also load Routes/web.php routes if exists
    $web_routes_file = APPPATH . 'Routes/web.php';
    if (file_exists($web_routes_file)) {
        require_once $web_routes_file;
    }
    
    echo "=== REGISTERED ROUTES ===\n";
    print_r($route);
}
