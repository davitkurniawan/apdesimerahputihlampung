<?php
namespace App\Traits {
    trait Upload {}
}

namespace {
    define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
    define('APPPATH', dirname(__DIR__) . '/donjo-app/');
    define('VIEWPATH', APPPATH . 'views/');
    define('FCPATH', dirname(__DIR__) . '/');

    require_once BASEPATH . 'core/Controller.php';

    // Include Admin_Controller
    class Admin_Controller extends CI_Controller {
        public function __construct() {
            parent::__construct();
        }
    }

    // Load the encrypted controller
    $controller_file = APPPATH . 'controllers/buku_umum/Pengurus.php';
    if (file_exists($controller_file)) {
        require_once $controller_file;
    }

    if (!class_exists('Pengurus')) {
        echo "Class 'Pengurus' not found after loading file\n";
        exit(1);
    }

    try {
        $ref = new ReflectionClass('Pengurus');
        echo "=== PENGURUS CLASS PROPERTIES ===\n";
        foreach ($ref->getProperties() as $prop) {
            echo "- Name: {$prop->name}\n";
        }
        
        echo "\n=== PENGURUS CLASS METHODS ===\n";
        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED) as $method) {
            if ($method->class === 'Pengurus') {
                echo "- Name: {$method->name}\n";
            }
        }
    } catch (Exception $e) {
        echo "Reflection Error: " . $e->getMessage() . "\n";
    }
}
