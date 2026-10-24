<?php
// scratch/inspect_setting_table.php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

$conn = \Illuminate\Support\Facades\DB::connection();

$settings = $conn->select("select * from setting_aplikasi where `key` like '%kades%' or `key` like '%sekdes%' or `key` like '%pamong%' or `key` like '%kepala%'");
echo "Settings:\n";
foreach ($settings as $s) {
    echo "key: {$s->key} | value: " . json_encode($s->value) . " | keterangan: {$s->keterangan}\n";
}
