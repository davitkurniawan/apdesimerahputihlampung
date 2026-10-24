<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$bondo_db = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass, $bondo_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$res = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk");
$row = $res->fetch_assoc();
echo "Jumlah Penduduk di Bondo: " . $row['cnt'] . "\n";
