<?php
// scratch/test_raw_query.php
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

$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

$conn = \Illuminate\Support\Facades\DB::connection();
$sql = "SELECT `tweb_desa_pamong`.`id_pend`, `pamong_id`, `pamong_nama`, `jabatan_id`, `ref_jabatan`.`jenis`, `ref_jabatan`.`nama` as `nama_jabatan`, `pamong_nip`, `pamong_niap`, `pamong_ttd`, `pamong_ub`, `pamong_status`, `pamong_nik`, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nama, tweb_penduduk.nama) AS pamong_nama, IF(tweb_desa_pamong.id_pend IS NULL, tweb_desa_pamong.pamong_nik, tweb_penduduk.nik) AS pamong_nik, gelar_depan, gelar_belakang, IF(tweb_desa_pamong.status_pejabat = 1, CONCAT(\"Desa \", ref_jabatan.nama), ref_jabatan.nama) AS pamong_jabatan from `tweb_desa_pamong` left join `tweb_penduduk` on `tweb_penduduk`.`id` = `tweb_desa_pamong`.`id_pend` left join `ref_jabatan` on `ref_jabatan`.`id` = `tweb_desa_pamong`.`jabatan_id` where `jabatan_id` = ? and `pamong_status` = ? and `tweb_desa_pamong`.`config_id` = ? limit 1";

$results = $conn->select($sql, [1, 1, 1]);
echo "KADES RESULTS COUNT: " . count($results) . "\n";
echo "KADES RESULTS: " . json_encode($results) . "\n";

$results_sek = $conn->select($sql, [2, 1, 1]);
echo "SEKDES RESULTS COUNT: " . count($results_sek) . "\n";
echo "SEKDES RESULTS: " . json_encode($results_sek) . "\n";

// Let's print ref_jabatan table contents as well!
echo "\nref_jabatan table contents:\n";
$ref_jabatan = $conn->select("select id, nama, jenis from ref_jabatan");
foreach ($ref_jabatan as $rj) {
    echo "id: {$rj->id} | nama: {$rj->nama} | jenis: {$rj->jenis}\n";
}
