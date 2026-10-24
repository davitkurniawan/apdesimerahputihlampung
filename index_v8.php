<?php include "debug_hook.php"; ?>
<?php
/**
 * XOSID - Neural Core Entry Point (RESTORATION MODE)
 */

// 1. SILENCE THE NOISE
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1); // Keep on for debugging

// 2. Core Constants
$host = $_SERVER['HTTP_HOST'] ?? '';
$is_saas_domain = ($host === 'localhost:8080' || $host === 'xosid.id' || $host === 'www.xosid.id');
$is_netmon_domain = ($host === 'netmon.localhost:8080' || $host === 'netmon.xosid.id');

// 2.1 XOSID NEURAL ROUTING BYPASS
$assign_to_config['index_page'] = '';

if (strpos($_SERVER['REQUEST_URI'], '/dashboard_neural') !== false) {
    $_SERVER['REQUEST_URI'] = str_replace('/dashboard_neural', '/main', $_SERVER['REQUEST_URI']);
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
if (!defined('APP_URL'))
    define('APP_URL', (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080'));
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);

// 3. XOSID BYPASSES
if (file_exists(__DIR__ . '/xosid_emergency_autoload.php'))
    require_once __DIR__ . '/xosid_emergency_autoload.php';
if (file_exists(__DIR__ . '/xosid_nuclear_boot.php'))
    require_once __DIR__ . '/xosid_nuclear_boot.php';

// 4. Load Autoloaders
require_once __DIR__ . '/vendor/autoload.php';

// 5. Boot Laravel
if (file_exists(__DIR__ . '/bootstrap/app.php'))
    $app = require_once __DIR__ . '/bootstrap/app.php';

// --- XOSID NEURAL DATABASE CONNECTION RESOLVER PATCH ---
if (isset($app)) {
    include_once __DIR__ . '/xosid_database_helpers.php';

    if (class_exists('XOSIDConfigRepository')) {
        $currentConfig = $app['config'] ?? null;
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

    // 2. Injeksi koneksi fallback
    if ($config) {
        $connections = $config->get('database.connections') ?: [];
        
        // BACA DARI CODEIGNITER
        if (file_exists(FCPATH . 'desa/config/database.php')) {
            require FCPATH . 'desa/config/database.php';
            if (isset($db['default'])) {
                $ci_db = $db['default'];
                $laravel_db = [
                    'driver'    => 'mysql',
                    'host'      => $ci_db['hostname'] ?? 'db',
                    'port'      => $ci_db['port'] ?? 3306,
                    'database'  => $ci_db['database'] ?? 'xosid',
                    'username'  => $ci_db['username'] ?? 'root',
                    'password'  => $ci_db['password'] ?? 'password',
                    'charset'   => 'utf8mb4',
                    'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
                    'prefix'    => '',
                    'strict'    => $ci_db['stricton'] ?? true,
                    'engine'    => null,
                ];
                $connections['default'] = $laravel_db;
                $connections['mysql'] = $laravel_db;
                $connections['opensid'] = $laravel_db;
                $connections['tenant'] = $laravel_db;
                $connections[''] = $laravel_db;
            }
        }
        
        $config->set('database.connections', $connections);
        $config->set('database.default', 'default');
    }

    // 3. Dekoratir Connection Resolver pada Eloquent Model
    if (class_exists('Illuminate\Database\Eloquent\Model') && class_exists('XOSIDConnectionResolver')) {
        $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        if (!$currentResolver && isset($app['db'])) {
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
            $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        }
        if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
            $newResolver = new XOSIDConnectionResolver($currentResolver);
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
        }
    }
}

// --- XOSID SAAS LANDING PAGE INTERCEPTOR ---
if (isset($_SERVER['REQUEST_URI'])) {
    $req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $is_saas_route = ($req_path === '/saas' || $req_path === '/saas/');
    
    if (($is_saas_domain && (empty($req_path) || $req_path === '/' || $req_path === '/index.php')) || $is_saas_route) {
        require_once __DIR__ . '/resources/views/xosid/saas_landing.blade.php';
        exit;
    }
    
    $is_member_route = (stripos($req_path, '/member') === 0);
    if ($is_member_route) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (stripos($req_path, '/member/lupa-password') === 0) {
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

    $is_registrasi_route = (stripos($req_path, '/registrasi') === 0);
    if ($is_registrasi_route) {
        require_once __DIR__ . '/resources/views/xosid/registrasi.blade.php';
        exit;
    }

    $is_superadmin_route = (stripos($req_path, '/xoSID-login') === 0 || stripos($req_path, '/xosid-login') === 0 || stripos($req_path, '/superadmin/login') === 0 || $req_path === '/superadmin' || $req_path === '/superadmin/');
    if ($is_superadmin_route && $is_saas_domain) {
        require_once __DIR__ . '/resources/views/xosid/superadmin_login.blade.php';
        exit;
    }
    
    $is_superadmin_verifikasi = (stripos($req_path, '/superadmin/verifikasi') === 0);
    if ($is_superadmin_verifikasi && $is_saas_domain) {
        require_once __DIR__ . '/resources/views/xosid/superadmin_verifikasi.blade.php';
        exit;
    }
    
    $clean_req_path = str_replace('/index.php', '', $req_path);
    $is_api_route = (stripos($clean_req_path, '/api/') === 0);
    if ($is_api_route) {
        if (stripos($clean_req_path, '/api/superadmin') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_superadmin_api.php';
        } elseif (stripos($clean_req_path, '/api/member') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_member_api.php';
        } elseif (stripos($clean_req_path, '/api/registrasi') === 0 && $is_saas_domain) {
            require_once __DIR__ . '/xosid_registrasi_api.php';
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

// 6. Boot CodeIgniter
require_once BASEPATH . 'core/CodeIgniter.php';
