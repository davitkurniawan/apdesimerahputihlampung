<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Manually configure tenant database connection
$config = $app->make('config');
$config->set('database.connections.mysql.database', 'xosid_tenant_demo');
$config->set('database.connections.mysql.username', 'root');
$config->set('database.connections.mysql.password', 'password');
$config->set('database.connections.mysql.host', 'db');
$config->set('database.connections.mysql.port', 3306);
$config->set('database.default', 'mysql');

$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    echo array_values((array)$table)[0] . "\n";
}
