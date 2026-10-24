<?php
// inspect_wilayah_runtime.php
header('Content-Type: text/plain');

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bumidaya.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Boot CodeIgniter by including index.php but mocking the REQUEST_URI to boot CI
$_SERVER['REQUEST_URI'] = '/index.php/wilayah';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['PATH_INFO'] = '/wilayah';

// Set session
session_id('3ec6e5024ee3cb49d7034ba59a8ed5a0');
@session_start();

try {
    if (!defined('FCPATH')) define('FCPATH', '/var/www/html/');
    if (!defined('APPPATH')) define('APPPATH', '/var/www/html/donjo-app/');
    if (!defined('BASEPATH')) define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
    
    // Check if Wilayah controller is already declared
    if (!class_exists('Wilayah', false)) {
        require_once APPPATH . 'controllers/Wilayah.php';
    }

    $ref = new ReflectionClass('Wilayah');
    echo "Class Wilayah details:\n";
    echo "File: " . $ref->getFileName() . "\n";
    echo "Parent: " . $ref->getParentClass()->getName() . "\n";
    
    $method = $ref->getMethod('index');
    echo "\nMethod index() details:\n";
    echo "  Number of parameters: " . $method->getNumberOfParameters() . "\n";
    foreach ($method->getParameters() as $p) {
        echo "    Parameter: $" . $p->getName() . " (Optional: " . ($p->isOptional() ? 'yes' : 'no') . ")\n";
    }
    
    $method_form = $ref->getMethod('form');
    echo "\nMethod form() details:\n";
    echo "  Number of parameters: " . $method_form->getNumberOfParameters() . "\n";
    foreach ($method_form->getParameters() as $p) {
        echo "    Parameter: $" . $p->getName() . " (Optional: " . ($p->isOptional() ? 'yes' : 'no') . ")\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
