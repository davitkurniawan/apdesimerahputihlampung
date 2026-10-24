<?php
// scratch/inspect_bondo_ids.php

$host = 'db';
$user = 'root';
$pass = 'password';
$db_bondo = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass, $db_bondo);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== CONFIG TABLE ===\n";
$res = $conn->query("SELECT id, nama_desa FROM config");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Nama Desa: {$row['nama_desa']}\n";
    }
}

echo "\n=== USER TABLE ===\n";
$res = $conn->query("SELECT id, username, config_id FROM user");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Username: {$row['username']} | Config ID: {$row['config_id']}\n";
    }
}

echo "\n=== PAMONG TABLE (Sample) ===\n";
$res = $conn->query("SELECT pamong_id, pamong_nama, config_id, jabatan_id, pamong_status FROM tweb_desa_pamong LIMIT 10");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Pamong ID: {$row['pamong_id']} | Nama: {$row['pamong_nama']} | Config ID: {$row['config_id']} | Jabatan ID: {$row['jabatan_id']} | Status: {$row['pamong_status']}\n";
    }
}

$conn->close();
