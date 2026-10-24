<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== APBDDes ROOT CATEGORIES ===\n";
$res = $conn->query("
    SELECT f.tahun, t.uuid, t.uraian, f.anggaran, f.realisasi
    FROM keuangan f
    JOIN keuangan_template t ON f.template_uuid = t.uuid
    WHERE t.parent_uuid IS NULL OR t.parent_uuid = ''
");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== APBDDes SUB-CATEGORIES (BELANJA LEVEL 1) ===\n";
$res = $conn->query("
    SELECT f.tahun, t.uuid, t.uraian, f.anggaran, f.realisasi
    FROM keuangan f
    JOIN keuangan_template t ON f.template_uuid = t.uuid
    WHERE t.parent_uuid = '5'
");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
