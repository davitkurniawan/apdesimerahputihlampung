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

// Simulate session for Bondo admin
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$_SESSION['user_id'] = 1; // admin user ID

echo "Forcing login with ID 1:\n";
try {
    \Illuminate\Support\Facades\Auth::loginUsingId(1);
} catch (\Throwable $e) {
    echo "Login Exception: " . $e->getMessage() . "\n";
}

echo "Resolving Kades (jabatan_id = 1):\n";
try {
    $kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
    if ($kades) {
        echo "Kades Pamong ID: " . $kades->pamong_id . "\n";
        echo "Kades resident ID (id_pend): " . json_encode($kades->id_pend) . "\n";
        $pend = $kades->penduduk;
        echo "Kades->penduduk: " . ($pend ? get_class($pend) . " (id=" . $pend->id . ", nama=" . $pend->nama . ")" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map ?? $pend->lokasi;
            echo "Kades->penduduk->map: " . ($map ? get_class($map) : "NULL") . "\n";
        }
    } else {
        echo "Kades: NULL\n";
    }
} catch (\Throwable $e) {
    echo "Kades Exception: " . $e->getMessage() . "\n";
}

echo "\nResolving Sekdes (jabatan_id = 2):\n";
try {
    $sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
    if ($sekdes) {
        echo "Sekdes Pamong ID: " . $sekdes->pamong_id . "\n";
        echo "Sekdes resident ID (id_pend): " . json_encode($sekdes->id_pend) . "\n";
        $pend = $sekdes->penduduk;
        echo "Sekdes->penduduk: " . ($pend ? get_class($pend) . " (id=" . $pend->id . ", nama=" . $pend->nama . ")" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map ?? $pend->lokasi;
            echo "Sekdes->penduduk->map: " . ($map ? get_class($map) : "NULL") . "\n";
        }
    } else {
        echo "Sekdes: NULL\n";
    }
} catch (\Throwable $e) {
    echo "Sekdes Exception: " . $e->getMessage() . "\n";
}


echo "\nChecking Event Dispatcher Listeners:\n";
if (class_exists('Illuminate\Database\Eloquent\Model')) {
    $disp = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
    if ($disp) {
        echo "Dispatcher Class: " . get_class($disp) . "\n";
        if (method_exists($disp, 'getListeners')) {
            $listeners = $disp->getListeners('eloquent.retrieved: *');
            echo "Listeners for wildcard count: " . count($listeners) . "\n";
            foreach ($listeners as $i => $l) {
                echo "  Listener #$i: " . (is_object($l) ? get_class($l) : gettype($l)) . "\n";
            }
        }
    } else {
        echo "Dispatcher: NULL\n";
    }
}
