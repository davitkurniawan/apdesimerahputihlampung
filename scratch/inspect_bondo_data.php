<?php
// scratch/inspect_bondo_data.php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

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

// Include index.php logic or load helper classes
include_once __DIR__ . '/../xosid_database_helpers.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Cek data di database tenant
if (isset($app['db'])) {
    $db = $app['db'];
    
    // User table
    try {
        $users = $db->select("select * from user limit 5");
        echo "USERS IN TENANT:\n" . json_encode($users) . "\n\n";
    } catch (\Throwable $e) {
        echo "Error user: " . $e->getMessage() . "\n";
    }
}
