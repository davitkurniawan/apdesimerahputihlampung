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

// Load emergency autoloaders/bypasses if any
if (file_exists('/var/www/html/xosid_emergency_autoload.php')) {
    require_once '/var/www/html/xosid_emergency_autoload.php';
}
if (file_exists('/var/www/html/xosid_nuclear_boot.php')) {
    require_once '/var/www/html/xosid_nuclear_boot.php';
}

require_once '/var/www/html/vendor/autoload.php';

// Boot Laravel
$app = require_once '/var/www/html/bootstrap/app.php';

echo "Laravel default db after boot: " . $app['config']->get('database.default') . "\n";
echo "Laravel connections count: " . count($app['config']->get('database.connections') ?: []) . "\n";

// Let's load the decorator from index.php
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

// Now let's try to simulate CodeIgniter's database loading
// In CodeIgniter, it will load desa/config/database.php
echo "Loading desa/config/database.php...\n";
require '/var/www/html/desa/config/database.php';
echo "CI default database: " . $db['default']['database'] . "\n";

// Did loading CI database change Laravel's config?
echo "Laravel default db after CI db load: " . $app['config']->get('database.default') . "\n";

// Wait! In the actual code, does CodeIgniter load Laravel database configuration later?
// Or does Laravel's bootstrap load it?
// Let's resolve the db manager and see the default connection name
$dbManager = $app['db'];
echo "DatabaseManager default connection: " . $dbManager->getDefaultConnection() . "\n";
try {
    $conn = $dbManager->connection();
    echo "Successfully got default connection: " . $conn->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "Error getting connection: " . $e->getMessage() . "\n";
}
?>
