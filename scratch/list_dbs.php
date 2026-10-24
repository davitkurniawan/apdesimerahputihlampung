<?php
$conn = new mysqli("db", "root", "password");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== DATABASES ===\n";
$res = $conn->query("SHOW DATABASES");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
