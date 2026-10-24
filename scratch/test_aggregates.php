<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== TOTAL INDIVIDUALS ===\n";
$res = $conn->query("SELECT COUNT(*) as total FROM tweb_penduduk WHERE status_dasar = 1");
print_r($res->fetch_assoc());

echo "\n=== TOTAL FAMILIES ===\n";
$res = $conn->query("SELECT COUNT(*) as total FROM tweb_keluarga");
print_r($res->fetch_assoc());

echo "\n=== GENDER DISTRIBUTION ===\n";
$res = $conn->query("
    SELECT s.nama, COUNT(p.id) as total
    FROM tweb_penduduk p
    JOIN tweb_penduduk_sex s ON p.sex = s.id
    WHERE p.status_dasar = 1
    GROUP BY p.sex
");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== EDUCATION AGGREGATE ===\n";
$res = $conn->query("
    SELECT ed.nama, COUNT(p.id) as total
    FROM tweb_penduduk p
    JOIN tweb_penduduk_pendidikan_kk ed ON p.pendidikan_kk_id = ed.id
    WHERE p.status_dasar = 1
    GROUP BY p.pendidikan_kk_id
    ORDER BY ed.id ASC
");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "\n=== OCCUPATION AGGREGATE ===\n";
$res = $conn->query("
    SELECT occ.nama, COUNT(p.id) as total
    FROM tweb_penduduk p
    JOIN tweb_penduduk_pekerjaan occ ON p.pekerjaan_id = occ.id
    WHERE p.status_dasar = 1
    GROUP BY p.pekerjaan_id
    ORDER BY total DESC
    LIMIT 10
");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

$conn->close();
