<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== SHOW TABLES LIKE '%menu%' ===\n";
$res = $conn->query("SHOW TABLES LIKE '%menu%'");
while ($row = $res->fetch_row()) {
    echo $row[0] . "\n";
}

$tables = ['menu', 'xosid_menu', 'xosid_menus'];
foreach ($tables as $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check->num_rows > 0) {
        echo "\n=== SCHEMA OF $table ===\n";
        $res = $conn->query("DESCRIBE $table");
        while ($row = $res->fetch_assoc()) {
            printf("  %-15s %-15s %-10s %-5s %-10s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default']);
        }
        
        echo "\n=== CONTENT OF $table ===\n";
        $res = $conn->query("SELECT * FROM $table");
        while ($row = $res->fetch_assoc()) {
            print_r($row);
        }
    } else {
        echo "\nTable $table does not exist.\n";
    }
}

$conn->close();
