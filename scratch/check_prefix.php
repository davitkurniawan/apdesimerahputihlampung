<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

include APPPATH . 'config/constants.php';
$config = [];
include APPPATH . 'config/config.php';
header('Content-Type: text/plain');
echo "subclass_prefix: " . ($config['subclass_prefix'] ?? 'NOT SET') . "\n";
