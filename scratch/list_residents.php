<?php
$servername = "db";
$username = "root";
$password = "password";
$dbname = "xosid";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Count total residents
    $stmt = $conn->query("SELECT COUNT(*) as count FROM tweb_penduduk");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Total residents in tweb_penduduk: $total\n";
    
    // List first 10 residents
    $stmt = $conn->query("SELECT id, nik, nama FROM tweb_penduduk ORDER BY id DESC LIMIT 15");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Last 15 residents:\n";
    foreach ($rows as $row) {
        echo "ID: {$row['id']} | NIK: {$row['nik']} | Nama: {$row['nama']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
