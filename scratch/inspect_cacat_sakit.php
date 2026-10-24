<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

function dumpDict($table, $pdo) {
    echo "=== Table $table ===\n";
    try {
        $stmt = $pdo->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "  " . json_encode($row) . "\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

dumpDict('tweb_cacat', $pdo);
dumpDict('tweb_sakit_menahun', $pdo);
