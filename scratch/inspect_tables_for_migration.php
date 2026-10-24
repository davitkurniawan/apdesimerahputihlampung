<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

function dumpTable($table, $pdo) {
    echo "=== Table $table ===\n";
    try {
        $stmt = $pdo->query("DESCRIBE $table");
        while ($row = $stmt->fetch()) {
            echo "  {$row['Field']} - {$row['Type']} | Null: {$row['Null']} | Key: {$row['Key']} | Default: {$row['Default']} | Extra: {$row['Extra']}\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

dumpTable('tweb_wil_clusterdesa', $pdo);
dumpTable('tweb_keluarga', $pdo);
dumpTable('tweb_rtm', $pdo);

// Let's also see what values exist in dictionaries: tweb_penduduk_sex, tweb_penduduk_agama, tweb_penduduk_kawin, tweb_penduduk_hubungan, tweb_penduduk_pekerjaan, tweb_penduduk_pendidikan_kk
function dumpDict($table, $pdo) {
    echo "=== Dictionary Table $table ===\n";
    try {
        $stmt = $pdo->query("SELECT * FROM $table LIMIT 20");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "  " . json_encode($row) . "\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

dumpDict('tweb_penduduk_sex', $pdo);
dumpDict('tweb_penduduk_agama', $pdo);
dumpDict('tweb_penduduk_kawin', $pdo);
dumpDict('tweb_penduduk_hubungan', $pdo);
dumpDict('tweb_penduduk_pekerjaan', $pdo);
dumpDict('tweb_penduduk_pendidikan_kk', $pdo);
