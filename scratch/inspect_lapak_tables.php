<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

$patterns = ['%lapak%', '%produk%'];
foreach ($patterns as $pat) {
    echo "=== TABLES LIKE '$pat' ===\n";
    $res = $conn->query("SHOW TABLES LIKE '$pat'");
    while ($row = $res->fetch_row()) {
        echo "  " . $row[0] . "\n";
    }
}

// If there are lapak tables, show schema
$tables = ['lapak_produk', 'lapak_kategori'];
foreach ($tables as $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check->num_rows > 0) {
        echo "\n=== SCHEMA OF $table ===\n";
        $res = $conn->query("DESCRIBE $table");
        while ($row = $res->fetch_assoc()) {
            printf("  %-25s %-20s\n", $row['Field'], $row['Type']);
        }
    }
}

$conn->close();
