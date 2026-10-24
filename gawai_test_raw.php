<?php
header('Content-Type: text/plain');
echo "--- RAW DB DIAGNOSTIC ---\n";
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database 'xosid' successfully.\n";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'gawai_layanan'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'gawai_layanan' exists in the active database!\n";
        $stmt = $pdo->query("SELECT * FROM gawai_layanan");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Total rows in database: " . count($rows) . "\n";
        print_r($rows);
    } else {
        echo "Table 'gawai_layanan' DOES NOT exist in the active database!\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
