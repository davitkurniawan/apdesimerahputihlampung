<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== SCHEMA OF tweb_penduduk ===\n";
$res = $conn->query("DESCRIBE tweb_penduduk");
while ($row = $res->fetch_assoc()) {
    printf("  %-25s %-20s\n", $row['Field'], $row['Type']);
}

$conn->close();
