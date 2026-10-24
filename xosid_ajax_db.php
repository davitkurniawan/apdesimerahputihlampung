<?php
// Resolve database name directly from HTTP host
$host = $_SERVER['HTTP_HOST'] ?? '';
$host_parts = explode(':', $host);
$host_no_port = strtolower($host_parts[0]);
$base_saas_domains = ['xosid.id', 'www.xosid.id', 'localhost', '127.0.0.1', 'master.localhost', 'master.xosid.id', 'amp.localhost', 'amp.xosid.id', 'www.amp.xosid.id'];
$database_name = 'xosid'; // Default master DB

if (!in_array($host_no_port, $base_saas_domains)) {
    if (preg_match('/\.xosid\.id$|\.localhost$|\.127\.0\.0\.1$/', $host_no_port)) {
        $parts = explode('.', $host_no_port);
        if (count($parts) > 1) {
            $subdomain = strtolower($parts[0]);
            if (!empty($subdomain) && $subdomain !== 'www' && $subdomain !== 'amp') {
                $database_name = 'xosid_tenant_' . $subdomain;
            }
        }
    }
}

// Fetch database port/auth dynamically from database.php config file if it exists
$db_port = 3306;
$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';

$db_config_file = __DIR__ . '/desa/config/database.php';
if (!file_exists($db_config_file)) {
    $db_config_file = __DIR__ . '/config/database.php';
}
if (file_exists($db_config_file)) {
    @include $db_config_file;
    if (isset($db['default'])) {
        $db_host = $db['default']['hostname'] ?? $db_host;
        $db_user = $db['default']['username'] ?? $db_user;
        $db_pass = $db['default']['password'] ?? $db_pass;
        $db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : $db_port;
        
        // Decode password if it's base64/JSON encrypted (XOSID logic)
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
            $db_pass = 'password';
        }
    }
}

$conn = new mysqli($db_host, $db_user, $db_pass, $database_name, $db_port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
