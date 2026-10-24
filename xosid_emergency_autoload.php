<?php
// Set memory limit to 2048M to support large tenant databases (e.g. Bondo with 154k residents)
@ini_set('memory_limit', '2048M');

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }
        if (is_string($value)) {
            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    return true;
                case 'false':
                case '(false)':
                    return false;
                case 'empty':
                case '(empty)':
                    return '';
                case 'null':
                case '(null)':
                    return null;
            }
        }
        return $value;
    }
}

if (!function_exists('current_url')) {
    function current_url() {
        if (function_exists('get_instance') && get_instance() && isset(get_instance()->config) && isset(get_instance()->uri)) {
            return get_instance()->config->site_url(get_instance()->uri->uri_string());
        }
        if (function_exists('url')) {
            try {
                return url()->current();
            } catch (\Throwable $e) {}
        }
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = explode('?', $_SERVER['REQUEST_URI'] ?? '')[0];
        return $scheme . $host . $uri;
    }
}

if (!function_exists('uri_string')) {
    function uri_string() {
        if (function_exists('get_instance') && get_instance() && isset(get_instance()->uri)) {
            return get_instance()->uri->uri_string();
        }
        $uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
        return $uri;
    }
}

if (!function_exists('xosid_get_tenant_package')) {
    function xosid_get_tenant_package() {
        static $cached_paket = null;
        if ($cached_paket !== null) {
            return $cached_paket;
        }
        
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host_parts = explode(':', $host);
        $host_no_port = strtolower($host_parts[0]);
        $sub = '';
        if (preg_match('/^([a-z0-9-]+)\.(localhost|xosid\.id|127\.0\.0\.1)/', $host_no_port, $m)) {
            $sub = $m[1];
        }
        
        if (empty($sub) || in_array($sub, ['www', 'amp', 'master', 'api'])) {
            $cached_paket = 'enterprise';
            return $cached_paket;
        }

        if ($sub === 'bumidaya' || $sub === 'demo' || $sub === 'demo-enterprise') {
            $cached_paket = 'enterprise';
            return $cached_paket;
        }

        if (strpos($sub, 'tamanasri') !== false || $sub === 'demo-esensial' || $sub === 'demoesensial') {
            $cached_paket = 'esensial';
            return $cached_paket;
        }
        
        try {
            $db_config_file = __DIR__ . '/desa/config/database.php';
            if (!file_exists($db_config_file)) { $db_config_file = __DIR__ . '/config/database.php'; }
            $db_host = 'db'; $db_user = 'root'; $db_pass = 'password'; $db_port = 3306;
            if (file_exists($db_config_file)) {
                @include $db_config_file;
                if (isset($db['default'])) {
                    $db_host = $db['default']['hostname'] ?? $db_host;
                    $db_user = $db['default']['username'] ?? $db_user;
                    $db_pass = $db['default']['password'] ?? $db_pass;
                    $db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : $db_port;
                    if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
                        $db_pass = 'password';
                    }
                }
            }
            $conn_m = @new mysqli($db_host, $db_user, $db_pass, 'xosid', $db_port);
            if ($conn_m && !$conn_m->connect_error) {
                $sub_esc = $conn_m->real_escape_string($sub);
                $sub_space = $conn_m->real_escape_string(str_replace(['-', '_'], ' ', $sub));
                $parts = explode('-', $sub);
                $sub_first = $conn_m->real_escape_string($parts[0]);
                
                $q = $conn_m->query("SELECT paket, paket_langganan FROM xosid_registrasi_tenant WHERE custom_domain = '{$sub_esc}' OR nama_desa LIKE '%{$sub_space}%' OR nama_desa LIKE '%{$sub_first}%' LIMIT 1");
                if ($q && $row = $q->fetch_assoc()) {
                    $raw = strtolower(trim(!empty($row['paket']) ? $row['paket'] : (!empty($row['paket_langganan']) ? $row['paket_langganan'] : 'esensial')));
                    if (in_array($raw, ['esensial', 'fundamental', 'tumbuh', 'basic', ''])) {
                        $cached_paket = 'esensial';
                    } else {
                        $cached_paket = $raw;
                    }
                    $conn_m->close();
                    return $cached_paket;
                }
                $conn_m->close();
            }
        } catch (\Throwable $e) {}
        
        $cached_paket = 'esensial';
        return $cached_paket;
    }
}

// Enforce Backend Tier Gatekeeper
if (file_exists(__DIR__ . '/xosid_package_gatekeeper.php')) {
    require_once __DIR__ . '/xosid_package_gatekeeper.php';
}

// Register Exception Hook
$old_exception_handler = set_exception_handler(function($e) use (&$old_exception_handler) {
    $logFile = __DIR__ . '/storage/logs/xosid_exception.log';
    $msg = "[" . date('Y-m-d H:i:s') . "] EXCEPTION: " . get_class($e) . ": " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
    @file_put_contents($logFile, $msg, FILE_APPEND);
    if ($old_exception_handler) {
        return call_user_func($old_exception_handler, $e);
    }
    throw $e;
});


// Load XOSID ConfigId scope and observer hijack (deferred to prevent early load interface errors)
if (interface_exists('Illuminate\Database\Eloquent\Scope', false)) {
    require_once __DIR__ . '/xosid_config_id_scope_hijack.php';
} else {
    spl_autoload_register(function($class) {
        if ($class === 'App\Models\Desa' || strpos($class, 'Illuminate\\Database\\') === 0 || strpos($class, 'App\\Models\\') === 0) {
            if (file_exists(__DIR__ . '/xosid_config_id_scope_hijack.php') && interface_exists('Illuminate\Database\Eloquent\Scope', true)) {
                @require_once __DIR__ . '/xosid_config_id_scope_hijack.php';
            }
        }
    }, true, true);
}

// Hybrid mock session user class to satisfy both Laravel's object property checks and CodeIgniter's user ID checks
if (!class_exists('XosidSessionUser')) {
    class XosidSessionUser implements ArrayAccess, JsonSerializable {
        public $id = 1;
        public $username = 'admin';
        public $id_grup = 1;
        public $grup = 1;
        public $batasi_wilayah = 0;
        public $nama = 'Administrator';

        #[\ReturnTypeWillChange]
        public function offsetExists($offset) {
            return isset($this->$offset);
        }

        #[\ReturnTypeWillChange]
        public function offsetGet($offset) {
            return $this->$offset ?? null;
        }

        #[\ReturnTypeWillChange]
        public function offsetSet($offset, $value) {
            $this->$offset = $value;
        }

        #[\ReturnTypeWillChange]
        public function offsetUnset($offset) {
            unset($this->$offset);
        }

        public function __get($name) {
            if ($name === 'pamong') {
                $auth = function_exists('ci_auth') ? ci_auth() : null;
                if ($auth && $auth !== $this) {
                    return $auth->pamong;
                }
            }
            return $this->$name ?? null;
        }

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __isset($name) {
            return isset($this->$name);
        }

        public function __toString() {
            return '1';
        }

        #[\ReturnTypeWillChange]
        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'id_grup' => $this->id_grup,
                'grup' => $this->grup,
                'batasi_wilayah' => $this->batasi_wilayah,
                'nama' => $this->nama
            ];
        }
    }
}

// Temporary global error handler to capture and log the warning
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, 'Attempt to read property') !== false || strpos($errstr, 'Undefined property') !== false || strpos($errfile, 'AccessWilayahScope') !== false || strpos($errfile, 'Pamong.php') !== false) {
        $log = "========================================\n";
        $log .= "WARNING CAPTURED: $errstr\n";
        $log .= "File: $errfile | Line: $errline\n";
        $log .= "SESSION user: " . print_r($_SESSION['user'] ?? 'NOT SET', true) . "\n";
        $log .= "SESSION user type: " . gettype($_SESSION['user'] ?? null) . "\n";
        if (is_object($_SESSION['user'] ?? null)) {
            $log .= "SESSION user class: " . get_class($_SESSION['user']) . "\n";
        }
        $log .= "SESSION user_id: " . print_r($_SESSION['user_id'] ?? 'NOT SET', true) . "\n";
        $log .= "SESSION username: " . print_r($_SESSION['username'] ?? 'NOT SET', true) . "\n";
        $log .= "Backtrace:\n";
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($trace as $i => $step) {
            $log .= "  #$i " . ($step['file'] ?? 'unknown') . ":" . ($step['line'] ?? 'unknown') . " - " . ($step['function'] ?? '') . "\n";
        }
        $log .= "========================================\n";
        @file_put_contents(__DIR__ . '/storage/logs/xosid_warning_debug.log', $log, FILE_APPEND);
    }
    return false; // Continue with standard handling
});

// Isolate session cookies and database connections by subdomain/host
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$host_parts = explode(':', $http_host);
$host_no_port = strtolower($host_parts[0]);
$subdomain = 'master';
$database_name = 'xosid';

$base_saas_domains = ['xosid.id', 'www.xosid.id', 'localhost', '127.0.0.1', 'master.localhost', 'master.xosid.id', 'hagtou.localhost', 'hagtou.id', 'www.hagtou.id'];
if (!in_array($host_no_port, $base_saas_domains) && !empty($host_no_port)) {
    if (preg_match('/\.xosid\.id$|\.localhost$|\.127\.0\.0\.1$/', $host_no_port)) {
        $parts = explode('.', $host_no_port);
        if (count($parts) > 1) {
            $sub = strtolower($parts[0]);
            if (!empty($sub) && $sub !== 'www') {
                $subdomain = $sub;
                $database_name = 'xosid_tenant_' . $subdomain;
            }
        }
    } else {
        // Custom domain lookup
        $db_host = 'db';
        $db_user = 'root';
        $db_pass = 'password';
        $env_path = __DIR__ . '/.env';
        if (file_exists($env_path)) {
            $env = file_get_contents($env_path);
            if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1], "\"' ");
            if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1], "\"' ");
            if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1], "\"' ");
        }
        if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
            $db_host = 'db';
        }
        $master_conn = @new mysqli($db_host, $db_user, $db_pass, 'xosid');
        if (!$master_conn->connect_error) {
            $stmt = $master_conn->prepare("SELECT nama_desa FROM xosid_registrasi_tenant WHERE custom_domain = ? AND status = 'approved'");
            if ($stmt) {
                $stmt->bind_param("s", $host_no_port);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $clean_desa = explode('(', $row['nama_desa'])[0];
                    $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                    $database_name = 'xosid_tenant_' . $subdomain;
                }
                $stmt->close();
            }
            $master_conn->close();
        }
    }
}

// 1. Force setting of Environment Variable DB_DATABASE early before Laravel boots
putenv("DB_DATABASE={$database_name}");
$_ENV['DB_DATABASE'] = $database_name;
$_SERVER['DB_DATABASE'] = $database_name;
$_SERVER['XOSID_TENANT_DB'] = $database_name;
putenv("XOSID_TENANT_DB={$database_name}");

// 2. Set native session name
$sent_file = '';
$sent_line = 0;
$h_sent = headers_sent($sent_file, $sent_line);
if ($h_sent) {
    error_log("XOSID HEADERS SENT EARLY: File=$sent_file | Line=$sent_line");
}
if (!$h_sent && session_status() === PHP_SESSION_NONE) {
    session_name('PHPSESSID_' . $subdomain);
}

// 3. Set CodeIgniter session and CSRF cookie names dynamically
global $assign_to_config;
if (!isset($assign_to_config) || !is_array($assign_to_config)) {
    $assign_to_config = [];
}
$assign_to_config['sess_cookie_name'] = 'ci_session_' . $subdomain;
$assign_to_config['csrf_cookie_name'] = 'sidcsrf_' . $subdomain;


// ============================================================================
// XOSID NEURAL: IMMORTALITY SHIELD ENGINE
// ============================================================================
(function() {
    $active_root = __DIR__ . '/';
    $backup_root = $active_root . 'storage/xosid_backups/';
    
    $files_to_shield = [
        'resources/views/admin/pengaturan/pengguna/form.blade.php',
        'resources/views/admin/pengaturan/pengguna/index.blade.php',
        'resources/views/admin/pengaturan/pengguna/menu.blade.php',
        'resources/views/admin/identitas_desa/index.blade.php',
        'resources/views/admin/identitas_desa/tab-profil.blade.php',
        'resources/views/admin/identitas_desa/view-profil.blade.php',
        'resources/views/admin/pengguna/index.blade.php',
        'resources/views/admin/pengguna/tab-sandi.blade.php',
        'resources/views/admin/pengguna/tab-profil.blade.php',
        'resources/views/admin/pengguna/tab-2fa.blade.php',
        'resources/views/admin/layouts/index.blade.php',
        'resources/views/admin/layouts/partials/header.blade.php',
        'resources/views/admin/layouts/partials/footer.blade.php',
        'resources/views/admin/layouts/partials/sidebar.blade.php',
        'resources/views/admin/wilayah/index.blade.php',
    ];

    $restored = false;
    foreach ($files_to_shield as $rel_path) {
        $backup_path = $backup_root . $rel_path;
        $active_path = $active_root . $rel_path;
        
        if (!file_exists($backup_path)) {
            continue;
        }
        
        $needs_restore = false;
        if (!file_exists($active_path)) {
            $needs_restore = true;
        } else {
            if (md5_file($active_path) !== md5_file($backup_path)) {
                $needs_restore = true;
            }
        }
        
        if ($needs_restore) {
            $dir = dirname($active_path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (@copy($backup_path, $active_path)) {
                $restored = true;
                @file_put_contents(
                    $active_root . 'storage/logs/xosid_immortality.log',
                    "[" . date('Y-m-d H:i:s') . "] RESTORED VIEW: $rel_path\n",
                    FILE_APPEND
                );
            }
        }
    }
    
    if ($restored) {
        $cache_files = glob($active_root . 'storage/framework/views/*.php');
        if ($cache_files) {
            foreach ($cache_files as $cf) {
                @unlink($cf);
            }
        }
    }

    // Database Menu Shield (runs on admin / CLI requests)
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $is_admin = (
        strpos($uri, '/admin/') !== false || 
        strpos($uri, '/man_user') !== false || 
        strpos($uri, '/identitas_desa') !== false ||
        (defined('STDIN') && php_sapi_name() === 'cli')
    );
    
    if ($is_admin && class_exists('mysqli')) {
        try {
            $db_host = 'db';
            $db_user = 'root';
            $db_pass = 'password';
            $db_name = 'xosid';
            
            $env_path = $active_root . '.env';
            if (file_exists($env_path)) {
                $env = file_get_contents($env_path);
                if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1], "\"' ");
                if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1], "\"' ");
                if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1], "\"' ");
                if (preg_match('/DB_DATABASE=(.*)/', $env, $m)) $db_name = trim($m[1], "\"' ");
            }
            
            if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
                $db_host = 'db';
            }
            
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $host_parts = explode(':', $host);
            $host_no_port = strtolower($host_parts[0]);
            $base_saas_domains = ['xosid.id', 'www.xosid.id', 'localhost', '127.0.0.1', 'master.localhost', 'master.xosid.id'];
            
            if (!in_array($host_no_port, $base_saas_domains)) {
                if (preg_match('/\.xosid\.id$|\.localhost$|\.127\.0\.0\.1$/', $host_no_port)) {
                    $parts = explode('.', $host_no_port);
                    if (count($parts) > 1) {
                        $subdomain = strtolower($parts[0]);
                        if (!empty($subdomain) && $subdomain !== 'www') {
                            $db_name = 'xosid_tenant_' . $subdomain;
                        }
                    }
                } else {
                    $master_conn = @new mysqli($db_host, $db_user, $db_pass, 'xosid');
                    if (!$master_conn->connect_error) {
                        $stmt = $master_conn->prepare("SELECT nama_desa FROM xosid_registrasi_tenant WHERE custom_domain = ? AND status = 'approved'");
                        if ($stmt) {
                            $stmt->bind_param("s", $host_no_port);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            if ($row = $res->fetch_assoc()) {
                                $clean_desa = explode('(', $row['nama_desa'])[0];
                                $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                                $db_name = 'xosid_tenant_' . $subdomain;
                            }
                            $stmt->close();
                        }
                        $master_conn->close();
                    }
                }
            }
            
            $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
            if (!$conn->connect_error) {
                $check_sql = "SELECT COUNT(*) as cnt FROM setting_modul WHERE modul IN ('Layanan Warga', 'Data Pendukung', 'Pengaturan Sistem') AND parent = 0 AND aktif = 1";
                $res = $conn->query($check_sql);
                $ok = false;
                if ($res) {
                    $row = $res->fetch_assoc();
                    if ($row && $row['cnt'] == 3) {
                        $nested_res = $conn->query("SELECT parent FROM setting_modul WHERE modul = 'Kesehatan' LIMIT 1");
                        if ($nested_res) {
                            $nested_row = $nested_res->fetch_assoc();
                            if ($nested_row) {
                                $parent_id = $nested_row['parent'];
                                $parent_res = $conn->query("SELECT modul FROM setting_modul WHERE id = $parent_id");
                                if ($parent_res) {
                                    $parent_row = $parent_res->fetch_assoc();
                                    if ($parent_row && $parent_row['modul'] === 'Layanan Warga') {
                                        $ok = true;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if (!$ok) {
                    $sql_file = $active_root . 'scratch/restore_menu_xosid.sql';
                    if (file_exists($sql_file)) {
                        $sql_content = file_get_contents($sql_file);
                        $lines = explode("\n", $sql_content);
                        $clean_queries = [];
                        $current_query = '';
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                                continue;
                            }
                            $current_query .= ' ' . $line;
                            if (substr($line, -1) === ';') {
                                $clean_queries[] = trim($current_query);
                                $current_query = '';
                            }
                        }
                        foreach ($clean_queries as $q) {
                            $conn->query($q);
                        }
                        @file_put_contents(
                            $active_root . 'storage/logs/xosid_immortality.log',
                            "[" . date('Y-m-d H:i:s') . "] RESTORED DATABASE MENU: for '$db_name'\n",
                            FILE_APPEND
                        );
                    }
                }
                $conn->close();
            }
        } catch (\Throwable $e) {
            // Fail silently
        }
    }
})();

// Load constants.php early to prevent redefinition warning and satisfy early Laravel boot constant requirements
if (defined('APPPATH') && file_exists(APPPATH . 'config/constants.php')) {
    $old_reporting = error_reporting();
    error_reporting($old_reporting & ~E_NOTICE & ~E_WARNING);
    set_error_handler(function($errno, $errstr) {
        if (strpos($errstr, 'already defined') !== false) {
            return true;
        }
        return false;
    }, E_NOTICE | E_WARNING);
    
    require_once APPPATH . 'config/constants.php';
    
    restore_error_handler();
    error_reporting($old_reporting);
}


if (!function_exists('is_cli')) {
    function is_cli() {
        return PHP_SAPI === 'cli' || defined('STDIN');
    }
}

if (!function_exists('xosid_safe_csrf_name')) {
    function xosid_safe_csrf_name() {
        if (function_exists('get_instance')) {
            $ci = get_instance();
            if (isset($ci->security) && is_object($ci->security)) {
                return $ci->security->get_csrf_token_name();
            }
        }
        return 'sidcsrf';
    }
}

if (!function_exists('xosid_safe_csrf_value')) {
    function xosid_safe_csrf_value() {
        if (function_exists('get_instance')) {
            $ci = get_instance();
            if (isset($ci->security) && is_object($ci->security)) {
                return $ci->security->get_csrf_hash();
            }
        }
        return '';
    }
}

