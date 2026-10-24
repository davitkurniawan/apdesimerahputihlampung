<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating gawai_layanan table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS `gawai_layanan` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `ip_address` varchar(100) DEFAULT NULL,
      `mac_address` varchar(100) DEFAULT NULL,
      `id_pengunjung` varchar(100) DEFAULT NULL,
      `printer_ip` varchar(100) DEFAULT NULL,
      `printer_port` varchar(50) DEFAULT NULL,
      `keterangan` varchar(300) DEFAULT NULL,
      `keyboard` tinyint(4) DEFAULT '0',
      `status` tinyint(4) DEFAULT '1',
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    $pdo->exec($sql);
    echo "Table gawai_layanan created successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
