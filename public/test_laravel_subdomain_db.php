<?php
header('Content-Type: text/plain');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

chdir('/var/www/html/');

// Set constants like index.php
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://gunungsugih-gunungsugih.localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

// Boot
require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_nuclear_boot.php';
require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

$config = $app['config'];

echo "database.default: " . var_export($config->get('database.default'), true) . "\n";
echo "DB_CONNECTION env: " . var_export(getenv('DB_CONNECTION'), true) . "\n";
echo "DB_CONNECTION server: " . var_export($_SERVER['DB_CONNECTION'] ?? 'not set', true) . "\n";
echo "database.connections.default: \n";
print_r($config->get('database.connections.default'));
echo "database.connections.mysql: \n";
print_r($config->get('database.connections.mysql'));
?>
