<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Get the ID of 'Data Pendukung'
$r = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Data Pendukung'");
if ($r->num_rows == 0) {
    die("Error: Data Pendukung menu not found.\n");
}
$row = $r->fetch_assoc();
$data_pendukung_id = $row['id'];

// 2. Rename Neural Analytics (ID 3) to 'Data dan Statistik' and move under Data Pendukung (ID 548)
$mysqli->query("UPDATE setting_modul SET modul = 'Data dan Statistik', parent = $data_pendukung_id, urut = 5 WHERE id = 3");

echo "Database updated successfully: Neural Analytics is now Data dan Statistik under Data Pendukung!\n";