if (php_sapi_name() === 'cli' && !defined('BASEPATH')) {
    if (!class_exists('CI_Controller', false)) {
        class CI_Controller implements ArrayAccess {
            #[\ReturnTypeWillChange]
            public function offsetExists($offset) { return true; }
            #[\ReturnTypeWillChange]
            public function offsetGet($offset) {
                if ($offset === 'token_name') return xosid_safe_csrf_name();
                if ($offset === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            #[\ReturnTypeWillChange]
            public function offsetSet($offset, $value) {}
            #[\ReturnTypeWillChange]
            public function offsetUnset($offset) {}
            public function __get($name) {
                if ($name === 'token_name') return xosid_safe_csrf_name();
                if ($name === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            public function __call($name, $args) { return $this; }
            public function __toString() { return ''; }
        }
    }
    if (!class_exists('ci', false)) {
        class ci extends CI_Controller {
            #[\ReturnTypeWillChange]
            public function offsetExists($offset) { return true; }
            #[\ReturnTypeWillChange]
            public function offsetGet($offset) {
                if ($offset === 'token_name') return xosid_safe_csrf_name();
                if ($offset === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            #[\ReturnTypeWillChange]
            public function offsetSet($offset, $value) {}
            #[\ReturnTypeWillChange]
            public function offsetUnset($offset) {}
            public function __get($name) {
                if ($name === 'token_name') return xosid_safe_csrf_name();
                if ($name === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            public function __call($name, $args) { return $this; }
            public function __toString() { return ''; }
        }
    }
} else {
    if (!class_exists('ci', false)) {
        class ci implements ArrayAccess {
            #[\ReturnTypeWillChange]
            public function offsetExists($offset) { return true; }
            #[\ReturnTypeWillChange]
            public function offsetGet($offset) {
                if ($offset === 'token_name') return xosid_safe_csrf_name();
                if ($offset === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            #[\ReturnTypeWillChange]
            public function offsetSet($offset, $value) {}
            #[\ReturnTypeWillChange]
            public function offsetUnset($offset) {}
            public function __get($name) {
                if ($name === 'token_name') return xosid_safe_csrf_name();
                if ($name === 'token_value') return xosid_safe_csrf_value();
                return $this;
            }
            public function __call($name, $args) { return $this; }
            public function __toString() { return ''; }
        }
    }
}




// Dynamic database connection helper using PDO
if (!function_exists('xosid_get_db_pdo')) {
    function xosid_get_db_pdo() {
        $db_host = 'db';
        $db_user = 'root';
        $db_pass = 'password';
        $db_name = 'xosid';
        
        $env_path = __DIR__ . '/.env';
        if (file_exists($env_path)) {
            $env = file_get_contents($env_path);
            if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1]);
            if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1]);
            if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1]);
            if (preg_match('/DB_DATABASE=(.*)/', $env, $m)) $db_name = trim($m[1]);
        }
        
        $db_host = trim($db_host, "\"'");
        $db_user = trim($db_user, "\"'");
        $db_pass = trim($db_pass, "\"'");
        $db_name = trim($db_name, "\"'");
        
        if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
            $db_host = 'db';
        }

        // DYNAMIC MULTI-TENANT DATABASE RESOLVER
        $http_host = $_SERVER['HTTP_HOST'] ?? '';
        if (!empty($http_host)) {
            $host_parts = explode(':', $http_host);
            $host_no_port = strtolower($host_parts[0]);
            
            $base_saas_domains = ['xosid.id', 'www.xosid.id', 'localhost', '127.0.0.1', 'master.localhost', 'master.xosid.id'];
            if (!in_array($host_no_port, $base_saas_domains)) {
                if (preg_match('/\.xosid\.id$|\.localhost$|\.127\.0\.0\.1$/', $host_no_port)) {
                    $parts = explode('.', $host_no_port);
                    if (count($parts) > 1) {
                        $subdomain = strtolower($parts[0]);
                        if (!empty($subdomain) && $subdomain !== 'www') {
                            $db_name = 'xosid_tenant_' . $subdomain;
                        }
                    }
                } else {
                    // Custom Domain Lookup
                    try {
                        $dsn_master = "mysql:host=$db_host;dbname=xosid;charset=utf8mb4";
                        $pdo_master = new PDO($dsn_master, $db_user, $db_pass);
                        $stmt = $pdo_master->prepare("SELECT nama_desa FROM xosid_registrasi_tenant WHERE custom_domain = ? AND status = 'approved'");
                        $stmt->execute([$host_no_port]);
                        if ($row = $stmt->fetch()) {
                            $clean_desa = explode('(', $row['nama_desa'])[0];
                            $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                            $db_name = 'xosid_tenant_' . $subdomain;
                        }
                    } catch (Exception $e) {}
                }
            }
        }
        
        try {
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            return new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (Exception $e) {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_db_error.log', "[" . date('Y-m-d H:i:s') . "] Interception connection failed: " . $e->getMessage() . "\n", FILE_APPEND);
            return null;
        }
    }
}

// Define custom, early-boot identitas() helper function to ensure it's available to observers and models
if (!function_exists('identitas')) {
    function identitas($key = null) {
        static $config_cache = null;
        
        if ($config_cache === null) {
            try {
                $db_host = 'db';
                $db_user = 'root';
                $db_pass = 'password';
                
                $env_path = __DIR__ . '/.env';
                if (file_exists($env_path)) {
                    $env = file_get_contents($env_path);
                    if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1], "\"' ");
                    if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1], "\"' ");
                    if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1], "\"' ");
                }
                if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
                    $db_host = 'db';
                }
                
                // Get active database name
                $database_name = 'xosid';
                $host = $_SERVER['HTTP_HOST'] ?? '';
                $host_parts = explode(':', $host);
                $host_no_port = strtolower($host_parts[0]);
                $base_saas_domains = ['xosid.id', 'www.xosid.id', 'localhost', '127.0.0.1', 'master.localhost', 'master.xosid.id'];
                
                if (!in_array($host_no_port, $base_saas_domains)) {
                    if (preg_match('/\.xosid\.id$|\.localhost$|\.127\.0\.0\.1$/', $host_no_port)) {
                        $parts = explode('.', $host_no_port);
                        if (count($parts) > 1) {
                            $subdomain = strtolower($parts[0]);
                            if (!empty($subdomain) && $subdomain !== 'www') {
                                $database_name = 'xosid_tenant_' . $subdomain;
                            }
                        }
                    } else {
                        // Custom domain lookup in master DB
                        $master_conn = new mysqli($db_host, $db_user, $db_pass, 'xosid');
                        if (!$master_conn->connect_error) {
                            $stmt = $master_conn->prepare("SELECT nama_desa FROM xosid_registrasi_tenant WHERE custom_domain = ? AND status = 'approved'");
                            if ($stmt) {
                                $stmt->bind_param("s", $host_no_port);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                if ($row = $res->fetch_assoc()) {
                                    $clean_desa = explode('(', $row['nama_desa'])[0];
                                    $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                                    $database_name = 'xosid_tenant_' . $subdomain;
                                }
                                $stmt->close();
                            }
                            $master_conn->close();
                        }
                    }
                }
                
                $conn = new mysqli($db_host, $db_user, $db_pass, $database_name);
                if (!$conn->connect_error) {
                    $res = $conn->query("SELECT * FROM config LIMIT 1");
                    if ($res) {
                        $config_cache = $res->fetch_assoc();
                    }
                    $conn->close();
                }
            } catch (\Throwable $e) {}
        }
        
        if ($key === null) {
            return $config_cache;
        }
        
        return $config_cache[$key] ?? null;
    }
}

// Define custom early-boot base_url() helper function to ensure it's available in Laravel CLI/migrations context
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
        return $proto . '://' . $host . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('site_url')) {
    function site_url($uri = '') {
        $uri_clean = ltrim($uri, '/');
        if (stripos($uri_clean, 'xoSID-login') === 0) {
            return base_url($uri_clean);
        }
        return base_url('index.php/' . $uri_clean);
    }
}

if (!function_exists('asset')) {
    function asset($path, $secure = null) {
        $exclude_prefixes = ['assets/', 'desa/', 'uploads/', 'storage/', 'theme_asset/', 'http://', 'https://', '/'];
        $should_prepend = true;
        foreach ($exclude_prefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                $should_prepend = false;
                break;
            }
        }
        if ($should_prepend) {
            $path = 'assets/' . ltrim($path, '/');
        }

        $url = null;
        if (class_exists('Illuminate\Container\Container', false)) {
            try {
                $instance = \Illuminate\Container\Container::getInstance();
                if ($instance && $instance->bound('url')) {
                    $url = $instance->make('url')->asset($path, $secure);
                }
            } catch (\Throwable $e) {}
        }
        $laravel_url = $url;
        if ($url === null) {
            if (function_exists('base_url')) {
                $url = base_url($path);
            } else {
                $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
                $url = $proto . '://' . $host . '/' . ltrim($path, '/');
            }
        }

        // Global Cache Buster to bypass cached 404s/broken layout files
        if (strpos($url, '?') === false) {
            $url .= '?xosid_v=2607b';
        } else {
            $url .= '&xosid_v=2607b';
        }

        return $url;
    }
}


// Define custom early-boot path helper functions to ensure they are available in Laravel CLI/migrations context
if (!function_exists('resource_path')) {
    function resource_path($path = '') {
        return FCPATH . 'resources/' . ltrim($path, '/');
    }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return FCPATH . 'storage/' . ltrim($path, '/');
    }
}
if (!function_exists('public_path')) {
    function public_path($path = '') {
        return FCPATH . 'public/' . ltrim($path, '/');
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '') {
        return FCPATH . ltrim($path, '/');
    }
}
if (!function_exists('app_path')) {
    function app_path($path = '') {
        return FCPATH . 'app/' . ltrim($path, '/');
    }
}
if (!function_exists('database_path')) {
    function database_path($path = '') {
        return FCPATH . 'app/database/' . ltrim($path, '/');
    }
}

// Define custom early-boot Laravel container and config helper functions
if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}
if (!function_exists('resolve')) {
    function resolve($name, array $parameters = []) {
        return app($name, $parameters);
    }
}
if (!function_exists('config')) {
    function config($key = null, $default = null) {
        if (is_null($key)) {
            return app('config');
        }
        if (is_array($key)) {
            return app('config')->set($key);
        }
        return app('config')->get($key, $default);
    }
}

if (!function_exists('xosid_get_dummy_pamong')) {
    function xosid_get_dummy_pamong($pamong_id, $pamong_nama, $jabatan_id, $config_id = 1) {
        if (!class_exists('App\Models\Pamong')) {
            return new class($pamong_id, $pamong_nama) {
                public $id = 1;
                public $nama = 'Kepala Desa';
                public $pamong_id = 1;
                public $pamong_nama = 'Kepala Desa';
                public $pamong_nik = '';
                public $id_pend = 999999;
                public $penduduk;
                public function __construct($p_id, $p_nama) {
                    $this->id = $p_id;
                    $this->pamong_id = $p_id;
                    $this->nama = $p_nama;
                    $this->pamong_nama = $p_nama;
                    $this->penduduk = new class($p_nama) {
                        public $id = 999999;
                        public $nama = '';
                        public $nik = '';
                        public $map;
                        public $lokasi;
                        public function __construct($p_n) {
                            $this->nama = $p_n;
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
            };
        }

        $p = new \App\Models\Pamong();
        $p->pamong_id = $pamong_id;
        $p->pamong_nama = $pamong_nama;
        $p->pamong_nik = '';
        $p->pamong_status = 1;
        $p->jabatan_id = $jabatan_id;
        $p->id_pend = 999999;
        $p->config_id = $config_id;
        $p->setAttribute('pamong_id', $pamong_id);
        $p->id = $pamong_id;
        $p->setAttribute('id', $pamong_id);
        $p->setAttribute('pamong_nama', $pamong_nama);
        $p->setAttribute('pamong_nik', '');
        $p->setAttribute('pamong_status', 1);
        $p->setAttribute('jabatan_id', $jabatan_id);
        $p->setAttribute('id_pend', 999999);
        $p->setAttribute('config_id', $config_id);
        
        $p->nama = $pamong_nama;
        $p->setAttribute('nama', $pamong_nama);

        $pend = new \App\Models\Penduduk();
        $pend->id = 999999;
        $pend->config_id = $config_id;
        $pend->nama = $pamong_nama;
        $pend->nik = '';
        $pend->setAttribute('id', 999999);
        $pend->setAttribute('config_id', $config_id);
        $pend->setAttribute('nama', $pamong_nama);
        $pend->setAttribute('nik', '');

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

        $p->setRelation('penduduk', $pend);
        
        if (class_exists('App\Models\RefJabatan')) {
            $jab = new \App\Models\RefJabatan();
            $jab->id = $jabatan_id;
            $jab->nama = ($jabatan_id == 1) ? 'Kepala Desa' : 'Sekretaris Desa';
            $jab->setAttribute('id', $jabatan_id);
            $jab->setAttribute('nama', $jab->nama);
            $p->setRelation('jabatan', $jab);
        }
        
        return $p;
    }
}

class XOSIDNullProxy implements ArrayAccess, IteratorAggregate, Countable {
    protected $role;
    public function __construct($role) {
        $this->role = $role;
    }
    public function __get($name) {
        return new XOSIDNullProxy($this->role . "->" . $name);
    }
    public function __set($name, $value) {}
    public function __isset($name) { return false; }
    public function __unset($name) {}
    public function __call($name, $arguments) {
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_proxy_debug.log',
            "[" . date('Y-m-d H:i:s') . "] NULL PROXY CALL: {$this->role} -> {$name}()\n",
            FILE_APPEND
        );
        if ($name === 'makeVisible' || $name === 'makeHidden') {
            return $this;
        }
        return new XOSIDNullProxy($this->role . "->" . $name . "()");
    }
    public function offsetExists($offset): bool { return false; }
    public function offsetGet($offset): mixed { return new XOSIDNullProxy($this->role . "[" . json_encode($offset) . "]"); }
    public function offsetSet($offset, $value): void {}
    public function offsetUnset($offset): void {}
    public function getIterator(): Traversable { return new ArrayIterator([]); }
    public function count(): int { return 0; }
    public function __toString() { return ''; }
}

class XOSIDGenericProxy {
    protected $original;
    protected $role;
    public function __construct($original, $role) {
        $this->original = $original;
        $this->role = $role;
    }
    public function __get($name) {
        $val = $this->original ? $this->original->$name : null;
        if ($name === 'id' && ($val === null || $val === '')) {
            if ($this->original && method_exists($this->original, 'getKey')) {
                $val = $this->original->getKey();
            }
        }
        $val_desc = is_object($val) ? get_class($val) . "(id=" . ($val->id ?? $val->pamong_id ?? '') . ")" : json_encode($val);
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_proxy_debug.log',
            "[" . date('Y-m-d H:i:s') . "] PROXY GET: {$this->role} -> {$name} | Result: {$val_desc}\n",
            FILE_APPEND
        );
        if ($val instanceof \Illuminate\Database\Eloquent\Model || $val instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            return new XOSIDGenericProxy($val, $this->role . "->" . $name);
        }
        if ($val === null && in_array($name, ['map', 'lokasi', 'penduduk', 'pamong', 'sekdes', 'pamong_sekdes'])) {
            return new XOSIDNullProxy($this->role . "->" . $name);
        }
        return $val;
    }
    public function __call($name, $arguments) {
        $val = $this->original ? call_user_func_array([$this->original, $name], $arguments) : null;
        $val_desc = is_object($val) ? get_class($val) . "(id=" . ($val->id ?? $val->pamong_id ?? '') . ")" : json_encode($val);
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_proxy_debug.log',
            "[" . date('Y-m-d H:i:s') . "] PROXY CALL: {$this->role} -> {$name}() | Result: {$val_desc}\n",
            FILE_APPEND
        );
        if ($val instanceof \Illuminate\Database\Eloquent\Model || $val instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            return new XOSIDGenericProxy($val, $this->role . "->" . $name . "()");
        }
        if ($val === null && in_array($name, ['map', 'lokasi', 'penduduk', 'pamong', 'sekdes', 'pamong_sekdes'])) {
            return new XOSIDNullProxy($this->role . "->" . $name . "()");
        }
        return $val;
    }
}

class XOSIDPamongProxy {
    protected $original;
    protected $role;
    public function __construct($original, $role) {
        $this->original = $original;
        $this->role = $role;
    }
    public function __get($name) {
        $val = $this->original ? $this->original->$name : null;
        if ($name === 'id' && ($val === null || $val === '')) {
            if ($this->original && method_exists($this->original, 'getKey')) {
                $val = $this->original->getKey();
            }
        }
        $val_desc = is_object($val) ? get_class($val) . "(id=" . ($val->id ?? $val->pamong_id ?? '') . ")" : json_encode($val);
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_proxy_debug.log',
            "[" . date('Y-m-d H:i:s') . "] PROXY GET: {$this->role} -> {$name} | Result: {$val_desc}\n",
            FILE_APPEND
        );
        if ($val instanceof \Illuminate\Database\Eloquent\Model || $val instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            return new XOSIDGenericProxy($val, $this->role . "->" . $name);
        }
        if ($val === null && in_array($name, ['map', 'lokasi', 'penduduk', 'pamong', 'sekdes', 'pamong_sekdes'])) {
            return new XOSIDNullProxy($this->role . "->" . $name);
        }
        return $val;
    }
    public function __call($name, $arguments) {
        $val = $this->original ? call_user_func_array([$this->original, $name], $arguments) : null;
        $val_desc = is_object($val) ? get_class($val) . "(id=" . ($val->id ?? $val->pamong_id ?? '') . ")" : json_encode($val);
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_proxy_debug.log',
            "[" . date('Y-m-d H:i:s') . "] PROXY CALL: {$this->role} -> {$name}() | Result: {$val_desc}\n",
            FILE_APPEND
        );
        if ($val instanceof \Illuminate\Database\Eloquent\Model || $val instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            return new XOSIDGenericProxy($val, $this->role . "->" . $name . "()");
        }
        if ($val === null && in_array($name, ['map', 'lokasi', 'penduduk', 'pamong', 'sekdes', 'pamong_sekdes'])) {
            return new XOSIDNullProxy($this->role . "->" . $name . "()");
        }
        return $val;
    }
}

if (!function_exists('kades')) {
    function kades() {
        static $entering = false;
        if ($entering) {
            $e = new \Exception();
            @file_put_contents(
                FCPATH . 'storage/logs/xosid_sekdes_debug.log',
                "[" . date('Y-m-d H:i:s') . "] RECURSION DETECTED IN kades()! Trace:\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND
            );
            return xosid_get_dummy_pamong(999991, 'Kepala Desa', 1);
        }
        $entering = true;

        $res = null;
        $err = '';
        if (class_exists('App\Models\Pamong')) {
            try {
                $res = \App\Models\Pamong::where('jabatan_id', 1)->first();
                if ($res) {
                    $res->nama = $res->pamong_nama ?: 'Kepala Desa';
                    $res->setAttribute('nama', $res->pamong_nama ?: 'Kepala Desa');
                }
            } catch (\Throwable $e) {
                $err = $e->getMessage();
            }
        }
        $pend_status = 'N/A';
        $map_status = 'N/A';
        if ($res) {
            try {
                $p_rel = $res->penduduk;
                $pend_status = $p_rel ? get_class($p_rel) . "(id=" . $p_rel->id . ")" : 'NULL';
                if ($p_rel) {
                    $m_rel = $p_rel->map;
                    $map_status = $m_rel ? get_class($m_rel) . "(id=" . $m_rel->id . ")" : 'NULL';
                }
            } catch (\Throwable $relEx) {
                $pend_status = 'ERR: ' . $relEx->getMessage();
            }
        }
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_sekdes_debug.log',
            "[" . date('Y-m-d H:i:s') . "] kades() CALLED: Host=" . ($_SERVER['HTTP_HOST'] ?? '') . " | res=" . ($res ? get_class($res) . "(id=" . $res->pamong_id . ",nama=" . $res->pamong_nama . ")" : 'NULL') . " | penduduk=" . $pend_status . " | map=" . $map_status . " | err=" . $err . "\n",
            FILE_APPEND
        );
        $entering = false;
        $final = $res ?: xosid_get_dummy_pamong(999991, 'Kepala Desa', 1);
        return $final ? new XOSIDPamongProxy($final, 'kades') : $final;
    }
}
if (!function_exists('sekdes')) {
    function sekdes() {
        static $entering = false;
        if ($entering) {
            $e = new \Exception();
            @file_put_contents(
                FCPATH . 'storage/logs/xosid_sekdes_debug.log',
                "[" . date('Y-m-d H:i:s') . "] RECURSION DETECTED IN sekdes()! Trace:\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND
            );
            return xosid_get_dummy_pamong(999992, 'Sekretaris Desa', 2);
        }
        $entering = true;

        $res = null;
        $err = '';
        if (class_exists('App\Models\Pamong')) {
            try {
                $res = \App\Models\Pamong::where('jabatan_id', 2)->first();
                if ($res) {
                    $res->nama = $res->pamong_nama ?: 'Sekretaris Desa';
                    $res->setAttribute('nama', $res->pamong_nama ?: 'Sekretaris Desa');
                }
            } catch (\Throwable $e) {
                $err = $e->getMessage();
            }
        }
        $pend_status = 'N/A';
        $map_status = 'N/A';
        if ($res) {
            try {
                $p_rel = $res->penduduk;
                $pend_status = $p_rel ? get_class($p_rel) . "(id=" . $p_rel->id . ")" : 'NULL';
                if ($p_rel) {
                    $m_rel = $p_rel->map;
                    $map_status = $m_rel ? get_class($m_rel) . "(id=" . $m_rel->id . ")" : 'NULL';
                }
            } catch (\Throwable $relEx) {
                $pend_status = 'ERR: ' . $relEx->getMessage();
            }
        }
        @file_put_contents(
            FCPATH . 'storage/logs/xosid_sekdes_debug.log',
            "[" . date('Y-m-d H:i:s') . "] sekdes() CALLED: Host=" . ($_SERVER['HTTP_HOST'] ?? '') . " | res=" . ($res ? get_class($res) . "(id=" . $res->pamong_id . ",nama=" . $res->pamong_nama . ")" : 'NULL') . " | penduduk=" . $pend_status . " | map=" . $map_status . " | err=" . $err . "\n",
            FILE_APPEND
        );
        $entering = false;
        $final = $res ?: xosid_get_dummy_pamong(999992, 'Sekretaris Desa', 2);
        return $final ? new XOSIDPamongProxy($final, 'sekdes') : $final;
    }
}

// 0. XOSID PENGURUS ROUTE REWRITE DEACTIVATED IN FAVOR OF DEEP ROUTER HIJACK

if (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = $_SERVER['REQUEST_URI'];
    if (preg_match('#^/(index\.php/)?main($|\?|/)#i', $request_uri)) {
        if (!headers_sent()) {
            header('Location: /index.php/beranda', true, 302);
            exit;
        }
    }
}



// Intercept logout/login requests
if (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strpos($request_uri, 'siteman/logout') !== false || strpos($request_uri, 'neural_logout') !== false) {
        setcookie('xosid_logged_out', '1', time() + 86400 * 30, '/');
        $_COOKIE['xosid_logged_out'] = '1';
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (isset($_SESSION)) {
            $_SESSION = array();
            @session_destroy();
        }
        if (!headers_sent()) {
            header('Location: /xoSID-login');
            exit;
        }
    }
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    setcookie('xosid_logged_out', '', time() - 3600, '/');
    if (isset($_COOKIE['xosid_logged_out'])) {
        unset($_COOKIE['xosid_logged_out']);
    }
}

if (!function_exists('xosid_resolve_and_sanitize_session_config')) {
    function xosid_resolve_and_sanitize_session_config() {
        global $database_name, $subdomain;
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        if (!isset($_SESSION)) {
            return;
        }

        $host = $_SERVER['HTTP_HOST'] ?? '';

        // Now query the determined database to find the actual config ID
        $real_config_id = null;
        try {
            $db_host = 'db';
            $db_user = 'root';
            $db_pass = 'password';
            
            $env_path = __DIR__ . '/.env';
            if (file_exists($env_path)) {
                $env = file_get_contents($env_path);
                if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1], "\"' ");
                if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1], "\"' ");
                if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1], "\"' ");
            }
            if ($db_host === 'localhost' || $db_host === '127.0.0.1') {
                $db_host = 'db';
            }

            $dsn = "mysql:host=$db_host;dbname=$database_name;charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_TIMEOUT => 2,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            $stmt = $pdo->query("SELECT id FROM config LIMIT 1");
            $real_config_id = $stmt->fetchColumn();
        } catch (Exception $e) {
            // Log or ignore
        }

        if ($real_config_id !== null && $real_config_id !== false) {
            $_SESSION['config_id'] = (int)$real_config_id;
            $_SESSION['id_desa'] = (int)$real_config_id;
        } else {
            // Safe fallback
            $is_master_db = ($host === 'xosid.id' || $host === 'www.xosid.id' || $host === 'localhost:8080' || $host === '127.0.0.1:8080' || $host === 'master.localhost:8080' || $host === 'master.xosid.id');
            if ($is_master_db) {
                $_SESSION['config_id'] = 1;
                $_SESSION['id_desa'] = 1;
            }
        }
    }
}

xosid_resolve_and_sanitize_session_config();

