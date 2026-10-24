<?php
header('Content-Type: text/plain');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

chdir('/var/www/html/');

// index.php lines 1 to 72 simulation:
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
if (!defined('FCPATH')) define('FCPATH', '/var/www/html/');
if (!defined('SELF')) define('SELF', 'index.php');
if (!defined('BASEPATH')) define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', '/var/www/html/donjo-app/');
if (!defined('VIEWPATH')) define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH')) define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH')) define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH')) define('MODPATH', FCPATH . 'Modules/');

if (file_exists('/var/www/html/xosid_emergency_autoload.php'))
    require_once '/var/www/html/xosid_emergency_autoload.php';
if (file_exists('/var/www/html/xosid_nuclear_boot.php'))
    require_once '/var/www/html/xosid_nuclear_boot.php';

require_once '/var/www/html/vendor/autoload.php';

if (file_exists('/var/www/html/bootstrap/app.php'))
    $app = require_once '/var/www/html/bootstrap/app.php';

$config = $app['config'];

echo "Laravel default database connection: " . ($config->get('database.default') ?? 'NULL/EMPTY') . "\n\n";
echo "Laravel connections:\n";
print_r($config->get('database.connections'));
?>
