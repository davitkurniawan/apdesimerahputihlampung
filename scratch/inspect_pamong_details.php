<?php
// scratch/inspect_pamong_details.php

$host = 'db';
$user = 'root';
$pass = 'password';
$db_bumidaya = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass, $db_bumidaya);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== PAMONG ROWS IN BUMIDAYA ===\n";
$res = $conn->query("SELECT p.*, j.nama as nama_jabatan FROM tweb_desa_pamong p LEFT JOIN ref_jabatan j ON p.jabatan_id = j.id ORDER BY p.urut ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Pamong ID: {$row['pamong_id']} | Nama: '{$row['pamong_nama']}' | Jabatan: '{$row['nama_jabatan']}' | NIK: '{$row['pamong_nik']}' | id_pend: '{$row['id_pend']}' | Status: '{$row['pamong_status']}'\n";
        
        if (!empty($row['id_pend'])) {
            $pend_res = $conn->query("SELECT * FROM tweb_penduduk WHERE id = {$row['id_pend']}");
            if ($pend_res && $pend_row = $pend_res->fetch_assoc()) {
                echo "  -> Found Penduduk: ID={$pend_row['id']} | Nama='{$pend_row['nama']}' | NIK='{$pend_row['nik']}'\n";
            } else {
                echo "  -> Penduduk NOT FOUND in tweb_penduduk for id_pend = {$row['id_pend']}\n";
            }
        }
    }
} else {
    echo "Query error: " . $conn->error . "\n";
}

$conn->close();
