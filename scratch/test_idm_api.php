<?php

$kode_desa = "1801102013";
$tahun = "2024";

// Try Kemendesa API
$url = "https://idm.kemendesa.go.id/open/api/desa/rumusan/{$kode_desa}/{$tahun}";
echo "Fetching: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $http_code\n";
if ($error) {
    echo "Curl Error: $error\n";
} else {
    echo "Response Length: " . strlen($response) . " bytes\n";
    $json = json_decode($response, true);
    if ($json) {
        echo "Valid JSON received!\n";
        echo "Keys: " . implode(', ', array_keys($json)) . "\n";
        if (isset($json['success'])) {
            echo "Success flag: " . ($json['success'] ? 'true' : 'false') . "\n";
        }
        if (isset($json['error_msg'])) {
            echo "Error message: " . $json['error_msg'] . "\n";
        }
        // Print first 500 chars of response
        echo "Response preview:\n" . substr($response, 0, 500) . "...\n";
    } else {
        echo "Invalid JSON or Empty response! Raw output:\n";
        echo substr($response, 0, 1000) . "\n";
    }
}
