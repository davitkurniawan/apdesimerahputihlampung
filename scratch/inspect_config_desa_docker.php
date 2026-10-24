<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Include database helpers
include_once FCPATH . 'xosid_database_helpers.php';

// Try to read database config and inject
if (file_exists(FCPATH . 'desa/config/database.php')) {
    require FCPATH . 'desa/config/database.php';
    if (isset($db['default'])) {
        $ci_db = $db['default'];
        $db_pass = $ci_db['password'] ?? 'password';
        
        // Decrypt password if needed
        if (is_string($db_pass) && strpos($db_pass, 'eyJpdiI6') === 0) {
            try {
                if (isset($app['encrypter'])) {
                    $db_pass = $app['encrypter']->decrypt($db_pass);
                }
            } catch (\Throwable $e) {}
        }

        $laravel_db = [
            'driver'    => 'mysql',
            'host'      => $ci_db['hostname'] ?? 'db',
            'port'      => $ci_db['port'] ?? 3306,
            'database'  => $ci_db['database'] ?? 'xosid',
            'username'  => $ci_db['username'] ?? 'root',
            'password'  => $db_pass,
            'charset'   => 'utf8mb4',
            'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
            'prefix'    => '',
            'strict'    => $ci_db['stricton'] ?? true,
            'engine'    => null,
        ];
        
        $config = $app['config'];
        $connections = $config->get('database.connections') ?: [];
        $connections['default'] = $laravel_db;
        $connections['mysql'] = $laravel_db;
        $config->set('database.connections', $connections);
        $config->set('database.default', 'default');
    }
}

try {
    $row = \Illuminate\Support\Facades\DB::table('config')->first();
    echo "SUCCESS:\n";
    print_r((array)$row);
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
