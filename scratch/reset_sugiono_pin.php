<?php
$dsn = "mysql:host=db;port=3306;dbname=xosid;charset=utf8mb4";
$pdo = new PDO($dsn, 'root', 'password', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== RESETTING SUGIONO PIN TO 123456 ===\n";
try {
    // Hash for '123456'
    $new_hash = password_hash('123456', PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("UPDATE tweb_penduduk_mandiri SET pin = :pin WHERE id_pend = 124");
    $stmt->execute(['pin' => $new_hash]);
    
    echo "Successfully updated Sugiono's PIN to '123456'!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
