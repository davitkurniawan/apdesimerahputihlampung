<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Get the ID of 'Layanan Warga'
$r = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Layanan Warga'");
if ($r->num_rows == 0) {
    die("Error: Layanan Warga menu not found.\n");
}
$row = $r->fetch_assoc();
$layanan_warga_id = $row['id'];

// 2. Rename XOSID Market (ID 459) to 'OLShop Warga' and move under Layanan Warga (ID 547)
$mysqli->query("UPDATE setting_modul SET modul = 'OLShop Warga', parent = $layanan_warga_id, urut = 7 WHERE id = 459");

echo "Database updated successfully: XOSID Market is now OLShop Warga under Layanan Warga!\n";
