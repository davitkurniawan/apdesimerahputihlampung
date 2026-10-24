<?php
define('ENVIRONMENT', 'production');
define('VERSION', '2606.0.0');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://demo.localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$console_kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$console_kernel->bootstrap();


// Declare CI_Controller if not exists
if (!class_exists('CI_Controller', false)) {
    class CI_Controller {
        public function __get($name) {
            return $this;
        }
        public function __call($name, $args) {
            return $this;
        }
        public function __toString() {
            return '';
        }
    }
}

// Subclass to satisfy CI_Controller type hint
class XOSIDMockCI extends CI_Controller {
    public function __get($name) {
        return $this;
    }
    public function __call($name, $args) {
        return $this;
    }
    public function __toString() {
        return '';
    }
}

// Bind a fluent mock for CodeIgniter ('ci') in Laravel container
$app->singleton('ci', function() {
    return new XOSIDMockCI();
});

require_once __DIR__ . '/../xosid_emergency_autoload.php';
require FCPATH . 'desa/config/database.php';

// Setup Laravel database connection
if (isset($app)) {
    include_once __DIR__ . '/../xosid_database_helpers.php';
    if (class_exists('XOSIDConfigRepository')) {
        $currentConfig = $app->make('config');
        if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
            $newConfig = new XOSIDConfigRepository($currentConfig);
            $app->instance('config', $newConfig);
            $config = $newConfig;
        } else {
            $config = $currentConfig;
        }
    } else {
        $config = $app['config'] ?? null;
    }

    $dbs = ['xosid_tenant_demo', 'xosid_tenant_bumidaya'];
    foreach ($dbs as $db_name) {
        if ($config) {
            $connections = $config->get('database.connections') ?: [];
            $laravel_db = [
                'driver'    => 'mysql',
                'host'      => 'db',
                'port'      => 3306,
                'database'  => $db_name,
                'username'  => 'root',
                'password'  => 'password',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'prefix'    => '',
                'strict'    => false,
                'engine'    => null,
            ];
            $laravel_db['name'] = 'default';
            $connections['default'] = $laravel_db;
            $connections['mysql'] = $laravel_db;
            $connections['opensid'] = $laravel_db;
            $connections['tenant'] = $laravel_db;
            $connections[''] = $laravel_db;
            
            $config->set('database.connections', $connections);
            $config->set('database.default', 'default');
        }

        if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('XOSIDConnectionResolver')) {
            $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
            if (!$currentResolver && isset($app['db'])) {
                \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
                $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
            }
            if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
                $newResolver = new XOSIDConnectionResolver($currentResolver);
                \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
            }
        }

        // Set event dispatcher
        \Illuminate\Database\Eloquent\Model::setEventDispatcher($app['events']);

        // Purge Laravel DB connection cache to force reconnecting to the correct database
        if (class_exists('Illuminate\Support\Facades\DB')) {
            \Illuminate\Support\Facades\DB::purge();
        }

        // Run migrations
        try {
            echo "Running migrations on " . $db_name . "...\n";
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            echo "Output: \n" . \Illuminate\Support\Facades\Artisan::output() . "\n";
        } catch (Throwable $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
}
