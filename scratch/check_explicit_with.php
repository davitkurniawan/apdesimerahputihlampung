<?php
// Test explicit with() calls
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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

echo "1. Pamong::where()->get() [no explicit with]...\n";
flush();
$r = \App\Models\Pamong::where('jabatan_id', 1)->get();
echo "   OK: " . count($r) . " results\n";

echo "2. Pamong::with('penduduk')->where()->get()...\n";
flush();
$r2 = \App\Models\Pamong::with('penduduk')->where('jabatan_id', 1)->get();
echo "   OK: " . count($r2) . " results\n";

echo "3. Pamong::with(['penduduk','jabatan'])->where()->get()...\n";
flush();
$r3 = \App\Models\Pamong::with(['penduduk','jabatan'])->where('jabatan_id', 1)->get();
echo "   OK: " . count($r3) . " results\n";

echo "4. Pamong where pamong_status=1 get all...\n";
flush();
$r4 = \App\Models\Pamong::where('pamong_status', 1)->get();
echo "   OK: " . count($r4) . " results\n";
foreach ($r4 as $p) {
    echo "     ID=" . $p->pamong_id . " nama=" . $p->pamong_nama . "\n";
}

echo "5. Config::with('pamong')->first()...\n";
flush();
$config = \App\Models\Config::with('pamong')->first();
echo "   OK: nama_desa=" . $config->nama_desa . "\n";
echo "   pamong: " . ($config->pamong ? $config->pamong->pamong_nama : 'NULL') . "\n";

echo "6. Penduduk::where('id', 17866)->first()...\n";
flush();
$pend = \App\Models\Penduduk::where('id', 17866)->first();
echo "   OK: nama=" . ($pend ? $pend->nama : 'NULL') . "\n";

echo "7. Penduduk::with(['wilayah','keluarga'])->where('id', 17866)->first()...\n";
flush();
$pend2 = \App\Models\Penduduk::with(['wilayah','keluarga'])->where('id', 17866)->first();
echo "   OK: nama=" . ($pend2 ? $pend2->nama : 'NULL') . "\n";

echo "\nAll tests passed!\n";
