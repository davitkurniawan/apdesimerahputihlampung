<?php
define('FORCE_HTTPS', false);
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('LOKASI_CONFIG_DESA', '/var/www/html/desa/config/');

if (!function_exists('resource_path')) {
    function resource_path($path = '') { return FCPATH . 'resources/' . ltrim($path, '/'); }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') { return FCPATH . 'storage/' . ltrim($path, '/'); }
}
if (!function_exists('config_path')) {
    function config_path($path = '') { return FCPATH . 'config/' . ltrim($path, '/'); }
}
if (!function_exists('app_path')) {
    function app_path($path = '') { return FCPATH . 'app/' . ltrim($path, '/'); }
}
if (!function_exists('public_path')) {
    function public_path($path = '') { return FCPATH . 'public/' . ltrim($path, '/'); }
}
if (!function_exists('base_path')) {
    function base_path($path = '') { return FCPATH . ltrim($path, '/'); }
}
if (!function_exists('base_url')) {
    function base_url($uri = '') { return 'http://localhost:8080/' . $uri; }
}
if (!function_exists('is_super_admin')) {
    function is_super_admin() { return true; }
}
if (!function_exists('cache')) {
    function cache() {
        return new class {
            public function remember($key, $ttl, $callback) {
                return $callback();
            }
            public function rememberForever($key, $callback) {
                return $callback();
            }
            public function get($key) { return null; }
            public function put($key, $val, $ttl = null) {}
        };
    }
}

require_once '/var/www/html/xosid_emergency_autoload.php';
require_once '/var/www/html/vendor/autoload.php';
$app = require_once '/var/www/html/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);

// Mock DB and CI instance to avoid fatal errors
class DummyCI {
    public $db;
    public $session;
    public function __construct() {
        $this->session = new class {
            public function userdata($key) { 
                if ($key === 'user') return 1;
                if ($key === 'grup') return 1;
                return 1; 
            }
        };
        $this->db = new class {
            public function select($s) { return $this; }
            public function where($k, $v = null) { return $this; }
            public function get($t = null) { return $this; }
            public function order_by($c, $d = '') { return $this; }
            public function result_array() {
                try {
                    $rows = \Illuminate\Support\Facades\DB::table('setting_modul')
                        ->where('aktif', 1)
                        ->orderBy('urut')
                        ->get();
                    $arr = [];
                    foreach ($rows as $r) {
                        $arr[] = (array)$r;
                    }
                    return $arr;
                } catch (\Throwable $e) {
                    return [];
                }
            }
            public function result() { return []; }
        };
    }
}
if (!function_exists('get_instance')) {
    function get_instance() {
        static $inst;
        if (!$inst) $inst = new DummyCI();
        return $inst;
    }
}

require_once APPPATH . 'helpers/general_helper.php';

$menu = admin_menu();
foreach ($menu as $m) {
    echo "Modul: {$m['modul']} | Slug: {$m['slug']} | URL: {$m['url']} | Ikon: {$m['ikon']}\n";
    if (isset($m['childrens'])) {
        foreach ($m['childrens'] as $c) {
            echo "  - Sub: {$c['modul']} | Slug: {$c['slug']} | URL: {$c['url']} | Ikon: {$c['ikon']}\n";
        }
    }
}
