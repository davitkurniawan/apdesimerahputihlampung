<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Inserting default gawai records...\n";
    
    // Clear any existing entries just in case
    $pdo->exec("DELETE FROM gawai_layanan WHERE ip_address IN ('127.0.0.1', '192.168.1.26')");
    
    $sql = "INSERT INTO gawai_layanan (ip_address, mac_address, id_pengunjung, printer_ip, printer_port, keterangan, keyboard, status, created_at, updated_at) VALUES 
    ('127.0.0.1', NULL, NULL, NULL, NULL, 'Laptop Pengembang (Localhost)', 0, 1, NOW(), NOW()),
    ('192.168.1.26', NULL, NULL, NULL, NULL, 'Uji Coba HP (Wi-Fi Local)', 0, 1, NOW(), NOW())";
    
    $pdo->exec($sql);
    echo "Default gawai records inserted successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
