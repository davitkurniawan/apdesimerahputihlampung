<?php
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    echo "Failed to connect to DB\n";
    exit(1);
}

try {
    echo "=== ALL TABLES ===\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "$table\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
