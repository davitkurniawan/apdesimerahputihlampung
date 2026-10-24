<?php
$db_config_file = __DIR__ . '/desa/config/database.php';
if (!file_exists($db_config_file)) {
    $db_config_file = __DIR__ . '/config/database.php';
}
require $db_config_file;

$host = $db['default']['hostname'];
$user = $db['default']['username'];
$pass = 'password';

$conn = new mysqli($host, $user, $pass, 'xosid');
if ($conn->connect_error) {
    echo "Koneksi gagal";
    exit;
}

$conn->query("ALTER TABLE xosid_member_invoices ADD COLUMN dokumen_status VARCHAR(50) DEFAULT 'pending'");
$conn->query("ALTER TABLE xosid_member_invoices ADD COLUMN file_bkp VARCHAR(255) DEFAULT NULL");
$conn->query("ALTER TABLE xosid_member_invoices ADD COLUMN file_tanda_terima VARCHAR(255) DEFAULT NULL");
echo "Done";
?>
