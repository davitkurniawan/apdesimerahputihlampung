<?php
$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'xosid';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Clear existing for a clean state
    $pdo->exec("DELETE FROM tweb_penduduk WHERE id IN (1, 2, 3)");
    
    // Insert dummy residents
    $sql = "INSERT INTO tweb_penduduk (
                id, config_id, nama, nik, kk_level, sex, tempatlahir, tanggallahir, 
                agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin, warganegara_id, 
                dokumen_pasport, dokumen_kitas, nama_ayah, nama_ibu, golongan_darah_id, 
                status_dasar, is_historical
            ) VALUES (
                ?, 1, ?, ?, 0, 1, 'Jakarta', '1990-01-01', 
                1, 1, 1, 1, 1, 
                '', '', 'Ayah', 'Ibu', '1', 
                1, 0
            )";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([1, 'Penduduk Test 1', '1234567890123456']);
    $stmt->execute([2, 'Penduduk Test 2', '2234567890123456']);
    $stmt->execute([3, 'Penduduk Test 3', '3234567890123456']);
    
    echo "SUCCESS: Dummy residents 1, 2, 3 inserted successfully.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
