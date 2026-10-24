<?php
try {
    $db = new PDO("mysql:host=xosid_neural_db;dbname=xosid;charset=utf8mb4", "root", "password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- user ---\n";
    $stmt = $db->query("SELECT id, username, nama, id_grup FROM user");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | Username: {$row['username']} | Name: {$row['nama']} | Group: {$row['id_grup']}\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
