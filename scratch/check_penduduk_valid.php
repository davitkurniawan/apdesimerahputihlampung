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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Get a valid Penduduk ID from bumidaya
$raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')->limit(1)->first();
if (!$raw) { echo "No penduduk!\n"; exit; }
echo "Valid Penduduk: id=" . $raw->id . " nama=" . $raw->nama . "\n";

// Create and manually hydrate
$pend = new \App\Models\Penduduk();
$pend->setRawAttributes((array)$raw, true);
$pend->exists = true;
echo "Hydrated OK\n";

// Test accessor one by one
$appendsList = ['pendidikan','pendidikan_kk','pendidikan_sedang','agama','warganegara','golongan_darah','umur','usia','cacat','cara_kb','alamat_wilayah','alamat_wilayah_kartu_keluarga','nama_asuransi','jml_anak','lokasi','status_perkawinan','jenis_kelamin','jenis_kelamin_inisial','jenis_kelamin_id','penduduk_hubungan','penduduk_status','pekerjaan','sakit_menahun','asuransi','bahasa'];

foreach ($appendsList as $prop) {
    echo "  $prop ... ";
    flush();
    try {
        $val = $pend->$prop;
        echo "OK (" . substr(var_export($val, true), 0, 50) . ")\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\nNow: Penduduk::first()...\n";
flush();
$p = \App\Models\Penduduk::first();
echo "OK: id=" . $p->id . " nama=" . $p->nama . "\n";

echo "\nDone!\n";
