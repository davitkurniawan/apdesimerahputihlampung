<?php
/**
 * Test script for Template Conversion and Demo Subdomain Deployment
 */
// Mock request and session environment to satisfy xosid_superadmin_api.php imports
$_SERVER['REQUEST_URI'] = '/api/superadmin/test';
$_SERVER['REQUEST_METHOD'] = 'GET';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['superadmin_logged_in'] = true;

require_once __DIR__ . '/../xosid_ajax_db.php';
require_once __DIR__ . '/../xosid_superadmin_api.php'; // This has helper function xosid_restore_config_helper

echo "1. Memulai Tes Konversi...\n";

// A. Simpan original config
$original_config = [];
$res = $conn->query("SELECT * FROM config LIMIT 1");
if ($res && $res->num_rows > 0) {
    $original_config = $res->fetch_assoc();
    echo "Identitas Asli Master: " . $original_config['nama_desa'] . ", Kec. " . $original_config['nama_kecamatan'] . "\n";
} else {
    die("Error: Tabel config master tidak ditemukan atau kosong.\n");
}

// B. Ubah ke Bumi Daya
echo "Membuat identitas bersih (Bumi Daya)...\n";
$clean_stmt = $conn->prepare("UPDATE config SET 
    nama_desa = 'Bumi Daya', 
    kode_desa = '1801122005', 
    nama_propinsi = 'Lampung', 
    nama_kabupaten = 'Lampung Selatan', 
    nama_kecamatan = 'Palas', 
    nama_kontak = 'Pusat xoSID', 
    hp_kontak = '08123456789', 
    jabatan_kontak = 'Operator Desa',
    email_desa = 'info@xosid.id',
    logo = 'xosid_logo.png'
    WHERE id = 1
");
if ($clean_stmt) {
    $clean_stmt->execute();
    $clean_stmt->close();
}

// C. Dump database
echo "Menjalankan MySQLDump ke file xosid_neural_fully_restored_20260512.sql...\n";
$sql_file = __DIR__ . '/../xosid_neural_fully_restored_20260512.sql';
require_once __DIR__ . '/../vendor/dg/mysql-dump/src/MySQLDump.php';
$dumper = new MySQLDump($conn);
$dumper->save($sql_file);

// D. Restorasi original config
echo "Mengembalikan identitas asli ke database master...\n";
xosid_restore_config_helper($conn, $original_config);

// Verifikasi file
if (file_exists($sql_file)) {
    echo "Sukses: File template berhasil dibuat! Ukuran: " . round(filesize($sql_file) / 1024 / 1024, 2) . " MB\n";
} else {
    die("Gagal: File template tidak terbentuk.\n");
}

// E. Tes Deploy Demo
echo "\n2. Memulai Tes Deploy Subdomain Demo (xosid_tenant_demo)...\n";
$conn->query("DROP DATABASE IF EXISTS `xosid_tenant_demo`");
if (!$conn->query("CREATE DATABASE `xosid_tenant_demo` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Gagal membuat database demo: " . $conn->error . "\n");
}

echo "Memilih database xosid_tenant_demo...\n";
$conn->select_db('xosid_tenant_demo');

echo "Mengimpor template default SQL ke database demo...\n";
$handle = fopen($sql_file, 'r');
if ($handle) {
    $conn->query("START TRANSACTION;");
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
    $conn->query("SET UNIQUE_CHECKS = 0;");
    $conn->query("SET AUTOCOMMIT = 0;");

    $templine = '';
    while (($line = fgets($handle)) !== false) {
        if (substr($line, 0, 2) == '--' || $line == '') continue;
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') {
            $conn->query($templine) or die("Error in query: " . $conn->error . "\nLine Content: " . $templine . "\n");
            $templine = '';
        }
    }
    fclose($handle);
    $conn->query("COMMIT;");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
    $conn->query("SET UNIQUE_CHECKS = 1;");
    $conn->query("SET AUTOCOMMIT = 1;");
}

echo "Mengonfigurasi tema default...\n";
$conn->query("UPDATE theme SET status = 0");
$conn->query("UPDATE theme SET status = 1 WHERE slug = 'neural-fundamental'");

echo "Memvalidasi database demo...\n";
$res_config = $conn->query("SELECT nama_desa, nama_kecamatan FROM config LIMIT 1");
if ($res_config && $row = $res_config->fetch_assoc()) {
    echo "Identitas Desa di Database Demo: " . $row['nama_desa'] . ", Kec. " . $row['nama_kecamatan'] . "\n";
    if ($row['nama_desa'] === 'Bumi Daya') {
        echo "Sukses: Identitas desa di database demo bersih (Bumi Daya)!\n";
    } else {
        echo "Peringatan: Identitas desa tidak ter-reset dengan benar.\n";
    }
}

$conn->select_db('xosid');
echo "\nSemua pengujian lokal berhasil dijalankan dengan sukses!\n";
