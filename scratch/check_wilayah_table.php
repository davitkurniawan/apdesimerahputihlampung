<?php
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bumidaya.localhost';
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$config = $app->make('config');
$config->set('database.connections.mysql.database', 'xosid_tenant_bumidaya');
$config->set('database.connections.mysql.username', 'root');
$config->set('database.connections.mysql.password', 'password');
$config->set('database.connections.mysql.host', 'db');
$config->set('database.connections.mysql.port', 3306);
$config->set('database.default', 'mysql');

$db = \Illuminate\Support\Facades\DB::connection();
echo "Columns:\n";
$cols = $db->select("SHOW COLUMNS FROM tweb_wil_clusterdesa");
foreach ($cols as $c) {
    print_r($c);
}
echo "\nRows count:\n";
$count = $db->table('tweb_wil_clusterdesa')->count();
echo $count . "\n";
echo "\nFirst 5 Rows:\n";
$rows = $db->table('tweb_wil_clusterdesa')->limit(5)->get();
foreach ($rows as $r) {
    print_r($r);
}
