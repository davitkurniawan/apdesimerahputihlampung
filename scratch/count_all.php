<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SELECT COUNT(*) as total FROM tweb_penduduk");
print_r($res->fetch_assoc());

$res2 = $conn->query("SELECT COUNT(*) as total FROM tweb_keluarga");
print_r($res2->fetch_assoc());

$conn->close();
