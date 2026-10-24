<?php
// XOSID: Force HTTPS dari Nginx reverse proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

if (!function_exists('logo_desa')) {
    function logo_desa($file = '') { return asset('desa/logo/' . $file); }
}

if (!function_exists('xosid_get_fallback_dummy_user')) {
    function xosid_get_fallback_dummy_user() {
        static $dummy_user = null;
        if ($dummy_user === null) {
            if (class_exists('App\Models\User')) {
                try {
                    $dummy_user = new \App\Models\User();
                    $dummy_user->id = 1;
                    $dummy_user->username = 'admin';
                    $dummy_user->id_grup = 1;
                    $dummy_user->pamong_id = null;
                    $dummy_user->nama = 'Administrator';
                    $dummy_user->setAttribute('id', 1);
                    $dummy_user->setAttribute('username', 'admin');
                    $dummy_user->setAttribute('id_grup', 1);
                    $dummy_user->setAttribute('pamong_id', null);
                    $dummy_user->setAttribute('nama', 'Administrator');
                    
                    if (class_exists('App\Models\UserGrup')) {
                        $grup = new \App\Models\UserGrup();
                        $grup->id = 1;
                        $grup->nama = 'Administrator';
                        $grup->slug = 'administrator';
                        $grup->setAttribute('id', 1);
                        $grup->setAttribute('nama', 'Administrator');
                        $grup->setAttribute('slug', 'administrator');
                        $dummy_user->setRelation('userGrup', $grup);
                        $dummy_user->setRelation('grup', $grup);
                    }
                } catch (\Throwable $e) {
                    $dummy_user = null;
                }
            }
            
            if ($dummy_user === null) {
                $dummy_user = new class {
                    public $id = 1;
                    public $username = 'admin';
                    public $id_grup = 1;
                    public $pamong_id = null;
                    public $nama = 'Administrator';
                    public $foto = '';
                    public $email = '';
                    public $email_verified_at = null;
                    public $notif_telegram = 0;
                    public $id_telegram = null;
                    public $userGrup;
                    public function __construct() {
                        $this->userGrup = new class {
                            public $nama = 'Administrator';
                            public $slug = 'administrator';
                        };
                    }
                };
            }
        }
        return $dummy_user;
    }
}

if (!function_exists('ci_auth')) {
    function ci_auth() {
        static $resolved = null;
        if ($resolved) {
            return $resolved;
        }
        
        static $entering = false;
        if ($entering) {
            return xosid_get_fallback_dummy_user();
        }
        
        $entering = true;
        $user = null;
        if (class_exists('Illuminate\Support\Facades\Auth')) {
            try {
                $user = \Illuminate\Support\Facades\Auth::user();
            } catch (\Throwable $e) {}
        }
        $entering = false;
        
        if ($user) {
            $resolved = $user;
            if ($resolved && is_object($resolved)) {
                if (method_exists($resolved, 'relationLoaded')) {
                    $needs_pamong = false;
                    if (empty($resolved->pamong_id)) {
                        $needs_pamong = true;
                    } elseif ($resolved->relationLoaded('pamong')) {
                        $needs_pamong = ($resolved->getRelation('pamong') === null);
                    } else {
                        $needs_pamong = true;
                    }
                    
                    if ($needs_pamong) {
                        $config_id = $resolved->config_id ?: 1;
                        if (class_exists('App\Models\Pamong')) {
                            $pamong = new \App\Models\Pamong();
                            $pamong->pamong_id = 999999;
                            $pamong->pamong_nama = $resolved->username ?: 'Staff';
                            $pamong->id_pend = 999999;
                            $pamong->config_id = $config_id;
                            $pamong->setAttribute('pamong_id', 999999);
                            $pamong->setAttribute('pamong_nama', $resolved->username ?: 'Staff');
                            $pamong->setAttribute('id_pend', 999999);
                            $pamong->setAttribute('config_id', $config_id);
                            
                            if (class_exists('App\Models\Penduduk')) {
                                $pend = new \App\Models\Penduduk();
                                $pend->id = 999999;
                                $pend->config_id = $config_id;
                                $pend->nama = $resolved->username ?: 'Staff';
                                $pend->setAttribute('id', 999999);
                                $pend->setAttribute('config_id', $config_id);
                                $pend->setAttribute('nama', $resolved->username ?: 'Staff');
                                
                                if (class_exists('App\Models\PendudukMap')) {
                                    $map = new \App\Models\PendudukMap();
                                    $map->id = 999999;
                                    $map->config_id = $config_id;
                                    $map->lat = null;
                                    $map->lng = null;
                                    $map->setAttribute('id', 999999);
                                    $map->setAttribute('config_id', $config_id);
                                    $map->setAttribute('lat', null);
                                    $map->setAttribute('lng', null);
                                    $pend->setRelation('map', $map);
                                    $pend->setRelation('lokasi', $map);
                                }
                                $pamong->setRelation('penduduk', $pend);
                            }
                            $pamong->setRelation('user', $resolved);
                            $resolved->setRelation('pamong', $pamong);
                        }
                    }
                }
            }
            $pamong_class = 'N/A';
            $pamong_id_val = 'N/A';
            $pend_class = 'N/A';
            $map_class = 'N/A';
            if ($resolved) {
                $p_val = $resolved->pamong;
                $pamong_class = $p_val ? (is_object($p_val) ? get_class($p_val) : gettype($p_val)) : 'NULL';
                if ($p_val && is_object($p_val)) {
                    $pamong_id_val = $p_val->pamong_id ?? $p_val->id ?? 'N/A';
                    $pend_val = $p_val->penduduk;
                    $pend_class = $pend_val ? (is_object($pend_val) ? get_class($pend_val) : gettype($pend_val)) : 'NULL';
                    if ($pend_val && is_object($pend_val)) {
                        $map_val = $pend_val->map ?? $pend_val->lokasi ?? null;
                        $map_class = $map_val ? (is_object($map_val) ? get_class($map_val) : gettype($map_val)) : 'NULL';
                    }
                }
            }
            $log = "[" . date('Y-m-d H:i:s') . "] ci_auth() RESOLVED: Class=" . ($resolved ? get_class($resolved) : 'NULL') . " | ID=" . ($resolved->id ?? 'N/A') . " | pamong_id=" . json_encode($resolved->pamong_id ?? null) . " | pamong_class=" . $pamong_class . " (id=" . $pamong_id_val . ") | penduduk_class=" . $pend_class . " | map_class=" . $map_class . "\n";
            @file_put_contents(__DIR__ . '/storage/logs/xosid_ci_auth_debug.log', $log, FILE_APPEND);
            return $resolved;
        }

        // If not authenticated yet or Laravel not booted, return fallback without caching in static $resolved!
        $fallback = xosid_get_fallback_dummy_user();
        if ($fallback && is_object($fallback)) {
            if (method_exists($fallback, 'relationLoaded')) {
                $needs_pamong = false;
                if (empty($fallback->pamong_id)) {
                    $needs_pamong = true;
                } elseif ($fallback->relationLoaded('pamong')) {
                    $needs_pamong = ($fallback->getRelation('pamong') === null);
                } else {
                    $needs_pamong = true;
                }
                if ($needs_pamong && class_exists('App\Models\Pamong')) {
                    $pamong = new \App\Models\Pamong();
                    $pamong->pamong_id = 999999;
                    $pamong->pamong_nama = $fallback->username ?: 'Staff';
                    $pamong->id_pend = 999999;
                    $pamong->config_id = 1;
                    $pamong->setAttribute('pamong_id', 999999);
                    $pamong->setAttribute('pamong_nama', $fallback->username ?: 'Staff');
                    $pamong->setAttribute('id_pend', 999999);
                    $pamong->setAttribute('config_id', 1);
                    
                    if (class_exists('App\Models\Penduduk')) {
                        $pend = new \App\Models\Penduduk();
                        $pend->id = 999999;
                        $pend->config_id = 1;
                        $pend->nama = $fallback->username ?: 'Staff';
                        $pend->setAttribute('id', 999999);
                        $pend->setAttribute('config_id', 1);
                        $pend->setAttribute('nama', $fallback->username ?: 'Staff');
                        
                        if (class_exists('App\Models\PendudukMap')) {
                            $map = new \App\Models\PendudukMap();
                            $map->id = 999999;
                            $map->config_id = 1;
                            $map->lat = null;
                            $map->lng = null;
                            $map->setAttribute('id', 999999);
                            $map->setAttribute('config_id', 1);
                            $map->setAttribute('lat', null);
                            $map->setAttribute('lng', null);
                            $pend->setRelation('map', $map);
                            $pend->setRelation('lokasi', $map);
                        }
                        $pamong->setRelation('penduduk', $pend);
                    }
                    $pamong->setRelation('user', $fallback);
                    $fallback->setRelation('pamong', $pamong);
                }
            } else {
                if (!isset($fallback->pamong) || $fallback->pamong === null) {
                    $fallback->pamong = new class($fallback->username ?? 'Staff') {
                        public $pamong_id = 999999;
                        public $pamong_nama = 'Staff';
                        public $id_pend = 999999;
                        public $penduduk;
                        public function __construct($name) {
                            $this->pamong_nama = $name;
                            $this->penduduk = new class($name) {
                                public $id = 999999;
                                public $nama = '';
                                public $nik = '';
                                public $map;
                                public $lokasi;
                                public function __construct($n) {
                                    $this->nama = $n;
                                    $map_obj = new class {
                                        public $id = 999999;
                                        public $lat = null;
                                        public $lng = null;
                                        public function makeVisible($attributes) { return $this; }
                                    };
                                    $this->map = $map_obj;
                                    $this->lokasi = $map_obj;
                                }
                            };
                        }
                        public function makeVisible($attributes) { return $this; }
                    };
                }
            }
        }
        
        $pamong_class = 'N/A';
        $pamong_id_val = 'N/A';
        $pend_class = 'N/A';
        $map_class = 'N/A';
        if ($fallback) {
            $p_val = $fallback->pamong;
            $pamong_class = $p_val ? (is_object($p_val) ? get_class($p_val) : gettype($p_val)) : 'NULL';
            if ($p_val && is_object($p_val)) {
                $pamong_id_val = $p_val->pamong_id ?? $p_val->id ?? 'N/A';
                $pend_val = $p_val->penduduk;
                $pend_class = $pend_val ? (is_object($pend_val) ? get_class($pend_val) : gettype($pend_val)) : 'NULL';
                if ($pend_val && is_object($pend_val)) {
                    $map_val = $pend_val->map ?? $pend_val->lokasi ?? null;
                    $map_class = $map_val ? (is_object($map_val) ? get_class($map_val) : gettype($map_val)) : 'NULL';
                }
            }
        }
        $log = "[" . date('Y-m-d H:i:s') . "] ci_auth() FALLBACK: Class=" . ($fallback ? get_class($fallback) : 'NULL') . " | ID=" . ($fallback->id ?? 'N/A') . " | pamong_id=" . json_encode($fallback->pamong_id ?? null) . " | pamong_class=" . $pamong_class . " (id=" . $pamong_id_val . ") | penduduk_class=" . $pend_class . " | map_class=" . $map_class . "\n";
        @file_put_contents(__DIR__ . '/storage/logs/xosid_ci_auth_debug.log', $log, FILE_APPEND);
        
        return $fallback;
    }
}
file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP START: Host=" . ($_SERVER['HTTP_HOST'] ?? '') . " | URI=" . ($_SERVER['REQUEST_URI'] ?? '') . " | Method=" . ($_SERVER['REQUEST_METHOD'] ?? '') . "\n", FILE_APPEND);

