<?php
// web_debug.php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/xosid_database_helpers.php';
require_once __DIR__ . '/xosid_emergency_autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
\Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/plain');

echo "=== SESSION DATA ===\n";
print_r($_SESSION);

echo "\n=== LARAVEL QUERY LOG ===\n";
\Illuminate\Support\Facades\DB::connection()->enableQueryLog();

$desa = \App\Models\Config::first();
echo "Desa: " . ($desa ? "FOUND (ID=" . $desa->id . ", Nama=" . $desa->nama_desa . ")" : "NULL") . "\n";

if ($desa) {
    $pamong = $desa->pamong;
    echo "Pamong (Kades): " . ($pamong ? "FOUND (ID=" . $pamong->pamong_id . ", Nama=" . $pamong->pamong_nama . ")" : "NULL") . "\n";
    if ($pamong) {
        $user = $pamong->user;
        echo "  User: " . ($user ? "FOUND (ID=" . $user->id . ", Username=" . $user->username . ")" : "NULL") . "\n";
        $penduduk = $pamong->penduduk;
        echo "  Penduduk: " . ($penduduk ? "FOUND (ID=" . $penduduk->id . ", Nama=" . $penduduk->nama . ")" : "NULL") . "\n";
    }

    $sekdes = $desa->sekdes;
    echo "Sekdes: " . ($sekdes ? "FOUND (ID=" . $sekdes->pamong_id . ", Nama=" . $sekdes->pamong_nama . ")" : "NULL") . "\n";
    if ($sekdes) {
        $user = $sekdes->user;
        echo "  User: " . ($user ? "FOUND (ID=" . $user->id . ", Username=" . $user->username . ")" : "NULL") . "\n";
        $penduduk = $sekdes->penduduk;
        echo "  Penduduk: " . ($penduduk ? "FOUND (ID=" . $penduduk->id . ", Nama=" . $penduduk->nama . ")" : "NULL") . "\n";
    }
}

echo "\n--- Queries ---\n";
print_r(\Illuminate\Support\Facades\DB::connection()->getQueryLog());
