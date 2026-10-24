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

    // Load Admin_Controller
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

    try {
        $ref = new ReflectionClass('Pengurus');
        
        $methods = ['ttd', 'tukar', 'kehadiran'];
        foreach ($methods as $m_name) {
            if ($ref->hasMethod($m_name)) {
                $method = $ref->getMethod($m_name);
                echo "=== METHOD: $m_name ===\n";
                $params = [];
                foreach ($method->getParameters() as $param) {
                    $params[] = ($param->isPassedByReference() ? '&' : '') . '$' . $param->getName() . ($param->isDefaultValueAvailable() ? ' = ' . json_encode($param->getDefaultValue()) : '');
                }
                echo "Params: (" . implode(', ', $params) . ")\n\n";
            } else {
                echo "Method $m_name not found\n\n";
            }
        }
    } catch (Exception $e) {
        echo "Reflection Error: " . $e->getMessage() . "\n";
    }
}