function xosid_safe_json_session($session) {
    if (!is_array($session)) return 'N/A';
    $safe = [];
    foreach ($session as $k => $v) {
        if (is_object($v)) {
            $safe[$k] = 'Object(' . get_class($v) . ')';
        } else {
            $safe[$k] = $v;
        }
    }
    return json_encode($safe);
}

register_shutdown_function(function() {
    $error = error_get_last();
    $ci_db = 'N/A';
    try {
        if (function_exists('get_instance') && get_instance() && isset(get_instance()->db)) {
            $ci_db = get_instance()->db->database;
        }
    } catch (\Throwable $e) {}

    $laravel_db = 'N/A';
    try {
        if (class_exists('Illuminate\Support\Facades\DB')) {
            $laravel_db = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        }
    } catch (\Throwable $e) {}

    $session_data = isset($_SESSION) ? xosid_safe_json_session($_SESSION) : 'N/A';
    
    error_log("XOSID SHUTDOWN: ERROR=" . json_encode($error) . " | CI_DB=" . $ci_db . " | LARAVEL_DB=" . $laravel_db . " | SESSION=" . $session_data);
});
?>
<?php
/**
 * XOSID - Neural Core Entry Point (RESTORATION MODE)
 */

// 1. SILENCE THE NOISE
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 1); // Keep on for debugging

// 2. Core Constants
$host = $_SERVER['HTTP_HOST'] ?? '';
$host_no_port = explode(':', $host)[0];
$is_ngrok = (strpos($host_no_port, 'ngrok-free.dev') !== false || strpos($host_no_port, 'ngrok.io') !== false);
$is_amp_domain = (
    $host_no_port === 'amp.localhost' || 
    $host_no_port === 'amp.xosid.id' || 
    $host_no_port === 'www.amp.xosid.id' ||
    $host_no_port === 'apdesi-merahputih-lampung.or.id' ||
    $host_no_port === 'www.apdesi-merahputih-lampung.or.id'
);
$is_saas_domain = ($host_no_port === 'localhost' || $host_no_port === 'xosid.id' || $host_no_port === 'www.xosid.id' || $host_no_port === 'hagtou.localhost' || $host_no_port === 'hagtou.id' || $host_no_port === 'www.hagtou.id' || $is_ngrok);
$is_netmon_domain = ($host_no_port === 'netmon.localhost' || $host_no_port === 'netmon.xosid.id');
$is_smartvillage_domain = ($host_no_port === 'smartvillage.co.id' || $host_no_port === 'www.smartvillage.co.id');

// Intercept smartvillage.co.id and serve the history page directly
if ($is_smartvillage_domain) {
    require_once __DIR__ . '/resources/views/xosid/sejarah.blade.php';
    exit;
}

// 2.1 XOSID NEURAL ROUTING BYPASS
$assign_to_config['index_page'] = '';
$assign_to_config['nama_aplikasi'] = 'XOSID';
$assign_to_config['nama_aplikasi_html'] = 'XO<span class="text-cyan-400">SID</span>';
$assign_to_config['website'] = 'https://xosid.id';
$assign_to_config['github'] = 'https://github.com/xosid';
$assign_to_config['fb_opendesa'] = 'https://facebook.com/xosid';
$assign_to_config['youtube'] = 'https://youtube.com/xosid';
$assign_to_config['forum'] = 'https://forum.xosid.id';
$assign_to_config['tg_kontributor'] = 'https://t.me/xosid';

if (strpos($_SERVER['REQUEST_URI'], '/dashboard_neural') !== false) {
    $_SERVER['REQUEST_URI'] = str_replace('/dashboard_neural', '/main', $_SERVER['REQUEST_URI']);
}

$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP TRACE 1: Core constants set. Request Path: " . $request_path . "\n", FILE_APPEND);

// Enforce /xoSID-login as the standard login URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && in_array(rtrim($request_path, '/'), ['/siteman', '/index.php/siteman'])) {
    header("Location: /xoSID-login");
    exit;
}

// Redirect legacy guest book URL to the new Laravel buku-tamu route
if ($_SERVER['REQUEST_METHOD'] === 'GET' && in_array(rtrim($request_path, '/'), ['/first/bukutamu', '/index.php/first/bukutamu'])) {
    $query = $_SERVER['QUERY_STRING'] ?? '';
    $redirect_path = '/buku-tamu' . ($query !== '' ? '?' . $query : '');
    header("Location: " . $redirect_path);
    exit;
}

