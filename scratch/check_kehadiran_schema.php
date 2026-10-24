<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- TABLE SCHEMAS ---\n";
    $tables = ['kehadiran_perangkat_desa', 'kehadiran_jam_kerja'];
    foreach ($tables as $table) {
        echo "\nSCHEMA FOR $table:\n";
        $stmt = $pdo->query("DESCRIBE $table");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($cols);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