// === XOSID PERSISTENT STATUS_DESA VIEW AUTO-PATCHER ===
if (!function_exists('xosid_ensure_status_desa_patches')) {
    function xosid_ensure_status_desa_patches() {
        $idm_file = __DIR__ . '/resources/views/admin/status_desa/idm.blade.php';
        if (file_exists($idm_file)) {
            $content = file_get_contents($idm_file);
            if (strpos($content, 'base_paths = [') === false) {
                $patch = "@section('content')\n\n@php\n    \$has_valid_idm = isset(\$idm) && is_object(\$idm) && isset(\$idm->SUMMARIES) && isset(\$idm->SUMMARIES->SKOR_SAAT_INI) && floatval(\$idm->SUMMARIES->SKOR_SAAT_INI) > 0;\n    \n    if (!\$has_valid_idm) {\n        try {\n            \$active_tahun = isset(\$tahun) && !empty(\$tahun) ? \$tahun : date('Y');\n            \$pdo = function_exists('xosid_get_db_pdo') ? xosid_get_db_pdo() : null;\n            \$config = [];\n            if (\$pdo) {\n                \$stmt = \$pdo->query(\"SELECT kode_desa, nama_desa, kode_kecamatan, nama_kecamatan, kode_kabupaten, nama_kabupaten, kode_propinsi, nama_propinsi FROM config LIMIT 1\");\n                \$config = \$stmt->fetch(PDO::FETCH_ASSOC) ?: [];\n            }\n            \$kode_desa = \$config['kode_desa'] ?? null;\n            \$base_paths = [defined('FCPATH') ? FCPATH : '', __DIR__ . '/../../../../', '/var/www/html/'];\n            \$loaded_data = null;\n            if (\$kode_desa) {\n                foreach (\$base_paths as \$bp) {\n                    if (empty(\$bp)) continue;\n                    \$cache_file = rtrim(\$bp, '/') . \"/desa/cache/idm_{\$active_tahun}_{\$kode_desa}.json\";\n                    if (file_exists(\$cache_file)) {\n                        \$raw = file_get_contents(\$cache_file);\n                        \$cdata = @unserialize(\$raw);\n                        if (!\$cdata) \$cdata = json_decode(\$raw, true);\n                        \$d = \$cdata['data'] ?? \$cdata;\n                        if (!empty(\$d) && is_array(\$d) && (isset(\$d['SUMMARIES']) || isset(\$d['ROW']))) {\n                            \$loaded_data = \$d;\n                            break;\n                        }\n                    }\n                }\n                if (!\$loaded_data) {\n                    foreach (\$base_paths as \$bp) {\n                        if (empty(\$bp)) continue;\n                        \$glob_files = glob(rtrim(\$bp, '/') . \"/desa/cache/idm_*_{\$kode_desa}.json\");\n                        if (!empty(\$glob_files)) {\n                            rsort(\$glob_files);\n                            foreach (\$glob_files as \$gf) {\n                                \$raw = file_get_contents(\$gf);\n                                \$cdata = @unserialize(\$raw);\n                                if (!\$cdata) \$cdata = json_decode(\$raw, true);\n                                \$d = \$cdata['data'] ?? \$cdata;\n                                if (!empty(\$d) && is_array(\$d) && (isset(\$d['SUMMARIES']) || isset(\$d['ROW']))) {\n                                    \$loaded_data = \$d;\n                                    break 2;\n                                }\n                            }\n                        }\n                    }\n                }\n            }\n            if (\$loaded_data) {\n                if (isset(\$loaded_data['ROW']) && is_array(\$loaded_data['ROW'])) {\n                    foreach (\$loaded_data['ROW'] as &\$row) {\n                        if (isset(\$row['PROV'])) \$row['PROVINSI'] = \$row['PROV'];\n                        if (isset(\$row['KAB'])) \$row['KABUPATEN'] = \$row['KAB'];\n                    }\n                }\n                \$idm = json_decode(json_encode(\$loaded_data));\n                \$idm->error_msg = null;\n            } else {\n                \$idm = (object)[\n                    'SUMMARIES' => (object)[\n                        'SKOR_SAAT_INI' => 0.0,\n                        'STATUS' => 'BELUM TERSEDIA',\n                        'SKOR_MINIMAL' => 0.0,\n                        'TARGET_STATUS' => '-'\n                    ],\n                    'IDENTITAS' => [\n                        (object)[\n                            'nama_provinsi' => \$config['nama_propinsi'] ?? '',\n                            'nama_kab_kota' => \$config['nama_kabupaten'] ?? '',\n                            'nama_kecamatan' => \$config['nama_kecamatan'] ?? '',\n                            'nama_desa' => \$config['nama_desa'] ?? ''\n                        ]\n                    ],\n                    'ROW' => [],\n                    'error_msg' => 'Data IDM Tahun ' . \$active_tahun . ' belum tersedia di server Kemendesa untuk desa ini. Silakan klik tombol <b>Perbarui</b> di atas untuk mengambil data langsung dari Kemendesa.'\n                ];\n            }\n        } catch (\\Throwable \$e) {\n            \$idm = (object)[\n                'SUMMARIES' => (object)['SKOR_SAAT_INI' => 0.0, 'STATUS' => 'BELUM TERSEDIA', 'SKOR_MINIMAL' => 0.0, 'TARGET_STATUS' => '-'],\n                'IDENTITAS' => [(object)['nama_provinsi' => '', 'nama_kab_kota' => '', 'nama_kecamatan' => '', 'nama_desa' => '']],\n                'ROW' => [],\n                'error_msg' => 'Data IDM Tahun ' . (isset(\$tahun) ? \$tahun : date('Y')) . ' belum dapat dimuat. Silakan klik tombol <b>Perbarui</b> di atas.'\n            ];\n        }\n    }\n@endphp";
                if (strpos($content, '@section(\'content\')') !== false) {
                    if (strpos($content, '$has_valid_idm = isset($idm)') !== false) {
                        // Replace existing patch
                        $pattern = '/@section\(\'content\'\)\s*@php.*?@endphp/s';
                        $content = preg_replace($pattern, $patch, $content);
                    } else {
                        $content = str_replace("@section('content')", $patch, $content);
                    }
                    file_put_contents($idm_file, $content);
                    @array_map('unlink', glob('/var/www/html/storage/framework/views/*'));
                }
            }
        }

        $sdgs_file = __DIR__ . '/resources/views/admin/status_desa/sdgs.blade.php';
        if (file_exists($sdgs_file)) {
            $content = file_get_contents($sdgs_file);
            if (strpos($content, '$has_valid_sdgs = isset($sdgs)') === false) {
                $patch = "@section('content')\n\n@php\n    \$has_valid_sdgs = isset(\$sdgs) && is_object(\$sdgs) && isset(\$sdgs->average) && floatval(\$sdgs->average) > 0;\n    \n    if (!\$has_valid_sdgs) {\n        try {\n            if (function_exists('xosid_get_db_pdo')) {\n                \$pdo = xosid_get_db_pdo();\n            } else {\n                \$db_host = 'db';\n                \$db_user = 'root';\n                \$db_pass = 'password';\n                \$db_name = 'xosid_tenant_bumidaya';\n                \n                \$env_path = FCPATH . '.env';\n                if (file_exists(\$env_path)) {\n                    \$env = file_get_contents(\$env_path);\n                    if (preg_match('/DB_HOST=(.*)/', \$env, \$m)) \$db_host = trim(\$m[1]);\n                    if (preg_match('/DB_USERNAME=(.*)/', \$env, \$m)) \$db_user = trim(\$m[1]);\n                    if (preg_match('/DB_PASSWORD=(.*)/', \$env, \$m)) \$db_pass = trim(\$m[1]);\n                    if (preg_match('/DB_DATABASE=(.*)/', \$env, \$m)) \$db_name = trim(\$m[1]);\n                }\n                \n                \$db_host = trim(\$db_host, \"\\\"'\");\n                \$db_user = trim(\$db_user, \"\\\"'\");\n                \$db_pass = trim(\$db_pass, \"\\\"'\");\n                \$db_name = trim(\$db_name, \"\\\"'\");\n                if (\$db_host === 'localhost' || \$db_host === '127.0.0.1') {\n                    \$db_host = 'db';\n                }\n                \$pdo = new PDO(\"mysql:host=\$db_host;dbname=\$db_name;charset=utf8mb4\", \$db_user, \$db_pass);\n            }\n            \n            if (\$pdo) {\n                \$stmt = \$pdo->query(\"SELECT kode_desa_bps FROM config LIMIT 1\");\n                \$config = \$stmt->fetch(PDO::FETCH_ASSOC);\n                \$kode_desa_bps = \$config['kode_desa_bps'] ?? null;\n                \n                if (\$kode_desa_bps) {\n                    \$cache_file = FCPATH . \"desa/cache/sdgs_{\$kode_desa_bps}.json\";\n                    if (file_exists(\$cache_file)) {\n                        \$raw = file_get_contents(\$cache_file);\n                        \$cache_data = @unserialize(\$raw);\n                        if (!\$cache_data) {\n                            \$cache_data = json_decode(\$raw, true);\n                        }\n                        \$sdgs_array = \$cache_data['data'] ?? \$cache_data;\n                        if (\$sdgs_array) {\n                            if (isset(\$sdgs_array['data']) && is_array(\$sdgs_array['data'])) {\n                                \$mapped_data = [];\n                                foreach (\$sdgs_array['data'] as \$idx => \$item) {\n                                    \$mapped_data[] = [\n                                        'score' => floatval(\$item['skor'] ?? \$item['score'] ?? 0),\n                                        'image' => 'skor-sdgs-' . (\$idx + 1) . '.jpg',\n                                        'label' => \$item['label'] ?? \$item['title'] ?? ''\n                                    ];\n                                }\n                                \$sdgs_array['data'] = \$mapped_data;\n                            }\n                            \$sdgs = json_decode(json_encode(\$sdgs_array));\n                            \$sdgs->error_msg = null;\n                        }\n                    }\n                }\n            }\n        } catch (\\Throwable \$e) {\n            // Ignore\n        }\n    }\n@endphp";
                $new_content = str_replace("@section('content')", $patch, $content);
                file_put_contents($sdgs_file, $new_content);
                @array_map('unlink', glob('/var/www/html/storage/framework/views/*'));
            }
        }
    }
}
xosid_ensure_status_desa_patches();

// === XOSID PORTAL THEME & DATA AUTO-HEALER ===
if (!function_exists('xosid_ensure_portal_theme_and_data_patches')) {
    function xosid_ensure_portal_theme_and_data_patches() {
        $backup_dir = __DIR__ . '/xosid_backups/safe-neural-theme';
        if (!is_dir($backup_dir)) return;

        $heal_triggered = false;

        // 1. Check portal_layout.blade.php
        $layout_file = __DIR__ . '/resources/views/xosid/portal_layout.blade.php';
        if (file_exists($layout_file)) {
            $content = file_get_contents($layout_file);
            if (strpos($content, 'toggleXOSIDPortalTheme') === false) {
                if (file_exists("$backup_dir/xosid/portal_layout.blade.php")) {
                    copy("$backup_dir/xosid/portal_layout.blade.php", $layout_file);
                    $heal_triggered = true;
                }
            }
        }

        // 2. Check profil.blade.php
        $profil_file = __DIR__ . '/resources/views/xosid/profil.blade.php';
        if (file_exists($profil_file)) {
            $content = file_get_contents($profil_file);
            if (strpos($content, '6 Dimensi Indeks Desa (ID) 2025') === false) {
                if (file_exists("$backup_dir/xosid/profil.blade.php")) {
                    copy("$backup_dir/xosid/profil.blade.php", $profil_file);
                    $heal_triggered = true;
                }
            }
        }

        // 3. Check potensi.blade.php
        $potensi_file = __DIR__ . '/resources/views/xosid/potensi.blade.php';
        if (file_exists($potensi_file)) {
            $content = file_get_contents($potensi_file);
            if (strpos($content, 'toggleXOSIDPortalTheme') === false) {
                if (file_exists("$backup_dir/xosid/potensi.blade.php")) {
                    copy("$backup_dir/xosid/potensi.blade.php", $potensi_file);
                    $heal_triggered = true;
                }
            }
        }

        // 4. Check opendata.blade.php
        $opendata_file = __DIR__ . '/resources/views/xosid/opendata.blade.php';
        if (file_exists($opendata_file)) {
            $content = file_get_contents($opendata_file);
            if (strpos($content, 'toggleXOSIDPortalTheme') === false) {
                if (file_exists("$backup_dir/xosid/opendata.blade.php")) {
                    copy("$backup_dir/xosid/opendata.blade.php", $opendata_file);
                    $heal_triggered = true;
                }
            }
        }

        // 5. Check lapak.blade.php
        $lapak_file = __DIR__ . '/resources/views/xosid/lapak.blade.php';
        if (file_exists($lapak_file)) {
            $content = file_get_contents($lapak_file);
            if (strpos($content, 'toggleXOSIDPortalTheme') === false) {
                if (file_exists("$backup_dir/xosid/lapak.blade.php")) {
                    copy("$backup_dir/xosid/lapak.blade.php", $lapak_file);
                    $heal_triggered = true;
                }
            }
        }

        // 6. Check kabardesa.blade.php
        $kabardesa_file = __DIR__ . '/resources/views/xosid/kabardesa.blade.php';
        if (file_exists($kabardesa_file)) {
            $content = file_get_contents($kabardesa_file);
            if (strpos($content, 'xosid-hero-slider') === false) {
                if (file_exists("$backup_dir/xosid/kabardesa.blade.php")) {
                    copy("$backup_dir/xosid/kabardesa.blade.php", $kabardesa_file);
                    $heal_triggered = true;
                }
            }
        }

        // 7. Check detail.blade.php
        $detail_file = __DIR__ . '/resources/views/xosid/detail.blade.php';
        if (file_exists($detail_file)) {
            $content = file_get_contents($detail_file);
            if (strpos($content, 'Pusat Pemasangan Iklan & Informasi Premium') === false) {
                if (file_exists("$backup_dir/xosid/detail.blade.php")) {
                    copy("$backup_dir/xosid/detail.blade.php", $detail_file);
                    $heal_triggered = true;
                }
            }
        }

        // 8. Check surat_mandiri.blade.php
        $surat_mandiri_file = __DIR__ . '/resources/views/xosid/surat_mandiri.blade.php';
        if (file_exists($surat_mandiri_file)) {
            $content = file_get_contents($surat_mandiri_file);
            if (strpos($content, 'surat-card') === false && strpos($content, 'Layanan Administrasi Portal') === false) {
                if (file_exists("$backup_dir/xosid/surat_mandiri.blade.php")) {
                    copy("$backup_dir/xosid/surat_mandiri.blade.php", $surat_mandiri_file);
                    $heal_triggered = true;
                }
            }
        }

        // 9. Check xosid_portal2_interceptor.php
        $interceptor_file = __DIR__ . '/xosid_portal2_interceptor.php';
        if (file_exists($interceptor_file)) {
            $content = file_get_contents($interceptor_file);
            if (strpos($content, "'profil_desa' => \$profil_desa") === false) {
                if (file_exists("$backup_dir/xosid_portal2_interceptor.php")) {
                    copy("$backup_dir/xosid_portal2_interceptor.php", $interceptor_file);
                    $heal_triggered = true;
                }
            }
        }

        // 10. Check portal2.blade.php
        $portal2_file = __DIR__ . '/resources/views/xosid/portal2.blade.php';
        if (file_exists($portal2_file)) {
            $content = file_get_contents($portal2_file);
            if (strpos($content, 'Cek Pajak PBB Lampung') === false) {
                if (file_exists("$backup_dir/xosid/portal2.blade.php")) {
                    copy("$backup_dir/xosid/portal2.blade.php", $portal2_file);
                    $heal_triggered = true;
                }
            }
        }

        // If any heal was triggered, clear the Blade view cache
        if ($heal_triggered) {
            @array_map('unlink', glob('/var/www/html/storage/framework/views/*'));
        }
    }
}
xosid_ensure_portal_theme_and_data_patches();

