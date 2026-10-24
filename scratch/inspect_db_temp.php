<?php
// Set mock HTTP Host to see what database is resolved
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
require_once __DIR__ . '/../desa/config/database.php';
echo "Resolved DB Name for bumidaya.localhost:8080: " . $db['default']['database'] . "\n\n";

try {
    $pdo = new PDO("mysql:host=db;charset=utf8mb4", 'root', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "List of databases:\n";
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($databases as $dbName) {
        if (strpos($dbName, 'xosid') === 0 || $dbName === 'information_schema' || $dbName === 'mysql') {
            echo " - $dbName\n";
        }
    }
    
    echo "\nChecking config table in xosid databases:\n";
    foreach ($databases as $dbName) {
        if (strpos($dbName, 'xosid') === 0) {
            try {
                $p = new PDO("mysql:host=db;dbname=$dbName;charset=utf8mb4", 'root', 'password');
                $s = $p->query("SELECT nama_desa, kode_desa FROM config LIMIT 1");
                $cfg = $s->fetch(PDO::FETCH_ASSOC);
                if ($cfg) {
                    echo "Database [$dbName]: nama_desa = {$cfg['nama_desa']}, kode_desa = {$cfg['kode_desa']}\n";
                } else {
                    echo "Database [$dbName]: config table exists but is empty.\n";
                }
            } catch (Exception $e) {
                echo "Database [$dbName]: failed to query config table: " . $e->getMessage() . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
