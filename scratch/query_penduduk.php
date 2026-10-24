<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TWEB_PENDUDUK SCHEMA ===\n";
$res = $conn->query("DESCRIBE tweb_penduduk");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== SEX VALUES IN TWEB_PENDUDUK ===\n";
$res = $conn->query("SELECT sex, COUNT(*) as total FROM tweb_penduduk GROUP BY sex");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== STATUS_DASAR VALUES IN TWEB_PENDUDUK ===\n";
$res = $conn->query("SELECT status_dasar, COUNT(*) as total FROM tweb_penduduk GROUP BY status_dasar");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
