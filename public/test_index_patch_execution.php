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

echo "Laravel booted.\n";

// Run index.php patch logic explicitly with echo statements
if (isset($app)) {
    echo "app is set.\n";
    include_once '/var/www/html/xosid_database_helpers.php';
    echo "xosid_database_helpers included.\n";

    if (class_exists('XOSIDConfigRepository')) {
        echo "XOSIDConfigRepository class exists.\n";
        $currentConfig = $app['config'] ?? null;
        if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
            $newConfig = new XOSIDConfigRepository($currentConfig);
            $app->instance('config', $newConfig);
            $config = $newConfig;
            echo "Config decorated.\n";
        } else {
            $config = $currentConfig;
            echo "Config NOT decorated (already decorated or null).\n";
        }
    } else {
        echo "XOSIDConfigRepository class does NOT exist.\n";
        $config = $app['config'] ?? null;
    }

    if ($config) {
        echo "config is not null.\n";
        $connections = $config->get('database.connections') ?: [];
        $defaultConfig = $connections['default'] ?? null;
        if ($defaultConfig) {
            echo "default connection config found.\n";
            foreach (['', 'mysql', 'opensid', 'tenant'] as $name) {
                if (empty($connections[$name])) {
                    $connections[$name] = $defaultConfig;
                }
            }
            $config->set('database.connections', $connections);
        } else {
            echo "default connection config NOT found!\n";
        }
        if (!$config->get('database.default')) {
            $config->set('database.default', 'default');
            echo "database.default forced to default.\n";
        } else {
            echo "database.default is: " . $config->get('database.default') . "\n";
        }
    }

    if (class_exists('Illuminate\Database\Eloquent\Model')) {
        echo "Eloquent Model class exists.\n";
        if (class_exists('XOSIDConnectionResolver')) {
            echo "XOSIDConnectionResolver class exists.\n";
            $currentResolver = Illuminate\Database\Eloquent\Model::getConnectionResolver();
            if ($currentResolver) {
                echo "currentResolver is: " . get_class($currentResolver) . "\n";
                $newResolver = new XOSIDConnectionResolver($currentResolver);
                Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
                echo "Connection resolver set to XOSIDConnectionResolver.\n";
            } else {
                echo "currentResolver is NULL.\n";
            }
        } else {
            echo "XOSIDConnectionResolver class does NOT exist.\n";
        }
    } else {
        echo "Eloquent Model class does NOT exist.\n";
    }
}
?>