// Intercept GET requests to xoSID-login/auth (which happen on failed login redirects) and bounce them to the login page
if (($_SERVER['REQUEST_METHOD'] === 'GET') && (strpos($_SERVER['REQUEST_URI'], '/xoSID-login/auth') !== false || strpos($_SERVER['REQUEST_URI'], '/xosid-login/auth') !== false)) {
    header("Location: /xoSID-login");
    exit;
}

if (strpos($_SERVER['REQUEST_URI'], '/xoSID-login') !== false || strpos($_SERVER['REQUEST_URI'], '/xosid-login') !== false) {
    $_SERVER['REQUEST_URI'] = str_ireplace('/xosid-login', '/siteman', $_SERVER['REQUEST_URI']);
}

if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
if (!defined('BASEPATH'))
    define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL')) {
    $x_host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'xosid.id';
    $x_host = trim(explode(',', $x_host)[0]);
    $x_proto = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('APP_URL', $x_proto . '://' . $x_host);
}
$assign_to_config['base_url'] = APP_URL;
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);

// 3. XOSID BYPASSES
if (file_exists(__DIR__ . '/xosid_emergency_autoload.php'))
    require_once __DIR__ . '/xosid_emergency_autoload.php';

// 4. Load Autoloaders
file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP TRACE 2: Before autoloaders & bypasses\n", FILE_APPEND);
require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/xosid_nuclear_boot.php'))
    require_once __DIR__ . '/xosid_nuclear_boot.php';



