<?php
try {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=xosid", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Ambil 5 penduduk pertama
    $stmt = $pdo->prepare("SELECT id, nik, nama, foto, sex FROM tweb_penduduk LIMIT 5");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
