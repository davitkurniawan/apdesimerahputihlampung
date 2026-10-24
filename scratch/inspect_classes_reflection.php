<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);
define('APP_URL', 'http://localhost:8080');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null) { return 'Desa'; }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public function user() { return $this; }
        };
    }
}
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function inspectClass($className) {
    echo "========================================\n";
    echo "INSPECTING CLASS: $className\n";
    if (!class_exists($className) && !interface_exists($className) && !trait_exists($className)) {
        echo "Class/Trait/Interface does not exist.\n";
        return;
    }
    
    $ref = new ReflectionClass($className);
    echo "Is Trait: " . ($ref->isTrait() ? 'YES' : 'NO') . "\n";
    echo "Is Interface: " . ($ref->isInterface() ? 'YES' : 'NO') . "\n";
    
    $parent = $ref->getParentClass();
    if ($parent) {
        echo "Parent Class: " . $parent->getName() . "\n";
    }
    
    $interfaces = $ref->getInterfaceNames();
    if (!empty($interfaces)) {
        echo "Interfaces: " . implode(', ', $interfaces) . "\n";
    }
    
    echo "Constants:\n";
    print_r($ref->getConstants());
    
    echo "Static Properties:\n";
    $staticProps = $ref->getProperties(ReflectionProperty::IS_STATIC);
    foreach ($staticProps as $prop) {
        $prop->setAccessible(true);
        echo "  " . $prop->getName() . " = " . json_encode($prop->getValue()) . "\n";
    }
    
    echo "Instance Properties:\n";
    $props = $ref->getProperties();
    foreach ($props as $prop) {
        if ($prop->isStatic()) continue;
        echo "  " . $prop->getName() . "\n";
    }
    
    echo "Methods:\n";
    $methods = $ref->getMethods();
    foreach ($methods as $method) {
        if ($method->getDeclaringClass()->getName() !== $className) continue;
        $params = [];
        foreach ($method->getParameters() as $param) {
            $params[] = ($param->hasType() ? $param->getType()->getName() . ' ' : '') . '$' . $param->getName();
        }
        echo "  " . ($method->isStatic() ? 'static ' : '') . $method->getName() . "(" . implode(', ', $params) . ")\n";
    }
}

inspectClass('App\Scopes\ConfigIdScope');
inspectClass('App\Traits\ConfigId');
inspectClass('App\Observers\ConfigIdObserver');
inspectClass('App\Models\Config');

