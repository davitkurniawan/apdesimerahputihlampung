<?php
// scratch/test_identitas_instantiation.php
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
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

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

// Load autoloaders and database helper
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Register resolver and event dispatcher manually
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

if (class_exists('Illuminate\Events\Dispatcher')) {
    $dispatcher = new \Illuminate\Events\Dispatcher();
    \App\Models\Pamong::setEventDispatcher($dispatcher);
    \App\Models\Config::setEventDispatcher($dispatcher);
    \App\Models\Penduduk::setEventDispatcher($dispatcher);
}

// Config dynamic listener
\App\Models\Config::retrieved(function($model) {
    $model->id = 1;
    $model->setAttribute('id', 1);
    try {
        if (class_exists('App\Models\Pamong')) {
            $kades = \App\Models\Pamong::kepalaDesa()->first();
            if ($kades) {
                $model->setRelation('pamong', $kades);
            } else {
                $mock = new \App\Models\Pamong;
                $mock->pamong_nama = $model->nama_kepala_desa ?: 'Kepala Desa';
                $mock->pamong_nik = $model->nip_kepala_desa ?: '';
                $mock->pamong_id = 1;
                $mock->jabatan_id = 1;
                $mock->pamong_status = 1;
                $model->setRelation('pamong', $mock);
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

// Load CI core classes manually so we have CI environment loaded
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

if (!function_exists('get_instance')) {
    function &get_instance() {
        $ci = \CI_Controller::get_instance();
        return $ci;
    }
}

// Load MY_Controller and Admin_Controller
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';

// Load the target controller
require_once APPPATH . 'controllers/Identitas_desa.php';

try {
    echo "Instantiating Identitas_desa...\n";
    $controller = new Identitas_desa();
    echo "SUCCESS: Identitas_desa instantiated successfully!\n";
} catch (\Throwable $e) {
    echo "EXCEPTION CAPTURED: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
