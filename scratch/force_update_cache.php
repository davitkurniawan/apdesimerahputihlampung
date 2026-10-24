<?php
$kode_desa = '1801102013';
$bps_code = '1803120022';
$cache_dir = '/var/www/html/desa/cache/';

if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

// 1. Fetch & Write IDM 2024
$url_idm = "https://idm.kemendesa.go.id/open/api/desa/rumusan/{$kode_desa}/2024";
echo "Fetching IDM 2024...\n";
$ch = curl_init($url_idm);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);
if ($data && isset($data['mapData'])) {
    $cache_file = $cache_dir . "idm_2024_{$kode_desa}.json";
    $cache_data = [
        'time' => time(),
        'ttl' => 31536000,
        'data' => $data['mapData']
    ];
    file_put_contents($cache_file, serialize($cache_data));
    echo "Saved IDM 2024 cache to $cache_file\n";
} else {
    echo "Failed to fetch IDM 2024.\n";
}

// 2. Fetch & Write SDGs
$url_sdgs = "https://sid.kemendesa.go.id/sdgs/searching/score-sdgs?location_code=$bps_code";
echo "Fetching SDGs...\n";
$ch = curl_init($url_sdgs);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
$res_sdgs = curl_exec($ch);
curl_close($ch);

$data_sdgs = json_decode($res_sdgs, true);
if ($data_sdgs && isset($data_sdgs['average'])) {
    $mapped_data = [];
    foreach ($data_sdgs['data'] ?? [] as $item) {
        $mapped_data[] = [
            'skor' => $item['score'] ?? 0,
            'label' => $item['title'] ?? ''
        ];
    }
    $sdgs_data = [
        'average' => $data_sdgs['average'],
        'data' => $mapped_data
    ];
    $cache_file_sdgs = $cache_dir . "sdgs_{$bps_code}.json";
    $cache_data_sdgs = [
        'time' => time(),
        'ttl' => 31536000,
        'data' => $sdgs_data
    ];
    file_put_contents($cache_file_sdgs, serialize($cache_data_sdgs));
    echo "Saved SDGs cache to $cache_file_sdgs\n";
} else {
    echo "Failed to fetch SDGs.\n";
}
