<?php
// Parse .env manually to populate environment variables
if (file_exists('/var/www/html/.env')) {
    $lines = file('/var/www/html/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Define env helper if not defined (to support Laravel env calls in database.php)
if (!function_exists('env')) {
    function env($key, $default = null) {
        $val = getenv($key);
        if ($val === false) {
            return $default;
        }
        if ($val === 'true') return true;
        if ($val === 'false') return false;
        return $val;
    }
}

// Define CodeIgniter constants
define('ENVIRONMENT', 'development');
define('BASEPATH', '/var/www/html/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('FCPATH', '/var/www/html/');
define('LOKASI_CONFIG_DESA', '/var/www/html/desa/');

// Load database configurations and update Pemerintah Desa module URL to 'pengurus'
$db_file = '/var/www/html/donjo-app/config/database.php';
if (!file_exists($db_file)) {
    die("Database config file not found.\n");
}

// Include the database config
require_once $db_file;

$db_cfg = $db['default'];

// Check port, default to 3306 if not present
$port = isset($db_cfg['port']) ? (int)$db_cfg['port'] : 3306;

// Resolve hostname
$hostname = $db_cfg['hostname'];

// Hardcode tenant database name for bumidaya subdomain
$database = 'xosid_tenant_bumidaya';

$conn = new mysqli($hostname, $db_cfg['username'], $db_cfg['password'], $database, $port);
if ($conn->connect_error) {
    die("Connection failed to $hostname / $database: " . $conn->connect_error . "\n");
}

echo "Connected successfully to database: " . $database . " on $hostname\n";

// Show current
$res = $conn->query("SELECT * FROM setting_modul WHERE modul LIKE '%Pemerintah%' OR url LIKE '%pengurus%'");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "BEFORE - ID: " . $row['id'] . " | Modul: " . $row['modul'] . " | URL: " . $row['url'] . " | Parent: " . $row['parent'] . "\n";
    }
}

// Update the URL to 'pengurus'
$stmt = $conn->prepare("UPDATE setting_modul SET url = 'pengurus', modul = 'Pemerintahan Desa' WHERE modul LIKE '%Pemerintah%'");
if ($stmt) {
    $stmt->execute();
    echo "Updated rows: " . $stmt->affected_rows . "\n";
    $stmt->close();
} else {
    echo "Error preparing update statement: " . $conn->error . "\n";
}

// Show after
$res = $conn->query("SELECT * FROM setting_modul WHERE modul LIKE '%Pemerintah%' OR url LIKE '%pengurus%'");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "AFTER - ID: " . $row['id'] . " | Modul: " . $row['modul'] . " | URL: " . $row['url'] . "\n";
    }
}

$conn->close();
