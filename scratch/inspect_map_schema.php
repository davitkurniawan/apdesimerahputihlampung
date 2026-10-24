<?php
// scratch/inspect_map_schema.php

$conn = new mysqli("db", "root", "password", "xosid_tenant_bondo-bangsri");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== SCHEMA OF tweb_penduduk_map ===\n";
$res = $conn->query("DESCRIBE tweb_penduduk_map");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        printf("  %-25s %-20s %-10s %-10s %-10s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default']);
    }
} else {
    echo "Error describing tweb_penduduk_map: " . $conn->error . "\n";
}

$conn->close();
