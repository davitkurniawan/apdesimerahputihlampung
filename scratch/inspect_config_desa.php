<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Mock/stub constants so it doesn't complain
define('FCPATH', dirname(__DIR__) . '/');
define('DESAPATH', FCPATH . 'desa/');

// Include database helpers
include_once __DIR__ . '/../xosid_database_helpers.php';

// Try to read database config and inject
if (file_exists(FCPATH . 'desa/config/database.php')) {
    require FCPATH . 'desa/config/database.php';
    if (isset($db['default'])) {
        $ci_db = $db['default'];
        $laravel_db = [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1', // connect locally
            'port'      => 3307,        // local port mapped to 3306 in docker
            'database'  => $ci_db['database'] ?? 'xosid',
            'username'  => $ci_db['username'] ?? 'root',
            'password'  => $ci_db['password'] ?? 'password',
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
    $row = \Illuminate\Support\Facades\DB::table('config_desa')->first();
    echo "SUCCESS:\n";
    print_r((array)$row);
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
