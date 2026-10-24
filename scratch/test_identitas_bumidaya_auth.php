<?php
// scratch/test_identitas_bumidaya_auth.php

// 1. Login to Bumidaya
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/index.php/siteman/auth');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bumidaya.localhost:8080"));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'Xosid108!',
    'captcha_code' => '1234'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$cookie_file = __DIR__ . "/cookie_bumidaya.txt";
@unlink($cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "LOGIN HTTP STATUS: " . $http_code . "\n";
echo "LOGIN BODY PREVIEW:\n" . substr(strip_tags($response), 0, 1000) . "\n\n";

// 2. GET Identitas Desa in Bumidaya
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/identitas_desa");
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bumidaya.localhost:8080"));

$html = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "GET IDENTITAS_DESA HTTP STATUS: " . $http_code . "\n";
if ($http_code === 500) {
    echo "ERROR: makeVisible() error is present!\n";
    echo strip_tags($html) . "\n";
} else {
    echo "SUCCESS! No 500 error! HTML length: " . strlen($html) . "\n";
    if (preg_match('/<title>(.*)<\/title>/', $html, $matches)) {
        echo "Page Title: " . $matches[1] . "\n";
    }
}
@unlink($cookie_file);
