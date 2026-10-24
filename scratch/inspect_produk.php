<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

$tables = ['produk', 'produk_kategori', 'pelapak'];
foreach ($tables as $table) {
    echo "=== SCHEMA OF $table ===\n";
    $res = $conn->query("DESCRIBE $table");
    while ($row = $res->fetch_assoc()) {
        printf("  %-25s %-20s\n", $row['Field'], $row['Type']);
    }
    
    echo "=== COUNT OF $table ===\n";
    $res = $conn->query("SELECT COUNT(*) as cnt FROM $table");
    print_r($res->fetch_assoc());
}

$conn->close();
