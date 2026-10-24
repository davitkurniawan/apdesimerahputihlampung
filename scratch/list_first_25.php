<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

$stmt = $pdo->query("SELECT id, nik, nama, id_kk, status_dasar FROM tweb_penduduk ORDER BY id ASC LIMIT 25");
while ($row = $stmt->fetch()) {
    echo "ID: {$row['id']} | NIK: {$row['nik']} | Nama: {$row['nama']} | ID_KK: {$row['id_kk']} | Status Dasar: {$row['status_dasar']}\n";
}