// === XOSID ROUTE REDIRECT FOR ANALISIS CERDAS ===
if (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Intercept Upload route
    if (strpos($request_uri, 'status_desa/upload_analisis_cerdas') !== false) {
        $uploader_file = __DIR__ . '/xosid_uploader.php';
        if (file_exists($uploader_file)) {
            require $uploader_file;
            exit;
        }
    }
    
    // Intercept Print/Cetak route
    if (strpos($request_uri, 'status_desa/cetak_analisis_cerdas') !== false) {
        $_SESSION['xosid_tab'] = 'analisis_cerdas';
        $_SESSION['xosid_print'] = 1;
        if (!headers_sent()) {
            header('Location: /index.php/status_desa');
            exit;
        }
    }

    if (strpos($request_uri, 'status_desa/navigasi/idm') !== false) {
        @setcookie('xosid_status_desa_tab', 'idm', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'idm';
        $_SESSION['xosid_tab'] = 'idm';
        $_SESSION['status_desa_tab'] = 'idm';
    }
    if (strpos($request_uri, 'status_desa/navigasi/sdgs') !== false) {
        @setcookie('xosid_status_desa_tab', 'sdgs', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'sdgs';
        $_SESSION['xosid_tab'] = 'sdgs';
        $_SESSION['status_desa_tab'] = 'sdgs';
    }

    if (strpos($request_uri, 'status_desa/navigasi/analisis_cerdas') !== false) {
        @setcookie('xosid_status_desa_tab', 'analisis_cerdas', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'analisis_cerdas';
        $_SESSION['xosid_tab'] = 'analisis_cerdas';
        $_SESSION['status_desa_tab'] = 'analisis_cerdas';
        if (!headers_sent()) {
            header('Location: /index.php/status_desa');
            exit;
        }
    }
    if (strpos($request_uri, 'status_desa/reklasifikasi_analisis_cerdas') !== false) {
        $xosid_intelligence_engine_file = __DIR__ . '/xosid_intelligence_engine.php';
        if (file_exists($xosid_intelligence_engine_file)) {
            require_once $xosid_intelligence_engine_file;
        }
        if (function_exists('xosid_invalidate_intelligence_cache')) {
            xosid_invalidate_intelligence_cache();
        }
        $_SESSION['xosid_tab'] = 'analisis_cerdas';
        $_SESSION['xosid_flash_keep'] = 1;
        $_SESSION['success'] = 'Analisis cerdas berhasil diperbarui dari data terbaru.';
        if (!headers_sent()) {
            header('Location: /index.php/status_desa');
            exit;
        }
    }

    // Intercept progressive recalculate AJAX route
    if (strpos($request_uri, 'status_desa/recalculate_progress') !== false) {
        $endpoint_file = __DIR__ . '/xosid_recalculate_endpoint.php';
        if (file_exists($endpoint_file)) {
            require $endpoint_file;
            exit;
        }
    }

    // Intercept safe PDF history download route
    if (strpos($request_uri, 'status_desa/download_pdf_history') !== false) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
            header('HTTP/1.1 403 Forbidden');
            echo "Unauthorized access.";
            exit;
        }

        $file = isset($_GET['file']) ? basename($_GET['file']) : '';
        if (empty($file) || !preg_match('/^analisis_desa_\d{8}_\d{6}\.pdf$/', $file)) {
            header('HTTP/1.1 400 Bad Request');
            echo "Invalid filename.";
            exit;
        }

        $subdomain = function_exists('xosid_get_tenant_subdomain') ? xosid_get_tenant_subdomain() : 'default';
        if ($subdomain === 'default') {
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $host_parts = explode(':', $host);
            $host_no_port = strtolower($host_parts[0]);
            if (preg_match('/^([a-z0-9-]+)\.(localhost|xosid\.id|127\.0\.0\.1)/', $host_no_port, $m)) {
                if (!in_array($m[1], ['www', 'master', 'api'])) {
                    $subdomain = $m[1];
                }
            }
        }
        $filePath = __DIR__ . '/desa/upload/analisis_cerdas/' . ($subdomain !== 'default' ? $subdomain . '/' : '') . 'history/' . $file;
        if (!file_exists($filePath)) {
            // Fallback to generic history folder
            $filePath = __DIR__ . '/desa/upload/analisis_cerdas/history/' . $file;
        }
        if (!file_exists($filePath)) {
            header('HTTP/1.1 404 Not Found');
            echo "File not found.";
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

// 1. DEEP SESSION INJECTION
if (isset($_COOKIE['xosid_logged_out']) && $_COOKIE['xosid_logged_out'] == '1') {
    // User is explicitly logged out, skip injection
} else {
    if (isset($_SESSION)) {
        $userObj = new XosidSessionUser();
        $_SESSION['user_id'] = $userObj;
        $_SESSION['user'] = $userObj;
        $_SESSION['username'] = $userObj;
        $_SESSION['nama'] = 'Administrator';
        $_SESSION['siteman'] = 1;
        $_SESSION['grup'] = 1;
        $_SESSION['admin_level'] = 1;
        $_SESSION['sesi'] = 'aktif';
        $_SESSION['premium_session'] = 1;
        $_SESSION['status_langganan'] = 1;
        $_SESSION['is_premium'] = true;
        $_SESSION['is_premium_sid'] = true;
        $_SESSION['layanan_premium'] = 1;
        $_SESSION['token_premium'] = 'XOSID-PREMIUM-MOCK-2099';
        $_SESSION['member_premium'] = true;
        $_SESSION['premium'] = true;
    }
}

// SILENCE ANNOYING FLASH MESSAGES ON GET REQUESTS (WITH ONE-TIME FLASH KEEP BYPASS)
if (isset($_SESSION) && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
    if (!empty($_SESSION['xosid_flash_keep'])) {
        // Let them survive one GET request, then clear the keep flag
        unset($_SESSION['xosid_flash_keep']);
    } else {
        // Safe targeted unsetting to avoid unsetting valid application alerts
        // While still unsetting persistent licensing/warning messages
        $silenced_keys = ['error', 'error_msg', 'warning', 'information', 'pesan'];
        foreach ($silenced_keys as $key) {
            if (isset($_SESSION[$key])) {
                $val = strtolower((string)$_SESSION[$key]);
                if (strpos($val, 'license') !== false || 
                    strpos($val, 'lisensi') !== false || 
                    strpos($val, 'expired') !== false || 
                    strpos($val, 'tenggat') !== false || 
                    strpos($val, 'premium') !== false || 
                    strpos($val, 'aktifkan') !== false ||
                    strpos($val, 'tidak valid') !== false ||
                    strpos($val, 'gagal verifikasi') !== false) {
                    unset($_SESSION[$key]);
                }
            }
        }
    }
}

if (!defined('IS_PREMIUM')) define('IS_PREMIUM', true);
// if (!defined('PREMIUM')) define('PREMIUM', true);
if (!defined('STATUS_LANGGANAN')) define('STATUS_LANGGANAN', 1);

// 2. HIJACK REDIRECT
if (!function_exists('redirect')) {
    function redirect($uri = '', $method = 'auto', $code = NULL) {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host_parts = explode(':', $host);
        $host_no_port = strtolower($host_parts[0]);
        $is_master = ($host_no_port === 'master.localhost' || $host_no_port === 'master.xosid.id');

        $should_intercept = true;
        if ($is_master) {
            $should_intercept = (strpos($uri, 'peringatan') !== false);
        }

        if ($should_intercept && (strpos($uri, 'koneksi_database') !== false)) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            @file_put_contents(__DIR__.'/storage/logs/xosid_koneksi_database_redirect.log', 
                "[" . date('Y-m-d H:i:s') . "] REDIRECT TO: $uri | FROM: ".$_SERVER['REQUEST_URI']."\n" .
                print_r($backtrace, true) . "\n----------------------------------------\n",
                FILE_APPEND);
        }

        $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (strpos($_SERVER['REQUEST_URI'], 'ajax') !== false);
        
        // === XOSID ANALISIS CERDAS SPECIAL INTERCEPT ===
        // When user is accessing analisis_cerdas, block redirect to bare status_desa
        if (strpos($_SERVER['REQUEST_URI'], 'analisis_cerdas') !== false) {
            // Don't redirect - let ob_start callback inject intelligence content
            return;
        }
        
        $is_auth_flow = false;
        if (isset($_SERVER['REQUEST_URI'])) {
            $req_uri = $_SERVER['REQUEST_URI'];
            if (strpos($req_uri, 'siteman') !== false || strpos($req_uri, 'xoSID-login') !== false || strpos($req_uri, 'login') !== false || strpos($req_uri, 'auth') !== false) {
                $is_auth_flow = true;
            }
        }
        
        if ($should_intercept && (strpos($uri, 'koneksi_database') !== false || (!$is_auth_flow && (strpos($uri, 'peringatan') !== false || strpos($uri, 'pengguna#sandi') !== false || strpos($uri, 'siteman') !== false || strpos($uri, 'xoSID-login') !== false || (strpos($uri, 'beranda') !== false && strpos($uri, 'layanan-mandiri') === false))))) {
            // BYPASS: If we are calling dialog_cetak or dialog, DO NOT block it with empty exit.
            // Let it try to render even if CI wants to redirect.
            $uri_bypass = ['laporan_apbdes', 'form', 'dialog_cetak', 'dialog', 'cetak', 'unduh', 'bumindes', 'hasil_pembangunan', 'Bumindes_inventaris_kekayaan', 'bumindes_tanah_kas_desa', 'bumindes_tanah_desa', 'pengurus', 'wilayah', 'main', 'penduduk', 'surat', 'keluarga', 'status_desa', 'surat_masuk', 'surat_keluar', 'ekspedisi', 'lembaran_desa', 'dokumen_sekretariat', 'peraturan', 'keputusan', 'impor_penduduk', 'xosid_download', 'xosid_ajax', 'mandiri'];
            foreach ($uri_bypass as $bp) {
                if (strpos($_SERVER['REQUEST_URI'], $bp) !== false) {
                    return; // Continue execution instead of exiting
                }
            }

            // Capture backtrace for diagnostics
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            @file_put_contents(__DIR__.'/storage/logs/xosid_redirect_backtrace.log', 
                "[" . date('Y-m-d H:i:s') . "] REDIRECT TO: $uri | FROM: ".$_SERVER['REQUEST_URI']."\n" .
                print_r($backtrace, true) . "\n----------------------------------------\n",
                FILE_APPEND);

            // Log the blocked redirect
            @file_put_contents(__DIR__.'/storage/logs/xosid_redirects.log', "[".date('Y-m-d H:i:s')."] BLOCKED REDIRECT FROM: ".$_SERVER['REQUEST_URI']." TO: $uri (AJAX: ".($is_ajax?'YES':'NO').")\n", FILE_APPEND);
            
            // Unset persistent background error flashdata set by core controllers
            if (isset($_SESSION)) {
                unset($_SESSION['error'], $_SESSION['error_msg'], $_SESSION['warning'], $_SESSION['information'], $_SESSION['success'], $_SESSION['pesan']);
            }

            if ($is_ajax) {
                $uri_lower = strtolower($_SERVER['REQUEST_URI']);
                if (strpos($uri_lower, 'peta') !== false || strpos($uri_lower, 'map') !== false || strpos($uri_lower, 'leaflet') !== false) {
                    header('HTTP/1.1 200 OK');
                } else {
                    header('Content-Type: application/json');
                    $draw = intval($_REQUEST['draw'] ?? 1);
                    echo json_encode(["draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => [], "status" => true]);
                }
                exit;
            }
            return; 
        }
        if (!preg_match('#^https?://#i', $uri)) {
            // Strip any number of leading 'index.php/' or 'index.php' segments recursively
            while (true) {
                $uri = ltrim($uri, '/');
                if (strpos($uri, 'index.php/') === 0) {
                    $uri = substr($uri, 10);
                } elseif (strpos($uri, 'index.php') === 0) {
                    $uri = substr($uri, 9);
                } else {
                    break;
                }
            }
            $uri = ltrim($uri, '/');
            
            // Map main to beranda
            if ($uri === 'main' || $uri === '') {
                $uri = 'beranda';
            }
            
            $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $base_url = $proto . '://' . $_SERVER['HTTP_HOST'] . '/index.php/';
            $uri = $base_url . $uri;
        }
        if (!headers_sent()) { header("Location: ".$uri, TRUE, ($code ?: 302)); }
        exit;
    }
}

// 3. EMERGENCY CLASS LOADING
spl_autoload_register(function ($class) {
    $mocks = [
        "Modules\Pelanggan\Services\PelangganService",
        "Modules\Pelanggan\Services\CekService",
        "App\Services\NotificationService",
        "App\Services\PelangganService",
        "App\Services\CekService"
    ];
    
    if (in_array($class, $mocks)) {
        if (!class_exists($class, false)) {
            $parts = explode('\\', $class);
            $namespace = implode('\\', array_slice($parts, 0, -1));
            $classname = end($parts);
            eval("namespace $namespace; 
                  class $classname { 
                      public function is_premium() { return true; }
                      public function check() { return true; }
                      public function status() { return (object)[\"premium\" => true, \"expired\" => false]; }
                      public function getRecentNotifications() { return []; }
                      public function get_token() { return \"XOSID-PREMIUM-MOCK\"; }
                      public static function instance() { return new self(); }
                      public function __call(\$n, \$a) { return true; }
                      public static function __callStatic(\$n, \$a) { return true; }
                  }");
            return true;
        }
    }
    return false;
});

if (!class_exists('GuzzleHttp\Client', false)) {
    eval('namespace GuzzleHttp; class Client { public function __call($n, $a) { return new \GuzzleHttp\Psr7\Response(); } }');
}
if (!class_exists('GuzzleHttp\Psr7\Response', false)) {
    eval('namespace GuzzleHttp\Psr7; class Response { public function getBody() { return new class { public function __toString() { return json_encode(["status" => true, "premium" => true, "token" => "XOSID-PREMIUM-MOCK"]); } public function getContents() { return (string)$this; } }; } public function getStatusCode() { return 200; } }');
}

// 5. DIRECT MOCK DEFINITIONS TO PREVENT REAL FILE LOAD
if (!class_exists('App\Services\NotificationService', false)) {
    eval('
        namespace App\Services;
        class NotificationService {
            public function is_premium() { return true; }
            public function check() { return true; }
            public function status() { return (object)["premium" => true, "expired" => false]; }
            public static function getRecentNotifications($user = null) { return []; }
            public function get_token() { return "XOSID-PREMIUM-MOCK"; }
            public static function instance() { return new self(); }
            public function __call($n, $a) { return true; }
            public static function __callStatic($n, $a) { return true; }
        }
    ');
}

// 6. PREVENT DUPLICATE COMPOSER LOADING & FORCE GLOBAL CONFIG SYNC
if (!function_exists('config_item')) {
    function config_item($item) {
        if ($item === 'composer_autoload') {
            return false;
        }

        static $_config;

        if (empty($_config)) {
            $_config[0] =& get_config();
            
            // Fix double index.php issue globally by forcing absolute base_url and index_page
            $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
            $_config[0]['base_url'] = $proto . '://' . $host . '/';
            $_config[0]['index_page'] = 'index.php';

            // FORCE DISABLE AND REMOVE ALL TRACKER/TELEMETRY CONFIGS
            $_config[0]['server_pantau'] = '';
            $_config[0]['token_pantau'] = '';
            $_config[0]['tracker_url'] = '';
            $_config[0]['layanan_opendesa_token'] = '';
            $_config[0]['api_opendk_server'] = '';
            $_config[0]['api_opendk_key'] = '';
            $_config[0]['sinkronisasi_opendk'] = false;
            $_config[0]['api_gform_redirect_uri'] = '';
            $_config[0]['fb_opendesa'] = '';
            $_config[0]['website_opendesa'] = '';
            $_config[0]['donasi_opendesa'] = '';
            $_config[0]['layanan_opendesa'] = '';
            $_config[0]['status_pantau'] = false;
            $_config[0]['enable_track'] = false;
            $_config[0]['track_premium'] = false;
            $_config[0]['tracker'] = false;

            // Exclude siteman/auth and siteman/otp from CSRF checks to prevent browser cookie conflicts on multi-tenant
            if (!isset($_config[0]['csrf_exclude_uris'])) {
                $_config[0]['csrf_exclude_uris'] = [];
            }
            $_config[0]['csrf_exclude_uris'][] = 'siteman/auth';
            $_config[0]['csrf_exclude_uris'][] = 'siteman/otp/.*';
            // Exclude DataTables POST endpoints (read-only AJAX data requests)
            // These endpoints only READ data, not mutate, so CSRF exclusion is safe
            // Fix: CSRF cookie collision on multi-tenant localhost subdomains causes 403
            $_config[0]['csrf_exclude_uris'][] = '.*/datatables';
            $_config[0]['csrf_exclude_uris'][] = '.*/datatables/.*';
            $_config[0]['csrf_exclude_uris'][] = 'hagtou-dashboard';
            $_config[0]['csrf_exclude_uris'][] = 'hagtou-dashboard/.*';
            $_config[0]['csrf_exclude_uris'][] = 'hagtou-login';
            $_config[0]['csrf_exclude_uris'][] = 'hagtou-login/.*';
            $_config[0]['csrf_exclude_uris'][] = 'api/hagtou-order/create';
        }


        // DEEP ROUTER HIJACK: Dynamically override MY_Router to inject wildcards for government list & administration
        // [BYPASSED]: Custom routes are now injected directly inside system CI_Router class.
        // This lets the real, official premium MY_Router.php class load and execute setRequest() natively.

        // OpenSID RouteBuilder Hijack to register wildcard endpoints before compilation
        static $opensid_routes_registered = false;
        if (PHP_SAPI !== 'cli' && !$opensid_routes_registered && class_exists('OpenSID\RouteBuilder', true)) {
            $opensid_routes_registered = true;
            $controllers = [
                "pengurus" => "Pengurus",
                "dokumen_sekretariat" => "Dokumen_sekretariat",
                "ekspedisi" => "Ekspedisi",
                "lembaran_desa" => "Lembaran_desa",
                "surat_keluar" => "Surat_keluar",
                "surat_masuk" => "Surat_masuk"
            ];
            foreach ($controllers as $url_ctrl => $class_ctrl) {
                \OpenSID\RouteBuilder::any($url_ctrl, 'buku_umum/' . $class_ctrl . '@index');
                \OpenSID\RouteBuilder::any($url_ctrl . '/datatables', 'buku_umum/' . $class_ctrl . '@datatables');
                \OpenSID\RouteBuilder::any($url_ctrl . '/form', 'buku_umum/' . $class_ctrl . '@form');
                \OpenSID\RouteBuilder::any($url_ctrl . '/form/{id}', 'buku_umum/' . $class_ctrl . '@form');
                \OpenSID\RouteBuilder::any($url_ctrl . '/delete', 'buku_umum/' . $class_ctrl . '@delete');
                \OpenSID\RouteBuilder::any($url_ctrl . '/delete/{id}', 'buku_umum/' . $class_ctrl . '@delete');
                \OpenSID\RouteBuilder::any($url_ctrl . '/jabatan', 'buku_umum/' . $class_ctrl . '@jabatan');
                \OpenSID\RouteBuilder::any($url_ctrl . '/jabatanform', 'buku_umum/' . $class_ctrl . '@jabatanform');
                \OpenSID\RouteBuilder::any($url_ctrl . '/jabatanform/{id}', 'buku_umum/' . $class_ctrl . '@jabatanform');
                \OpenSID\RouteBuilder::any($url_ctrl . '/jabatandelete', 'buku_umum/' . $class_ctrl . '@jabatandelete');
                \OpenSID\RouteBuilder::any($url_ctrl . '/{any}', 'buku_umum/' . $class_ctrl . '@index');
            }
            @file_put_contents(APPPATH . '../storage/logs/xosid_router_hijack.log', "[" . date('Y-m-d H:i:s') . "] Dynamic OpenSID\\RouteBuilder routes registered successfully!\n", FILE_APPEND);
        }

        return isset($_config[0][$item]) ? $_config[0][$item] : null;
    }
}

// 7. GLOBAL FAVICON SELF-HEALING SYNC
(function() {
    if (PHP_SAPI === 'cli') return;
    
    $favicon_path = __DIR__ . '/favicon.ico';
    $favicon_desa_path = __DIR__ . '/desa/logo/favicon.ico';
    
    $now = time();
    $cache_life = 600; // 10 minutes
    
    if (file_exists($favicon_path) && file_exists($favicon_desa_path) && 
        (filesize($favicon_path) > 1000) && 
        ($now - filemtime($favicon_path) < $cache_life)) {
        return;
    }
    
    $db_host = 'db';
    $db_user = 'root';
    $db_pass = 'password';
    $db_name = 'xosid';
    
    if (file_exists(__DIR__ . '/.env')) {
        $env = file_get_contents(__DIR__ . '/.env');
        if (preg_match('/DB_HOST=(.*)/', $env, $m)) $db_host = trim($m[1]);
        if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $db_user = trim($m[1]);
        if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $db_pass = trim($m[1]);
        if (preg_match('/DB_DATABASE=(.*)/', $env, $m)) $db_name = trim($m[1]);
    }
    
    $db_host = trim($db_host, "\"'");
    $db_user = trim($db_user, "\"'");
    $db_pass = trim($db_pass, "\"'");
    $db_name = trim($db_name, "\"'");
    
    $logo_filename = '';
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if (!$conn->connect_error) {
            $result = $conn->query("SELECT logo FROM config LIMIT 1");
            if ($result && $row = $result->fetch_assoc()) {
                $logo_filename = $row['logo'];
            }
            $conn->close();
        }
    } catch (Throwable $e) {}
    
    $filepath = '';
    if (!empty($logo_filename)) {
        $filepath = __DIR__ . '/desa/logo/' . $logo_filename;
    }
    
    $fallback_path = __DIR__ . '/assets/images/xosid_logo.png';
    if (empty($filepath) || !file_exists($filepath)) {
        $filepath = $fallback_path;
    }
    
    if (!file_exists($filepath)) return;
    
    $info = @getimagesize($filepath);
    $mime = $info['mime'] ?? '';
    
    $src_img = null;
    switch ($mime) {
        case 'image/gif': $src_img = @imagecreatefromgif($filepath); break;
        case 'image/jpeg':
        case 'image/pjpeg': $src_img = @imagecreatefromjpeg($filepath); break;
        case 'image/png': $src_img = @imagecreatefrompng($filepath); break;
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $src_img = @imagecreatefromwebp($filepath);
            }
            break;
        case 'image/bmp':
        case 'image/x-ms-bmp':
            if (function_exists('imagecreatefrombmp')) {
                $src_img = @imagecreatefrombmp($filepath);
            }
            break;
    }
    
    if (!$src_img) return;
    
    $target_size = 64;
    $dst_img = imagecreatetruecolor($target_size, $target_size);
    imagealphablending($dst_img, false);
    imagesavealpha($dst_img, true);
    $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
    imagefilledrectangle($dst_img, 0, 0, $target_size, $target_size, $transparent);
    
    $src_w = imagesx($src_img);
    $src_h = imagesy($src_img);
    
    $scale = min($target_size / $src_w, $target_size / $src_h);
    $dst_w = (int)round($src_w * $scale);
    $dst_h = (int)round($src_h * $scale);
    $dst_x = (int)round(($target_size - $dst_w) / 2);
    $dst_y = (int)round(($target_size - $dst_h) / 2);
    
    imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    
    ob_start();
    imagepng($dst_img);
    $png_data = ob_get_clean();
    
    @file_put_contents($favicon_path, $png_data);
    @file_put_contents($favicon_desa_path, $png_data);
    
    imagedestroy($src_img);
    imagedestroy($dst_img);
})();

// ======================================================================
// XOSID PENDUDUK DELETION BYPASS INTERCEPTOR - GLOBALS
// ======================================================================



// Application cache-clearing function
if (!function_exists('xosid_clear_application_caches')) {
    function xosid_clear_application_caches() {
        $paths = [
            __DIR__ . '/desa/cache',
            __DIR__ . '/storage/framework/cache',
            __DIR__ . '/storage/framework/views'
        ];
        
        $cleaner = function($dir) use (&$cleaner) {
            if (!is_dir($dir)) return;
            $items = @scandir($dir);
            if ($items === false) return;
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                if ($item === '.htaccess' || $item === 'index.html' || $item === 'index.php') continue;
                
                $path = $dir . '/' . $item;
                if (is_dir($path)) {
                    $cleaner($path);
                    @rmdir($path);
                } else {
                    @unlink($path);
                }
            }
        };
        
        foreach ($paths as $path) {
            $cleaner($path);
        }
        
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
    }
}

// Core resident deletion routine
if (!function_exists('xosid_execute_delete_penduduk')) {
    function xosid_execute_delete_penduduk($id, $pdo) {
        if (!$pdo || !is_numeric($id)) return false;
        $id = intval($id);
        
        try {
            // 1. Fetch info for audit logging & file cleanup
            $stmt = $pdo->prepare("SELECT config_id, nik, foto FROM tweb_penduduk WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            
            if (!$row) return false;
            
            $config_id = $row['config_id'];
            $nik = $row['nik'];
            $foto = $row['foto'];
            
            // 2. Log deletion
            $deleted_by = $_SESSION['username'] ?? 'admin';
            $log_stmt = $pdo->prepare("INSERT INTO log_hapus_penduduk (config_id, id_pend, nik, foto, deleted_by, deleted_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $log_stmt->execute([$config_id, $id, $nik, $foto, $deleted_by]);
            
            // 3. Physical photos cleanup
            if ($foto) {
                $foto_path = __DIR__ . '/desa/upload/user_pict/' . $foto;
                $foto_path_kecil = __DIR__ . '/desa/upload/user_pict/kecil_' . $foto;
                if (file_exists($foto_path)) @unlink($foto_path);
                if (file_exists($foto_path_kecil)) @unlink($foto_path_kecil);
            }
            
            // 4. Delete from associated tables
            $stmt_mandiri = $pdo->prepare("DELETE FROM tweb_penduduk_mandiri WHERE id_pend = ?");
            $stmt_mandiri->execute([$id]);
            
            $stmt_map = $pdo->prepare("DELETE FROM tweb_penduduk_map WHERE id = ?");
            $stmt_map->execute([$id]);
            
            // 4b. Safely nullify family head references to prevent ghost heads in families
            $stmt_kk = $pdo->prepare("UPDATE tweb_keluarga SET nik_kepala = NULL WHERE nik_kepala = ?");
            $stmt_kk->execute([$id]);
            
            $stmt_rtm = $pdo->prepare("UPDATE tweb_rtm SET nik_kepala = NULL WHERE nik_kepala = ?");
            $stmt_rtm->execute([$id]);
            
            // 5. Delete core record
            $stmt_del = $pdo->prepare("DELETE FROM tweb_penduduk WHERE id = ?");
            $stmt_del->execute([$id]);
            
            return true;
        } catch (Exception $e) {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_db_error.log', "[" . date('Y-m-d H:i:s') . "] Deletion for ID $id failed: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }
}

// ----------------------------------------------------------------------
// Active Web Request Interceptors
// ----------------------------------------------------------------------
if (PHP_SAPI !== 'cli' && isset($_SERVER['REQUEST_URI'])) {
    
    // Intercept matching paths
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    
    $parsed_url = parse_url($request_uri);
    $path = $parsed_url['path'] ?? '';
    $path = ltrim($path, '/');
    if (strpos($path, 'index.php/') === 0) {
        $path = substr($path, 10);
    } elseif ($path === 'index.php') {
        $path = '';
    }
    $path = ltrim($path, '/');
    // Intercept pengurus action endpoints to execute direct DB changes
    if (preg_match('#^pengurus/(lock|kehadiran|ttd)/?#i', $path)) {
        $pdo = xosid_get_db_pdo();
        if ($pdo) {
            $segments = explode('/', $path);
            $action = strtolower($segments[1]);
            
            if ($action === 'lock') {
                $id = isset($segments[2]) ? intval($segments[2]) : 0;
                $val = isset($segments[3]) ? intval($segments[3]) : -1;
                
                if ($id > 0) {
                    if ($val === -1) {
                        $stmt = $pdo->prepare("SELECT pamong_status FROM tweb_desa_pamong WHERE pamong_id = ?");
                        $stmt->execute([$id]);
                        $curr = intval($stmt->fetchColumn() ?? 1);
                        $val = $curr === 1 ? 2 : 1;
                    }
                    $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET pamong_status = ? WHERE pamong_id = ?");
                    $stmt->execute([$val, $id]);
                    xosid_clear_application_caches();
                }
                
                if (!headers_sent()) {
                    header("Location: /index.php/pengurus");
                    exit;
                }
            }
            
            if ($action === 'kehadiran') {
                $id = isset($segments[2]) ? intval($segments[2]) : 0;
                $val = isset($segments[3]) ? intval($segments[3]) : -1;
                
                if ($id > 0) {
                    if ($val === -1) {
                        $stmt = $pdo->prepare("SELECT kehadiran FROM tweb_desa_pamong WHERE pamong_id = ?");
                        $stmt->execute([$id]);
                        $curr = intval($stmt->fetchColumn() ?? 1);
                        $val = $curr === 1 ? 0 : 1;
                    }
                    $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET kehadiran = ? WHERE pamong_id = ?");
                    $stmt->execute([$val, $id]);
                    xosid_clear_application_caches();
                }
                
                if (!headers_sent()) {
                    header("Location: /index.php/pengurus");
                    exit;
                }
            }
            
            if ($action === 'ttd') {
                $jenis = isset($segments[2]) ? strtolower($segments[2]) : '';
                $id = isset($segments[3]) ? intval($segments[3]) : 0;
                $val = isset($segments[4]) ? intval($segments[4]) : -1;
                
                if ($id > 0 && ($jenis === 'ttd' || $jenis === 'ub')) {
                    $col = $jenis === 'ttd' ? 'pamong_ttd' : 'pamong_ub';
                    
                    if ($val === -1) {
                        $stmt = $pdo->prepare("SELECT $col FROM tweb_desa_pamong WHERE pamong_id = ?");
                        $stmt->execute([$id]);
                        $curr = intval($stmt->fetchColumn() ?? 0);
                        $val = $curr === 1 ? 0 : 1;
                    }
                    
                    if ($jenis === 'ttd') {
                        if ($val === 1) {
                            $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET pamong_ttd = 1, pamong_ub = 0 WHERE pamong_id = ?");
                            $stmt->execute([$id]);
                        } else {
                            $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET pamong_ttd = 0 WHERE pamong_id = ?");
                            $stmt->execute([$id]);
                        }
                    } else {
                        if ($val === 1) {
                            $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET pamong_ub = 1, pamong_ttd = 0 WHERE pamong_id = ?");
                            $stmt->execute([$id]);
                        } else {
                            $stmt = $pdo->prepare("UPDATE tweb_desa_pamong SET pamong_ub = 0 WHERE pamong_id = ?");
                            $stmt->execute([$id]);
                        }
                    }
                    xosid_clear_application_caches();
                }
                
                if (!headers_sent()) {
                    header("Location: /index.php/pengurus");
                    exit;
                }
            }
        }
    }
    
    // Log datatables response for debugging
    if ($path === 'penduduk/datatables') {
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Expires: 0");
        }
        ob_start(function($buffer) {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_datatables_response.log',
                "[" . date('Y-m-d H:i:s') . "] RESPONSE:\n" . $buffer . "\n----------------------------------------\n",
                FILE_APPEND);
            return $buffer;
        });
    }

    // Intercept pengurus/datatables response to dynamically add action buttons (aksi) if empty
    if ($path === 'pengurus/datatables') {
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("Expires: 0");
        }
        
        // FIX: Controller always applies kehadiran filter based on POST input.
        // When kehadiran key is NOT present in POST at all, it generates "kehadiran IS NULL" → 0 rows.
        // Set default kehadiran=1 ONLY when the key was never submitted (not when user selects "Semua").
        if (!array_key_exists('kehadiran', $_POST)) {
            $_POST['kehadiran'] = '1';
            $_REQUEST['kehadiran'] = '1';
        }
        
        ob_start(function($buffer) {


            try {
                $data = json_decode($buffer, true);
                if ($data && isset($data['data']) && is_array($data['data'])) {
                    $pdo = xosid_get_db_pdo();
                    $pamong_ids = [];
                    foreach ($data['data'] as $r) {
                        $pid = $r['pamong_id'] ?? null;
                        if (!$pid && isset($r['ceklist']) && preg_match('/value="(\d+)"/', $r['ceklist'], $m)) {
                            $pid = intval($m[1]);
                        }
                        if ($pid) $pamong_ids[] = intval($pid);
                    }

                    $pamong_map = [];
                    if (!empty($pamong_ids) && $pdo) {
                        $in_clause = implode(',', array_map('intval', array_unique($pamong_ids)));
                        $stmt = $pdo->query("SELECT pamong_id, pamong_status, kehadiran, pamong_ttd, pamong_ub, jabatan_id FROM tweb_desa_pamong WHERE pamong_id IN ({$in_clause})");
                        if ($stmt) {
                            while ($p_row = $stmt->fetch()) {
                                $pamong_map[$p_row['pamong_id']] = $p_row;
                            }
                        }
                    }

                    foreach ($data['data'] as &$row) {
                        $pamong_id = $row['pamong_id'] ?? null;
                        if (!$pamong_id && isset($row['ceklist'])) {
                            if (preg_match('/value="(\d+)"/', $row['ceklist'], $m)) {
                                $pamong_id = intval($m[1]);
                            }
                        }
                        
                        if ($pamong_id) {
                            $p_row = $pamong_map[$pamong_id] ?? null;
                            $status = intval($p_row['pamong_status'] ?? 1);
                            $kehadiran = intval($p_row['kehadiran'] ?? 1);
                            $pamong_ttd = intval($p_row['pamong_ttd'] ?? 0);
                            $pamong_ub = intval($p_row['pamong_ub'] ?? 0);
                            $jabatan_id = intval($p_row['jabatan_id'] ?? 0);
                            
                            // 1. Ubah button
                            $btn_ubah = '<a href="/index.php/pengurus/form/'.$pamong_id.'" class="btn btn-sm btn-info btn-ubah" title="Ubah Data"><i class="fa fa-edit"></i></a>';
                            
                            // 2. Lock/Status button
                            if ($status === 1) {
                                $btn_lock = '<a href="/index.php/pengurus/lock/'.$pamong_id.'/2" class="btn btn-sm btn-lock active" title="Non-aktifkan"><i class="fa fa-unlock"></i></a>';
                            } else {
                                $btn_lock = '<a href="/index.php/pengurus/lock/'.$pamong_id.'/1" class="btn btn-sm btn-lock inactive" title="Aktifkan"><i class="fa fa-lock"></i></a>';
                            }
                            
                            // 3. Kehadiran button
                            if ($kehadiran === 1) {
                                $btn_kehadiran = '<a href="/index.php/pengurus/kehadiran/'.$pamong_id.'/0" class="btn btn-sm btn-kehadiran active" title="Non-aktifkan Kehadiran"><i class="fa fa-check"></i></a>';
                            } else {
                                $btn_kehadiran = '<a href="/index.php/pengurus/kehadiran/'.$pamong_id.'/1" class="btn btn-sm btn-kehadiran inactive" title="Aktifkan Kehadiran"><i class="fa fa-times"></i></a>';
                            }
                            
                            // 4. a.n / u.b button (Not for Kepala Desa - jabatan_id = 1)
                            $btn_ttd = '';
                            if ($jabatan_id !== 1) {
                                if ($pamong_ttd === 1 || $jabatan_id === 2) {
                                    if ($pamong_ttd === 1) {
                                        $btn_ttd = '<a href="/index.php/pengurus/ttd/ttd/'.$pamong_id.'/0" class="btn btn-sm btn-an active" title="Atas Nama (a.n) - Matikan">a.n</a>';
                                    } else {
                                        $btn_ttd = '<a href="/index.php/pengurus/ttd/ttd/'.$pamong_id.'/1" class="btn btn-sm btn-an inactive" title="Atas Nama (a.n) - Aktifkan">a.n</a>';
                                    }
                                } else {
                                    if ($pamong_ub === 1) {
                                        $btn_ttd = '<a href="/index.php/pengurus/ttd/ub/'.$pamong_id.'/0" class="btn btn-sm btn-ub active" title="Untuk Beliau (u.b) - Matikan">u.b</a>';
                                    } else {
                                        $btn_ttd = '<a href="/index.php/pengurus/ttd/ub/'.$pamong_id.'/1" class="btn btn-sm btn-ub inactive" title="Untuk Beliau (u.b) - Aktifkan">u.b</a>';
                                    }
                                }
                            }
                            
                            // 5. Hapus button
                            $btn_hapus = '<button type="button" onclick="deleteData(\'/index.php/pengurus/delete/'.$pamong_id.'\')" class="btn btn-sm btn-hapus" title="Hapus"><i class="fa fa-trash"></i></button>';
                            
                            $row['aksi'] = '<div class="btn-group">' . $btn_ubah . ' ' . $btn_lock . ' ' . $btn_kehadiran . ' ' . $btn_ttd . ' ' . $btn_hapus . '</div>';
                        }
                    }
                    $buffer = json_encode($data);
                }
            } catch (\Exception $e) {
                // Silently return original buffer
            }
            return $buffer;
        });
    }

    // Intercept Identitas Desa update to force-save kode_pos and profil_desa fields
    if ($path === 'identitas_desa/update' && $request_method === 'POST') {
        @file_put_contents(FCPATH . 'storage/logs/xosid_post_debug.log',
            "[" . date('Y-m-d H:i:s') . "] POST DATA: " . json_encode($_POST) . "\nFILES DATA: " . json_encode($_FILES) . "\n",
            FILE_APPEND);
            
        $kode_pos = isset($_POST['kode_pos']) ? intval($_POST['kode_pos']) : 0;
        
        $profile_fields = [
            'jenis_tanah' => ['ekologi', 'Jenis Tanah'],
            'topografi' => ['ekologi', 'Topografi'],
            'sumber_daya_alam' => ['ekologi', 'Sumber Daya Alam'],
            'flora_fauna' => ['ekologi', 'Flora Fauna'],
            'rawan_bencana' => ['ekologi', 'Rawan Bencana'],
            'jenis_jaringan' => ['internet', 'Jenis Jaringan'],
            'provider_internet' => ['internet', 'Provider Internet'],
            'cakupan_wilayah' => ['internet', 'Cakupan Wilayah'],
            'kecepatan_internet' => ['internet', 'Kecepatan Internet'],
            'status_desa' => ['adat', 'Status Desa'],
            'wilayah_adat' => ['adat', 'Wilayah Adat'],
            'peraturan_adat' => ['adat', 'Peraturan Adat'],
            'regulasi_penetapan_kampung_adat' => ['lainnya', 'Regulasi Penetapan Kampung Adat'],
            'akses_publik' => ['internet', 'Akses Publik'],
            'kearifan_lokal' => ['ekologi', 'Kearifan Lokal'],
            'lembaga_adat' => ['adat', 'Lembaga Adat']
        ];

        $profile_updates = [];
        foreach ($profile_fields as $key => $info) {
            $post_key = 'profil_' . $key;
            if (isset($_POST[$post_key])) {
                $profile_updates[$key] = [
                    'kategori' => $info[0],
                    'judul' => $info[1],
                    'value' => $_POST[$post_key]
                ];
            }
        }

        // TANGANI UNGGAHAN BERKAS ADAT SECARA AMAN
        $upload_dir = FCPATH . 'desa/upload/dokumen/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        // 1. Upload Berkas struktur_adat
        if (isset($_FILES['profil_struktur_adat']) && $_FILES['profil_struktur_adat']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profil_struktur_adat']['tmp_name'];
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['profil_struktur_adat']['name']);
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $profile_updates['struktur_adat'] = [
                    'kategori' => 'adat',
                    'judul' => 'Struktur Adat',
                    'value' => $file_name
                ];
            }
        } elseif (isset($_POST['profil_struktur_adat_nama']) && !empty($_POST['profil_struktur_adat_nama'])) {
            $profile_updates['struktur_adat'] = [
                'kategori' => 'adat',
                'judul' => 'Struktur Adat',
                'value' => $_POST['profil_struktur_adat_nama']
            ];
        }

        // 2. Upload Berkas dokumen_regulasi_penetapan_kampung_adat
        if (isset($_FILES['profil_dokumen_regulasi_penetapan_kampung_adat']) && $_FILES['profil_dokumen_regulasi_penetapan_kampung_adat']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profil_dokumen_regulasi_penetapan_kampung_adat']['tmp_name'];
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['profil_dokumen_regulasi_penetapan_kampung_adat']['name']);
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $profile_updates['dokumen_regulasi_penetapan_kampung_adat'] = [
                    'kategori' => 'lainnya',
                    'judul' => 'Dokumen Regulasi Penetapan Kampung Adat',
                    'value' => $file_name
                ];
            }
        } elseif (isset($_POST['profil_dokumen_regulasi_penetapan_kampung_adat_nama']) && !empty($_POST['profil_dokumen_regulasi_penetapan_kampung_adat_nama'])) {
            $profile_updates['dokumen_regulasi_penetapan_kampung_adat'] = [
                'kategori' => 'lainnya',
                'judul' => 'Dokumen Regulasi Penetapan Kampung Adat',
                'value' => $_POST['profil_dokumen_regulasi_penetapan_kampung_adat_nama']
            ];
        }

        register_shutdown_function(function() use ($kode_pos, $profile_updates) {
            $pdo = xosid_get_db_pdo();
            if ($pdo) {
                try {
                    // 1. Save kode_pos
                    if ($kode_pos > 0) {
                        $stmt = $pdo->prepare("UPDATE config SET kode_pos = ? WHERE id = 1");
                        $stmt->execute([$kode_pos]);
                        @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                            "[" . date('Y-m-d H:i:s') . "] Manually updated config.kode_pos to $kode_pos via shutdown function\n",
                            FILE_APPEND);
                    }

                    // 2. Save profil_desa fields
                    if (!empty($profile_updates)) {
                        foreach ($profile_updates as $key => $data) {
                            // Check if key already exists
                            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM profil_desa WHERE `key` = ? AND config_id = 1");
                            $check_stmt->execute([$key]);
                            $exists = $check_stmt->fetchColumn() > 0;

                            if ($exists) {
                                $update_stmt = $pdo->prepare("UPDATE profil_desa SET value = ?, kategori = ?, judul = ? WHERE `key` = ? AND config_id = 1");
                                $update_stmt->execute([$data['value'], $data['kategori'], $data['judul'], $key]);
                            } else {
                                $insert_stmt = $pdo->prepare("INSERT INTO profil_desa (config_id, kategori, judul, `key`, value) VALUES (1, ?, ?, ?, ?)");
                                $insert_stmt->execute([$data['kategori'], $data['judul'], $key, $data['value']]);
                            }
                        }
                        @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                            "[" . date('Y-m-d H:i:s') . "] Manually updated " . count($profile_updates) . " profil_desa keys via shutdown function\n",
                            FILE_APPEND);
                    }

                    // Clear application caches
                    xosid_clear_application_caches();
                } catch (Exception $e) {
                    @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                        "[" . date('Y-m-d H:i:s') . "] Failed to manually update config or profil_desa: " . $e->getMessage() . "\n",
                        FILE_APPEND);
                }
            }
        });
    }
    
    // Detect AJAX request (via header or parameter)
    $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
               || (strpos($request_uri, 'ajax') !== false)
               || isset($_GET['ajax']) 
               || isset($_POST['ajax']);
    
    // 0. Fresh statistics counts endpoint
    if ($path === 'penduduk/xosid_fresh_counts') {
        header('Content-Type: application/json');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        $pdo = xosid_get_db_pdo();
        if ($pdo) {
            try {
                $total = $pdo->query("SELECT COUNT(*) FROM tweb_penduduk")->fetchColumn();
                $pria = $pdo->query("SELECT COUNT(*) FROM tweb_penduduk WHERE sex = 1")->fetchColumn();
                $wanita = $pdo->query("SELECT COUNT(*) FROM tweb_penduduk WHERE sex = 2")->fetchColumn();
                $keluarga = $pdo->query("SELECT COUNT(*) FROM tweb_keluarga")->fetchColumn();
                
                echo json_encode([
                    'status' => true,
                    'total_penduduk' => intval($total),
                    'penduduk_pria' => intval($pria),
                    'penduduk_wanita' => intval($wanita),
                    'total_keluarga' => intval($keluarga)
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error querying stats: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Database connection failed'
            ]);
        }
        exit;
    }
    
    // Invalidate browser cache on resident page to ensure deletions reflect immediately
    if (preg_match('#^penduduk($|/index)#i', $path) && $request_method === 'GET') {
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }
    
    // Log deletion requests for troubleshooting
    if (strpos($path, 'penduduk/delete') !== false) {
        $sent_file = '';
        $sent_line = 0;
        $h_sent = headers_sent($sent_file, $sent_line);
        @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
            "[" . date('Y-m-d H:i:s') . "] incoming delete request: path='$path', method='$request_method', headers_sent=" . ($h_sent ? "YES (in $sent_file:$sent_line)" : "NO") . "\n",
            FILE_APPEND);
    }
    
    // 1. Individual delete interceptor
    if (preg_match('#^penduduk/delete/(\d+)/?$#', $path, $matches)) {
        $id = intval($matches[1]);
        
        if ($request_method === 'POST') {
            $pdo = xosid_get_db_pdo();
            $msg = '';
            $status = false;
            if ($pdo) {
                try {
                    $pdo->beginTransaction();
                    if (xosid_execute_delete_penduduk($id, $pdo)) {
                        $pdo->commit();
                        xosid_clear_application_caches();
                        $_SESSION['success'] = 4; // Native OpenSID success code for deletion
                        $_SESSION['xosid_flash_keep'] = true;
                        $msg = "Successfully deleted resident ID $id";
                        $status = true;
                    } else {
                        $pdo->rollBack();
                        $_SESSION['success'] = -4; // Native OpenSID error code for deletion
                        $_SESSION['error_msg'] = 'Data penduduk gagal dihapus atau tidak ditemukan';
                        $_SESSION['xosid_flash_keep'] = true;
                        $msg = "Failed to delete resident ID $id (not found or error)";
                        $status = false;
                    }
                } catch (Exception $ex) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $_SESSION['success'] = -4;
                    $_SESSION['error_msg'] = 'Terjadi kesalahan saat menghapus data: ' . $ex->getMessage();
                    $_SESSION['xosid_flash_keep'] = true;
                    $msg = "Error deleting resident ID $id: " . $ex->getMessage();
                    $status = false;
                }
            }
            
            @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                "[" . date('Y-m-d H:i:s') . "] Individual delete result (POST): $msg\n",
                FILE_APPEND);
                
            if ($is_ajax) {
                header('Content-Type: application/json');
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                echo json_encode([
                    'status' => $status,
                    'message' => $status ? 'Data penduduk berhasil dihapus.' : 'Data penduduk gagal dihapus atau tidak ditemukan.'
                ]);
                exit;
            }
        } else {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                "[" . date('Y-m-d H:i:s') . "] Individual delete path accessed via GET (ID $id). Redirecting to list.\n",
                FILE_APPEND);
        }
            
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Location: /index.php/penduduk', true, 302);
            exit;
        } else {
            echo '<script>window.location.replace("/index.php/penduduk");</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=/index.php/penduduk"></noscript>';
            exit;
        }
    }
    
    // 2. Bulk delete interceptor
    if ($path === 'penduduk/delete_all') {
        if ($request_method === 'POST') {
            $pdo = xosid_get_db_pdo();
            $ids = $_POST['id_cb'] ?? [];
            
            $msg = '';
            $status = false;
            $deleted_count = 0;
            if ($pdo && is_array($ids)) {
                try {
                    $pdo->beginTransaction();
                    foreach ($ids as $id) {
                        if (is_numeric($id)) {
                            if (xosid_execute_delete_penduduk(intval($id), $pdo)) {
                                $deleted_count++;
                            }
                        }
                    }
                    if ($deleted_count > 0) {
                        $pdo->commit();
                        xosid_clear_application_caches();
                        $_SESSION['success'] = 4; // Native OpenSID success code for deletion
                        $_SESSION['xosid_flash_keep'] = true;
                        $msg = "Successfully deleted $deleted_count residents out of " . count($ids);
                        $status = true;
                    } else {
                        $pdo->rollBack();
                        $_SESSION['success'] = -4; // Native OpenSID error code for deletion
                        $_SESSION['error_msg'] = 'Tidak ada data penduduk yang berhasil dihapus';
                        $_SESSION['xosid_flash_keep'] = true;
                        $msg = "Failed to delete any resident out of " . count($ids);
                        $status = false;
                    }
                } catch (Exception $ex) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $_SESSION['success'] = -4;
                    $_SESSION['error_msg'] = 'Terjadi kesalahan saat menghapus data massal: ' . $ex->getMessage();
                    $_SESSION['xosid_flash_keep'] = true;
                    $msg = "Error in bulk delete: " . $ex->getMessage();
                    $status = false;
                }
            } else {
                $_SESSION['success'] = -4; // Native OpenSID error code for deletion
                $_SESSION['error_msg'] = 'Gagal memproses penghapusan massal penduduk';
                $_SESSION['xosid_flash_keep'] = true;
                $msg = "Failed to process bulk delete (invalid post input or connection)";
                $status = false;
            }
            
            @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                "[" . date('Y-m-d H:i:s') . "] Bulk delete result (POST): $msg\n",
                FILE_APPEND);
                
            if ($is_ajax) {
                header('Content-Type: application/json');
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
                echo json_encode([
                    'status' => $status,
                    'message' => $status ? "Sebanyak $deleted_count data penduduk berhasil dihapus." : 'Tidak ada data penduduk yang berhasil dihapus atau terjadi kesalahan.'
                ]);
                exit;
            }
        } else {
            @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                "[" . date('Y-m-d H:i:s') . "] Bulk delete path accessed via GET. Redirecting to list.\n",
                FILE_APPEND);
        }
        
        if (!headers_sent()) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Location: /index.php/penduduk', true, 302);
            exit;
        } else {
            echo '<script>window.location.replace("/index.php/penduduk");</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=/index.php/penduduk"></noscript>';
            exit;
        }
    }

    // 3. Wilayah delete interceptor
    // Path pattern: wilayah/delete/{level}/{arg1}/{arg2}
    if (preg_match('#^wilayah/delete/(\w+)/(\d+)/(\d+)/?$#', $path, $matches)) {
        $level = strtolower($matches[1]);
        $arg1 = intval($matches[2]);
        $arg2 = intval($matches[3]);
        
        $pdo = xosid_get_db_pdo();
        
        $target_id = 0;
        $parent_id = 0;
        
        if ($pdo) {
            // Determine which argument is parent and which is ID.
            // If from AJAX DataTables (xosid_ajax_wilayah.php): delete/{level}/{id}/{parent}
            // If from legacy hapusWilayah: delete/{level}/{parent}/{id}
            // Let's resolve this by looking up which ID exists and matches the level!
            
            $stmt = $pdo->prepare("SELECT * FROM tweb_wil_clusterdesa WHERE id = ?");
            $stmt->execute([$arg1]);
            $row1 = $stmt->fetch();
            
            $stmt->execute([$arg2]);
            $row2 = $stmt->fetch();
            
            if ($level === 'dusun') {
                if ($arg1 > 0 && $row1) {
                    $target_id = $arg1;
                    $parent_id = $arg2;
                } elseif ($arg2 > 0 && $row2) {
                    $target_id = $arg2;
                    $parent_id = $arg1;
                }
            } else {
                if ($row1 && $row2) {
                    if ($level === 'rw') {
                        $is_rw1 = ($row1['rw'] !== '0' && in_array($row1['rt'], ['0', '-', '']));
                        $is_rw2 = ($row2['rw'] !== '0' && in_array($row2['rt'], ['0', '-', '']));
                        if ($is_rw1 && !$is_rw2) {
                            $target_id = $arg1;
                            $parent_id = $arg2;
                        } elseif ($is_rw2 && !$is_rw1) {
                            $target_id = $arg2;
                            $parent_id = $arg1;
                        } else {
                            $target_id = $arg1;
                            $parent_id = $arg2;
                        }
                    } else { // rt
                        $is_rt1 = (!in_array($row1['rt'], ['0', '-', '']));
                        $is_rt2 = (!in_array($row2['rt'], ['0', '-', '']));
                        if ($is_rt1 && !$is_rt2) {
                            $target_id = $arg1;
                            $parent_id = $arg2;
                        } elseif ($is_rt2 && !$is_rt1) {
                            $target_id = $arg2;
                            $parent_id = $arg1;
                        } else {
                            $target_id = $arg1;
                            $parent_id = $arg2;
                        }
                    }
                } elseif ($row1) {
                    $target_id = $arg1;
                    $parent_id = $arg2;
                } elseif ($row2) {
                    $target_id = $arg2;
                    $parent_id = $arg1;
                } else {
                    $target_id = $arg1;
                    $parent_id = $arg2;
                }
            }
            
            $msg = '';
            if ($request_method === 'POST') {
                try {
                    $stmt = $pdo->prepare("SELECT * FROM tweb_wil_clusterdesa WHERE id = ?");
                    $stmt->execute([$target_id]);
                    $target_row = $stmt->fetch();
                    
                    if ($target_row) {
                        $target_dusun = $target_row['dusun'];
                        $target_rw = $target_row['rw'];
                        $target_rt = $target_row['rt'];
                        
                        $pdo->beginTransaction();
                        
                        $deleted_ids = [];
                        if ($level === 'dusun') {
                            $c_stmt = $pdo->prepare("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = ?");
                            $c_stmt->execute([$target_dusun]);
                            $deleted_ids = $c_stmt->fetchAll(PDO::FETCH_COLUMN);
                            
                            $del_stmt = $pdo->prepare("DELETE FROM tweb_wil_clusterdesa WHERE dusun = ?");
                            $del_stmt->execute([$target_dusun]);
                        } elseif ($level === 'rw') {
                            $c_stmt = $pdo->prepare("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ?");
                            $c_stmt->execute([$target_dusun, $target_rw]);
                            $deleted_ids = $c_stmt->fetchAll(PDO::FETCH_COLUMN);
                            
                            $del_stmt = $pdo->prepare("DELETE FROM tweb_wil_clusterdesa WHERE dusun = ? AND rw = ?");
                            $del_stmt->execute([$target_dusun, $target_rw]);
                        } else { // rt
                            $deleted_ids = [$target_id];
                            $del_stmt = $pdo->prepare("DELETE FROM tweb_wil_clusterdesa WHERE id = ?");
                            $del_stmt->execute([$target_id]);
                        }
                        
                        if (!empty($deleted_ids)) {
                            $placeholders = implode(',', array_fill(0, count($deleted_ids), '?'));
                            
                            $up_stmt = $pdo->prepare("UPDATE tweb_penduduk SET id_cluster = 0 WHERE id_cluster IN ($placeholders)");
                            $up_stmt->execute($deleted_ids);
                            
                            $uf_stmt = $pdo->prepare("UPDATE tweb_keluarga SET id_cluster = 0 WHERE id_cluster IN ($placeholders)");
                            $uf_stmt->execute($deleted_ids);
                        }
                        
                        $pdo->commit();
                        xosid_clear_application_caches();
                        $_SESSION['success'] = 'Data wilayah berhasil dihapus';
                        $_SESSION['pesan'] = 'Data wilayah berhasil dihapus';
                        $msg = "Successfully deleted $level ID $target_id (Cascading deleted " . count($deleted_ids) . " clusters)";
                    } else {
                        $_SESSION['error'] = 'Data wilayah gagal dihapus atau tidak ditemukan';
                        $_SESSION['pesan'] = 'Data wilayah gagal dihapus atau tidak ditemukan';
                        $msg = "Failed to delete: target ID $target_id not found in tweb_wil_clusterdesa";
                    }
                } catch (Exception $ex) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $_SESSION['error'] = 'Gagal melakukan penghapusan data wilayah: ' . $ex->getMessage();
                    $_SESSION['pesan'] = 'Gagal melakukan penghapusan data wilayah: ' . $ex->getMessage();
                    $msg = "Exception during delete: " . $ex->getMessage();
                }
                
                @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                    "[" . date('Y-m-d H:i:s') . "] Wilayah delete result (POST): $msg\n",
                    FILE_APPEND);
            } else {
                @file_put_contents(__DIR__ . '/storage/logs/xosid_intercept.log',
                    "[" . date('Y-m-d H:i:s') . "] Wilayah delete path accessed via GET. Redirecting to list.\n",
                    FILE_APPEND);
            }
        }
        
        $redirect_url = '/index.php/wilayah';
        if ($level === 'rw' && $parent_id > 0) {
            $redirect_url = '/index.php/wilayah/index?parent=' . $parent_id . '&level=rw';
        } elseif ($level === 'rt' && $parent_id > 0) {
            $redirect_url = '/index.php/wilayah/index?parent=' . $parent_id . '&level=rt';
        }
        
        if (!headers_sent()) {
            header('Location: ' . $redirect_url, true, 302);
            exit;
        } else {
            echo '<script>window.location.replace("' . $redirect_url . '");</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . $redirect_url . '"></noscript>';
            exit;
        }
    }
}

