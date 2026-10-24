<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Check if 'Surat Warga' already exists under parent 4
$check = $mysqli->query("SELECT id FROM setting_modul WHERE parent = 4 AND modul = 'Surat Warga'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $surat_warga_id = $row['id'];
} else {
    // Insert 'Surat Warga'
    $mysqli->query("INSERT INTO setting_modul (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent) VALUES (1, 'Surat Warga', 'surat_warga', '#', 1, 'fa-file-text', 1, 2, 0, 'fa fa-file-text', 4)");
    $surat_warga_id = $mysqli->insert_id;
}

// 2. Move submenus to Surat Warga
$submenus = [369, 370, 389, 425, 426];
foreach ($submenus as $id) {
    $mysqli->query("UPDATE setting_modul SET parent = $surat_warga_id WHERE id = $id");
}

// 3. Move Surat Dinas (ID 493) to parent 4
$mysqli->query("UPDATE setting_modul SET parent = 4, urut = 2 WHERE id = 493");

// 4. Move Buku Administrasi (ID 301) to parent 4
$mysqli->query("UPDATE setting_modul SET parent = 4, urut = 3 WHERE id = 301");

// 5. Move Kehadiran (ID 337) to parent 4, rename to Digital Presensi
$mysqli->query("UPDATE setting_modul SET parent = 4, modul = 'Digital Presensi', urut = 4 WHERE id = 337");

// 6. Hide duplicate Alasan Keluar (ID 371)
$mysqli->query("UPDATE setting_modul SET hidden = 1 WHERE id = 371");

// 7. Update Portal Publik & Web (ID 510) urut
$mysqli->query("UPDATE setting_modul SET urut = 5 WHERE id = 510");

echo "Database updated successfully with new menu structure! ID is " . $surat_warga_id . "\n";