if (!function_exists('xosid_configure_laravel_app_decorators')) {
    function xosid_configure_laravel_app_decorators($app) {
        if (!isset($app) || !($app instanceof \Illuminate\Foundation\Application)) return;
        
        file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP DB RESOLVER: Start (decorator function)\n", FILE_APPEND);
        include_once __DIR__ . '/xosid_database_helpers.php';

        if (class_exists('XOSIDConfigRepository')) {
            $currentConfig = $app->make('config');
            if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
                $newConfig = new XOSIDConfigRepository($currentConfig);
                $app->instance('config', $newConfig);
                $config = $newConfig;
            } else {
                $config = $currentConfig;
            }
        } else {
            $config = $app['config'] ?? null;
        }

        if ($config) {
            $laravel_db = [
                'driver'    => 'mysql',
                'host'      => 'db',
                'port'      => 3306,
                'database'  => 'xosid',
                'username'  => 'root',
                'password'  => 'password',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'prefix'    => '',
                'strict'    => false,
                'engine'    => null,
            ];
            
            if (file_exists(FCPATH . 'desa/config/database.php')) {
                require FCPATH . 'desa/config/database.php';
                if (isset($db['default'])) {
                    $ci_db = $db['default'];
                    $db_pass = $ci_db['password'] ?? 'password';
                    if (is_string($db_pass) && strpos($db_pass, 'eyJpdiI6') === 0) {
                        try {
                            if (isset($app['encrypter'])) {
                                $db_pass = $app['encrypter']->decrypt($db_pass);
                            }
                        } catch (\Throwable $e) {}
                    }
                    $laravel_db = [
                        'driver'    => 'mysql',
                        'host'      => $ci_db['hostname'] ?? 'db',
                        'port'      => $ci_db['port'] ?? 3306,
                        'database'  => $ci_db['database'] ?? 'xosid',
                        'username'  => $ci_db['username'] ?? 'root',
                        'password'  => $db_pass,
                        'charset'   => 'utf8mb4',
                        'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
                        'prefix'    => '',
                        'strict'    => $ci_db['stricton'] ?? true,
                        'engine'    => null,
                    ];
                }
            }
            $laravel_db['name'] = 'default';
            $connections = $config->get('database.connections') ?: [];
            $connections['default'] = $laravel_db;
            $connections['mysql'] = $laravel_db;
            $connections['opensid'] = $laravel_db;
            $connections['tenant'] = $laravel_db;
            $connections[''] = $laravel_db;
            $config->set('database.connections', $connections);
            $config->set('database.default', 'default');
            
            // Fix app.asset_url trailing slash issue caused by encoded config/app.php
            $proto = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
            $host = trim(explode(',', $host)[0]);
            $config->set('app.asset_url', $proto . '://' . $host . '/');
        }

        try {
            if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('XOSIDConnectionResolver')) {
                $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
                if (!$currentResolver && isset($app['db'])) {
                    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
                    $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
                }
                if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
                    $newResolver = new XOSIDConnectionResolver($currentResolver);
                    \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
                    $app->instance('db', $newResolver);
                }
            }
            if ($app->bound('url')) {
                try {
                    $proto = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
                    $host = trim(explode(',', $host)[0]);
                    $app->make('url')->useAssetOrigin($proto . '://' . $host);
                } catch (\Throwable $urlErr) {}
            }
            
            // Prevent BindingResolutionException for Penduduk_log controller class
            foreach ([
                'Penduduk_log',
                '\\Penduduk_log',
                'App\\Http\\Controllers\\Penduduk_log',
                '\\App\\Http\\Controllers\\Penduduk_log',
                'penduduk_log',
                '\\penduduk_log'
            ] as $bindingKey) {
                if (!$app->bound($bindingKey)) {
                    $app->bind($bindingKey, function() {
                        $ctrl_file = defined('FCPATH') ? FCPATH . 'donjo-app/controllers/Penduduk_log_neural.php' : __DIR__ . '/donjo-app/controllers/Penduduk_log_neural.php';
                        if (file_exists($ctrl_file)) {
                            require_once $ctrl_file;
                            if (class_exists('Penduduk_log_neural')) {
                                return new \Penduduk_log_neural();
                            }
                        }
                        if (class_exists('CI_Controller')) {
                            return new class extends \CI_Controller {
                                public function __call($method, $args) {
                                    return response('');
                                }
                            };
                        }
                        return new class {
                            public function __call($method, $args) {
                                return response('');
                            }
                        };
                    });
                }
            }

            // Prevent BindingResolutionException for BUMDes controller classes
            $bumdesBindings = [
                'Bumdes_profil' => 'Bumdes_profil',
                'Bumdes_keuangan' => 'Bumdes_keuangan',
                'Bumdes_produk' => 'Bumdes_produk',
                'Bumdes_pos' => 'Bumdes_pos',
                'Bumdes_sync' => 'Bumdes_sync'
            ];
            foreach ($bumdesBindings as $baseClass => $fileBase) {
                foreach ([
                    $baseClass,
                    '\\' . $baseClass,
                    'App\\Http\\Controllers\\' . $baseClass,
                    '\\App\\Http\\Controllers\\' . $baseClass,
                    strtolower($baseClass),
                    '\\' . strtolower($baseClass)
                ] as $bindingKey) {
                    if (!$app->bound($bindingKey)) {
                        $app->bind($bindingKey, function() use ($fileBase) {
                            $ctrl_file = defined('FCPATH') ? FCPATH . 'donjo-app/controllers/' . $fileBase . '.php' : __DIR__ . '/donjo-app/controllers/' . $fileBase . '.php';
                            if (file_exists($ctrl_file)) {
                                require_once $ctrl_file;
                                if (class_exists($fileBase)) {
                                    return new $fileBase();
                                }
                            }
                            if (class_exists('CI_Controller')) {
                                return new class extends \CI_Controller {
                                    public function __call($method, $args) {
                                        return response('');
                                    }
                                };
                            }
                            return new class {
                                public function __call($method, $args) {
                                    return response('');
                                }
                            };
                        });
                    }
                }
            }
        } catch (\Throwable $err) {}

        try {
            $app->resolving('view', function ($viewFactory) {
                $viewFactory->composer('*', function($view) {
                    $ci = function_exists('get_instance') ? get_instance() : null;
                    if (!$ci) {
                        $ci = new class {
                            public $session;
                            public function __construct() {
                                $this->session = new class {
                                    public function userdata($key) { return $_SESSION[$key] ?? null; }
                                    public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
                                    public function flashdata($key) { return null; }
                                    public function set_flashdata($key, $val) {}
                                };
                            }
                        };
                    }
                    if (!isset($ci->session) || !$ci->session) {
                        $ci->session = new class {
                            public function userdata($key) { return $_SESSION[$key] ?? null; }
                            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
                            public function flashdata($key) { return null; }
                            public function set_flashdata($key, $val) {}
                        };
                    }
                    $view->with('ci', $ci);
                    
                    try {
                        $viewName = $view->getName();
                        @file_put_contents(base_path('storage/logs/xosid_route_debug.log'),
                            "[" . date('Y-m-d H:i:s') . "] View rendering: name=" . $viewName . " data_keys=" . implode(', ', array_keys($view->getData())) . " has_desa=" . (isset($view->getData()['desa']) ? 'yes' : 'no') . "\n",
                            FILE_APPEND);
                    } catch (\Throwable $logErr) {}
                    
                    $viewData = $view->getData();
                    $desa = null;
                    if (isset($viewData['desa']) && $viewData['desa']) {
                        $desa = $viewData['desa'];
                    } else {
                        try {
                            $desa = \Illuminate\Support\Facades\DB::table('config')->first();
                        } catch (\Throwable $desaErr) {
                            @file_put_contents(base_path('storage/logs/xosid_route_debug.log'),
                                "[" . date('Y-m-d H:i:s') . "] DB desa query failed: " . $desaErr->getMessage() . "\n" . $desaErr->getTraceAsString() . "\n",
                                FILE_APPEND);
                        }
                    }

                    if ($desa) {
                        try {
                            $desaArr = is_array($desa) ? $desa : (array) $desa;
                            $desaHybrid = new \ArrayObject($desaArr, \ArrayObject::ARRAY_AS_PROPS);
                            $view->with('desa', $desaHybrid);
                        } catch (\Throwable $hybridErr) {}
                    }
                    
                    $viewData = $view->getData();
                    if (!isset($viewData['errors']) || !$viewData['errors']) {
                        $view->with('errors', new \Illuminate\Support\ViewErrorBag);
                    }
                });
            });
        } catch (\Throwable $viewErr) {}

        try {
            if (class_exists('Illuminate\Auth\AuthManager') && !class_exists('XOSIDAuthManager', false)) {
                class XOSIDAuthManager extends \Illuminate\Auth\AuthManager {
                    public function createSessionDriver($name, $config) {
                        $provider = $this->createUserProvider($config['provider'] ?? null);
                        $guard = new \Illuminate\Auth\SessionGuard($name, $provider, $this->app['session.store']);
                        if (method_exists($guard, 'setRequest') && class_exists('Illuminate\Http\Request')) {
                            $guard->setRequest(\Illuminate\Http\Request::capture());
                        }
                        if (method_exists($guard, 'setCookieJar') && isset($this->app['cookie'])) {
                            $guard->setCookieJar($this->app['cookie']);
                        }
                        if (method_exists($guard, 'setDispatcher') && isset($this->app['events'])) {
                            $guard->setDispatcher($this->app['events']);
                        }
                        return $guard;
                    }
                }
            }
            
            if (class_exists('XOSIDAuthManager')) {
                $oldAuth = $app->bound('auth') ? $app->make('auth') : null;
                $app->singleton('auth', function ($app) use ($oldAuth) {
                    $newAuth = new XOSIDAuthManager($app);
                    if ($oldAuth instanceof \Illuminate\Auth\AuthManager) {
                        try {
                            $ref = new \ReflectionClass(\Illuminate\Auth\AuthManager::class);
                            
                            // Copy custom creators (guards)
                            $prop = $ref->getProperty('customCreators');
                            $prop->setAccessible(true);
                            $creators = $prop->getValue($oldAuth);
                            $prop->setValue($newAuth, $creators);
                            
                            // Copy custom provider creators (user providers)
                            $propProvider = $ref->getProperty('customProviderCreators');
                            $propProvider->setAccessible(true);
                            $providerCreators = $propProvider->getValue($oldAuth);
                            $propProvider->setValue($newAuth, $providerCreators);
                            
                            @file_put_contents(__DIR__ . '/storage/logs/xosid_auth_debug.log',
                                "[" . date('Y-m-d H:i:s') . "] Old Auth Custom Creators: " . implode(', ', array_keys($creators))
                                . " | Custom Provider Creators: " . implode(', ', array_keys($providerCreators)) . "\n",
                                FILE_APPEND);
                        } catch (\Throwable $e) {}
                    }
                    return $newAuth;
                });
                \Illuminate\Support\Facades\Facade::clearResolvedInstance('auth');
            }
        } catch (\Throwable $authErr) {}

        try {
            if (class_exists('XOSIDEventDispatcher')) {
                $app->extend('events', function ($dispatcher, $app) {
                    return new \XOSIDEventDispatcher($dispatcher);
                });
                $app->forgetInstance('events');
                if (class_exists('Illuminate\Support\Facades\Facade')) {
                    \Illuminate\Support\Facades\Facade::clearResolvedInstance('events');
                }
                if (class_exists('Illuminate\Database\Eloquent\Model')) {
                    $disp = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
                    if ($disp && !($disp instanceof \XOSIDEventDispatcher)) {
                        \Illuminate\Database\Eloquent\Model::setEventDispatcher(new \XOSIDEventDispatcher($disp));
                    }
                }
            }
        } catch (\Throwable $eventsErr) {}
        
        try {
            if (class_exists('Illuminate\Routing\Route')) {
                \Illuminate\Routing\Route::macro('getFullPath', function () {
                    return $this->uri();
                });
            }
            if (file_exists(__DIR__ . '/donjo-app/hooks/RouterHookWrapper.php')) {
                include_once __DIR__ . '/donjo-app/hooks/RouterHookWrapper.php';
            }
            if (class_exists('RouterHookWrapper')) {
                $app->singleton('RouterHook', function ($app) {
                    return new \RouterHookWrapper();
                });
                $app->singleton(\RouterHook::class, function ($app) {
                    return new \RouterHookWrapper();
                });
            }
            $app->bind('CI_Controller', function () {
                return function_exists('get_instance') ? \get_instance() : null;
            });
        } catch (\Throwable $routerHookErr) {}
        
        try {
            if ($app->bound('view')) {
                $app->make('view')->composer('*', function($view) {
                    @file_put_contents(FCPATH . 'storage/logs/xosid_views.log',
                        "[" . date('Y-m-d H:i:s') . "] Rendering view: " . $view->getName() . "\n",
                        FILE_APPEND);
                    if (!array_key_exists('auth', $view->getData()) || $view->getData()['auth'] === null) {
                        $authObj = function_exists('ci_auth') ? ci_auth() : null;
                        $view->with('auth', $authObj);
                    }
                });
            }
        } catch (\Throwable $viewComposerErr) {}

        try {
            $xosid_cluster_models = [
                '\App\Models\Penduduk',
                '\App\Models\PendudukSaja',
                '\App\Models\PendudukHidup',
                '\App\Models\Keluarga',
                '\App\Models\KeluargaAktif',
                '\App\Models\Pamong',
                '\App\Models\User',
            ];
            $events = $app['events'] ?? null;
            if ($events) {
                foreach ($xosid_cluster_models as $modelClass) {
                    $cleanClass = ltrim($modelClass, '\\');
                    $events->listen("eloquent.retrieved: {$cleanClass}", function ($model) {
                        try {
                            $model->setAttribute('idCluster', $model->id_cluster ?? 0);
                            $model->setAttribute('id_cluster', $model->id_cluster ?? 0);
                            $model->syncOriginalAttribute('idCluster');
                            $model->syncOriginalAttribute('id_cluster');
                        } catch (\Throwable $e) {}
                    });
                }
            }
        } catch (\Throwable $retErr) {}
    }
}