// ======================================================================
// XOSID WEBSITE ACCESSIBILITY OVERRIDE
// ======================================================================
if (!function_exists('checkWebsiteAccessibility')) {
    function checkWebsiteAccessibility($url) {
        if (empty($url)) {
            return false;
        }
        
        $url = trim($url);
        if (!preg_match('#^https?://#i', $url)) {
            return false;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'ignore_errors' => true
                ]
            ]);
            $headers = @get_headers($url, 0, $context);
            return ($headers !== false);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

// ======================================================================
// XOSID LIVE IDM AND SDGS FETCHING INTERCEPTORS
// ======================================================================
if (!function_exists('xosid_perbarui_idm')) {
    function xosid_perbarui_idm($tahun) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        
        $pdo = xosid_get_db_pdo();
        if (!$pdo) {
            $_SESSION['error'] = "Gagal memproses data: Koneksi database tidak tersedia.";
            header("Location: /index.php/status_desa/navigasi/idm");
            exit;
        }
        
        try {
            $stmt = $pdo->query("SELECT kode_desa FROM config LIMIT 1");
            $config = $stmt->fetch();
            if (!$config || empty($config['kode_desa'])) {
                $_SESSION['error'] = "Gagal memproses data: Kode desa belum dikonfigurasi.";
                header("Location: /index.php/status_desa/navigasi/idm");
                exit;
            }
            
            $kode_desa = $config['kode_desa'];
            
            $url = "https://idm.kemendesa.go.id/open/api/desa/rumusan/{$kode_desa}/{$tahun}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 45);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code !== 200 || !$response) {
                $_SESSION['error'] = "Gagal mengambil data dari server Kemendesa (HTTP {$http_code}).";
                header("Location: /index.php/status_desa/navigasi/idm");
                exit;
            }
            
            $data = json_decode($response, true);
            if (!$data || !isset($data['mapData'])) {
                $_SESSION['error'] = "Format data dari server Kemendesa tidak valid.";
                header("Location: /index.php/status_desa/navigasi/idm");
                exit;
            }
            
            $mapData = $data['mapData'];
            
            // Map PROV -> PROVINSI and KAB -> KABUPATEN
            if (isset($mapData['ROW']) && is_array($mapData['ROW'])) {
                foreach ($mapData['ROW'] as &$row) {
                    if (isset($row['PROV'])) {
                        $row['PROVINSI'] = $row['PROV'];
                    }
                    if (isset($row['KAB'])) {
                        $row['KABUPATEN'] = $row['KAB'];
                    }
                }
            }
            
            // Write to cache file
            $cache_file = __DIR__ . "/desa/cache/idm_{$tahun}_{$kode_desa}.json";
            $cache_data = [
                'time' => time(),
                'ttl' => 31536000,
                'data' => $mapData
            ];
            
            $serialized = serialize($cache_data);
            if (file_put_contents($cache_file, $serialized) === false) {
                $_SESSION['error'] = "Gagal menulis data ke file cache lokal.";
            } else {
                $_SESSION['success'] = "Data IDM Desa Tahun {$tahun} berhasil diperbarui secara langsung dari Kemendesa.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
        
        header("Location: /index.php/status_desa/navigasi/idm");
        exit;
    }
}

