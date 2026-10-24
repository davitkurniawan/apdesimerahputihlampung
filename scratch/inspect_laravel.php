<?php
// scratch/inspect_laravel.php
if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', 'index.php');
if (!defined('BASEPATH'))
    define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL'))
    define('APP_URL', 'http://localhost:8080');
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);
if (!defined('LOKASI_CONFIG_DESA'))
    define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once __DIR__ . '/../vendor/autoload.php';

try {
    @include_once __DIR__ . '/../donjo-app/helpers/opensid_helper.php';
} catch (\Throwable $e) {}

// Boot Laravel app
if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
}

if (isset($app)) {
    echo "App class: " . get_class($app) . "\n";
    echo "Methods:\n";
    $reflection = new ReflectionClass($app);
    foreach ($reflection->getMethods() as $method) {
        echo " - " . $method->getName() . "\n";
    }
} else {
    echo "App not defined!\n";
}
