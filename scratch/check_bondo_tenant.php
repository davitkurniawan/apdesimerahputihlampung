<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$master_db = 'xosid';

$conn = new mysqli($host, $user, $pass, $master_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$res = $conn->query("DESCRIBE xosid_registrasi_tenant");
echo "=== COLUMNS FOR xosid_registrasi_tenant ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Field: {$row['Field']} | Type: {$row['Type']}\n";
    }
}