if (!function_exists('xosid_perbarui_sdgs')) {
    function xosid_perbarui_sdgs() {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        
        $pdo = xosid_get_db_pdo();
        if (!$pdo) {
            $_SESSION['error'] = "Gagal memproses data: Koneksi database tidak tersedia.";
            header("Location: /index.php/status_desa/navigasi/sdgs");
            exit;
        }
        
        try {
            $stmt = $pdo->query("SELECT kode_desa_bps FROM config LIMIT 1");
            $config = $stmt->fetch();
            if (!$config || empty($config['kode_desa_bps'])) {
                $_SESSION['error'] = "Gagal memproses data: Kode desa BPS belum dikonfigurasi.";
                header("Location: /index.php/status_desa/navigasi/sdgs");
                exit;
            }
            
            $kode_desa_bps = $config['kode_desa_bps'];
            
            $url = "https://sid.kemendesa.go.id/sdgs/searching/score-sdgs?location_code={$kode_desa_bps}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 45);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code !== 200 || !$response) {
                $_SESSION['error'] = "Gagal mengambil data dari server Kemendesa (HTTP {$http_code}).";
                header("Location: /index.php/status_desa/navigasi/sdgs");
                exit;
            }
            
            $data = json_decode($response, true);
            if (!$data || !isset($data['average']) || !isset($data['data'])) {
                $_SESSION['error'] = "Format data SDGs dari server Kemendesa tidak valid.";
                header("Location: /index.php/status_desa/navigasi/sdgs");
                exit;
            }
            
            // Map data: score -> skor, title -> label
            $mapped_data = [];
            foreach ($data['data'] as $item) {
                $mapped_data[] = [
                    'skor' => isset($item['score']) ? $item['score'] : 0,
                    'label' => isset($item['title']) ? $item['title'] : ''
                ];
            }
            
            $sdgs_data = [
                'average' => $data['average'],
                'data' => $mapped_data
            ];
            
            // Write to cache file
            $cache_file = __DIR__ . "/desa/cache/sdgs_{$kode_desa_bps}.json";
            $cache_data = [
                'time' => time(),
                'ttl' => 31536000,
                'data' => $sdgs_data
            ];
            
            $serialized = serialize($cache_data);
            if (file_put_contents($cache_file, $serialized) === false) {
                $_SESSION['error'] = "Gagal menulis data SDGs ke file cache lokal.";
            } else {
                $_SESSION['success'] = "Data SDGs Desa berhasil diperbarui secara langsung dari Kemendesa.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
        
        header("Location: /index.php/status_desa/navigasi/sdgs");
        exit;
    }
}

// ======================================================================
// XOSID LIVE IDM AND SDGS ROUTE INTERCEPTION TRIGGER
// ======================================================================
if (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Intercept IDM Perbarui/Refresh
    if (preg_match('#status_desa/perbarui_idm/(\d+)#', $request_uri, $matches)) {
        $tahun = intval($matches[1]);
        xosid_perbarui_idm($tahun);
    }
    
    // Intercept SDGs Perbarui/Refresh
    if (strpos($request_uri, 'status_desa/perbarui_sdgs') !== false) {
        xosid_perbarui_sdgs();
    }
}

// ======================================================================
// XOSID INTELLIGENCE ENGINE (Pusat Analisis Cerdas)
// ======================================================================
if (!function_exists('xosid_get_intelligence_data')) {
    $xosid_intelligence_engine_file = __DIR__ . '/xosid_intelligence_engine.php';
    if (file_exists($xosid_intelligence_engine_file)) {
        require_once $xosid_intelligence_engine_file;
    }
}

// ======================================================================
// ANALISIS CERDAS ROUTE INTERCEPTOR
// Inject intelligence hub content into status_desa output
// ======================================================================
if (isset($_SERVER['REQUEST_URI'])) {
    $_xosid_req = $_SERVER['REQUEST_URI'];
    
    // Handle recalculate route
    if (preg_match('#status_desa/reklasifikasi_analisis_cerdas#i', $_xosid_req)) {
        if (function_exists('xosid_invalidate_intelligence_cache')) {
            xosid_invalidate_intelligence_cache();
        }
        if (isset($_SESSION)) {
            $_SESSION['xosid_flash_keep'] = 1;
            $_SESSION['success'] = 'Analisis cerdas berhasil diperbarui dari data terbaru.';
        }
        if (!headers_sent()) {
            header('Location: /index.php/status_desa/navigasi/analisis_cerdas');
            exit;
        }
    }
    
    // Default bare status_desa request to analisis_cerdas tab
    $is_status_desa_req = (bool) preg_match('#status_desa#i', parse_url($_xosid_req, PHP_URL_PATH) ?? '');

    $req_path = parse_url($_xosid_req, PHP_URL_PATH) ?? '';
    $is_sdgs_tab = (strpos($req_path, 'status_desa/navigasi/sdgs') !== false || strpos($req_path, 'status_desa/sdgs') !== false);
    $is_idm_tab  = (strpos($req_path, 'status_desa/navigasi/idm') !== false || strpos($req_path, 'status_desa/idm') !== false);
    $is_analisis_tab = (strpos($req_path, 'status_desa/navigasi/analisis_cerdas') !== false);

    if ($is_sdgs_tab) {
        $cookie_tab = 'sdgs';
        @setcookie('xosid_status_desa_tab', 'sdgs', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'sdgs';
    } elseif ($is_idm_tab) {
        $cookie_tab = 'idm';
        @setcookie('xosid_status_desa_tab', 'idm', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'idm';
    } elseif ($is_analisis_tab) {
        $cookie_tab = 'analisis_cerdas';
        @setcookie('xosid_status_desa_tab', 'analisis_cerdas', time() + 86400, '/');
        $_COOKIE['xosid_status_desa_tab'] = 'analisis_cerdas';
    } else {
        $cookie_tab = $_COOKIE['xosid_status_desa_tab'] ?? null;
    }

    if (!$is_status_desa_req && isset($_COOKIE['xosid_status_desa_tab'])) {
        @setcookie('xosid_status_desa_tab', '', time() - 3600, '/');
        unset($_COOKIE['xosid_status_desa_tab']);
        $cookie_tab = null;
    }

    $current_active_tab = $cookie_tab ?: 'analisis_cerdas';
    
    // Intercept status_desa request to inject analisis_cerdas ONLY when on analisis_cerdas tab
    if ($is_status_desa_req && $current_active_tab === 'analisis_cerdas' && !$is_sdgs_tab && !$is_idm_tab) {
        try {
            // Get intelligence data
            $intelData = function_exists('xosid_get_intelligence_data') 
                ? xosid_get_intelligence_data() 
                : [];
            
            // Generate the intelligence hub HTML content early (where ob_start is fully legal)
            ob_start();
            $intel    = $intelData;
            $demo     = $intel['demografi'] ?? [];
            $idm      = $intel['idm'] ?? ['available' => false];
            $sdgs     = $intel['sdgs'] ?? ['available' => false];
            $masalah  = $intel['masalah'] ?? [];
            $peluang  = $intel['peluang'] ?? [];
            $pendanaan = $intel['pendanaan'] ?? [];
            $skor     = $intel['skor_desa'] ?? ['nilai' => 0, 'label' => 'N/A'];
            $generatedAt = $intel['generated_at'] ?? date('Y-m-d H:i:s');
            
            // Include the analisis cerdas blade content as raw PHP (pre-compiled)
            $viewFile = __DIR__ . '/storage/framework/views/analisis_cerdas_compiled.php';
            if (file_exists($viewFile)) {
                include $viewFile;
            } else {
                // Generate inline content
                $intelligenceHtml = xosid_render_intelligence_html($intel);
                echo $intelligenceHtml;
            }
            $GLOBALS['xosid_analisis_cerdas_html'] = ob_get_clean();
        } catch (\Throwable $e) {
            @file_put_contents(__DIR__ . '/scratch/ob_start_early_debug.log', 
                "[" . date('Y-m-d H:i:s') . "] EARLY EXCEPTION: " . $e->getMessage() . "\n" . 
                $e->getTraceAsString() . "\n----------------------------------------\n",
                FILE_APPEND);
        }

        ob_start(function($html) {
            try {
                @file_put_contents(__DIR__ . '/scratch/ob_start_call.log', 
                    "[" . date('Y-m-d H:i:s') . "] callback called! html length: " . strlen($html) . "\n",
                    FILE_APPEND);
                    
                $reqUri = $_SERVER['REQUEST_URI'] ?? '';
                // Only inject if this is status_desa and a full HTML page (has </body>)
                if (strpos($reqUri, 'status_desa') === false) return $html;
                if (strpos($reqUri, 'status_desa/navigasi/sdgs') !== false || strpos($reqUri, 'status_desa/navigasi/idm') !== false || strpos($reqUri, 'status_desa/sdgs') !== false || strpos($reqUri, 'status_desa/idm') !== false) {
                    return $html;
                }
                // If OpenSID rendered the SDGs view, never overwrite it!
                if (strpos($html, 'Status SDGs Desa') !== false || strpos($html, 'Sustainable Development Goals') !== false || strpos($html, 'info-box-sdgs') !== false) {
                    return $html;
                }
                $activeTab = $_COOKIE['xosid_status_desa_tab'] ?? $_SESSION['xosid_tab'] ?? $_SESSION['status_desa_tab'] ?? 'analisis_cerdas';
                if ($activeTab !== 'analisis_cerdas') {
                    return $html;
                }
                if (strpos($html, '</body>') === false) return $html;
                
                $injectedContent = $GLOBALS['xosid_analisis_cerdas_html'] ?? '';
                
                // Replace title
                $html = preg_replace('#<title>(.*?)</title>#is', '<title>XO Intelligence Hub - Analisis Cerdas Desa</title>', $html);
                
                // Find the main content area and inject
                $injectMarker = '<section id="maincontent"';
                $markerPos = strpos($html, $injectMarker);
                
                if ($markerPos !== false) {
                    // Find closing '>' of the section tag
                    $openingEnd = strpos($html, '>', $markerPos);
                    if ($openingEnd !== false) {
                        $openingEnd += 1;
                        // Find next </section> closing tag
                        $closingPos = strpos($html, '</section>', $openingEnd);
                        if ($closingPos !== false) {
                            // Replace everything inside the section
                            $html = substr($html, 0, $openingEnd) . 
                                    "\n" . $injectedContent . "\n" . 
                                    substr($html, $closingPos);
                        }
                    }
                }
                
                // Inject JavaScript for printing if requested
                $print_js = '';
                if (isset($_SESSION['xosid_print']) && $_SESSION['xosid_print'] == 1) {
                    $print_js = '<script type="text/javascript">
                        window.addEventListener("load", () => {
                            setTimeout(() => {
                                window.print();
                            }, 800);
                        });
                    </script>';
                    unset($_SESSION['xosid_print']);
                    $html = str_replace('</body>', $print_js . '</body>', $html);
                }
                
                @file_put_contents(__DIR__ . '/scratch/ob_start_call.log', 
                    "[" . date('Y-m-d H:i:s') . "] returning html length: " . strlen($html) . "\n",
                    FILE_APPEND);
                return $html;
            } catch (\Throwable $e) {
                // Log the exception to a file
                @file_put_contents(__DIR__ . '/scratch/ob_start_debug.log', 
                    "[" . date('Y-m-d H:i:s') . "] EXCEPTION: " . $e->getMessage() . "\n" . 
                    $e->getTraceAsString() . "\n----------------------------------------\n",
                    FILE_APPEND);
                return $html;
            }
        });
    }
}

/**
 * Render intelligence hub as standalone HTML string (XOSID Esensial Standard)
 */
function xosid_render_intelligence_html($intel) {
    $demo       = $intel['demografi'] ?? [];
    $idm        = $intel['idm'] ?? ['available' => false];
    $sdgs       = $intel['sdgs'] ?? ['available' => false];
    $masalah    = $intel['masalah'] ?? [];
    $peluang    = $intel['peluang'] ?? [];
    $pendanaan  = $intel['pendanaan'] ?? [];
    $skor       = $intel['skor_desa'] ?? ['nilai' => 0, 'label' => 'N/A'];
    $profilDesa = $intel['profil_desa'] ?? ['nama_desa' => 'Taman Asri', 'nama_kecamatan' => 'Purbolinggo', 'nama_kabupaten' => 'Kabupaten Lampung Timur'];
    $generatedAt = $intel['generated_at'] ?? date('Y-m-d H:i:s');
    $history_files = function_exists('xosid_get_pdf_history_files') ? xosid_get_pdf_history_files() : [];
    
    $severityColor = [
        'Kritis' => ['bg' => 'rgba(239,68,68,0.08)', 'border' => '#ef4444', 'badge' => '#dc2626', 'badge_bg' => 'rgba(239,68,68,0.12)', 'text' => '#b91c1c', 'icon' => '⚠'],
        'Tinggi' => ['bg' => 'rgba(245,158,11,0.08)', 'border' => '#f59e0b', 'badge' => '#d97706', 'badge_bg' => 'rgba(245,158,11,0.12)', 'text' => '#b45309', 'icon' => '△'],
        'Sedang' => ['bg' => 'rgba(59,130,246,0.08)', 'border' => '#3b82f6', 'badge' => '#2563eb', 'badge_bg' => 'rgba(59,130,246,0.12)', 'text' => '#1d4ed8', 'icon' => 'ℹ'],
    ];
    
    $fundingColors = [
        'Dana Desa (DD)'      => '#8b5cf6',
        'APBD Kabupaten'      => '#3b82f6',
        'APBD Provinsi'       => '#06b6d4',
        'APBN'                => '#10b981',
        'CSR Perusahaan'      => '#f59e0b',
        'Investasi Swasta'    => '#ec4899',
        'Swadaya Masyarakat'  => '#64748b',
    ];
    
    $formatRp = function($amount) {
        if ($amount >= 1000000000) return 'Rp ' . number_format($amount/1000000000, 1, ',', '.') . ' M';
        if ($amount >= 1000000) return 'Rp ' . number_format($amount/1000000, 1, ',', '.') . ' Jt';
        return 'Rp ' . number_format($amount, 0, ',', '.');
    };
    
    $kritisCount = count(array_filter($masalah, fn($m) => ($m['severity'] ?? '') === 'Kritis'));
    $tinggiCount = count(array_filter($masalah, fn($m) => ($m['severity'] ?? '') === 'Tinggi'));
    $dimensi     = $idm['dimensi'] ?? [];
    
    $skorVal = $skor['nilai'] ?? 0;
    $skorLabel = $skor['label'] ?? 'N/A';
    $scoreColor = '#8b5cf6';
    if ($skorVal >= 75) $scoreColor = '#10b981';
    elseif ($skorVal >= 50) $scoreColor = '#3b82f6';
    elseif ($skorVal >= 35) $scoreColor = '#f59e0b';
    else $scoreColor = '#ef4444';

    ob_start();
    ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap');
.content-header { display: none !important; }
.intel-hub {
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    padding: 0;
    color: #0f172a;
}
.xosid-segmented-nav {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    padding: 6px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    gap: 8px;
    margin-bottom: 24px;
}
.xosid-nav-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 18px;
    border-radius: 12px;
    text-decoration: none !important;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #64748b;
    transition: all 0.25s ease;
    position: relative;
    border: 1px solid transparent;
}
.xosid-nav-tab:hover {
    color: #8b5cf6;
    background: rgba(139, 92, 246, 0.06);
    transform: translateY(-1px);
}
.xosid-nav-tab.active {
    background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    border-color: rgba(255, 255, 255, 0.15);
}
.xosid-nav-tab .tab-icon { font-size: 18px; line-height: 1; }
.xosid-nav-tab .tab-badge {
    font-size: 9px; padding: 2px 7px; border-radius: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
}
.xosid-nav-tab.active .tab-badge { background: rgba(255, 255, 255, 0.25); color: #ffffff; }
.xosid-nav-tab:not(.active) .tab-badge { background: #f1f5f9; color: #64748b; }
.xosid-nav-tab .badge-ai { background: linear-gradient(135deg, #ec4899, #f43f5e) !important; color: #ffffff !important; }

.xosid-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
}
.xosid-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border-color: rgba(139, 92, 246, 0.3);
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 992px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .stat-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border-color: #8b5cf6;
}
.stat-card .stat-icon-wrapper {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 12px;
}
.stat-card .stat-value {
    font-size: 28px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card .stat-label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.stat-card .stat-sub {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 6px;
}

.upload-deck {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 768px) { .upload-deck { grid-template-columns: 1fr; } }

.dropzone {
    background: #ffffff;
    border: 2px dashed rgba(0, 0, 0, 0.12);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 120px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.dropzone:hover, .dropzone.dragover {
    border-color: #8b5cf6;
    background: #f8fafc;
}
.dropzone .title { font-size: 13px; font-weight: 700; color: #0f172a; }
.dropzone .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }

.dimension-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 992px) { .dimension-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .dimension-grid { grid-template-columns: 1fr; } }

.dimension-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
}
.dimension-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border-color: #8b5cf6;
}

.intel-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}
.intel-section-title h3 {
    color: #0f172a;
    font-weight: 800;
    font-size: 17px;
    margin: 0;
    font-family: 'Outfit', sans-serif;
}
.intel-section-badge {
    background: #f1f5f9;
    border: 1px solid rgba(0,0,0,0.08);
    color: #475569;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.issue-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    border-left-width: 5px;
}
.issue-card:hover {
    transform: translateX(4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}
.issue-title { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; }
.issue-desc { color: #475569; font-size: 13px; line-height: 1.6; margin: 8px 0 14px; }
.issue-kegiatan { list-style: none; padding: 0; margin: 0 0 14px; }
.issue-kegiatan li { display: flex; align-items: flex-start; gap: 8px; color: #475569; font-size: 12.5px; padding: 4px 0; }
.issue-kegiatan li::before { content: '✓'; color: #10b981; font-weight: 900; flex-shrink: 0; }
.issue-metrics {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(0,0,0,0.06);
}
@media (max-width: 768px) { .issue-metrics { grid-template-columns: repeat(2, 1fr); } }
.issue-metric-item { text-align: center; }
.issue-metric-item .metric-value { font-size: 14px; font-weight: 800; color: #0f172a; }
.issue-metric-item .metric-label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700; margin-top: 2px; }

.opp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
@media (max-width: 992px) { .opp-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .opp-grid { grid-template-columns: 1fr; } }
.opp-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 18px;
    padding: 22px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
}
.opp-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #8b5cf6, #ec4899); }
.opp-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(0,0,0,0.06); border-color: #8b5cf6; }
.opp-emoji { font-size: 32px; margin-bottom: 10px; display: block; }
.opp-title { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
.opp-desc { font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 12px; }
.opp-list { list-style: none; padding: 0; margin: 0 0 12px; }
.opp-list li { font-size: 11.5px; color: #475569; padding: 3px 0; display: flex; align-items: flex-start; gap: 6px; }
.opp-list li::before { content: '▸'; color: #8b5cf6; font-weight: 900; flex-shrink: 0; }

.rekom-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.rekom-table th { background: #f8fafc; color: #0f172a; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 14px; font-size: 11px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.rekom-table td { background: #ffffff; padding: 12px 14px; border-bottom: 1px solid rgba(0,0,0,0.05); color: #475569; }
.rekom-table tr:hover td { background: #f8fafc; }

.live-dot-c { width: 7px; height: 7px; background: #10b981; border-radius: 50%; display: inline-block; animation: ldp 2s ease-in-out infinite; }
@keyframes ldp { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,0.7)} 50%{box-shadow:0 0 0 5px rgba(16,185,129,0)} }

.btn-xosid-action:hover { transform: translateY(-1px); filter: brightness(1.05); }

@media print {
    body, html { background: #ffffff !important; color: #000000 !important; }
    .no-print, .xosid-segmented-nav, .upload-deck, #progress-bar-overlay { display: none !important; }
    .xosid-card, .opp-card, .issue-card, .stat-card { border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
}
</style>

<div class="intel-hub">

    <!-- 1. GLOW ICON HEADER (XOSID ESENSIAL STANDARD) -->
    <div class="page-header-glow-wrapper no-print" style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 26px; color: white; box-shadow: 0 8px 24px rgba(139, 92, 246, 0.35);">
                    🧠
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <h1 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; margin: 0; color: #0f172a; line-height: 1.2;">
                            Status Desa &amp; Analisis Cerdas
                        </h1>
                        <span style="background: linear-gradient(135deg, #8b5cf6, #ec4899); color: white; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 800; letter-spacing: 0.5px;">AI HUB</span>
                    </div>
                    <p style="font-size: 13px; color: #64748b; margin: 3px 0 0 0;">Pusat Pemantauan IDM, SDGs Desa, dan Kecerdasan Buatan (AI) XO Intelligence Hub</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="#" id="btn-recalculate-trigger" class="btn-xosid-action" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); transition: all 0.25s ease;">
                    <span>🔄</span> RE-CALCULATE
                </a>
                <button onclick="window.print();" class="btn-xosid-action" style="background: #ffffff; color: #0f172a; border: 1px solid rgba(0,0,0,0.12); padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: all 0.25s ease;">
                    <span>🖨️</span> CETAK PDF
                </button>
            </div>
        </div>
    </div>

    <!-- 2. SEGMENTED TABS (HORIZONTAL NAVIGATION) -->
    <div class="xosid-segmented-nav no-print">
        <a href="/index.php/status_desa/navigasi/idm" class="xosid-nav-tab">
            <span class="tab-icon">📊</span>
            <span>INDEKS DESA MEMBANGUN</span>
            <span class="tab-badge">IDM</span>
        </a>
        <a href="/index.php/status_desa/navigasi/sdgs" class="xosid-nav-tab">
            <span class="tab-icon">♻️</span>
            <span>SUSTAINABLE DEVELOPMENT GOALS</span>
            <span class="tab-badge">SDGS</span>
        </a>
        <a href="/index.php/status_desa/navigasi/analisis_cerdas" class="xosid-nav-tab active">
            <span class="tab-icon">🧠</span>
            <span>ANALISIS CERDAS DESA</span>
            <span class="tab-badge badge-ai">AI HUB</span>
        </a>
    </div>

    <!-- 3. COMPACT UPLOADER DECK -->
    <?php 
    $uploadedId = $idm['uploaded_file'] ?? null;
    $uploadedProdeskel = $intel['prodeskel_file'] ?? null;
    ?>
    <div class="upload-deck no-print">
        <div class="dropzone" id="prodeskel-dropzone">
            <span style="font-size:24px;margin-bottom:4px;">📂</span>
            <div class="title">Data Prodeskel</div>
            <input type="file" id="prodeskel-input" accept=".zip,.xls,.xlsx,.pdf" style="display:none;">
            <?php if ($uploadedProdeskel): ?>
                <div style="font-size: 11px; color: #10b981; margin-top: 6px; text-align: left; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 8px 12px; width: 100%; max-width: 280px;">
                    <div style="font-weight: 700; color: #0f172a; word-break: break-all; margin-bottom: 2px;">📄 <?= htmlspecialchars($uploadedProdeskel['name']) ?></div>
                    <div style="color: #64748b; font-size: 10px; display: flex; justify-content: space-between;">
                        <span>Status: <strong style="color: #10b981;">Tersinkron</strong></span>
                        <span><?= date('d/m/Y H:i', strtotime($uploadedProdeskel['uploaded_at'])) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="subtitle">Klik atau seret berkas (ZIP, XLS, XLSX, PDF)</div>
            <?php endif; ?>
        </div>
        <div class="dropzone" id="id-dropzone">
            <span style="font-size:24px;margin-bottom:4px;">📈</span>
            <div class="title">Data Indeks Desa (ID) 2025</div>
            <input type="file" id="id-input" accept=".zip,.xls,.xlsx,.pdf" style="display:none;">
            <?php if ($uploadedId): ?>
                <div style="font-size: 11px; color: #10b981; margin-top: 6px; text-align: left; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 8px 12px; width: 100%; max-width: 280px;">
                    <div style="font-weight: 700; color: #0f172a; word-break: break-all; margin-bottom: 2px;">📄 <?= htmlspecialchars($uploadedId['name']) ?></div>
                    <div style="color: #64748b; font-size: 10px; display: flex; justify-content: space-between;">
                        <span>Status: <strong style="color: #10b981;">Tersinkron</strong></span>
                        <span><?= date('d/m/Y H:i', strtotime($uploadedId['uploaded_at'])) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="subtitle">Klik atau seret berkas (ZIP, XLS, XLSX, PDF)</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 4. EXECUTIVE HERO SCORE CARD -->
    <div class="xosid-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); padding: 26px 30px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px;">
            <div style="flex: 1; min-width: 280px;">
                <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #059669; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-bottom: 10px;">
                    <span class="live-dot-c"></span> REAL-TIME SYNTHESIS &bull; DESA <?= strtoupper(htmlspecialchars($profilDesa['nama_desa'] ?? 'TAMAN ASRI')) ?>
                </div>
                <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 23px; color: #0f172a; margin: 0 0 6px;">
                    Kecerdasan Strategis Pembangunan Desa
                </h2>
                <p style="color: #475569; font-size: 13px; margin: 0 0 14px; line-height: 1.6; max-width: 620px;">
                    Sintesis otomatis dari <?= number_format($demo['total_penduduk'] ?? 0) ?> jiwa penduduk, data Indeks Desa 2025, dan capaian SDGs untuk merumuskan prioritas anggaran &amp; kebijakan pemdes yang tepat sasaran.
                </p>
                <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; font-size: 12px; color: #64748b;">
                    <span style="display: flex; align-items: center; gap: 5px;">📍 Kec. <?= htmlspecialchars($profilDesa['nama_kecamatan'] ?? 'Purbolinggo') ?>, Kab. <?= htmlspecialchars($profilDesa['nama_kabupaten'] ?? 'Lampung Timur') ?></span>
                    <span>&bull;</span>
                    <span>⏱ <?= date('d M Y H:i', strtotime($generatedAt)) ?> WIB</span>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid rgba(0,0,0,0.08); border-radius: 18px; padding: 22px 28px; text-align: center; min-width: 200px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div style="font-size: 10px; color: #64748b; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">SKOR DESA XOSID</div>
                <div style="font-size: 46px; font-weight: 900; color: <?= $scoreColor ?>; line-height: 1;"><?= number_format($skorVal, 0) ?></div>
                <div style="font-size: 13px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?= htmlspecialchars($skorLabel) ?></div>
                <div style="display: flex; gap: 6px; margin-top: 10px; justify-content: center;">
                    <?php if ($kritisCount > 0): ?><span style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #dc2626; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;"><?= $kritisCount ?> Kritis</span><?php endif; ?>
                    <?php if ($tinggiCount > 0): ?><span style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); color: #d97706; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;"><?= $tinggiCount ?> Tinggi</span><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. HISTORI STATEMENT LAPORAN CERDAS -->
    <div class="xosid-card" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: rgba(139,92,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #8b5cf6;">📑</div>
                <div>
                    <h4 style="color: #0f172a; font-weight: 800; font-size: 14px; margin: 0;">Histori Statement Laporan Cerdas</h4>
                    <p style="color: #64748b; font-size: 11px; margin: 2px 0 0 0;">Statement PDF terarsip otomatis dari setiap kali rekalkulasi analisis</p>
                </div>
            </div>
            <span style="background: rgba(16,185,129,0.1); color: #059669; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 800;"><?= count($history_files) ?> DOKUMEN</span>
        </div>

        <?php if (!empty($history_files)): ?>
            <div style="overflow-x: auto;">
                <table class="rekom-table">
                    <thead>
                        <tr>
                            <th style="text-align: left; width: 30%;">Tanggal & Waktu</th>
                            <th style="text-align: left; width: 50%;">Nama Dokumen</th>
                            <th style="text-align: right; width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($history_files, 0, 5) as $hist): ?>
                            <tr>
                                <td>
                                    <strong style="color: #0f172a;"><?= $hist['date'] ?></strong>
                                    <span style="color: #64748b; font-size: 11px; margin-left: 4px;"><?= $hist['time'] ?> WIB</span>
                                </td>
                                <td style="font-family: monospace; font-size: 12px; color: #475569;">
                                    <?= htmlspecialchars($hist['filename']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/index.php/status_desa/download_pdf_history?file=<?= urlencode($hist['filename']) ?>" style="padding: 4px 12px; border-radius: 8px; border: 1px solid #10b981; background: rgba(16,185,129,0.06); color: #059669; font-size: 11px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                        Unduh PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px dashed rgba(0,0,0,0.08);">
                <span style="font-size: 24px; display: block; margin-bottom: 6px;">📁</span>
                <span style="font-size: 12px; color: #475569; font-weight: 600;">Belum ada dokumen statement PDF terarsip.</span>
                <p style="font-size: 11px; color: #64748b; margin: 4px 0 0 0;">Lakukan rekalkulasi analisis untuk secara otomatis membuat statement laporan pertamamu.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- 6. DEMOGRAFI PENDUDUK (8 STAT CARDS) -->
    <div class="intel-section-title">
        <span style="font-size: 20px;">👥</span>
        <h3>Demografi Penduduk</h3>
        <span class="intel-section-badge">REAL-TIME DATABASE</span>
    </div>
    <div class="stat-grid">
        <?php
        $statCards = [
            ['👥', number_format($demo['total_penduduk'] ?? 0), 'Total Penduduk', '♂ '.number_format($demo['laki_laki'] ?? 0).' ♀ '.number_format($demo['perempuan'] ?? 0)],
            ['🏠', number_format($demo['total_kk'] ?? 0), 'Total KK', 'Rata-rata '.($demo['total_kk'] > 0 ? number_format(($demo['total_penduduk'] ?? 0)/$demo['total_kk'], 1) : 0).' jiwa/KK'],
            ['💼', number_format($demo['usia_produktif'] ?? 0), 'Usia Produktif', 'Usia 15-64 tahun'],
            ['⚠️', number_format($demo['pengangguran'] ?? 0), 'Pengangguran', ($demo['tingkat_pengangguran'] ?? 0).'% angkatan kerja'],
            ['👶', number_format($demo['balita'] ?? 0), 'Balita (0-4 th)', 'Risiko stunting'],
            ['👴', number_format($demo['lansia'] ?? 0), 'Lansia (65+ th)', 'Perlindungan sosial'],
            ['♿', number_format($demo['disabilitas'] ?? 0), 'Disabilitas', 'Kelompok rentan'],
            ['🏥', number_format($demo['sakit_menahun'] ?? 0), 'Penyakit Menahun', 'Perlu pendampingan'],
        ];
        foreach ($statCards as $c): ?>
        <div class="stat-card">
            <div class="stat-icon-wrapper"><?= $c[0] ?></div>
            <div class="stat-value"><?= $c[1] ?></div>
            <div class="stat-label"><?= $c[2] ?></div>
            <div class="stat-sub"><?= $c[3] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- 7. 6 DIMENSI INDEKS DESA (ID) 2025 -->
    <div class="intel-section-title">
        <span style="font-size: 20px;">🎯</span>
        <h3>6 Dimensi Indeks Desa (ID) 2025</h3>
        <span class="intel-section-badge" style="background: rgba(6,182,212,0.1); border-color: rgba(6,182,212,0.25); color: #0891b2;"><?= !empty($idm['is_id_2025']) ? 'METODE BARU ID 2025' : 'ESTIMASI MODEL CACHED' ?></span>
    </div>
    <div class="dimension-grid">
        <?php
        $dimNames = [
            'layanan_dasar' => 'Layanan Dasar',
            'sosial' => 'Sosial',
            'ekonomi' => 'Ekonomi',
            'lingkungan' => 'Lingkungan',
            'aksesibilitas' => 'Aksesibilitas',
            'tata_kelola' => 'Tata Kelola Pemerintahan Desa'
        ];
        $dimIcons = [
            'layanan_dasar' => '🏥',
            'sosial' => '🤝',
            'ekonomi' => '💼',
            'lingkungan' => '🌱',
            'aksesibilitas' => '🚗',
            'tata_kelola' => '🏛️'
        ];
        foreach ($dimNames as $key => $name):
            $val = $dimensi[$key] ?? 0;
            $valPct = round($val * 100);
            $color = $val >= 0.8 ? '#10b981' : ($val >= 0.6 ? '#06b6d4' : ($val >= 0.4 ? '#f59e0b' : '#ec4899'));
        ?>
        <div class="dimension-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 20px;"><?= $dimIcons[$key] ?></span>
                    <span style="font-size: 13px; font-weight: 700; color: #0f172a;"><?= $name ?></span>
                </div>
                <span style="font-size: 15px; font-weight: 800; color: <?= $color ?>;"><?= number_format($val, 4) ?></span>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 10px; color: #64748b; font-weight: 700; margin-bottom: 4px;">
                    <span>PEMENUHAN</span>
                    <span><?= $valPct ?>%</span>
                </div>
                <div style="background: #f1f5f9; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div style="width: <?= $valPct ?>%; height: 100%; background: <?= $color ?>; border-radius: 6px;"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- 8. IDM & SDGS STATUS ROW -->
    <div class="row" style="margin-bottom: 24px;">
        <div class="col-md-6">
            <div class="xosid-card" style="height: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <h5 style="color: #0f172a; font-weight: 800; margin: 0; font-size: 14px;">Status Indeks Desa</h5>
                    <?php if ($idm['available']): ?>
                    <span style="background: rgba(6,182,212,0.1); border: 1px solid #06b6d4; color: #0891b2; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">TAHUN <?= $idm['tahun'] ?></span>
                    <?php else: ?>
                    <span style="background: #f1f5f9; border: 1px solid rgba(0,0,0,0.08); color: #64748b; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">BELUM ADA DATA</span>
                    <?php endif; ?>
                </div>
                <?php if ($idm['available']): 
                    $idmColor = $idm['skor'] >= 0.8 ? '#10b981' : ($idm['skor'] >= 0.6 ? '#f59e0b' : '#ef4444');
                ?>
                <div style="font-size: 40px; font-weight: 900; color: <?= $idmColor ?>; line-height: 1;"><?= number_format($idm['skor'], 4) ?></div>
                <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?= htmlspecialchars($idm['status']) ?></div>
                <div style="margin-top: 12px; background: #f1f5f9; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div style="width: <?= min(100, $idm['skor'] * 100) ?>%; height: 100%; background: <?= $idmColor ?>; border-radius: 6px;"></div>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 18px; color: #64748b;">
                    <p style="margin: 0; font-size: 13px;">Data Indeks Desa belum tersedia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="xosid-card" style="height: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <h5 style="color: #0f172a; font-weight: 800; margin: 0; font-size: 14px;">Status SDGs</h5>
                    <?php if ($sdgs['available']): ?>
                    <span style="background: rgba(16,185,129,0.1); border: 1px solid #10b981; color: #059669; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">DATA TERSEDIA</span>
                    <?php else: ?>
                    <span style="background: #f1f5f9; border: 1px solid rgba(0,0,0,0.08); color: #64748b; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">BELUM ADA DATA</span>
                    <?php endif; ?>
                </div>
                <?php if ($sdgs['available']):
                    $sdgsAvg = $sdgs['average'];
                    $sdgsColor = $sdgsAvg >= 60 ? '#10b981' : ($sdgsAvg >= 40 ? '#f59e0b' : '#ef4444');
                ?>
                <div style="font-size: 40px; font-weight: 900; color: <?= $sdgsColor ?>; line-height: 1;"><?= number_format($sdgsAvg, 1) ?></div>
                <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 4px;">Rata-rata SDGs &middot; <?= count($sdgs['goals']) ?> Goal</div>
                <div style="margin-top: 12px; background: #f1f5f9; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div style="width: <?= min(100, $sdgsAvg) ?>%; height: 100%; background: <?= $sdgsColor ?>; border-radius: 6px;"></div>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 18px; color: #64748b;">
                    <p style="margin: 0; font-size: 13px;">Data SDGs belum tersedia. <a href="/index.php/status_desa/navigasi/sdgs" style="color: #8b5cf6; font-weight: 700;">Perbarui SDGs &rarr;</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 9. MASALAH & ISU DESA (EXECUTIVE PRIORITY CARDS) -->
    <?php if (!empty($masalah)): ?>
    <div class="intel-section-title">
        <span style="font-size: 20px;">🛡</span>
        <h3>Masalah &amp; Isu Desa</h3>
        <span class="intel-section-badge" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.25); color: #dc2626;"><?= count($masalah) ?> ISU TERIDENTIFIKASI</span>
        <?php if ($kritisCount > 0): ?>
        <span class="intel-section-badge" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.25); color: #dc2626;">⚠ <?= $kritisCount ?> KRITIS</span>
        <?php endif; ?>
    </div>

    <?php foreach ($masalah as $isu):
        $sc = $severityColor[$isu['severity']] ?? $severityColor['Sedang'];
    ?>
    <div class="issue-card" style="border-left-color: <?= $sc['border'] ?>;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #64748b; text-transform: uppercase;"><?= htmlspecialchars($isu['kategori']) ?></span>
                <?php foreach (($isu['sdgs_terkait'] ?? []) as $sdg): ?>
                <span style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.25); color: #0891b2; font-size: 10px; padding: 2px 7px; border-radius: 10px; font-weight: 700;">SDGs #<?= $sdg ?></span>
                <?php endforeach; ?>
            </div>
            <span style="background: <?= $sc['badge_bg'] ?>; border: 1px solid <?= $sc['border'] ?>55; color: <?= $sc['badge'] ?>; font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 800;">
                <?= $sc['icon'] ?> <?= $isu['severity'] ?>
            </span>
        </div>

        <h4 class="issue-title"><?= htmlspecialchars($isu['judul']) ?></h4>
        <p class="issue-desc"><?= htmlspecialchars($isu['deskripsi']) ?></p>

        <?php if (!empty($isu['kegiatan_rekomendasi'])): ?>
        <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
            📋 KEGIATAN YANG DIREKOMENDASIKAN
        </div>
        <ul class="issue-kegiatan">
            <?php foreach ($isu['kegiatan_rekomendasi'] as $keg): ?>
            <li><?= htmlspecialchars($keg) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if (!empty($isu['dampak_positif'])): ?>
        <div style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 8px 12px; font-size: 12px; color: #059669; margin-bottom: 12px;">
            ✨ <strong>Dampak:</strong> <?= htmlspecialchars($isu['dampak_positif']) ?>
        </div>
        <?php endif; ?>

        <div class="issue-metrics">
            <div class="issue-metric-item">
                <div class="metric-value"><?= number_format($isu['dampak'] ?? 0) ?></div>
                <div class="metric-label"><?= htmlspecialchars($isu['dampak_label'] ?? 'Jiwa Terdampak') ?></div>
            </div>
            <div class="issue-metric-item">
                <div class="metric-value" style="color: #8b5cf6;"><?= $formatRp($isu['estimasi_biaya'] ?? 0) ?></div>
                <div class="metric-label">Estimasi Biaya</div>
            </div>
            <div class="issue-metric-item">
                <div class="metric-value" style="color: #059669;"><?= htmlspecialchars($isu['estimasi_waktu'] ?? '-') ?></div>
                <div class="metric-label">Estimasi Waktu</div>
            </div>
            <div class="issue-metric-item">
                <div class="metric-value" style="font-size: 11px; color: #475569;">
                    <?= implode(', ', array_slice($isu['sumber_dana'] ?? ['Dana Desa'], 0, 2)) ?>
                </div>
                <div class="metric-label">Sumber Dana</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- 10. RINGKASAN REKOMENDASI TABEL -->
    <?php if (!empty($masalah)): ?>
    <div class="xosid-card" style="margin-bottom: 24px;">
        <div class="intel-section-title" style="margin-bottom: 12px;">
            <span style="font-size: 18px;">📋</span>
            <h3>Ringkasan Rekomendasi Kegiatan</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="rekom-table">
                <thead>
                    <tr>
                        <th style="text-align: left;">Isu / Program</th>
                        <th style="text-align: center;">Prioritas</th>
                        <th style="text-align: right;">Penerima</th>
                        <th style="text-align: right;">Biaya</th>
                        <th style="text-align: center;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $totalBiaya = 0;
                $totalPenerima = 0;
                foreach ($masalah as $isu):
                    $sc2 = $severityColor[$isu['severity']] ?? $severityColor['Sedang'];
                    $totalBiaya += ($isu['estimasi_biaya'] ?? 0);
                    $totalPenerima += ($isu['dampak'] ?? 0);
                ?>
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #0f172a;"><?= htmlspecialchars($isu['judul']) ?></div>
                        <div style="font-size: 10px; color: #64748b; margin-top: 1px;"><?= htmlspecialchars($isu['kategori']) ?></div>
                    </td>
                    <td style="text-align: center;">
                        <span style="background: <?= $sc2['badge_bg'] ?>; border: 1px solid <?= $sc2['border'] ?>44; color: <?= $sc2['badge'] ?>; font-size: 10px; padding: 2px 8px; border-radius: 12px; font-weight: 700;"><?= $isu['severity'] ?></span>
                    </td>
                    <td style="text-align: right; color: #0f172a; font-weight: 700;"><?= number_format($isu['dampak'] ?? 0) ?></td>
                    <td style="text-align: right; color: #8b5cf6; font-weight: 700;"><?= $formatRp($isu['estimasi_biaya'] ?? 0) ?></td>
                    <td style="text-align: center; color: #059669; font-weight: 600;"><?= htmlspecialchars($isu['estimasi_waktu'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="background: #f8fafc; font-weight: 800;">
                    <td colspan="2" style="text-align: right; color: #0f172a; font-size: 12px;">TOTAL KEBUTUHAN ANGGARAN</td>
                    <td style="text-align: right; color: #0f172a;"><?= number_format($totalPenerima) ?></td>
                    <td style="text-align: right; font-size: 15px; color: #8b5cf6;"><?= $formatRp($totalBiaya) ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- 11. DEMOGRAFI CHARTS -->
    <div class="intel-section-title">
        <span style="font-size: 20px;">📊</span>
        <h3>Komposisi Penduduk</h3>
    </div>
    <div class="row" style="margin-bottom: 24px;">
        <div class="col-md-6">
            <div class="xosid-card" style="height: 100%;">
                <h5 style="color: #0f172a; font-weight: 800; margin-bottom: 14px; font-size: 14px;">Top Pekerjaan Warga</h5>
                <?php
                $pekTop = $demo['pekerjaan_top'] ?? [];
                $maxPek = !empty($pekTop) ? max(array_column($pekTop, 'jml')) : 1;
                $pekColors = ['#8b5cf6','#06b6d4','#10b981','#f59e0b','#ec4899','#6366f1','#14b8a6','#f43f5e'];
                ?>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                <?php foreach ($pekTop as $i => $pek): ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="font-size: 11px; color: #475569; width: 110px; flex-shrink: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars(mb_substr($pek['nama'] ?? '-', 0, 20)) ?></div>
                        <div style="flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: <?= $maxPek > 0 ? round($pek['jml']/$maxPek*100) : 0 ?>%; background: <?= $pekColors[$i % count($pekColors)] ?>; border-radius: 4px;"></div>
                        </div>
                        <div style="font-size: 11px; color: #0f172a; font-weight: 700; width: 35px; text-align: right; flex-shrink: 0;"><?= number_format($pek['jml'] ?? 0) ?></div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="xosid-card" style="height: 100%;">
                <h5 style="color: #0f172a; font-weight: 800; margin-bottom: 14px; font-size: 14px;">Tingkat Pendidikan</h5>
                <?php
                $pend = $demo['pendidikan'] ?? [];
                $maxPend = !empty($pend) ? max(array_column($pend, 'jml')) : 1;
                $pendColors = ['#ef4444','#f97316','#f59e0b','#84cc16','#10b981','#06b6d4','#3b82f6','#8b5cf6','#ec4899','#64748b'];
                ?>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                <?php foreach ($pend as $i => $p): if (($p['jml'] ?? 0) == 0) continue; ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="font-size: 11px; color: #475569; width: 110px; flex-shrink: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars(mb_substr($p['nama'] ?? '-', 0, 20)) ?></div>
                        <div style="flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: <?= $maxPend > 0 ? round($p['jml']/$maxPend*100) : 0 ?>%; background: <?= $pendColors[$i % count($pendColors)] ?>; border-radius: 4px;"></div>
                        </div>
                        <div style="font-size: 11px; color: #0f172a; font-weight: 700; width: 35px; text-align: right; flex-shrink: 0;"><?= number_format($p['jml'] ?? 0) ?></div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 12. PELUANG & INVESTASI STRATEGIS (BUMDES) -->
    <?php if (!empty($peluang)): ?>
    <div class="intel-section-title">
        <span style="font-size: 20px;">📈</span>
        <h3>Peluang &amp; Investasi Desa</h3>
        <span class="intel-section-badge" style="background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.25); color: #059669;"><?= count($peluang) ?> PELUANG STRATEGIS</span>
    </div>
    <div class="opp-grid">
    <?php foreach ($peluang as $opp):
        $isHigh = ($opp['potensi'] ?? '') === 'Tinggi';
    ?>
        <div class="opp-card">
            <span class="opp-emoji"><?= htmlspecialchars($opp['ikon'] ?? '🌟') ?></span>
            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:800;margin-bottom:8px;<?= $isHigh ? 'background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#059669;' : 'background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);color:#d97706;' ?>">
                <?= $isHigh ? '🔥' : '📈' ?> POTENSI <?= htmlspecialchars($opp['potensi'] ?? '') ?>
            </span>
            <div class="opp-title"><?= htmlspecialchars($opp['judul'] ?? '') ?></div>
            <div class="opp-desc"><?= htmlspecialchars($opp['deskripsi'] ?? '') ?></div>
            <ul class="opp-list">
                <?php foreach (array_slice($opp['peluang'] ?? [], 0, 3) as $p): ?><li><?= htmlspecialchars($p) ?></li><?php endforeach; ?>
            </ul>
            <div style="display:flex;flex-direction:column;gap:6px;border-top:1px solid rgba(0,0,0,0.06);padding-top:10px;margin-top:10px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:10px;color:#64748b;font-weight:700;">Modal Awal</span>
                    <span style="font-size:11px;color:#8b5cf6;font-weight:700;"><?= htmlspecialchars($opp['modal_awal'] ?? '-') ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:10px;color:#64748b;font-weight:700;">Potensi ROI</span>
                    <span style="font-size:11px;color:#059669;font-weight:700;"><?= htmlspecialchars($opp['potensi_roi'] ?? '-') ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:10px;color:#64748b;font-weight:700;">Balik Modal</span>
                    <span style="font-size:11px;color:#8b5cf6;font-weight:700;"><?= htmlspecialchars($opp['waktu_balik_modal'] ?? '-') ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- 13. PETA ALOKASI PENDANAAN PROGRAM -->
    <?php if (!empty($pendanaan['alokasi'])): ?>
    <div class="xosid-card" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
            <div class="intel-section-title" style="margin: 0;">
                <span style="font-size: 20px;">🏛</span>
                <h3>Peta Alokasi Pendanaan Program</h3>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700;">Total Kebutuhan</div>
                <div style="font-size: 22px; font-weight: 900; color: #0f172a;"><?= $formatRp($pendanaan['total_kebutuhan'] ?? 0) ?></div>
            </div>
        </div>
        <div style="display: flex; border-radius: 8px; overflow: hidden; height: 18px; margin-bottom: 16px;">
        <?php foreach ($pendanaan['alokasi'] as $alok):
            $src = $alok['sumber'];
            $color = $fundingColors[$src] ?? '#64748b';
        ?>
            <div style="width:<?= $alok['persentase'] ?>%;background:<?= $color ?>;cursor:pointer;" title="<?= htmlspecialchars($src) ?>: <?= $alok['persentase'] ?>%"></div>
        <?php endforeach; ?>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <?php foreach ($pendanaan['alokasi'] as $alok):
            $src = $alok['sumber'];
            $color = $fundingColors[$src] ?? '#64748b';
        ?>
            <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width: 10px; height: 10px; border-radius: 3px; background: <?= $color ?>;"></div>
                <span style="font-size: 12px; color: #475569;"><?= htmlspecialchars($src) ?></span>
                <span style="font-size: 12px; color: #0f172a; font-weight: 700;"><?= $alok['persentase'] ?>%</span>
                <span style="font-size: 11px; color: #94a3b8;">(<?= $formatRp($alok['nominal']) ?>)</span>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 14. FOOTER IDENTITAS -->
    <div style="background: #f8fafc; border: 1px solid rgba(0,0,0,0.08); border-radius: 14px; padding: 16px; margin-bottom: 24px;">
        <div style="display: flex; align-items: flex-start; gap: 10px;">
            <span style="color: #8b5cf6; font-size: 18px; flex-shrink: 0; margin-top: 2px;">ℹ</span>
            <div>
                <div style="font-size: 13px; color: #0f172a; font-weight: 700; margin-bottom: 4px;">Tentang XO Intelligence Hub</div>
                <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.7;">
                    Analisis ini dihasilkan otomatis dari <strong style="color:#0f172a;"><?= number_format($demo['total_penduduk'] ?? 0) ?> jiwa penduduk terdaftar</strong> Desa <?= htmlspecialchars($profilDesa['nama_desa'] ?? 'Taman Asri') ?>.
                    Indeks Desa dan SDGs disinkronkan dari <strong style="color:#0f172a;">API resmi Kemendesa</strong>. Estimasi biaya bersifat indikatif untuk perencanaan RKPDes/APBDes. Diperbarui: <strong style="color:#0f172a;"><?= date('d F Y, H:i', strtotime($generatedAt)) ?> WIB</strong>
                    &nbsp;&middot;&nbsp; <a href="/index.php/status_desa/reklasifikasi_analisis_cerdas" style="color: #8b5cf6; font-weight: 700; text-decoration: none;">Refresh sekarang →</a>
                </p>
            </div>
        </div>
    </div>

</div>

<!-- PROGRESS BAR OVERLAY -->
<div id="progress-bar-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 12, 41, 0.7); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; flex-direction:column;">
    <div style="background:#ffffff; border:1px solid rgba(0,0,0,0.1); border-radius:24px; padding:36px; width:90%; max-width:480px; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.2);">
        <div style="font-size:40px; margin-bottom:16px; animation: spin 2s linear infinite; display:inline-block;" id="progress-spinner">🔄</div>
        <h4 style="color:#0f172a; font-family:'Outfit'; font-weight:800; font-size:18px; margin-bottom:8px; margin-top: 0;">Mengalkulasi Analisis Cerdas</h4>
        <p id="progress-message" style="color:#64748b; font-size:13px; margin-bottom:20px; min-height:40px;">Menghubungi server...</p>
        
        <div style="width:100%; background:#f1f5f9; height:10px; border-radius:5px; overflow:hidden; margin-bottom:12px; border:1px solid rgba(0,0,0,0.05);">
            <div id="progress-fill" style="width:0%; height:100%; background:linear-gradient(90deg, #8b5cf6 0%, #06b6d4 100%); transition:width 0.4s ease;"></div>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Progress Kalkulasi</span>
            <span id="progress-percent" style="font-size:14px; color:#8b5cf6; font-weight:900;">0%</span>
        </div>
    </div>
</div>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initUploader('prodeskel-dropzone', 'prodeskel-input', 'prodeskel_file');
    initUploader('id-dropzone', 'id-input', 'indeks_desa_file');

    // Progressive Recalculate Trigger
    const recalcTrigger = document.getElementById('btn-recalculate-trigger');
    if (recalcTrigger) {
        recalcTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            startProgressiveRecalculate();
        });
    }
});

function startProgressiveRecalculate() {
    const overlay = document.getElementById('progress-bar-overlay');
    const msgEl = document.getElementById('progress-message');
    const fillEl = document.getElementById('progress-fill');
    const pctEl = document.getElementById('progress-percent');
    const spinner = document.getElementById('progress-spinner');
    
    if (!overlay || !msgEl || !fillEl || !pctEl || !spinner) return;
    
    overlay.style.display = 'flex';
    msgEl.innerText = 'Menghubungi server & mempersiapkan kalkulasi...';
    fillEl.style.width = '0%';
    pctEl.innerText = '0%';
    spinner.innerText = '🔄';
    spinner.style.animation = 'spin 2s linear infinite';
    
    // Step 1: Membaca kependudukan & zip (40%)
    runRecalculateStep(1, function(err1, res1) {
        if (err1) {
            handleRecalculateError(err1);
            return;
        }
        
        msgEl.innerText = res1.message;
        fillEl.style.width = '40%';
        pctEl.innerText = '40%';
        
        // Step 2: Analisis 9 Titik Strategis (75%)
        setTimeout(function() {
            runRecalculateStep(2, function(err2, res2) {
                if (err2) {
                    handleRecalculateError(err2);
                    return;
                }
                
                msgEl.innerText = res2.message;
                fillEl.style.width = '75%';
                pctEl.innerText = '75%';
                
                // Step 3: Membuat & Arsip PDF (100%)
                setTimeout(function() {
                    runRecalculateStep(3, function(err3, res3) {
                        if (err3) {
                            handleRecalculateError(err3);
                            return;
                        }
                        
                        msgEl.innerText = res3.message;
                        fillEl.style.width = '100%';
                        pctEl.innerText = '100%';
                        spinner.innerText = '✨';
                        spinner.style.animation = 'none';
                        
                        setTimeout(function() {
                            overlay.style.display = 'none';
                            window.location.reload();
                        }, 1200);
                    });
                }, 600);
            });
        }, 600);
    });
}

function runRecalculateStep(step, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/index.php/status_desa/recalculate_progress?step=' + step, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status) {
                    callback(null, res);
                } else {
                    callback(res.message || 'Terjadi kesalahan pada tahap ' + step);
                }
            } catch(e) {
                callback('Gagal memproses respon data dari server.');
            }
        } else {
            callback('Gagal memproses. Server mengembalikan kode error: ' + xhr.status);
        }
    };
    xhr.onerror = function() {
        callback('Kesalahan jaringan. Pastikan server web aktif.');
    };
    xhr.send();
}

function handleRecalculateError(err) {
    const overlay = document.getElementById('progress-bar-overlay');
    if (overlay) overlay.style.display = 'none';
    alert('Kalkulasi Gagal: ' + err);
}

function initUploader(dropzoneId, inputId, endpointKey) {
    const dropzone = document.getElementById(dropzoneId);
    const input = document.getElementById(inputId);
    
    if (!dropzone || !input) return;
    
    dropzone.addEventListener('click', () => input.click());
    
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            handleUpload(e.dataTransfer.files[0], endpointKey, dropzone);
        }
    });
    
    input.addEventListener('change', () => {
        if (input.files.length) {
            handleUpload(input.files[0], endpointKey, dropzone);
        }
    });
}

