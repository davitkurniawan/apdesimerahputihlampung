<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TWEB_WIL_CLUSTERDESA SCHEMA ===\n";
$res = $conn->query("DESCRIBE tweb_wil_clusterdesa");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== TWEB_WIL_CLUSTERDESA FIRST 20 ROWS ===\n";
$res = $conn->query("SELECT * FROM tweb_wil_clusterdesa LIMIT 20");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== TOTAL WILAYAH CLUSTER COUNT ===\n";
$res = $conn->query("SELECT COUNT(*) as cnt FROM tweb_wil_clusterdesa");
print_r($res->fetch_assoc());

$conn->close();
