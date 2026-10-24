<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TWEB_KELUARGA SCHEMA ===\n";
$res = $conn->query("DESCRIBE tweb_keluarga");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
$conn->close();