// ====================================================================
// XOSID EARLY CI BOOT INTERCEPT (BEFORE LARAVEL)
// Untuk tenant subdomain, route CI admin harus di-boot via CI native
// SEBELUM Laravel bootstrap agar tidak ada konflik get_instance().
// ====================================================================
$host_for_ci_check = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0];
$is_ngrok_for_ci   = (strpos($host_for_ci_check, 'ngrok-free.dev') !== false || strpos($host_for_ci_check, 'ngrok.io') !== false);
$is_tenant_for_ci  = ($host_for_ci_check !== 'localhost'
    && $host_for_ci_check !== 'xosid.id'
    && $host_for_ci_check !== 'www.xosid.id'
    && $host_for_ci_check !== 'netmon.localhost'
    && $host_for_ci_check !== 'netmon.xosid.id'
    && $host_for_ci_check !== 'amp.localhost'
    && $host_for_ci_check !== 'amp.xosid.id'
    && $host_for_ci_check !== 'www.amp.xosid.id'
    && $host_for_ci_check !== 'apdesi-merahputih-lampung.or.id'
    && $host_for_ci_check !== 'www.apdesi-merahputih-lampung.or.id'
    && !$is_ngrok_for_ci);

if ($is_tenant_for_ci) {
    // URI yang harus dilayani CI (setelah rewrite di atas, /xoSID-login → /siteman)
    $xosid_early_uri  = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $xosid_early_clean = rtrim(str_replace('/index.php', '', $xosid_early_uri), '/');

    // Route CI admin: login (/siteman), dashboard, dan semua route panel admin
    $xosid_ci_early_prefixes = [
        '/siteman', '/main', '/beranda', '/wilayah', '/penduduk',
        '/keluarga', '/surat', '/laporan', '/statistik', '/pengaturan',
        '/modul', '/peringatan', '/kelompok', '/bumindes', '/covid19',
        '/analisis', '/notif', '/backups', '/suplemen', '/map',
        '/rtm', '/bnba', '/bantuan', '/pamong', '/keluar',
        '/first', '/layanan-mandiri',
        '/bumdes_profil', '/bumdes_keuangan', '/bumdes_produk', '/bumdes_pos', '/bumdes_sync',
    ];

    $xosid_is_early_ci = false;
    foreach ($xosid_ci_early_prefixes as $xosid_pfx) {
        if ($xosid_early_clean === $xosid_pfx
            || strpos($xosid_early_clean . '/', $xosid_pfx . '/') === 0) {
            $xosid_is_early_ci = true;
            break;
        }
    }

    // Exclude public guest wilayah routes from early CI boot
    if ($xosid_early_clean === '/first/wilayah' || strpos($xosid_early_clean . '/', '/first/wilayah/') === 0) {
        $xosid_is_early_ci = false;
    }

    if (strpos($xosid_early_clean, '/layanan-mandiri') !== false ||
        $xosid_early_clean === '/first/pengaduan' || $xosid_early_clean === '/index.php/first/pengaduan' ||
        $xosid_early_clean === '/pengaduan' || $xosid_early_clean === '/index.php/pengaduan') {
        $xosid_is_early_ci = false;
    }

    if ($xosid_is_early_ci) {
        @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
            "[" . date('Y-m-d H:i:s') . "] XOSID EARLY CI BOOT: Booting CI for route: "
            . $xosid_early_uri . "\n", FILE_APPEND);

        // Laravel Application diperlukan agar base_path(), session, files, view dll bekerja
        // saat ionCube hooks.php dijalankan oleh CI boot.
        // Gunakan bootstrapWith() persis seperti HTTP kernel — ini register + boot semua providers.
        if (!($GLOBALS['xosid_laravel_app_set'] ?? false)) {
            try {
                $xosid_ci_app = require __DIR__ . '/bootstrap/app.php';
                if ($xosid_ci_app instanceof \Illuminate\Foundation\Application) {
                    \Illuminate\Support\Facades\Facade::setFacadeApplication($xosid_ci_app);
                    $xosid_ci_app->instance('app', $xosid_ci_app);

                    // Inject HTTP Request early (before any bootstrap class or providers run)
                    $xosid_http_req = \Illuminate\Http\Request::capture();
                    $xosid_ci_app->instance('request', $xosid_http_req);
                    \Illuminate\Support\Facades\Facade::clearResolvedInstance('request');

                    // Bootstrap LENGKAP: LoadConfig → HandleExceptions → Facades → Providers → Boot
                    $xosid_ci_app->bootstrapWith([
                        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
                        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
                        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
                        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
                        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
                    ]);

                    // Sekarang boot providers dengan request sudah tersedia
                    $xosid_ci_app->bootstrapWith([
                        \Illuminate\Foundation\Bootstrap\BootProviders::class,
                    ]);

                     // Jalankan database resolver & auth/events decorator patches
                     xosid_configure_laravel_app_decorators($xosid_ci_app);

                     // PHP 8.4: Intercept and ignore "already defined" constant warnings to prevent Laravel crash during CI helper load
                     try {
                          $xosid_early_prev_handler = set_error_handler(function($level, $message, $file, $line) use (&$xosid_early_prev_handler) {
                              if (strpos($message, 'already defined') !== false 
                                  || strpos($message, 'Undefined array key') !== false
                                  || strpos($message, 'Undefined variable') !== false
                                  || strpos($message, 'Undefined property') !== false
                                  || strpos($message, 'dynamic property') !== false
                                  || strpos($message, 'array offset on null') !== false
                              ) {
                                  return true; // Ignore this warning
                              }
                             if ($xosid_early_prev_handler) {
                                 return call_user_func($xosid_early_prev_handler, $level, $message, $file, $line);
                             }
                             return false;
                         });
                     } catch (\Throwable $e) {}

                    $GLOBALS['xosid_laravel_app_set'] = true;
                    $GLOBALS['xosid_laravel_app']     = $xosid_ci_app;
                    $GLOBALS['app']                   = $xosid_ci_app;
                    $app                              = $xosid_ci_app;
                    @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                        "[" . date('Y-m-d H:i:s') . "] XOSID EARLY CI: Full Laravel bootstrap OK (via bootstrapWith)\n",
                        FILE_APPEND);
                }
            } catch (\Throwable $xosid_ci_app_err) {
                @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                    "[" . date('Y-m-d H:i:s') . "] XOSID: bootstrapWith failed: "
                    . $xosid_ci_app_err->getMessage() . "\n", FILE_APPEND);
            }
        }

        // Suppress E_DEPRECATED sebelum CI boot.
        // PHP 8.1+ melaporkan dynamic property sebagai E_DEPRECATED (8192).
        // CI error handler mengkonversi ini ke ErrorException → 500.
        // ionCube AuthenticatedSessionController memakai dynamic property (tidak bisa dimodifikasi).
        $xosid_prev_error_reporting = error_reporting();
        error_reporting($xosid_prev_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        // Fix CI Cache library: CI mencari Cache.php langsung di libraries/
        // tapi file sebenarnya ada di libraries/Cache/Cache.php
        $xosid_cache_src = BASEPATH . 'libraries/Cache/Cache.php';
        $xosid_cache_dst = BASEPATH . 'libraries/Cache.php';
        if (file_exists($xosid_cache_src) && !file_exists($xosid_cache_dst)) {
            @copy($xosid_cache_src, $xosid_cache_dst);
        }
        // XOSID Hook: Override get_config dynamically to force enable_hooks = TRUE
        if (!function_exists('get_config')) {
            function &get_config(Array $replace = array()) {
                static $config;
                if (empty($config)) {
                    $file_path = APPPATH.'config/config.php';
                    $found = FALSE;
                    if (file_exists($file_path)) {
                        $found = TRUE;
                        require($file_path);
                    }
                    if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php')) {
                        require($file_path);
                    }
                    foreach ($replace as $key => $val) {
                        $config[$key] = $val;
                    }
                    
                    // Bypass hooks for sync_all endpoint so it does not trigger the ionCube authentication redirect hook
                    $xosid_req_uri = $_SERVER['REQUEST_URI'] ?? '';
                    if (strpos($xosid_req_uri, '/bumdes_sync/sync_all') !== false) {
                        $config['enable_hooks'] = FALSE;
                    } else {
                        $config['enable_hooks'] = TRUE;
                    }
                }
                return $config;
            }
        }


        require_once BASEPATH . 'core/CodeIgniter.php';
        $xosid_ci =& get_instance();
        $xosid_ci_route_info = 'N/A';
        if ($xosid_ci) {
            $xosid_ci_route_info = get_class($xosid_ci) . '::' . ($xosid_ci->router->method ?? 'N/A');
        }
        @file_put_contents(__DIR__ . '/storage/logs/xosid_ci_route.log',
            "[" . date('Y-m-d H:i:s') . "] Route URI: " . ($_SERVER['REQUEST_URI'] ?? '') . " | Controller: " . $xosid_ci_route_info . "\n",
            FILE_APPEND);
        exit;
    }
}
// ====================================================================

