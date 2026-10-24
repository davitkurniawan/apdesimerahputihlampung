<?php
/**
 * XOSID NEURAL RECOVERY SCRIPT
 * Recovery tool to rebuild essential database tables for XAMPP migration.
 */

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'password';
$db   = getenv('DB_DATABASE') ?: 'xosid';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

echo "<h3>Initializing Neural Recovery...</h3>";

// 1. Create CONFIG table (Identitas Desa)
$conn->query("CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(100) DEFAULT NULL,
  `nama_kecamatan` varchar(100) DEFAULT NULL,
  `nama_kabupaten` varchar(100) DEFAULT NULL,
  `nama_propinsi` varchar(100) DEFAULT NULL,
  `kode_desa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$conn->query("INSERT INTO `config` (nama_desa, nama_kecamatan, nama_kabupaten, nama_propinsi, kode_desa) 
              VALUES ('Supradesa Central', 'Kecamatan Neural', 'Kabupaten Intelligence', 'Provinsi Masa Depan', 'XOSID-001')
              ON DUPLICATE KEY UPDATE nama_desa=VALUES(nama_desa);");

// 2. Create WILAYAH (Dusun/RW/RT)
$conn->query("CREATE TABLE IF NOT EXISTS `tweb_wil_clusterdesa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dusun` varchar(100) DEFAULT NULL,
  `rw` varchar(10) DEFAULT '0',
  `rt` varchar(10) DEFAULT '0',
  `nama_pamong` varchar(100) DEFAULT 'System Node',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$conn->query("TRUNCATE TABLE `tweb_wil_clusterdesa`;");
$conn->query("INSERT INTO `tweb_wil_clusterdesa` (dusun, rw, rt, nama_pamong) VALUES 
              ('Dusun Alpha', '0', '0', 'Node Administrator'),
              ('Dusun Beta', '0', '0', 'Quantum Monitor'),
              ('Dusun Gamma', '0', '0', 'Logic Guardian');");

// 3. Create PENDUDUK Mock Data for Analytics
$conn->query("CREATE TABLE IF NOT EXISTS `tweb_penduduk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `sex` int(1) DEFAULT '1',
  `status_dasar` int(1) DEFAULT '1',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 4. Create USER for Admin Login Bypass
$conn->query("CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_grup` int(11) DEFAULT '1',
  `nama` varchar(100) DEFAULT 'Administrator',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$admin_pass = password_hash('admin', PASSWORD_DEFAULT);
$conn->query("INSERT INTO `user` (username, password, id_grup, nama) 
              VALUES ('admin', '$admin_pass', 1, 'XOSID Intelligence Admin')
              ON DUPLICATE KEY UPDATE username=VALUES(username);");

// 5. Create Setting Aplikasi
$conn->query("DROP TABLE IF EXISTS `setting_aplikasi`;");
$conn->query("CREATE TABLE IF NOT EXISTS `setting_aplikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) DEFAULT '1',
  `key` varchar(100) DEFAULT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$conn->query("INSERT INTO `setting_aplikasi` (config_id, `key`, `value`) VALUES 
              (1, 'sebutan_desa', 'Desa'),
              (1, 'sebutan_dusun', 'Dusun'),
              (1, 'default_tampil_peta_infrastruktur', '1')
              ON DUPLICATE KEY UPDATE value=VALUES(value);");

echo "<p style='color:green;'>[SUCCESS] Core Neural tables rebuilt.</p>";
echo "<p>Next: Run the PHP server and access localhost:8080</p>";

$conn->close();
?>
