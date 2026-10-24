<?php
// scratch/check_kades_sekdes_relations.php
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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';

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

// Config dynamic listener
\App\Models\Config::retrieved(function($model) {
    try {
        if (class_exists('App\Models\Pamong')) {
            $kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
            if ($kades) {
                $model->setRelation('pamong', $kades);
            }
        }
    } catch (\Throwable $relErr) {}
});

// Pamong dynamic listener
\App\Models\Pamong::retrieved(function($model) {
    if (!array_key_exists('id_pend', $model->getAttributes())) {
        $model->setAttribute('id_pend', null);
    }
    if (empty($model->id_pend)) {
        $model->id_pend = 999999;
        $model->setAttribute('id_pend', 999999);
    }
    try {
        $pend = $model->penduduk;
        if (!$pend) {
            $pend = \App\Models\Penduduk::find($model->id_pend);
        }
        if ($pend) {
            $model->setRelation('penduduk', $pend);
        } else {
            $dummy = new \App\Models\Penduduk();
            $dummy->id = $model->id_pend;
            $dummy->config_id = $model->config_id ?: 1;
            $dummy->nama = $model->pamong_nama ?: 'Staff';
            $dummy->nik = $model->pamong_nik ?: '';
            
            $map = new \App\Models\PendudukMap();
            $map->id = $model->id_pend;
            $map->config_id = $model->config_id ?: 1;
            $dummy->setRelation('map', $map);
            $dummy->setRelation('lokasi', $map);
            
            $model->setRelation('penduduk', $dummy);
        }
    } catch (\Throwable $e) {}
});

// Penduduk dynamic listener
\App\Models\Penduduk::retrieved(function($model) {
    try {
        if ($model->id == 999999) {
            $map = new \App\Models\PendudukMap();
            $map->id = 999999;
            $map->config_id = $model->config_id ?: 1;
            $model->setRelation('map', $map);
            $model->setRelation('lokasi', $map);
        } else {
            $map = $model->map;
            if (!$map) {
                $target_id = $model->id;
                if ($model->kk_level != 1 && $model->id_kk) {
                    $kk = $model->keluarga;
                    if ($kk && $kk->nik_kepala) {
                        $target_id = $kk->nik_kepala;
                    }
                }
                $map = \App\Models\PendudukMap::where('id', $target_id)->first();
                if (!$map) {
                    $map = new \App\Models\PendudukMap();
                    $map->id = $target_id;
                    $map->config_id = $model->config_id ?: 1;
                    $map->lat = null;
                    $map->lng = null;
                }
                $model->setRelation('map', $map);
                $model->setRelation('lokasi', $map);
            }
        }
    } catch (\Throwable $e) {}
});

$pamong = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->leftJoin('ref_jabatan', 'tweb_desa_pamong.jabatan_id', '=', 'ref_jabatan.id')
    ->select('tweb_desa_pamong.*', 'ref_jabatan.nama as nama_jabatan')
    ->where('tweb_desa_pamong.pamong_status', '=', 1)
    ->orderBy('tweb_desa_pamong.urut', 'asc')
    ->get();

foreach ($pamong as $p) {
    print_r($p);
}
