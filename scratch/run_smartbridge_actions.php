<?php
// scratch/run_smartbridge_actions.php
// Script to run conversion and deploy_demo processes to verify their functionality and update the template SQL file.

define('ENVIRONMENT', 'production');
define('VERSION', '2606.0.0');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');

require_once __DIR__ . '/../vendor/autoload.php';

// Connect to MySQL
$conn = new mysqli('db', 'root', 'password', 'xosid');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== STEP 1: CONVERT BLUEPRINT TEMPLATE ===\n";
echo "Switching connection to xosid_tenant_demo...\n";
if (!$conn->select_db('xosid_tenant_demo')) {
    die("Database template default (xosid_tenant_demo) not found.\n");
}

echo "Setting default identity (Bumi Daya, 1801102013)...\n";
$clean_stmt = $conn->prepare("UPDATE config SET 
    nama_desa = 'Bumi Daya', 
    kode_desa = '1801102013', 
    kode_desa_bps = '1803120022',
    nama_propinsi = 'Lampung', 
    nama_kabupaten = 'Lampung Selatan', 
    nama_kecamatan = 'Palas', 
    nama_kontak = 'Pusat xoSID', 
    hp_kontak = '08123456789', 
    jabatan_kontak = 'Operator Desa',
    email_desa = 'info@xosid.id',
    logo = 'xosid_logo.png'
    WHERE id = 1
");
if ($clean_stmt) {
    $clean_stmt->execute();
    $clean_stmt->close();
    echo "Default identity updated.\n";
} else {
    echo "Failed to prepare update query: " . $conn->error . "\n";
}

echo "Exporting blueprint SQL using MySQLDump to xosid_neural_fully_restored_20260512.sql...\n";
$sql_file = __DIR__ . '/../xosid_neural_fully_restored_20260512.sql';
try {
    if (!class_exists('MySQLDump')) {
        require_once __DIR__ . '/../vendor/dg/mysql-dump/src/MySQLDump.php';
    }
    $dumper = new MySQLDump($conn);
    $dumper->save($sql_file);
    echo "Blueprint SQL successfully saved to $sql_file (Size: " . round(filesize($sql_file)/1024/1024, 2) . " MB).\n";
} catch (Exception $e) {
    die("Failed to export blueprint SQL: " . $e->getMessage() . "\n");
}

// Switch back to xosid
$conn->select_db('xosid');

echo "\n=== STEP 2: DEPLOY DEMO SUBDOMAIN ===\n";
echo "Recreating xosid_tenant_demo...\n";
$conn->query("DROP DATABASE IF EXISTS `xosid_tenant_demo`");
if (!$conn->query("CREATE DATABASE `xosid_tenant_demo` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Failed to create xosid_tenant_demo: " . $conn->error . "\n");
}

echo "Importing template SQL into xosid_tenant_demo...\n";
$conn->select_db('xosid_tenant_demo');
$handle = fopen($sql_file, 'r');
if ($handle) {
    $conn->query("START TRANSACTION;");
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
    $conn->query("SET UNIQUE_CHECKS = 0;");
    $conn->query("SET AUTOCOMMIT = 0;");

    $templine = '';
    while (($line = fgets($handle)) !== false) {
        if (substr($line, 0, 2) == '--' || $line == '') continue;
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') {
            $conn->query($templine) or die("Error in query: " . $conn->error . "\nQuery: $templine\n");
            $templine = '';
        }
    }
    fclose($handle);
    $conn->query("COMMIT;");
    echo "Import completed successfully.\n";
} else {
    die("Failed to open SQL template file.\n");
}

$conn->query("SET FOREIGN_KEY_CHECKS = 1;");
$conn->query("SET UNIQUE_CHECKS = 1;");
$conn->query("SET AUTOCOMMIT = 1;");

echo "Aktivasi tema default neural-fundamental...\n";
$conn->query("UPDATE theme SET status = 0");
$conn->query("UPDATE theme SET status = 1 WHERE slug = 'neural-fundamental'");
if ($conn->affected_rows === 0) {
    $conn->query("INSERT INTO theme (config_id, nama, slug, versi, sistem, path, status, keterangan)
                  VALUES (1, 'Neural Fundamental', 'neural-fundamental', '2605.0.0', 0,
                          'storage/app/themes/neural-fundamental', 1, 'Tema Portal Desa XOSID')");
}
echo "Theme neural-fundamental activated.\n";

$conn->select_db('xosid');
$conn->close();
echo "\n=== ALL STEPS COMPLETED SUCCESSFULLY ===\n";
?>
