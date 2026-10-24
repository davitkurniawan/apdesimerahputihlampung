<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Registering Docker Gateway IP 172.19.0.1...\n";
    
    // Check if 172.19.0.1 is already registered
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM gawai_layanan WHERE ip_address = ?");
    $stmt->execute(['172.19.0.1']);
    $exists = $stmt->fetchColumn();
    
    if (!$exists) {
        $sql = "INSERT INTO gawai_layanan (ip_address, mac_address, id_pengunjung, printer_ip, printer_port, keterangan, keyboard, status, created_at, updated_at) VALUES 
        ('172.19.0.1', NULL, NULL, NULL, NULL, 'Docker Host Gateway (Localhost/HP Proxy)', 0, 1, NOW(), NOW())";
        $pdo->exec($sql);
        echo "Docker Gateway IP 172.19.0.1 registered successfully!\n";
    } else {
        echo "Docker Gateway IP 172.19.0.1 is already registered.\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
