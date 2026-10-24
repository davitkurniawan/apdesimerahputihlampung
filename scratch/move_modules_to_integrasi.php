<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi MySQL gagal: " . $conn->connect_error . "\n");
}

echo "=== MEMINDAHKAN MODUL PREMIUM KE MENU INTEGRASI DATA ===\n";

// 1. Dapatkan ID untuk Integrasi Data (slug: satu-data)
$res_parent = $conn->query("SELECT id FROM `$dst_db`.`setting_modul` WHERE config_id = 1 AND slug = 'satu-data'");
if (!$res_parent || $res_parent->num_rows === 0) {
    die("[ERROR] Menu 'Integrasi Data' (slug: satu-data) tidak ditemukan.\n");
}
$parent_row = $res_parent->fetch_assoc();
$integrasi_id = intval($parent_row['id']);
echo "Menu Induk 'Integrasi Data' ditemukan dengan ID: $integrasi_id\n\n";

// 2. Cari modul premium yang akan dipindahkan
$target_slugs = ['prodeskel', 'ppid', 'data-presisi'];
$modules_to_move = [];

$res_modules = $conn->query("SELECT id, modul, slug FROM `$dst_db`.`setting_modul` WHERE config_id = 1 AND slug IN ('" . implode("','", $target_slugs) . "')");
while ($row = $res_modules->fetch_assoc()) {
    $modules_to_move[$row['slug']] = intval($row['id']);
}

// 3. Update Parent & Level untuk modul premium utama
echo "Mengupdate menu utama:\n";
foreach ($modules_to_move as $slug => $id) {
    $sql_update = "UPDATE `$dst_db`.`setting_modul` SET parent = $integrasi_id, level = 2 WHERE id = $id";
    if ($conn->query($sql_update)) {
        echo " - [OK] Modul '$slug' (ID: $id) dipindahkan di bawah Integrasi Data (Parent: $integrasi_id, Level: 2)\n";
    } else {
        echo " - [ERROR] Gagal mengupdate modul '$slug': " . $conn->error . "\n";
    }
}

// 4. Update Level untuk anak-anak dari modul premium utama menjadi Level 3
echo "\nMengupdate level sub-menu menjadi Level 3:\n";
foreach ($modules_to_move as $slug => $id) {
    $sql_update_children = "UPDATE `$dst_db`.`setting_modul` SET level = 3 WHERE config_id = 1 AND parent = $id";
    if ($conn->query($sql_update_children)) {
        $affected = $conn->affected_rows;
        echo " - [OK] $affected sub-menu dari '$slug' (Parent ID: $id) diupdate ke Level 3\n";
    } else {
        echo " - [ERROR] Gagal mengupdate sub-menu dari '$slug': " . $conn->error . "\n";
    }
}

// 5. Sesuaikan urutan (urut) agar tertata rapi
// Kita ingin:
// DTKS (urut: 1)
// DTSEN (urut: 2)
// PRODESKEL (urut: 3)
// PPID (urut: 4)
// DATA PRESISI (urut: 5)
echo "\nMengatur urutan sub-menu Integrasi Data:\n";
$order_mapping = [
    'dtks' => 1,
    'dtsen' => 2,
    'prodeskel' => 3,
    'ppid' => 4,
    'data-presisi' => 5
];

foreach ($order_mapping as $slug => $urut) {
    $sql_order = "UPDATE `$dst_db`.`setting_modul` SET urut = $urut WHERE config_id = 1 AND slug = '$slug'";
    if ($conn->query($sql_order)) {
        echo " - [OK] Urutan '$slug' diatur ke: $urut\n";
    } else {
        echo " - [ERROR] Gagal mengatur urutan '$slug': " . $conn->error . "\n";
    }
}

echo "\n=== PROSES SELESAI ===\n";
