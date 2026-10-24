<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

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

echo "Testing man_user query via DB facade:\n";
try {
    $results = \Illuminate\Support\Facades\DB::select("
        SELECT `tweb_desa_pamong`.`id_pend`, `pamong_id`, `pamong_nama`, `jabatan_id`, `ref_jabatan`.`jenis`, `ref_jabatan`.`nama` as `nama_jabatan`, `pamong_nip`, `pamong_niap`, `pamong_ttd`, `pamong_ub`, `pamong_status`, `pamong_nik`, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS pamong_nik, gelar_depan, gelar_belakang, IF(tweb_desa_pamong.status_pejabat = 1, CONCAT(\"Pj. \", ref_jabatan.nama), ref_jabatan.nama) AS pamong_jabatan 
        from `tweb_desa_pamong` 
        left join `tweb_penduduk` on `tweb_penduduk`.`id` = `tweb_desa_pamong`.`id_pend` 
        left join `ref_jabatan` on `ref_jabatan`.`id` = `tweb_desa_pamong`.`jabatan_id` 
        where `pamong_status` = 1 
        and `pamong_id` not in (SELECT `pamong_id` from `user` where `id` != 1 and `pamong_id` is not null) 
        and `tweb_desa_pamong`.`config_id` = 1
    ");
    echo "SUCCESS: Query executed successfully! Row count: " . count($results) . "\n";
    if (count($results) > 0) {
        echo "First row sample: " . json_encode($results[0]) . "\n";
    }
} catch (\Throwable $e) {
    echo "EXCEPTION CAPTURED: " . $e->getMessage() . "\n";
}
