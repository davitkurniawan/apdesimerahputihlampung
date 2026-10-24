<?php
header('Content-Type: text/plain');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

chdir('/var/www/html/');

// Boot hybrid stack (like index.php does)
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
if (!defined('FCPATH')) define('FCPATH', '/var/www/html/');
if (!defined('SELF')) define('SELF', 'index.php');
if (!defined('BASEPATH')) define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', '/var/www/html/donjo-app/');
if (!defined('VIEWPATH')) define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH')) define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH')) define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH')) define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL')) define('APP_URL', 'http://gunungsugih-gunungsugih.localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_nuclear_boot.php';
require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Now, let's inspect the connection resolver state
use Illuminate\Database\Eloquent\Model;

echo "Laravel default db connection: " . $app['config']->get('database.default') . "\n";
echo "Laravel connections count: " . count($app['config']->get('database.connections')) . "\n";

$resolver = Model::getConnectionResolver();
if ($resolver) {
    echo "Resolver class: " . get_class($resolver) . "\n";
    try {
        echo "Default connection via resolver: " . get_class($resolver->connection()) . "\n";
    } catch (Throwable $e) {
        echo "Resolver connection() failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "No connection resolver bound to Model!\n";
}

// Let's try to load the model query that fails
// We will look at MY_Controller line 133, which calls cekConfig.
// Let's instantiate MY_Controller or mock the query.
// Since MY_Controller is a CI Controller, we can load CodeIgniter core to run it.
?>
