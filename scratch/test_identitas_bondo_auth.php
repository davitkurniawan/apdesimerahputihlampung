<?php
// scratch/test_identitas_bondo_auth.php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/siteman/auth");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bondo-bangsri.localhost:8080"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);

// Set up cookies to simulate session
$cookie_file = __DIR__ . "/cookie_bondo.txt";
@unlink($cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

// POST login
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'Xosid108!',
    'captcha_code' => '1234'
]));
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "LOGIN HTTP STATUS: $code\n";
if ($code === 0) {
    echo "CURL ERROR: " . curl_error($ch) . "\n";
}
echo "LOGIN BODY PREVIEW:\n" . substr(strip_tags($res), 0, 1000) . "\n\n";

// GET identitas_desa
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/identitas_desa");
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bondo-bangsri.localhost:8080"));
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "GET IDENTITAS_DESA HTTP STATUS: $code\n";
if (strpos($html, 'Call to a member function makeVisible() on null') !== false) {
    echo "ERROR: makeVisible() error is still present!\n";
    echo strip_tags($html) . "\n";
} elseif (strpos($html, 'An uncaught Exception') !== false) {
    echo "ERROR: Uncaught exception:\n";
    echo strip_tags($html) . "\n";
} else {
    echo "SUCCESS: Page loaded successfully! HTML length: " . strlen($html) . "\n";
    echo "HTML Body:\n" . $html . "\n";
    if (preg_match('/<title>(.*)<\/title>/', $html, $matches)) {
        echo "Page Title: " . $matches[1] . "\n";
    }
}
@unlink($cookie_file);
