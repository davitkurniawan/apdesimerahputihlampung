<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

echo "=== tweb_penduduk columns ===\n";
$stmt = $pdo->query("DESCRIBE tweb_penduduk");
while ($row = $stmt->fetch()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n=== First 20 Residents ===\n";
$stmt = $pdo->query("SELECT id, nik, nama, id_kk, status_dasar FROM tweb_penduduk ORDER BY id ASC LIMIT 20");
while ($row = $stmt->fetch()) {
    echo "ID: {$row['id']} | NIK: {$row['nik']} | Nama: {$row['nama']} | ID_KK: {$row['id_kk']} | Status Dasar: {$row['status_dasar']}\n";
}

echo "\n=== Total Resident Count ===\n";
$stmt = $pdo->query("SELECT COUNT(*) FROM tweb_penduduk");
echo "Total: " . $stmt->fetchColumn() . "\n";
