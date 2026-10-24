<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Check if 'Pengaturan Sistem' already exists
$check = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Pengaturan Sistem'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $pengaturan_sistem_id = $row['id'];
} else {
    // Insert 'Pengaturan Sistem'
    $mysqli->query("INSERT INTO setting_modul (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent) VALUES (1, 'Pengaturan Sistem', 'pengaturan_sistem', '#', 1, 'fa-cogs', 37, 1, 0, 'fa fa-cogs', 0)");
    $pengaturan_sistem_id = $mysqli->insert_id;
}

// 2. Move Pengaturan (ID 11) under Pengaturan Sistem
$mysqli->query("UPDATE setting_modul SET parent = $pengaturan_sistem_id, urut = 1 WHERE id = 11");

// 3. Move Admin Web (ID 13) under Pengaturan Sistem
$mysqli->query("UPDATE setting_modul SET parent = $pengaturan_sistem_id, urut = 2 WHERE id = 13");

// 4. Move Anjungan (ID 312) under Pengaturan Sistem
$mysqli->query("UPDATE setting_modul SET parent = $pengaturan_sistem_id, urut = 3 WHERE id = 312");

echo "Database updated successfully for Pengaturan Sistem! ID is " . $pengaturan_sistem_id . "\n";
