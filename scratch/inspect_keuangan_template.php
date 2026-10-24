<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== SCHEMA OF keuangan_template ===\n";
$res = $conn->query("DESCRIBE keuangan_template");
while ($row = $res->fetch_assoc()) {
    printf("  %-25s %-20s\n", $row['Field'], $row['Type']);
}

echo "\n=== CONTENT OF keuangan_template (LIMIT 15) ===\n";
$res = $conn->query("SELECT * FROM keuangan_template LIMIT 15");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
