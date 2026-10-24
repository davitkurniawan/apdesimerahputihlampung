<?php
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

// Mock CI environment so we can boot CodeIgniter
ob_start();
require_once BASEPATH . 'core/CodeIgniter.php';
ob_end_clean();

$ci = &get_instance();
$ci->load->model('identitas_desa_model');

$model = $ci->identitas_desa_model;
$ref = new ReflectionClass(get_class($model));
echo "Class: " . $ref->getName() . "\n";
echo "Methods:\n";
foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    if ($method->getDeclaringClass()->getName() === $ref->getName()) {
        echo "  " . $method->getName() . "\n";
    }
}

echo "\nCalling methods:\n";
if ($ref->hasMethod('get_data')) {
    echo "get_data() result:\n";
    print_r($model->get_data());
}
if ($ref->hasMethod('get_identitas')) {
    echo "get_identitas() result:\n";
    print_r($model->get_identitas());
}
