<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

echo "=== DRY-RUNNING DELETION OF ALL RESIDENTS ===\n";
$stmt = $pdo->query("SELECT id, nik, nama FROM tweb_penduduk ORDER BY id ASC");
$residents = $stmt->fetchAll();

$success_count = 0;
$fail_count = 0;

foreach ($residents as $res) {
    $id = $res['id'];
    $name = $res['nama'];
    $nik = $res['nik'];
    
    try {
        $pdo->beginTransaction();
        
        // Try deleting from associated tables that we know about in xosid_execute_delete_penduduk
        $pdo->prepare("DELETE FROM tweb_penduduk_mandiri WHERE id_pend = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM tweb_penduduk_map WHERE id = ?")->execute([$id]);
        
        // Now try to delete the main record
        $pdo->prepare("DELETE FROM tweb_penduduk WHERE id = ?")->execute([$id]);
        
        $pdo->rollBack();
        $success_count++;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $fail_count++;
        echo "ID: $id | NIK: $nik | Nama: $name | FAILED: " . $e->getMessage() . "\n";
    }
}

echo "\nSummary: $success_count can be deleted, $fail_count blocked.\n";
