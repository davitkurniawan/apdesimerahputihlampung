<?php
// scratch/test_curl_debug.php

$cookie_file = tempnam('/tmp', 'cookie');
$ch = curl_init();

echo "--- 1. POST Login ---\n";
curl_setopt($ch, CURLOPT_URL, 'http://localhost/index.php/siteman/auth');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: bondo-bangsri.localhost:8080']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'Xosid108!',
    'captcha_code' => '1234'
]));
$res = curl_exec($ch);
$info = curl_getinfo($ch);
$err = curl_error($ch);

echo "HTTP Code: " . $info['http_code'] . "\n";
echo "Curl Error: " . $err . "\n";
echo "Response Length: " . strlen($res) . "\n";
echo "Response Body:\n" . $res . "\n\n";

echo "--- 2. GET Identitas Desa ---\n";
curl_setopt($ch, CURLOPT_URL, 'http://localhost/index.php/identitas_desa');
curl_setopt($ch, CURLOPT_POST, false);
$res2 = curl_exec($ch);
$info2 = curl_getinfo($ch);
$err2 = curl_error($ch);

echo "HTTP Code: " . $info2['http_code'] . "\n";
echo "Curl Error: " . $err2 . "\n";
echo "Response Length: " . strlen($res2) . "\n";
echo "Response Body:\n" . $res2 . "\n\n";

unlink($cookie_file);
