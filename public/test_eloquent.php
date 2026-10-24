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

// Apply the index.php patch
include_once '/var/www/html/xosid_database_helpers.php';
if (isset($app)) {
    if (class_exists('XOSIDConfigRepository')) {
        $currentConfig = $app['config'] ?? null;
        if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
            $newConfig = new XOSIDConfigRepository($currentConfig);
            $app->instance('config', $newConfig);
        }
    }
    
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

    if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('XOSIDConnectionResolver')) {
        $currentResolver = Illuminate\Database\Eloquent\Model::getConnectionResolver();
        if ($currentResolver) {
            $newResolver = new XOSIDConnectionResolver($currentResolver);
            Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
            echo "Successfully decorated resolver!\n";
        } else {
            echo "Resolver is NULL, trying to force set it!\n";
            // If it is null, let's boot the container or set it directly to $app['db']
            Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
            
            // Now decorate it
            $currentResolver = Illuminate\Database\Eloquent\Model::getConnectionResolver();
            if ($currentResolver) {
                $newResolver = new XOSIDConnectionResolver($currentResolver);
                Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
                echo "Successfully set and decorated resolver!\n";
            }
        }
    }
}

// Now let's query the Config model
try {
    echo "Querying App\\Models\\Config...\n";
    $cfg = App\Models\Config::first();
    if ($cfg) {
        echo "Successfully got Config! Key: " . $cfg->key . ", Value: " . $cfg->value . "\n";
    } else {
        echo "Config table is empty.\n";
    }
} catch (Exception $e) {
    echo "Error querying: " . $e->getMessage() . "\n";
}
?>