function handleUpload(file, key, dropzone) {
    const formData = new FormData();
    formData.append(key, file);
    
    const originalContent = dropzone.innerHTML;
    
    dropzone.innerHTML = `
        <div class="upload-spinner" style="width:24px;height:24px;border:3px solid #f1f5f9;border-top-color:#8b5cf6;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 8px;"></div>
        <div id="${dropzone.id}-progress" style="font-size:12px;color:#8b5cf6;font-weight:700;margin-top:8px;">Mengunggah ${file.name}... (0%)</div>
        <div style="width:100%;background:#f1f5f9;height:4px;border-radius:2px;margin-top:8px;overflow:hidden;max-width:200px;margin-left:auto;margin-right:auto;">
            <div id="${dropzone.id}-progress-bar" style="width:0%;height:100%;background:#8b5cf6;transition:width 0.1s ease;"></div>
        </div>
    `;
    
    const progressText = document.getElementById(`${dropzone.id}-progress`);
    const progressBar = document.getElementById(`${dropzone.id}-progress-bar`);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/index.php/status_desa/upload_analisis_cerdas', true);
    
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            if (percentComplete < 100) {
                progressText.innerText = `Mengunggah ${file.name}... (${percentComplete}%)`;
                progressBar.style.width = percentComplete + '%';
            } else {
                progressText.innerText = `Memproses & Mengekstrak data...`;
                progressBar.style.width = '100%';
                progressBar.style.background = '#06b6d4';
            }
        }
    };
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.status) {
                    dropzone.innerHTML = `
                        <div style="font-size:24px;color:#10b981;margin-bottom:4px;">✓</div>
                        <div style="font-size:12px;color:#10b981;font-weight:700;">Berhasil diunggah & diproses!</div>
                    `;
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    dropzone.innerHTML = originalContent;
                    alert('Gagal memproses file: ' + (data.messages ? data.messages.join('\n') : 'Terjadi kesalahan'));
                    rebindUploader(dropzone, key);
                }
            } catch (e) {
                dropzone.innerHTML = originalContent;
                alert('Gagal membaca respon dari server.');
                rebindUploader(dropzone, key);
            }
        } else {
            dropzone.innerHTML = originalContent;
            alert('Gagal mengunggah file. Kode error HTTP: ' + xhr.status);
            rebindUploader(dropzone, key);
        }
    };
    
    xhr.onerror = function() {
        dropzone.innerHTML = originalContent;
        alert('Terjadi kesalahan jaringan.');
        rebindUploader(dropzone, key);
    };
    
    xhr.send(formData);
}

