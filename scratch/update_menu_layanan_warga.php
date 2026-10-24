<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Check if 'Layanan Warga' already exists
$check = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Layanan Warga'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $layanan_warga_id = $row['id'];
} else {
    // Insert 'Layanan Warga'
    $mysqli->query("INSERT INTO setting_modul (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent) VALUES (1, 'Layanan Warga', 'layanan_warga', '#', 1, 'fa-users', 35, 1, 0, 'fa fa-users', 0)");
    $layanan_warga_id = $mysqli->insert_id;
}

// 2. Move Kesehatan (ID 206) to Layanan Warga and hide non-stunting submenus
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, urut = 1 WHERE id = 206");
$mysqli->query("UPDATE setting_modul SET hidden = 1 WHERE parent = 206 AND id != 478");

// 3. Move Bantuan (ID 6) to Layanan Warga
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, urut = 2 WHERE id = 6");

// 4. Move and rename Pengaduan (ID 469) to Pengaduan Warga
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, modul = 'Pengaduan Warga', urut = 3 WHERE id = 469");

// 5. Move and rename Hubung Warga (ID 10) to Kontak Warga
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, modul = 'Kontak Warga', urut = 4 WHERE id = 10");

// 6. Move Layanan Mandiri (ID 14) to Layanan Warga
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, urut = 5 WHERE id = 14");

// Rename Layanan Mandiri submenus as requested
$mysqli->query("UPDATE setting_modul SET modul = 'Pendaftaran Layanan' WHERE id = 387");
$mysqli->query("UPDATE setting_modul SET modul = 'Pendaftaran Gadget' WHERE id = 483");
$mysqli->query("UPDATE setting_modul SET modul = 'Tanggapan Warga' WHERE id = 456");
$mysqli->query("UPDATE setting_modul SET modul = 'Pengaturan' WHERE id = 449");

// 7. Move Buku Tamu (ID 354) to Layanan Warga
$mysqli->query("UPDATE setting_modul SET parent = $layanan_warga_id, urut = 6 WHERE id = 354");

echo "Database updated successfully for Layanan Warga! ID is " . $layanan_warga_id . "\n";
