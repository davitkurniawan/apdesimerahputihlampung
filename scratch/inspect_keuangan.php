<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== SCHEMA OF keuangan ===\n";
$res = $conn->query("DESCRIBE keuangan");
while ($row = $res->fetch_assoc()) {
    printf("  %-25s %-20s\n", $row['Field'], $row['Type']);
}

echo "\n=== CONTENT OF keuangan (LIMIT 10) ===\n";
$res = $conn->query("SELECT * FROM keuangan LIMIT 10");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
