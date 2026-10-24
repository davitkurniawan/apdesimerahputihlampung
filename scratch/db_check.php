<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- GAWAI LAYANAN ROWS ---\n";
    $stmt = $pdo->query("SELECT * FROM gawai_layanan");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo "No registered devices found in gawai_layanan table.\n";
    } else {
        foreach ($rows as $row) {
            print_r($row);
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
