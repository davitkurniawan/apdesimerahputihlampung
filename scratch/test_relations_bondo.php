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

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

foreach ([1 => 'Kades', 2 => 'Sekdes'] as $jabatan_id => $label) {
    echo "\n=== INSPECTING {$label} (jabatan_id = {$jabatan_id}) ===\n";
    $pamong = \App\Models\Pamong::where('jabatan_id', $jabatan_id)->first();
    if (!$pamong) {
        echo "{$label} pamong record not found!\n";
        continue;
    }
    
    echo "Pamong ID: " . $pamong->pamong_id . "\n";
    echo "Pamong Nama: " . var_export($pamong->pamong_nama, true) . "\n";
    
    // User
    $user = null;
    try {
        $user = $pamong->user;
        echo "  User: " . ($user ? get_class($user) . " (id=" . $user->id . ", username=" . var_export($user->username, true) . ")" : 'NULL') . "\n";
        if ($user) {
            $user->makeVisible('password');
            echo "    -> User makeVisible succeeded!\n";
            $grup = $user->userGrup ?? $user->grup ?? null;
            echo "    -> User Grup: " . ($grup ? get_class($grup) . " (nama=" . var_export($grup->nama, true) . ")" : 'NULL') . "\n";
            if ($grup) {
                $grup->makeVisible('nama');
            }
        }
    } catch (\Throwable $e) {
        echo "  User Relation Error: " . $e->getMessage() . "\n";
    }
    
    // Penduduk
    $pend = null;
    try {
        $pend = $pamong->penduduk;
        echo "  Penduduk: " . ($pend ? get_class($pend) . " (id=" . $pend->id . ", nama=" . var_export($pend->nama, true) . ")" : 'NULL') . "\n";
        if ($pend) {
            $pend->makeVisible('nik');
            echo "    -> Penduduk makeVisible succeeded!\n";
            
            // Map
            $map = $pend->map;
            echo "    -> Map: " . ($map ? get_class($map) . " (id=" . $map->id . ")" : 'NULL') . "\n";
            if ($map) {
                $map->makeVisible('lat');
                echo "      -> Map makeVisible succeeded!\n";
            }
            
            // Lokasi
            $lokasi = $pend->lokasi;
            echo "    -> Lokasi: " . ($lokasi ? get_class($lokasi) . " (id=" . $lokasi->id . ")" : 'NULL') . "\n";
            if ($lokasi) {
                $lokasi->makeVisible('lat');
                echo "      -> Lokasi makeVisible succeeded!\n";
            }
        }
    } catch (\Throwable $e) {
        echo "  Penduduk/Map/Lokasi Error: " . $e->getMessage() . "\n";
    }
}
