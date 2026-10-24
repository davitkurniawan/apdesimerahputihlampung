<?php
namespace App\Traits {
    trait Upload {}
}

namespace {
    define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
    define('APPPATH', dirname(__DIR__) . '/donjo-app/');
    define('VIEWPATH', APPPATH . 'views/');
    define('FCPATH', dirname(__DIR__) . '/');

    require_once BASEPATH . 'core/Router.php';

    // Load the encrypted MY_Router.php
    $router_file = APPPATH . 'core/MY_Router.php';
    if (file_exists($router_file)) {
        require_once $router_file;
    }

    if (!class_exists('MY_Router')) {
        echo "Class 'MY_Router' not found after loading file\n";
        exit(1);
    }

    try {
        $ref = new ReflectionClass('MY_Router');
        echo "=== CLASS METHODS FOR MY_ROUTER ===\n";
        $methods = $ref->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED);
        foreach ($methods as $method) {
            if ($method->class === 'MY_Router') {
                echo "- Name: {$method->name}\n";
                // Print parameters
                $params = [];
                foreach ($method->getParameters() as $param) {
                    $params[] = ($param->isPassedByReference() ? '&' : '') . '$' . $param->getName();
                }
                echo "  Params: (" . implode(', ', $params) . ")\n";
            }
        }
    } catch (Exception $e) {
        echo "Reflection Error: " . $e->getMessage() . "\n";
    }
}
