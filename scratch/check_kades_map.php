<?php
// scratch/check_kades_map.php
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

if (class_exists('App\Models\Pamong')) {
    $k = \App\Models\Pamong::find(27);
    if ($k) {
        echo "Kades pamong_id: {$k->pamong_id} | nama: {$k->pamong_nama} | id_pend: {$k->id_pend}\n";
        $pend = $k->penduduk;
        echo "Kades penduduk: " . ($pend ? "FOUND (id={$pend->id})" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "Kades penduduk->map: " . ($map ? "FOUND" : "NULL") . "\n";
        }
    }
    
    $s = \App\Models\Pamong::find(28);
    if ($s) {
        echo "Sekdes pamong_id: {$s->pamong_id} | nama: {$s->pamong_nama} | id_pend: {$s->id_pend}\n";
        $pend = $s->penduduk;
        echo "Sekdes penduduk: " . ($pend ? "FOUND (id={$pend->id})" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "Sekdes penduduk->map: " . ($map ? "FOUND" : "NULL") . "\n";
        }
    }
}
