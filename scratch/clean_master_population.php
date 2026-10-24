<?php
// scratch/clean_master_population.php

$host = 'db';
$user = 'root';
$pass = 'password';
$db = 'xosid';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected to master database '$db'.\n";

// Disable foreign key checks to allow truncation/deletes safely
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$tables_to_clear = [
    'tweb_penduduk',
    'tweb_keluarga',
    'tweb_penduduk_map',
    'tweb_wil_clusterdesa',
    'tweb_keluarga_aktif',
    'log_penduduk',
    'log_keluarga',
    'log_surat',
    'tweb_desa_pamong' // Also clean up Bondo pamong from master DB
];

foreach ($tables_to_clear as $table) {
    // Check if table exists
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check && $check->num_rows > 0) {
        $count_res = $conn->query("SELECT COUNT(*) as total FROM $table");
        $count = $count_res ? $count_res->fetch_assoc()['total'] : 0;
        
        echo "Table '$table' has $count rows. Clearing...\n";
        
        if ($conn->query("TRUNCATE TABLE $table")) {
            echo "  -> Table '$table' successfully truncated.\n";
        } else {
            echo "  -> Error truncating '$table': " . $conn->error . "\n";
        }
    } else {
        echo "Table '$table' does not exist.\n";
    }
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

$conn->close();
echo "Cleanup completed successfully!\n";
