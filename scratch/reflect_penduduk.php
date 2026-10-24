<?php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    $ref = new ReflectionClass('App\Models\Penduduk');
    echo "Class: " . $ref->getName() . "\n";
    
    echo "Methods:\n";
    foreach ($ref->getMethods() as $method) {
        $name = $method->getName();
        if (strpos($name, 'get') === 0 && strpos($name, 'Attribute') !== false) {
            echo "  " . $name . "()\n";
        }
        if (in_array($name, ['map', 'lokasi', 'desa', 'wilayah'])) {
            echo "  Relation method: " . $name . "()\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
