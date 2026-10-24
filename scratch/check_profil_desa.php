<?php
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    echo "Failed to connect to DB\n";
    exit(1);
}

try {
    echo "=== TABLE STRUCTURE ===\n";
    $stmt = $pdo->query("DESCRIBE profil_desa");
    while ($row = $stmt->fetch()) {
        print_r($row);
    }

    echo "\n=== TABLE CONTENTS ===\n";
    $stmt = $pdo->query("SELECT * FROM profil_desa");
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
