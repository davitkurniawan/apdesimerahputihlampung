<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("DB connection failed\n");
}
$stmt = $pdo->query("SELECT id, nik, nama, status_dasar FROM tweb_penduduk ORDER BY id ASC");
$rows = $stmt->fetchAll();
echo "Total residents: " . count($rows) . "\n";
foreach ($rows as $row) {
    echo "ID: {$row['id']} | NIK: {$row['nik']} | Nama: {$row['nama']} | Status Dasar: {$row['status_dasar']}\n";
}
