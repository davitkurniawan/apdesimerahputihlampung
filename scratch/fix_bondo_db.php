<?php
// scratch/fix_bondo_db.php
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

echo "=== FIXING BONDO DATABASE USER MAPPINGS ===\n";

// Find Kades (jabatan_id = 1)
$kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
if ($kades) {
    echo "Kades pamong ID: {$kades->pamong_id}\n";
    // Update adminsuper or kades user
    \Illuminate\Support\Facades\DB::statement("UPDATE user SET pamong_id = ? WHERE username = 'adminsuper' OR username = 'kades'", [$kades->pamong_id]);
    echo "Updated Kades user mapping.\n";
}

// Find Sekdes (jabatan_id = 2)
$sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
if ($sekdes) {
    echo "Sekdes pamong ID: {$sekdes->pamong_id}\n";
    \Illuminate\Support\Facades\DB::statement("UPDATE user SET pamong_id = ? WHERE username = 'sekdes'", [$sekdes->pamong_id]);
    echo "Updated Sekdes user mapping.\n";
}

// Clear query cache
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}
\Illuminate\Support\Facades\Cache::flush();
echo "Cleared caches.\n";
