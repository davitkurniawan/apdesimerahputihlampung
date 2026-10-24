<?php
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    echo "Failed to connect to DB\n";
    exit(1);
}

try {
    echo "=== SETTINGS IN setting_aplikasi RELATED TO idm, sdgs, status ===\n";
    $stmt = $pdo->query("SELECT * FROM `setting_aplikasi` WHERE `key` LIKE '%idm%' OR `key` LIKE '%sdgs%' OR `key` LIKE '%status%' OR `key` LIKE '%api%'");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($settings as $s) {
        echo "Key: {$s['key']}\n  Value: {$s['value']}\n  Description: {$s['keterangan']}\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
