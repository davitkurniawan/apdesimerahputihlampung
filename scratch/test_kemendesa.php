<?php
$kode_desa = '1801102013';

// 1. Test IDM
$url_idm = "https://idm.kemendesa.go.id/open/api/desa/rumusan/{$kode_desa}/2024";
echo "Fetching IDM from: $url_idm\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_idm);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
$response_idm = curl_exec($ch);
$http_code_idm = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "IDM HTTP Code: $http_code_idm\n";
if ($http_code_idm === 200) {
    $data = json_decode($response_idm, true);
    if ($data) {
        echo "IDM JSON parsed successfully.\n";
        echo "Has mapData: " . (isset($data['mapData']) ? 'Yes' : 'No') . "\n";
        if (isset($data['mapData'])) {
            echo "Skor saat ini: " . ($data['mapData']['SUMMARIES']['SKOR_SAAT_INI'] ?? $data['mapData']['skor'] ?? 'N/A') . "\n";
            echo "Status: " . ($data['mapData']['SUMMARIES']['STATUS'] ?? $data['mapData']['status'] ?? 'N/A') . "\n";
        } else {
            echo "Raw response keys: " . implode(', ', array_keys($data)) . "\n";
        }
    } else {
        echo "Failed to parse IDM JSON. Raw length: " . strlen($response_idm) . "\n";
        echo "Snippet: " . substr($response_idm, 0, 500) . "\n";
    }
} else {
    echo "IDM Request failed.\n";
}

// 2. Test SDGs
// Note: SDGs uses BPS code. Bumi Daya BPS code is usually different from Kemendesa code, but let's check.
// BPS code for Bumi Daya (Kec. Palas, Lamsel): Kemendesa is 1801102013 (18.01.10.2013).
// BPS code is 1801040013 or similar. Let's see if config table has BPS code.
try {
    $pdo = new PDO("mysql:host=db;dbname=xosid_tenant_bumidaya;charset=utf8mb4", 'root', 'password');
    $stmt = $pdo->query("SELECT kode_desa_bps FROM config LIMIT 1");
    $bps = $stmt->fetchColumn();
    echo "\nBPS Code in DB: $bps\n";
    if ($bps) {
        $url_sdgs = "https://sid.kemendesa.go.id/sdgs/searching/score-sdgs?location_code=$bps";
        echo "Fetching SDGs from: $url_sdgs\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_sdgs);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
        $response_sdgs = curl_exec($ch);
        $http_code_sdgs = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "SDGs HTTP Code: $http_code_sdgs\n";
        if ($http_code_sdgs === 200) {
            $data_sdgs = json_decode($response_sdgs, true);
            if ($data_sdgs) {
                echo "SDGs parsed successfully. Average: " . ($data_sdgs['average'] ?? 'N/A') . "\n";
            } else {
                echo "Failed to parse SDGs JSON. Raw length: " . strlen($response_sdgs) . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
