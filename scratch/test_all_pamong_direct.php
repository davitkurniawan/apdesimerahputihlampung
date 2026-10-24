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

echo "=== TESTING ALL PAMONGS FOR BONDO ===\n";
$pamongs = \App\Models\Pamong::all();
echo "Loaded " . count($pamongs) . " pamong records.\n\n";

foreach ($pamongs as $p) {
    echo "Pamong ID: {$p->pamong_id} | Nama: '{$p->pamong_nama}' (jabatan_id={$p->jabatan_id})\n";
    
    // Check user relation
    try {
        $user = $p->user;
        echo "  - User: " . ($user ? get_class($user) . " (ID=" . $user->id . ", username=" . var_export($user->username, true) . ")" : 'NULL') . "\n";
        if ($user) {
            $user->makeVisible('password');
            echo "    -> makeVisible('password') succeeded!\n";
        }
    } catch (\Throwable $e) {
        echo "    -> ERROR on User relation: " . $e->getMessage() . "\n";
    }

    // Check penduduk relation
    try {
        $pend = $p->penduduk;
        echo "  - Penduduk: " . ($pend ? get_class($pend) . " (ID=" . $pend->id . ", nama=" . var_export($pend->nama, true) . ")" : 'NULL') . "\n";
        if ($pend) {
            $pend->makeVisible('nik');
            echo "    -> makeVisible('nik') succeeded!\n";
            
            // Check map relation on penduduk
            try {
                $map = $pend->map;
                echo "    - Map: " . ($map ? get_class($map) . " (ID=" . $map->id . ")" : 'NULL') . "\n";
                if ($map) {
                    $map->makeVisible('lat');
                    echo "      -> map->makeVisible('lat') succeeded!\n";
                }
            } catch (\Throwable $e) {
                echo "      -> ERROR on Map relation: " . $e->getMessage() . "\n";
            }
        }
    } catch (\Throwable $e) {
        echo "    -> ERROR on Penduduk relation: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}
echo "=== END OF PAMONG TEST ===\n";
?>
