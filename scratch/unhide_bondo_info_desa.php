<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$bondo_db = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass, $bondo_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$sql = "UPDATE setting_modul SET hidden = 0, modul = 'Identitas Wilayah' WHERE id = 200";
if ($conn->query($sql)) {
    echo "=== SUKSES ===\n";
    echo "Menu 'Identitas Wilayah' (ID: 200) di Bondo telah diaktifkan (hidden = 0).\n";
} else {
    echo "Error: " . $conn->error . "\n";
}
