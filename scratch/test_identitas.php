<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Boot index.php resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Injeksi composer view manual untuk testing
\Illuminate\Support\Facades\View::composer('*', function($view) {
    $ci = new stdClass();
    $ci->session = new class {
        public function userdata($k) { return null; }
    };
    $view->with('ci', $ci);
    $view->with('errors', new \Illuminate\Support\ViewErrorBag);
});

// Mock helpers needed by views
if (!function_exists('get_instance')) {
    function get_instance() {
        $ci = new stdClass();
        $ci->session = new class {
            public function userdata($k) { return null; }
        };
        $ci->load = new class {
            public function helper($name) {}
        };
        $ci->db = new class {
            public function select($s) { return $this; }
            public function from($f) { return $this; }
            public function where($w, $v = null) { return $this; }
            public function order_by($o, $d = null) { return $this; }
            public function get() {
                return new class {
                    public function result_array() { return []; }
                };
            }
        };
        return $ci;
    }
}
if (!function_exists('ci_route')) {
    function ci_route($name, $params = []) { return 'http://localhost/' . $name; }
}
if (!function_exists('cek_koneksi_internet')) {
    function cek_koneksi_internet() { return true; }
}
if (!function_exists('can')) {
    function can($permission) { return true; }
}
if (!function_exists('get_config')) {
    function &get_config() {
        static $c;
        if (!isset($c)) $c = ['base_url' => 'http://localhost/', 'index_page' => ''];
        return $c;
    }
}
if (!function_exists('SebutanDesa')) {
    function SebutanDesa($title = '') { return 'Desa'; }
}
if (!function_exists('favico_desa')) {
    function favico_desa() { return 'favicon.ico'; }
}
if (!function_exists('get_dynamic_title_page_from_path')) {
    function get_dynamic_title_page_from_path() { return 'Identitas Desa'; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('AmbilVersi')) {
    function AmbilVersi() { return '2606.0.0'; }
}
if (!function_exists('AmbilFoto')) {
    function AmbilFoto($f) { return $f; }
}

try {
    echo "Attempting to render admin.identitas_desa.index view...\n";
    
    $main = [
        'nama_desa' => 'Bumi Daya',
        'kode_desa' => '1801082003',
        'nama_kecamatan' => 'Palas',
        'nama_kabupaten' => 'Lampung Selatan',
        'nama_propinsi' => 'Lampung'
    ];
    
    $auth = new stdClass();
    $auth->nama = 'Administrator';
    $auth->foto = 'user.png';
    
    $output = \Illuminate\Support\Facades\View::make('admin.identitas_desa.index', [
        'main' => $main,
        'logo_path' => 'logo.png',
        'populasi' => 2450,
        'cek_profil_desa' => false,
        'auth' => $auth,
        'controller' => 'identitas_desa',
        'modul_ini' => 'identitas_desa',
        'sub_modul_ini' => 'identitas_desa',
        'kategori_pengaturan' => false,
        'menu_tree' => []
    ])->render();
    
    echo "SUCCESS! Rendered HTML length: " . strlen($output) . "\n";
    echo "First 500 characters of rendered output:\n";
    echo substr($output, 0, 500) . "\n...\n";
} catch (Throwable $e) {
    echo "ERROR: " . get_class($e) . "\n";
    echo "MESSAGE: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
