<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('ENVIRONMENT', 'development');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('DESAPATH', __DIR__ . '/../desa/');
define('STORAGEPATH', __DIR__ . '/../storage/');
define('MODPATH', __DIR__ . '/../Modules/');
define('FCPATH', __DIR__ . '/../');

if (file_exists(APPPATH . 'config/constants.php')) {
    require_once APPPATH . 'config/constants.php';
}

$config = [];
if (file_exists(APPPATH . 'config/config.php')) {
    include APPPATH . 'config/config.php';
}

$keys = [
    'cookie_prefix',
    'cookie_domain',
    'cookie_path',
    'cookie_secure',
    'cookie_httponly',
    'sess_driver',
    'sess_cookie_name',
    'sess_expiration',
    'sess_save_path',
    'sess_match_ip',
    'sess_time_to_update',
    'sess_regenerate_destroy',
    'csrf_protection',
    'csrf_token_name',
    'csrf_cookie_name',
    'csrf_expire',
    'csrf_regenerate',
    'csrf_exclude_uris'
];

echo "=== CODEIGNITER CONFIG ===\n";
foreach ($keys as $k) {
    echo "$k: " . (isset($config[$k]) ? var_export($config[$k], true) : 'NOT SET') . "\n";
}
