<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';
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
$app->boot();

$db = \Illuminate\Support\Facades\DB::connection();
echo "id_cluster distribution in tweb_penduduk:\n";
$rows = $db->table('tweb_penduduk')->select('id_cluster', \Illuminate\Support\Facades\DB::raw('count(*) as count'))->groupBy('id_cluster')->get();
foreach ($rows as $r) {
    print_r($r);
}
