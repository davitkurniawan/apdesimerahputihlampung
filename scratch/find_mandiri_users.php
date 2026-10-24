<?php
$dsn = "mysql:host=db;port=3306;dbname=xosid;charset=utf8mb4";
$pdo = new PDO($dsn, 'root', 'password', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== MAP SETTINGS IN SETTING_APLIKASI ===\n";
try {
    $stmt = $pdo->query("SELECT * FROM setting_aplikasi WHERE `key` LIKE '%mapbox%' OR `key` LIKE '%peta%' OR `key` LIKE '%map%'");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
