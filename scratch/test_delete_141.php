<?php
session_start();
$_SESSION['username'] = 'admin';

require_once __DIR__ . '/../xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

echo "Resident count before: " . $pdo->query("SELECT COUNT(*) FROM tweb_penduduk")->fetchColumn() . "\n";
echo "Checking if resident 141 exists: " . $pdo->query("SELECT COUNT(*) FROM tweb_penduduk WHERE id = 141")->fetchColumn() . "\n";

echo "Attempting to delete resident 141 using interceptor routine...\n";
$res = xosid_execute_delete_penduduk(141, $pdo);
if ($res) {
    echo "SUCCESS: xosid_execute_delete_penduduk returned true.\n";
} else {
    echo "FAILED: xosid_execute_delete_penduduk returned false.\n";
}

echo "Resident count after: " . $pdo->query("SELECT COUNT(*) FROM tweb_penduduk")->fetchColumn() . "\n";
echo "Checking if resident 141 exists after: " . $pdo->query("SELECT COUNT(*) FROM tweb_penduduk WHERE id = 141")->fetchColumn() . "\n";
?>
