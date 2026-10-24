<?php
// scratch/search_bondo_in_bumidaya.php

$host = 'db';
$user = 'root';
$pass = 'password';

$dbs = ['xosid_tenant_bumidaya', 'xosid_tenant_bumidaya-palas'];

foreach ($dbs as $db) {
    echo "=========================================\n";
    echo "SEARCHING IN DATABASE: $db\n";
    echo "=========================================\n";
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\n";
        continue;
    }
    
    // Set charset
    $conn->set_charset("utf8mb4");
    
    // Get all tables
    $tables = [];
    $res = $conn->query("SHOW TABLES");
    while ($row = $res->fetch_row()) {
        $tables[] = $row[0];
    }
    
    $found_any = false;
    foreach ($tables as $table) {
        // Get text/varchar columns
        $cols = [];
        $col_res = $conn->query("DESCRIBE `$table`");
        if (!$col_res) continue;
        while ($col = $col_res->fetch_assoc()) {
            $type = strtolower($col['Type']);
            if (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
                $cols[] = $col['Field'];
            }
        }
        
        if (empty($cols)) continue;
        
        // Build query to search for 'bondo'
        $conditions = [];
        foreach ($cols as $col) {
            // Force collation to prevent mismatch errors
            $conditions[] = "`$col` COLLATE utf8mb4_general_ci LIKE '%bondo%'";
        }
        $query = "SELECT * FROM `$table` WHERE " . implode(' OR ', $conditions) . " LIMIT 5";
        $search_res = $conn->query($query);
        if ($search_res && $search_res->num_rows > 0) {
            echo "Table `$table` contains matches:\n";
            $found_any = true;
            while ($row = $search_res->fetch_assoc()) {
                echo "  " . json_encode($row) . "\n";
            }
        }
    }
    
    if (!$found_any) {
        echo "No occurrences of 'bondo' found in $db.\n";
    }
    
    $conn->close();
}