// 5. Boot Laravel

file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP TRACE 3: Before Laravel boot\n", FILE_APPEND);
try {
    @include_once __DIR__ . '/donjo-app/helpers/opensid_helper.php';
} catch (\Throwable $e) {}

// XOSID PATCH v2607: RouteServiceProvider shim sudah didefinisikan di atas (sebelum early CI boot block)

if (file_exists(__DIR__ . '/bootstrap/app.php')) {
    $app = require_once __DIR__ . '/bootstrap/app.php';

    $xosid_bootstrap_ok = false;
    try {
        if (isset($app)) {
            $request = \Illuminate\Http\Request::capture();
            $app->instance('request', $request);
            $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
            if (method_exists($kernel, 'bootstrap')) {
                $kernel->bootstrap();
            }
            $xosid_bootstrap_ok = true;
        }
    } catch (\Throwable $bootErr) {
        $bootErrMsg = $bootErr->getMessage();
        file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP BOOTSTRAP ERROR: " . $bootErrMsg . "\n", FILE_APPEND);
        @file_put_contents(__DIR__ . '/storage/logs/xosid_exception.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP BOOTSTRAP ERROR: " . $bootErrMsg . "\n" . $bootErr->getTraceAsString() . "\n", FILE_APPEND);

        // XOSID PATCH v2607: RouteServiceProvider calls MY_Router::setRequest() which
        // doesn't exist in this ionCube build. After MY_Router is decoded by ionCube,
        // we can inject the method using PHP's internal class manipulation via runkit-like eval.
        // Since MY_Router is now loaded, we define a child class and rebind in container.
        if (strpos($bootErrMsg, 'MY_Router::setRequest') !== false && isset($app)) {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                "[" . date('Y-m-d H:i:s') . "] XOSID: Applying MY_Router::setRequest() shim via anonymous subclass...\n",
                FILE_APPEND);
            try {
                // MY_Router is now loaded by ionCube. Create a subclass that adds setRequest().
                if (class_exists('MY_Router', false) && !class_exists('XOSID_MY_Router_Shimmed', false)) {
                    // Evaluate the subclass definition dynamically
                    eval('class XOSID_MY_Router_Shimmed extends MY_Router {
                        protected $xosid_request = null;
                        public function setRequest($request) {
                            $this->xosid_request = $request;
                            return $this;
                        }
                        public function getXosidRequest() {
                            return $this->xosid_request;
                        }
                    }');
                }

                // Retry bootstrap with the shim applied via container binding
                if (class_exists('XOSID_MY_Router_Shimmed', false)) {
                    // Rebind MY_Router in the container if it was bound
                    if (method_exists($app, 'bind')) {
                        // Ensure the routes are registered without calling setRequest
                        $app->bind('MY_Router', function() { return new XOSID_MY_Router_Shimmed(); });
                    }

                    // Retry bootstrap
                    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
                    if (method_exists($kernel, 'bootstrap')) {
                        $kernel->bootstrap();
                    }
                    $xosid_bootstrap_ok = true;
                    @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                        "[" . date('Y-m-d H:i:s') . "] XOSID: Bootstrap retry with shim SUCCEEDED\n",
                        FILE_APPEND);
                }
            } catch (\Throwable $shimErr) {
                @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                    "[" . date('Y-m-d H:i:s') . "] XOSID: Bootstrap retry with shim FAILED: " . $shimErr->getMessage() . "\n",
                    FILE_APPEND);
            }
        }
    }
}

// AuthManager decorator will be registered at the end of the Laravel boot block
file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP TRACE 4: After Laravel boot. App set: " . (isset($app) ? 'YES' : 'NO') . "\n", FILE_APPEND);


if (isset($app) && $app instanceof \Illuminate\Foundation\Application) {
    xosid_configure_laravel_app_decorators($app);

    // PHP 8.4: Intercept and ignore "already defined" constant warnings to prevent Laravel crash
    try {
        $xosid_prev_handler = set_error_handler(function($level, $message, $file, $line) use (&$xosid_prev_handler) {
            if (strpos($message, 'already defined') !== false 
                || strpos($message, 'Undefined array key') !== false
                || strpos($message, 'Undefined variable') !== false
                || strpos($message, 'Undefined property') !== false
                || strpos($message, 'dynamic property') !== false
                || strpos($message, 'array offset on null') !== false
            ) {
                return true; // Ignore this warning
            }
            if ($xosid_prev_handler) {
                return call_user_func($xosid_prev_handler, $level, $message, $file, $line);
            }
            return false;
        });
    } catch (\Throwable $e) {}

    // PHP 8.4: Sanitize Content-Security-Policy & other security headers from multiline newlines to avoid Symfony Response error
    try {
        if (function_exists('config')) {
            $headers = config('security.headers');
            if (is_array($headers)) {
                foreach ($headers as $key => $val) {
                    if (is_string($val)) {
                        $headers[$key] = preg_replace('/\s+/', ' ', $val);
                    }
                }
                config(['security.headers' => $headers]);
            }
        }
    } catch (\Throwable $e) {
        @file_put_contents(__DIR__ . '/storage/logs/xosid_exception.log',
            "[" . date('Y-m-d H:i:s') . "] SECURITY HEADERS SANITIZATION ERROR: " . $e->getMessage() . "\n",
            FILE_APPEND);
    }
}

// --- XOSID UNIVERSAL INTERCEPTOR ---
file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log', "[" . date('Y-m-d H:i:s') . "] INDEX.PHP TRACE 5: Before Universal Interceptor\n", FILE_APPEND);
$intercept_routes = [
    '/portal2', '/index.php/portal2',
    '/lapak', '/index.php/lapak',
    '/opendata', '/index.php/opendata',
    '/potensi', '/index.php/potensi',
    '/first', '/index.php/first',
    '/layanan-mandiri/masuk', '/index.php/layanan-mandiri/masuk',
    '/layanan-mandiri/masuk-ektp', '/index.php/layanan-mandiri/masuk-ektp',
    '/layanan-mandiri/lupa-pin', '/index.php/layanan-mandiri/lupa-pin',
    '/layanan-mandiri/daftar', '/index.php/layanan-mandiri/daftar',
    '/api-access', '/index.php/api-access',
    '/lapak-umkm', '/index.php/lapak-umkm',
    '/kabar-desa', '/index.php/kabar-desa',
    // Halaman publik desa
    '/profil', '/index.php/profil',
    '/peta', '/index.php/peta',
    '/peta-desa', '/index.php/peta-desa',
    '/sejarah', '/index.php/sejarah',
    '/kerjasama', '/index.php/kerjasama',
    '/kiosk', '/index.php/kiosk',
    '/portal', '/index.php/portal',
    '/katadesa-ai', '/index.php/katadesa-ai',
    '/surat-mandiri', '/index.php/surat-mandiri',
    '/detail', '/index.php/detail',
    '/kabardesa', '/index.php/kabardesa',
    '/pengaduan', '/index.php/pengaduan',
    '/first/pengaduan', '/index.php/first/pengaduan',
    '/first/wilayah', '/index.php/first/wilayah',
    '/bumdes', '/index.php/bumdes',
    '/api-access', '/index.php/api-access',
    '/akademi', '/index.php/akademi',
];

