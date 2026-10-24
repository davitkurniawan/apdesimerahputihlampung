<?php
// scratch/check_identitas_relations.php

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

echo "=== FETCHING CONFIG MODEL ===\n";
$desa = \App\Models\Config::first();
if (!$desa) {
    echo "Config model not found!\n";
    exit;
}

echo "Config ID: " . $desa->id . "\n";
echo "Config Nama Desa: " . $desa->nama_desa . "\n";
echo "Class name: " . get_class($desa) . "\n";
var_dump(get_class($desa) === 'App\Models\Config');
var_dump(get_class($desa) === 'App\Models\Config');


echo "--- Attributes ---\n";
print_r(array_keys($desa->getAttributes()));
echo "--- Relations ---\n";
print_r(array_keys($desa->getRelations()));



echo "\n--- Inspecting \$desa->pamong (Kades) ---\n";
try {
    $pamong = $desa->pamong;
    echo "Relation loaded: " . ($desa->relationLoaded('pamong') ? 'YES' : 'NO') . "\n";
    echo "pamong is: " . ($pamong ? get_class($pamong) . " (ID=" . $pamong->pamong_id . ", Nama=" . var_export($pamong->pamong_nama, true) . ")" : 'NULL') . "\n";
    if ($pamong) {
        // Try makeVisible
        try {
            $pamong->makeVisible('pamong_nip');
            echo "pamong->makeVisible('pamong_nip') succeeded!\n";
        } catch (\Throwable $e) {
            echo "pamong->makeVisible('pamong_nip') FAILED: " . $e->getMessage() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error accessing \$desa->pamong: " . $e->getMessage() . "\n";
}

echo "\n--- Inspecting \$desa->sekdes ---\n";
try {
    $sekdes = $desa->sekdes;
    echo "sekdes is: " . ($sekdes ? get_class($sekdes) . " (ID=" . $sekdes->pamong_id . ", Nama=" . var_export($sekdes->pamong_nama, true) . ")" : 'NULL') . "\n";
    if ($sekdes) {
        try {
            $sekdes->makeVisible('pamong_nip');
            echo "sekdes->makeVisible('pamong_nip') succeeded!\n";
        } catch (\Throwable $e) {
            echo "sekdes->makeVisible('pamong_nip') FAILED: " . $e->getMessage() . "\n";
        }
        try {
            $sekdes_user = $sekdes->user;
            echo "sekdes->user is: " . ($sekdes_user ? get_class($sekdes_user) . " (ID=" . $sekdes_user->id . ", Username=" . var_export($sekdes_user->username, true) . ")" : 'NULL') . "\n";
            if ($sekdes_user) {
                $sekdes_user->makeVisible('password');
                echo "sekdes->user->makeVisible() succeeded!\n";
            }
        } catch (\Throwable $e) {
            echo "Error accessing \$sekdes->user: " . $e->getMessage() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error accessing \$desa->sekdes: " . $e->getMessage() . "\n";
}

echo "\n--- Inspecting \$desa->pamong_sekdes ---\n";
try {
    $pamong_sekdes = $desa->pamong_sekdes;
    echo "pamong_sekdes is: " . ($pamong_sekdes ? get_class($pamong_sekdes) . " (ID=" . $pamong_sekdes->pamong_id . ", Nama=" . var_export($pamong_sekdes->pamong_nama, true) . ")" : 'NULL') . "\n";
} catch (\Throwable $e) {
    echo "Error accessing \$desa->pamong_sekdes: " . $e->getMessage() . "\n";
}

echo "\n--- End of inspection ---\n";
?>
