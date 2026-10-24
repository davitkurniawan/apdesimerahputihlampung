<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pend_count = $conn->query("SELECT COUNT(*) as total FROM tweb_penduduk")->fetch_assoc()['total'];
$kk_count = $conn->query("SELECT COUNT(*) as total FROM tweb_keluarga")->fetch_assoc()['total'];
$cluster_count = $conn->query("SELECT COUNT(*) as total FROM tweb_wil_clusterdesa")->fetch_assoc()['total'];

echo "tweb_penduduk total records: $pend_count\n";
echo "tweb_keluarga total records: $kk_count\n";
echo "tweb_wil_clusterdesa total records: $cluster_count\n";

$conn->close();
