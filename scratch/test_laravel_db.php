<?php
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('SELF', 'index.php');
define('APP_URL', 'http://bumidaya.localhost:8080');
define('FORCE_HTTPS', false);

require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Include our resolver code
if (file_exists(FCPATH . 'desa/config/database.php')) {
    require FCPATH . 'desa/config/database.php';
    if (isset($db['default'])) {
        $ci_db = $db['default'];
        $db_pass = $ci_db['password'] ?? 'password';
        
        $app['config']->set('database.connections.mysql.host', $ci_db['hostname'] ?? 'db');
        $app['config']->set('database.connections.mysql.port', $ci_db['port'] ?? 3306);
        $app['config']->set('database.connections.mysql.database', $ci_db['database'] ?? 'xosid');
        $app['config']->set('database.connections.mysql.username', $ci_db['username'] ?? 'root');
        $app['config']->set('database.connections.mysql.password', $db_pass);
        
        if ($app->resolved('db')) {
            $app['db']->purge('mysql');
        }
    }
}

echo "Laravel Default Connection: " . $app['config']->get('database.default') . "\n";
$conn_config = $app['config']->get('database.connections.' . $app['config']->get('database.default'));
echo "Laravel Connection Host: " . $conn_config['host'] . "\n";
echo "Laravel Connection DB: " . $conn_config['database'] . "\n";

try {
    $db_name = $app['db']->connection()->getDatabaseName();
    echo "Eloquent Connected Database: " . $db_name . "\n";
    $result = $app['db']->select("SELECT app_key FROM config LIMIT 1");
    print_r($result);
} catch (\Throwable $e) {
    echo "Eloquent Connection Failed: " . $e->getMessage() . "\n";
}
