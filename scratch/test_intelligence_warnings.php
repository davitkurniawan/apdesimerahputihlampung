<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';

// Enable all error reporting so we catch notices and warnings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set custom error handler to throw exception on warnings/notices
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (error_reporting() & $errno) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
});

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$xosid_intelligence_engine_file = dirname(__DIR__) . '/xosid_intelligence_engine.php';
require_once $xosid_intelligence_engine_file;

try {
    echo "Running xosid_get_intelligence_data()...\n";
    $intel = xosid_get_intelligence_data();
    echo "Calculation completed successfully with NO warnings or notices!\n";
    echo "Generated at: " . $intel['generated_at'] . "\n";
    echo "IDM Skor: " . ($intel['idm']['skor'] ?? 'N/A') . " (Status: " . ($intel['idm']['status'] ?? 'N/A') . ")\n";
    echo "SDGs Average: " . ($intel['sdgs']['average'] ?? 'N/A') . "\n";
} catch (ErrorException $e) {
    echo "\n[WARNING/NOTICE DETECTED] " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
} catch (Throwable $e) {
    echo "\n[ERROR] " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
