<?php
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

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

echo "=== INSPECTING KADES BONDO ===\n";
$kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
if ($kades) {
    echo "Kades Pamong ID: " . $kades->pamong_id . "\n";
    echo "Kades Pamong Nama: " . $kades->pamong_nama . "\n";
    echo "Kades id_pend: " . var_export($kades->id_pend, true) . "\n";
    
    $penduduk = $kades->penduduk;
    echo "Kades Penduduk: " . ($penduduk ? "FOUND (ID=" . $penduduk->id . ", Nama=" . $penduduk->nama . ")" : "NULL") . "\n";
    
    if ($penduduk) {
        $map = $penduduk->map;
        echo "Kades Penduduk Map: " . ($map ? "FOUND (ID=" . $map->id . ", Lat=" . var_export($map->lat, true) . ", Lng=" . var_export($map->lng, true) . ")" : "NULL") . "\n";
        
        $lokasi = $penduduk->lokasi;
        echo "Kades Penduduk Lokasi: " . ($lokasi ? "FOUND (ID=" . $lokasi->id . ", Lat=" . var_export($lokasi->lat, true) . ", Lng=" . var_export($lokasi->lng, true) . ")" : "NULL") . "\n";
    }
} else {
    echo "Kades Pamong not found!\n";
}

$sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
if ($sekdes) {
    echo "\nSekdes Pamong ID: " . $sekdes->pamong_id . "\n";
    echo "Sekdes Pamong Nama: " . $sekdes->pamong_nama . "\n";
    echo "Sekdes id_pend: " . var_export($sekdes->id_pend, true) . "\n";
    
    $penduduk = $sekdes->penduduk;
    echo "Sekdes Penduduk: " . ($penduduk ? "FOUND (ID=" . $penduduk->id . ", Nama=" . $penduduk->nama . ")" : "NULL") . "\n";
    
    if ($penduduk) {
        $map = $penduduk->map;
        echo "Sekdes Penduduk Map: " . ($map ? "FOUND (ID=" . $map->id . ", Lat=" . var_export($map->lat, true) . ", Lng=" . var_export($map->lng, true) . ")" : "NULL") . "\n";
    }
} else {
    echo "Sekdes Pamong not found!\n";
}
