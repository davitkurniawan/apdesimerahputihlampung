<?php
// scratch/inspect_pamong_schema.php

require_once __DIR__ . '/../xosid_emergency_autoload.php';

function inspect_db($db_name) {
    echo "=== DATABASE: $db_name ===\n";
    $db_host = 'db';
    $db_user = 'root';
    $db_pass = 'password';
    
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check columns of tweb_desa_pamong
        $stmt = $pdo->query("DESCRIBE tweb_desa_pamong");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  Column: {$row['Field']} | Type: {$row['Type']} | Null: {$row['Null']} | Key: {$row['Key']}\n";
        }
        
        // Show some records
        echo "\n  --- RECORDS ---\n";
        $stmt = $pdo->query("SELECT * FROM tweb_desa_pamong LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

inspect_db('xosid_tenant_bumidaya');
inspect_db('xosid_tenant_bondo-bangsri');