if (!$is_saas_domain && !$is_netmon_domain && !$is_amp_domain) {
    $intercept_routes[] = '/';
    $intercept_routes[] = '';
    $intercept_routes[] = '/index.php';
    $intercept_routes[] = '/index.php/';
}

$is_artikel_detail = preg_match('#^/(index\.php/)*(artikel|detail)/.*$#', rtrim($request_path, '/'));

file_put_contents(__DIR__ . '/storage/logs/xosid_path_debug.log', "Request Path: " . $request_path . "\n", FILE_APPEND);

$is_intercepted_post = ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(rtrim($request_path, '/'), ['/pengaduan', '/first/pengaduan', '/index.php/pengaduan', '/index.php/first/pengaduan']));

if (strpos($request_path, '/api/v1/opendata') !== false) {
    require_once __DIR__ . '/xosid_opendata_api.php';
    exit;
}

if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'HEAD' || $is_intercepted_post) && !$is_saas_domain && !$is_netmon_domain && !$is_amp_domain && (in_array(rtrim($request_path, '/'), $intercept_routes) || $is_artikel_detail)) {
    require_once __DIR__ . '/xosid_portal2_interceptor.php';
    exit;
}

// --- XOSID DYNAMIC VIEW ROUTER (untuk halaman desa yang tidak di intercept_routes) ---
if (($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'HEAD') && !$is_saas_domain && !$is_netmon_domain && !$is_amp_domain) {
    $clean_path = rtrim(str_replace('/index.php', '', $request_path), '/');
    $view_slug  = ltrim($clean_path, '/');
    $view_slug  = str_replace('-', '_', $view_slug);
    // Hanya jika single-segment (bukan sub-path) dan bukan route CI
    if (!empty($view_slug) && !str_contains($view_slug, '/')) {
        $view_file = __DIR__ . '/resources/views/xosid/' . $view_slug . '.blade.php';
        if (file_exists($view_file)) {
            require_once $view_file;
            exit;
        }
    }
}

// --- XOSID SAAS LANDING PAGE INTERCEPTOR ---
if (isset($_SERVER['REQUEST_URI'])) {
    $req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $clean_req_path = str_replace('/index.php', '', $req_path);
    if (empty($clean_req_path)) {
        $clean_req_path = '/';
    }
    $is_saas_route = ($req_path === '/saas' || $req_path === '/saas/' || $clean_req_path === '/saas' || $clean_req_path === '/saas/');
    
    // --- AMP MERAH PUTIH SUBDOMAIN INTERCEPTOR ---
    if ($is_amp_domain) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        $clean_path = rtrim($clean_req_path, '/');
        if (empty($clean_path) || $clean_path === '/') {
            require_once __DIR__ . '/resources/views/xosid/amp_landing.blade.php';
            exit;
        }
        if ($clean_path === '/profil') {
            require_once __DIR__ . '/resources/views/xosid/amp_profil.blade.php';
            exit;
        }
        if (stripos($clean_path, '/berita') === 0) {
            require_once __DIR__ . '/resources/views/xosid/amp_berita.blade.php';
            exit;
        }
        if ($clean_path === '/agenda') {
            require_once __DIR__ . '/resources/views/xosid/amp_agenda.blade.php';
            exit;
        }
        if ($clean_path === '/galeri') {
            require_once __DIR__ . '/resources/views/xosid/amp_galeri.blade.php';
            exit;
        }
        if ($clean_path === '/peta-potensi') {
            require_once __DIR__ . '/resources/views/xosid/amp_peta.blade.php';
            exit;
        }
        if ($clean_path === '/portal-layanan') {
            require_once __DIR__ . '/resources/views/xosid/amp_portal_layanan.blade.php';
            exit;
        }
        if ($clean_path === '/registrasi') {
            require_once __DIR__ . '/resources/views/xosid/amp_registrasi.blade.php';
            exit;
        }
        if ($clean_path === '/login') {
            require_once __DIR__ . '/resources/views/xosid/amp_login.blade.php';
            exit;
        }
        if ($clean_path === '/dashboard') {
            require_once __DIR__ . '/resources/views/xosid/amp_dashboard.blade.php';
            exit;
        }
    }

    $is_hagtou_domain = ($host_no_port === 'hagtou.localhost' || $host_no_port === 'hagtou.id' || $host_no_port === 'www.hagtou.id');
    if ($is_hagtou_domain || $is_saas_domain) {
        if ($clean_req_path === '/bumdes-terdaftar' || $clean_req_path === '/bumdes-terdaftar/') {
            require_once __DIR__ . '/resources/views/xosid/hagtou_directory.blade.php';
            exit;
        }
        if ($clean_req_path === '/daftar-bumdes' || $clean_req_path === '/daftar-bumdes/' || $clean_req_path === '/hagtou-daftar') {
            require_once __DIR__ . '/resources/views/xosid/hagtou_register.blade.php';
            exit;
        }
        if ($clean_req_path === '/api/hagtou/register') {
            require_once __DIR__ . '/hagtou_register_api.php';
            exit;
        }
        if ($clean_req_path === '/hagtou-login' || $clean_req_path === '/hagtou-login/') {
            require_once __DIR__ . '/resources/views/xosid/hagtou_login.blade.php';
            exit;
        }
        if (stripos($clean_req_path, '/hagtou-dashboard') === 0) {
            require_once __DIR__ . '/hagtou_dashboard_handler.php';
            exit;
        }
        if ($clean_req_path === '/api/hagtou-order/create') {
            require_once __DIR__ . '/hagtou_order_handler.php';
            exit;
        }
        if ($clean_req_path === '/panduan' || $clean_req_path === '/panduan/') {
            require_once __DIR__ . '/resources/views/xosid/panduan.blade.php';
            exit;
        }
        if ($clean_req_path === '/akademi' || $clean_req_path === '/akademi/') {
            require_once __DIR__ . '/resources/views/xosid/akademi.blade.php';
            exit;
        }
    }
    if ($is_hagtou_domain) {
        if (empty($clean_req_path) || $clean_req_path === '/') {
            require_once __DIR__ . '/resources/views/xosid/hagtou_portal.blade.php';
            exit;
        }
    }
    
    if (!$is_saas_domain && $is_saas_route) {
        header("Location: " . (function_exists('site_url') ? site_url('/') : '/'));
        exit;
    }

    if ($is_saas_domain && ($clean_req_path === '/' || $is_saas_route)) {
        require_once __DIR__ . '/resources/views/xosid/saas_landing.blade.php';
        exit;
    }
    
    $is_member_route = (stripos($clean_req_path, '/member') === 0);
    if ($is_member_route) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (stripos($clean_req_path, '/member/lupa-password') === 0) {
            require_once __DIR__ . '/resources/views/xosid/member_forgot.blade.php';
            exit;
        }
        if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true) {
            require_once __DIR__ . '/resources/views/xosid/member_dashboard.blade.php';
        } else {
            require_once __DIR__ . '/resources/views/xosid/member_login.blade.php';
        }
        exit;
    }

    $is_partner_route = (stripos($clean_req_path, '/partner') === 0 || stripos($clean_req_path, '/mitra') === 0);
    if ($is_partner_route) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            unset($_SESSION['partner_logged_in']);
            unset($_SESSION['partner_user']);
            header("Location: /partner/login");
            exit;
        }
        if (isset($_SESSION['partner_logged_in']) && $_SESSION['partner_logged_in'] === true) {
            require_once __DIR__ . '/resources/views/xosid/partner_dashboard.blade.php';
        } else {
            require_once __DIR__ . '/resources/views/xosid/partner_login.blade.php';
        }
        exit;
    }

    $is_registrasi_route = (stripos($clean_req_path, '/registrasi') === 0);
    if ($is_registrasi_route && !$is_amp_domain) {
        require_once __DIR__ . '/resources/views/xosid/registrasi.blade.php';
        exit;
    }

    $is_superadmin_route = (stripos($clean_req_path, '/xoSID-login') === 0 || stripos($clean_req_path, '/xosid-login') === 0 || stripos($clean_req_path, '/superadmin/login') === 0 || $clean_req_path === '/superadmin' || $clean_req_path === '/superadmin/');
    if ($is_superadmin_route && $is_saas_domain) {
        require_once __DIR__ . '/resources/views/xosid/superadmin_login.blade.php';
        exit;
    }
    
    $is_superadmin_verifikasi = (stripos($clean_req_path, '/superadmin/verifikasi') === 0);
    if ($is_superadmin_verifikasi && $is_saas_domain) {
        require_once __DIR__ . '/resources/views/xosid/superadmin_verifikasi.blade.php';
        exit;
    }
    
    $is_superadmin_update = (stripos($clean_req_path, '/superadmin/update') === 0);
    if ($is_superadmin_update && $is_saas_domain) {
        require_once __DIR__ . '/resources/views/xosid/superadmin_update.blade.php';
        exit;
    }
    
    $is_api_route = (stripos($clean_req_path, '/api/') === 0);
    if ($is_api_route) {
        if (stripos($clean_req_path, '/api/amp') === 0) {
            require_once __DIR__ . '/xosid_amp_api.php';
            exit;
        } elseif (stripos($clean_req_path, '/api/superadmin') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_superadmin_api.php';
        } elseif (stripos($clean_req_path, '/api/partner') === 0 || stripos($clean_req_path, '/api/mitra') === 0) {
            require_once __DIR__ . '/xosid_partner_api.php';
            exit;
        } elseif (stripos($clean_req_path, '/api/member') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_member_api.php';
        } elseif (stripos($clean_req_path, '/api/registrasi') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_registrasi_api.php';
        } elseif (stripos($clean_req_path, '/api/catalog/sync') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/hagtou_catalog_api.php';
            exit;
        } elseif (stripos($clean_req_path, '/api/rajaongkir/') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/hagtou_rajaongkir_api.php';
            exit;
        } elseif (stripos($clean_req_path, '/api/wilayah') === 0) {
            if ($is_netmon_domain) {
                require_once __DIR__ . '/xosid_netmon_api.php';
            } else {
                require_once __DIR__ . '/xosid_wilayah_api.php';
            }
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'API Endpoint Not Found or Unauthorized']);
        }
        exit;
    }
    
    if ($is_saas_domain) {
        $view_name = str_replace('-', '_', basename($req_path));
        if (!empty($view_name) && $view_name !== 'index.php') {
            $view_file = __DIR__ . '/resources/views/xosid/' . $view_name . '.blade.php';
            if (file_exists($view_file)) {
                require_once $view_file;
                exit;
            }
        }
    }
}

