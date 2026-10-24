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

$config = $app['config'];

echo "Laravel default database connection: " . $config->get('database.default') . "\n\n";
echo "Laravel connections:\n";
print_r($config->get('database.connections'));
?>
