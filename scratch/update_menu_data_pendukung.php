<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Check if 'Data Pendukung' already exists
$check = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 0 AND modul = 'Data Pendukung'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $data_pendukung_id = $row['id'];
} else {
    // Insert 'Data Pendukung'
    $mysqli->query("INSERT INTO setting_modul (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent) VALUES (1, 'Data Pendukung', 'data_pendukung', '#', 1, 'fa-database', 36, 1, 0, 'fa fa-database', 0)");
    $data_pendukung_id = $mysqli->insert_id;
}

// 2. Move Pemetaan (ID 9) under Data Pendukung
$mysqli->query("UPDATE setting_modul SET parent = $data_pendukung_id, urut = 1 WHERE id = 9");

// 3. Move Pembangunan (ID 442) under Data Pendukung
$mysqli->query("UPDATE setting_modul SET parent = $data_pendukung_id, urut = 2 WHERE id = 442");

// 4. Move Pertanahan (ID 7) under Data Pendukung
$mysqli->query("UPDATE setting_modul SET parent = $data_pendukung_id, urut = 3 WHERE id = 7");

// 5. Move and rename Analisis (ID 5) to 'Survey dan Analisa' under Data Pendukung
$mysqli->query("UPDATE setting_modul SET parent = $data_pendukung_id, modul = 'Survey dan Analisa', urut = 4 WHERE id = 5");

echo "Database updated successfully for Data Pendukung! ID is " . $data_pendukung_id . "\n";
