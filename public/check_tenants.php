<?php
if (function_exists('opcache_reset')) { opcache_reset(); }
require_once __DIR__ . '/../xosid_ajax_db.php';
header('Content-Type: text/plain');

echo "--- DATABASES ---\n";
$res1 = $conn->query("SHOW DATABASES LIKE 'xosid_tenant_%'");
if ($res1) {
    while ($row = $res1->fetch_row()) {
        echo $row[0] . "\n";
    }
}

echo "\n--- TENANTS TABLE ---\n";
$res2 = $conn->query("SELECT id, nama_desa, kode_desa, status, custom_domain FROM xosid_registrasi_tenant");
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        echo "ID: " . $row['id'] . " | Desa: " . $row['nama_desa'] . " | Status: " . $row['status'] . " | Domain: " . $row['custom_domain'] . "\n";
    }
}
?>
