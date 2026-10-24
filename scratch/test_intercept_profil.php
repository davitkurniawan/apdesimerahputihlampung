<?php
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    echo "Failed to connect to DB\n";
    exit(1);
}

$test_data = [
    'jenis_tanah' => 'Tanah Lempung Berpasir',
    'topografi' => 'Dataran Tinggi Berbukit',
    'sumber_daya_alam' => 'Mata Air Gunung, Pasir Sungai',
    'flora_fauna' => 'Pinus, Elang Jawa, Rusa',
    'rawan_bencana' => 'Longsor Ringan',
    'jenis_jaringan' => 'Fiber Optik (FTTH)',
    'provider_internet' => 'Telkomsel & Indihome',
    'cakupan_wilayah' => '95% Wilayah Tercover',
    'kecepatan_internet' => '150 Mbps',
    'status_desa' => 'Adat',
    'wilayah_adat' => 'Kasepuhan Bumi Daya',
    'peraturan_adat' => 'Perdes Adat No. 3/2026',
    'regulasi_penetapan_kampung_adat' => 'SK Bupati Lampung Selatan No. 120/2026'
];

try {
    echo "Updating profil_desa table...\n";
    foreach ($test_data as $key => $val) {
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM profil_desa WHERE `key` = ? AND config_id = 1");
        $check_stmt->execute([$key]);
        $exists = $check_stmt->fetchColumn() > 0;

        if ($exists) {
            $update_stmt = $pdo->prepare("UPDATE profil_desa SET value = ? WHERE `key` = ? AND config_id = 1");
            $update_stmt->execute([$val, $key]);
            echo "Updated key: $key => $val\n";
        } else {
            $insert_stmt = $pdo->prepare("INSERT INTO profil_desa (config_id, kategori, judul, `key`, value) VALUES (1, 'test', 'Test', ?, ?)");
            $insert_stmt->execute([$key, $val]);
            echo "Inserted key: $key => $val\n";
        }
    }
    
    echo "\nVerifying update in database:\n";
    $stmt = $pdo->query("SELECT `key`, value FROM profil_desa");
    while ($row = $stmt->fetch()) {
        echo "- {$row['key']}: {$row['value']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
