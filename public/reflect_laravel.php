<?php
header('Content-Type: text/plain');
chdir('/var/www/html/');

define('FCPATH', '/var/www/html/');
define('ENVIRONMENT', 'production');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('SELF', 'index.php');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require __DIR__.'/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "Class: " . get_class($app) . "\n";
echo "Parent Class: " . get_parent_class($app) . "\n\n";

$ref = new ReflectionClass($app);
echo "Methods:\n";
foreach ($ref->getMethods() as $method) {
    echo " - " . $method->getName() . "\n";
}

echo "\nProperties:\n";
foreach ($ref->getProperties() as $prop) {
    echo " - " . $prop->getName() . "\n";
}
?>
