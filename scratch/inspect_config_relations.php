<?php
// scratch/inspect_config_relations.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xosid_emergency_autoload.php';

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('APP_URL', 'http://bondo-bangsri.localhost:8080');

// Boot Eloquent/Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$ref = new ReflectionClass('App\Models\Config');
echo "Config Model Class: " . $ref->getName() . "\n";
echo "Methods:\n";
foreach ($ref->getMethods() as $m) {
    if ($m->getDeclaringClass()->getName() === 'App\Models\Config') {
        echo "  - " . $m->getName() . "\n";
    }
}
