<?php
// PHP Reflection on CodeIgniter Controller

namespace App\Traits {
    trait Upload {}
}

namespace {
    // Define CodeIgniter constants to allow loading
    define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
    define('APPPATH', dirname(__DIR__) . '/donjo-app/');
    define('VIEWPATH', APPPATH . 'views/');
    define('FCPATH', dirname(__DIR__) . '/');

    // Load CodeIgniter core files to allow controller instantiation
    require_once BASEPATH . 'core/Controller.php';

    // Mock Admin_Controller
    class Admin_Controller extends CI_Controller {
        public function __construct() {
            parent::__construct();
        }
    }

    // Locate the controller
    $controller_file = APPPATH . 'controllers/buku_umum/Pengurus.php';
    if (!file_exists($controller_file)) {
        echo "Controller file not found at $controller_file\n";
        exit(1);
    }

    // Load the encrypted controller
    require_once $controller_file;

    if (!class_exists('Pengurus')) {
        echo "Class 'Pengurus' not found after loading controller\n";
        exit(1);
    }

    try {
        $ref = new ReflectionClass('Pengurus');
        echo "=== CLASS METHODS FOR PENGURUS ===\n";
        $methods = $ref->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            // Only print methods defined in Pengurus itself, not CI_Controller
            if ($method->class === 'Pengurus') {
                echo "- {$method->name}\n";
            }
        }
    } catch (Exception $e) {
        echo "Reflection Error: " . $e->getMessage() . "\n";
    }
}
