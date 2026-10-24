<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$bondo_db = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass, $bondo_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$res = $conn->query("SELECT id, username, nama, id_grup, pamong_id, active FROM user");
echo "=== ALL USERS IN BONDO ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Username: '{$row['username']}' | Nama: '{$row['nama']}' | pamong_id: " . var_export($row['pamong_id'], true) . " | Grup: {$row['id_grup']} | Active: {$row['active']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
$conn->close();
