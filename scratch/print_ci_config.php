<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('APP_URL', 'http://bondo-bangsri.localhost:8080');
define('STORAGEPATH', FCPATH . 'storage/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('MODPATH', FCPATH . 'Modules/');
define('LOKASI_CACHE', 'desa/cache/');

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

// Include the autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Include config file directly
include APPPATH . 'config/config.php';

echo "=== CODEIGNITER CONFIG ===\n";
echo "cookie_domain: '" . ($config['cookie_domain'] ?? 'not set') . "'\n";
echo "cookie_path:   '" . ($config['cookie_path'] ?? 'not set') . "'\n";
echo "cookie_prefix: '" . ($config['cookie_prefix'] ?? 'not set') . "'\n";
echo "cookie_secure: '" . ($config['cookie_secure'] ?? 'not set') . "'\n";
echo "csrf_protection: " . ($config['csrf_protection'] ? 'TRUE' : 'FALSE') . "\n";
echo "csrf_token_name: '" . ($config['csrf_token_name'] ?? 'not set') . "'\n";
echo "csrf_cookie_name: '" . ($config['csrf_cookie_name'] ?? 'not set') . "'\n";
echo "csrf_expire: " . ($config['csrf_expire'] ?? 'not set') . "\n";
echo "csrf_regenerate: " . ($config['csrf_regenerate'] ? 'TRUE' : 'FALSE') . "\n";
echo "csrf_exclude_uris: \n";
print_r($config['csrf_exclude_uris'] ?? []);
