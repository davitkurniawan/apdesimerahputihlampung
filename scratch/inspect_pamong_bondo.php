<?php
// scratch/inspect_pamong_bondo.php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$conn = new mysqli('db', 'root', 'password', 'xosid_tenant_bondo-bangsri');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== PAMONG DETAILS FOR BONDO ===\n";
$res = $conn->query("SELECT * FROM tweb_desa_pamong WHERE pamong_id IN (27, 28, 29, 30)");
while ($row = $res->fetch_assoc()) {
    echo "Pamong ID: {$row['pamong_id']}\n";
    foreach ($row as $k => $v) {
        echo "  $k: " . json_encode($v) . "\n";
    }
    echo "\n";
}

echo "=== RELATED PENDUDUK FOR BONDO ===\n";
$res = $conn->query("SELECT id, nama, nik, status_dasar FROM tweb_penduduk WHERE id IN (17866, 33257)");
while ($row = $res->fetch_assoc()) {
    echo "Penduduk ID: {$row['id']}\n";
    foreach ($row as $k => $v) {
        echo "  $k: " . json_encode($v) . "\n";
    }
    echo "\n";
}

$conn->close();