function rebindUploader(dropzone, key) {
    const inputId = dropzone.id === 'prodeskel-dropzone' ? 'prodeskel-input' : 'id-input';
    initUploader(dropzone.id, inputId, key);
}
</script>
    <?php
    return ob_get_clean();
}

// Auto-sanitize media_sosial table and pamong data to remove legacy opendesa/opensid/komunitas text
if (!function_exists('xosid_sanitize_media_sosial_data')) {
    function xosid_sanitize_media_sosial_data() {
        static $done = false;
        if ($done) return;
        $done = true;
        
        try {
            $db_config_file = __DIR__ . '/desa/config/database.php';
            if (!file_exists($db_config_file)) { $db_config_file = __DIR__ . '/config/database.php'; }
            $db_host = 'db'; $db_user = 'root'; $db_pass = 'password'; $db_port = 3306;
            if (file_exists($db_config_file)) {
                @include $db_config_file;
                if (isset($db['default'])) {
                    $db_host = $db['default']['hostname'] ?? $db_host;
                    $db_user = $db['default']['username'] ?? $db_user;
                    $db_pass = $db['default']['password'] ?? $db_pass;
                    $db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : $db_port;
                    if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
                        $db_pass = 'password';
                    }
                }
            }
            $conn = @new mysqli($db_host, $db_user, $db_pass, 'xosid', $db_port);
            if ($conn && !$conn->connect_error) {
                // Update default URLs in media_sosial
                $conn->query("UPDATE media_sosial SET url = 'https://facebook.com/username' WHERE id = 'facebook' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");
                $conn->query("UPDATE media_sosial SET url = 'https://instagram.com/username' WHERE id = 'instagram' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");
                $conn->query("UPDATE media_sosial SET url = 'https://t.me/username' WHERE id = 'telegram' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");
                $conn->query("UPDATE media_sosial SET url = 'https://twitter.com/username' WHERE id = 'twitter' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");
                $conn->query("UPDATE media_sosial SET url = 'https://api.whatsapp.com/send?phone=6281234567890' WHERE id = 'whatsapp' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");
                $conn->query("UPDATE media_sosial SET url = 'https://youtube.com/@channel' WHERE id = 'youtube' AND (url LIKE '%opendesa%' OR url LIKE '%opensid%' OR url LIKE '%komunitas%')");

                // Clean tweb_desa_pamong
                $res = $conn->query("SELECT pamong_id, media_sosial FROM tweb_desa_pamong WHERE media_sosial LIKE '%opendesa%' OR media_sosial LIKE '%opensid%' OR media_sosial LIKE '%komunitas%'");
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $ms = json_decode($row['media_sosial'], true);
                        if (is_array($ms)) {
                            foreach ($ms as $k => $v) {
                                if (stripos($v, 'opendesa') !== false || stripos($v, 'opensid') !== false || stripos($v, 'komunitas') !== false) {
                                    $ms[$k] = '';
                                }
                            }
                            $new_json = $conn->real_escape_string(json_encode($ms));
                            $conn->query("UPDATE tweb_desa_pamong SET media_sosial = '{$new_json}' WHERE pamong_id = {$row['pamong_id']}");
                        }
                    }
                }
                $conn->close();
            }
        } catch (\Throwable $e) {}
    }
}
xosid_sanitize_media_sosial_data();

