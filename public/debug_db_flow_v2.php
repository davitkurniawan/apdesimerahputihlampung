<?php
header('Content-Type: text/plain');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

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
define('APP_URL', 'http://gunungsugih-gunungsugih.localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once '/var/www/html/vendor/autoload.php';

// Boot Laravel
$app = require_once '/var/www/html/bootstrap/app.php';

echo "Laravel default db after boot: " . $app['config']->get('database.default') . "\n";

// Let's load the decorator
include_once '/var/www/html/xosid_database_helpers.php';
if (class_exists('XOSIDConfigRepository')) {
    $currentConfig = $app['config'] ?? null;
    if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
        $newConfig = new XOSIDConfigRepository($currentConfig);
        $app->instance('config', $newConfig);
        echo "Decorated config repository!\n";
    }
}

// Injeksi koneksi fallback
$config = $app['config'];
if ($config) {
    $connections = $config->get('database.connections') ?: [];
    $defaultConfig = $connections['default'] ?? null;
    if ($defaultConfig) {
        foreach (['', 'mysql', 'opensid', 'tenant'] as $name) {
            if (empty($connections[$name])) {
                $connections[$name] = $defaultConfig;
            }
        }
        $config->set('database.connections', $connections);
    }
    if (!$config->get('database.default')) {
        $config->set('database.default', 'default');
    }
}

echo "Laravel default db after fallback injection: " . $app['config']->get('database.default') . "\n";

// Let's check ArrayAccess on config
try {
    echo "Array access check: " . $app['config']['database.default'] . "\n";
} catch (Exception $e) {
    echo "Array access error: " . $e->getMessage() . "\n";
}

// Now let's try to simulate CodeIgniter's database loading
require '/var/www/html/desa/config/database.php';
echo "CI default database: " . $db['default']['database'] . "\n";

// Resolve database manager
$dbManager = $app['db'];
echo "DatabaseManager default connection name: " . $dbManager->getDefaultConnection() . "\n";

try {
    $conn = $dbManager->connection();
    echo "Successfully got default connection! DB name: " . $conn->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "Error getting default connection: " . $e->getMessage() . "\n";
}

try {
    $conn = $dbManager->connection('');
    echo "Successfully got connection('')! DB name: " . $conn->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "Error getting connection(''): " . $e->getMessage() . "\n";
}

try {
    $conn = $dbManager->connection(null);
    echo "Successfully got connection(null)! DB name: " . $conn->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "Error getting connection(null): " . $e->getMessage() . "\n";
}
?>