if (isset($_SERVER['HTTP_HOST'])) {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        @session_start();
    }
    error_log("XOSID DEBUG: HOST=" . $_SERVER['HTTP_HOST'] . " | URI=" . $_SERVER['REQUEST_URI'] . " | SESSION=" . xosid_safe_json_session($_SESSION ?? []));
}

// 6. XOSID CI BOOT INTERCEPT — Tangkap route CI admin sebelum Laravel handle
// Untuk tenant subdomain (non-saas), semua route yang tidak ditangkap sebelumnya
// dan bukan route portal publik harus di-boot via CI (CodeIgniter) langsung.
if (!$is_saas_domain && !$is_netmon_domain && ($xosid_bootstrap_ok ?? false)) {
    // Cek apakah request ini adalah route CI (login, admin panel, dll)
    // REQUEST_URI sudah diubah ke /siteman untuk /xoSID-login di atas
    $xosid_ci_current_uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

    // Route yang harus dilayani CI secara native (bukan portal publik, bukan Laravel)
    $xosid_ci_routes = [
        '/siteman', '/index.php/siteman',           // Login page (from /xoSID-login rewrite)
        '/main', '/index.php/main',                  // Dashboard
        '/beranda', '/index.php/beranda',
        '/wilayah', '/index.php/wilayah',
        '/penduduk', '/index.php/penduduk',
        '/keluarga', '/index.php/keluarga',
        '/surat', '/index.php/surat',
        '/laporan', '/index.php/laporan',
        '/statistik', '/index.php/statistik',
        '/pengaturan', '/index.php/pengaturan',
        '/modul', '/index.php/modul',
        '/peringatan', '/index.php/peringatan',
    ];

    $xosid_is_ci_admin_route = false;
    $xosid_ci_clean = rtrim($xosid_ci_current_uri, '/');
    foreach ($xosid_ci_routes as $xosid_ci_r) {
        if ($xosid_ci_clean === $xosid_ci_r || strpos($xosid_ci_clean, $xosid_ci_r . '/') === 0) {
            $xosid_is_ci_admin_route = true;
            break;
        }
    }

    // Juga tangkap request_path asli (sebelum rewrite)
    if (!$xosid_is_ci_admin_route) {
        $xosid_orig_clean = rtrim($request_path, '/');
        if (strpos($xosid_orig_clean, '/xoSID-login') === 0
            || strpos($xosid_orig_clean, '/xosid-login') === 0
            || strpos($xosid_orig_clean, '/siteman') === 0) {
            $xosid_is_ci_admin_route = true;
        }
    }

    if ($xosid_is_ci_admin_route) {
        @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
            "[" . date('Y-m-d H:i:s') . "] XOSID CI BOOT: Intercepting CI admin route: " . $xosid_ci_current_uri . " (original: " . $request_path . ")\n",
            FILE_APPEND);

        // Boot CodeIgniter kernel langsung untuk route ini
        // CI sudah memiliki route 'siteman' (login) dan semua route admin
        if (!function_exists('get_instance')) {
            require_once BASEPATH . 'core/CodeIgniter.php';
        }
        exit;
    }
}

// 7. Boot Laravel Kernel (Transisi rilis 2607.0.0)
// Digunakan untuk route Laravel murni (API, blade views tertentu)
if (!isset($kernel) && isset($app) && $app instanceof \Illuminate\Foundation\Application) {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
}
// Only handle if kernel bootstrap succeeded (tracked by $xosid_bootstrap_ok)
if (isset($kernel) && ($xosid_bootstrap_ok ?? false)) {
    try {
        $response = $kernel->handle(
            $request = \Illuminate\Http\Request::capture()
        );
        $headers_dump = [];
        foreach ($response->headers->all() as $name => $values) {
            $headers_dump[$name] = $values;
        }
        @file_put_contents(__DIR__ . '/storage/logs/xosid_headers_debug.log',
            "[" . date('Y-m-d H:i:s') . "] RESPONSE HEADERS: " . json_encode($headers_dump) . "\n",
            FILE_APPEND);
        $response->send();
    } catch (\Throwable $e) {
        @file_put_contents(__DIR__ . '/storage/logs/xosid_exception.log',
            "[" . date('Y-m-d H:i:s') . "] RESPONSE SEND EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n",
            FILE_APPEND);
        throw $e;
    }
    $kernel->terminate($request, $response);
} elseif (isset($kernel) && !($xosid_bootstrap_ok ?? false)) {
    // Bootstrap failed — log dan boot CI sebagai fallback
    @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
        "[" . date('Y-m-d H:i:s') . "] XOSID: Laravel bootstrap failed, booting CI as fallback for: " . ($_SERVER['REQUEST_URI'] ?? '') . "\n",
        FILE_APPEND);
    // CI fallback
    if (function_exists('get_instance')) {
        $rf = new ReflectionFunction('get_instance');
        @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
            "[" . date('Y-m-d H:i:s') . "] DEBUG: get_instance is already defined in: " . $rf->getFileName() . ":" . $rf->getStartLine() . "\n",
            FILE_APPEND);
    }
    if (file_exists(BASEPATH . 'core/CodeIgniter.php')) {
        if (!function_exists('get_instance')) {
            require_once BASEPATH . 'core/CodeIgniter.php';
        } else {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_raw_debug.log',
                "[" . date('Y-m-d H:i:s') . "] DEBUG: Skipping require of CodeIgniter.php because get_instance exists\n",
                FILE_APPEND);
        }
    }
}

