<?php
// scratch/test_pamong_properties.php
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

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? '/' . $path : '');
    }
}
if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public $username = 'admin';
            public $id_grup = 1;
            public $grup = 1;
            public $batasi_wilayah = 0;
            public function user() { return $this; }
            public function check() { return true; }
        };
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Emulate retrieved listener from index.php
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
        }
    } catch (\Throwable $relErr) {}
});

$pamongs = \App\Models\Pamong::all();
foreach ($pamongs as $p) {
    echo "ID: " . $p->pamong_id . " | pamong_nama: '" . $p->pamong_nama . "' | id_pend: " . json_encode($p->id_pend) . "\n";
    echo "  -> \$p->nama: " . json_encode($p->nama) . "\n";
    echo "  -> \$p->nik: " . json_encode($p->nik) . "\n";
    echo "  -> \$p->penduduk->nama: " . ($p->penduduk ? json_encode($p->penduduk->nama) : 'NULL') . "\n";
}
