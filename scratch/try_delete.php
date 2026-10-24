<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

$id = 51; // Let's try to delete resident ID 51
echo "Trying to delete resident ID $id...\n";

try {
    $pdo->beginTransaction();
    
    // We will execute the same logic as xosid_execute_delete_penduduk but with print statements
    $stmt = $pdo->prepare("SELECT config_id, nik, foto FROM tweb_penduduk WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    
    if (!$row) {
        throw new Exception("Resident ID $id not found.");
    }
    
    echo "Found resident: NIK={$row['nik']}, Foto={$row['foto']}\n";
    
    // Check columns of tweb_keluarga and tweb_rtm
    echo "\n=== Columns of tweb_keluarga ===\n";
    $q = $pdo->query("DESCRIBE tweb_keluarga");
    while($c = $q->fetch()) {
        echo "  {$c['Field']} - {$c['Type']}\n";
    }

    echo "\n=== Columns of tweb_rtm ===\n";
    try {
        $q = $pdo->query("DESCRIBE tweb_rtm");
        while($c = $q->fetch()) {
            echo "  {$c['Field']} - {$c['Type']}\n";
        }
    } catch(Exception $ex) {
        echo "  tweb_rtm doesn't exist or error: " . $ex->getMessage() . "\n";
    }

    $tables = [
        'tweb_keluarga' => 'nik_kepala', // commonly nik_kepala or id_kepala in OpenSID
        'tweb_penduduk_mandiri' => 'id_pend',
        'tweb_penduduk_map' => 'id',
        'log_penduduk' => 'id_pend',
    ];
    
    echo "\n=== References Check ===\n";
    foreach ($tables as $table => $col) {
        try {
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $col = ?");
            $stmt_check->execute([$id]);
            $cnt = $stmt_check->fetchColumn();
            echo "Referenced in table '$table' ($col): $cnt rows\n";
        } catch (Exception $ex) {
            echo "Error checking table '$table': " . $ex->getMessage() . "\n";
        }
    }
    
    echo "\nExecuting deletion steps...\n";
    
    try {
        $stmt_mandiri = $pdo->prepare("DELETE FROM tweb_penduduk_mandiri WHERE id_pend = ?");
        $stmt_mandiri->execute([$id]);
        echo "Deleted from tweb_penduduk_mandiri\n";
    } catch (Exception $ex) {
        echo "Error deleting from tweb_penduduk_mandiri: " . $ex->getMessage() . "\n";
    }
    
    try {
        $stmt_map = $pdo->prepare("DELETE FROM tweb_penduduk_map WHERE id = ?");
        $stmt_map->execute([$id]);
        echo "Deleted from tweb_penduduk_map\n";
    } catch (Exception $ex) {
        echo "Error deleting from tweb_penduduk_map: " . $ex->getMessage() . "\n";
    }
    
    // Check if there are other tables like log_hapus_penduduk or log_penduduk
    try {
        $stmt_del = $pdo->prepare("DELETE FROM tweb_penduduk WHERE id = ?");
        $stmt_del->execute([$id]);
        echo "SUCCESS: Deleted from tweb_penduduk\n";
    } catch (Exception $ex) {
        echo "FAILED to delete from tweb_penduduk: " . $ex->getMessage() . "\n";
    }
    
    $pdo->rollBack();
    echo "SUCCESS: Transaction rolled back successfully (no error).\n";
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
