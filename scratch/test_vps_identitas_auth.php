<?php
// scratch/test_vps_identitas_auth.php

$host = 'demo.xosid.id';
$url_login = 'http://localhost/index.php/siteman/auth';
$url_target = 'http://localhost/index.php/identitas_desa';

echo "Testing authenticated request to $host...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_login);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$cookie_file = __DIR__ . "/cookie_vps_demo.txt";
@unlink($cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

// POST Login
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'Xosid108!'
]));

$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "LOGIN HTTP STATUS: $code\n";

// GET Target Page
curl_setopt($ch, CURLOPT_URL, $url_target);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));

$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "GET TARGET HTTP STATUS: $code\n";

if (strpos($html, 'Call to a member function makeVisible() on null') !== false) {
    echo "RESULT: FAILED (makeVisible() on null error is present)\n";
} elseif (strpos($html, 'An uncaught Exception') !== false) {
    echo "RESULT: FAILED (Other uncaught exception)\n";
    echo substr(strip_tags($html), 0, 800) . "\n";
} elseif ($code == 200 && strpos($html, 'nama_desa') !== false) {
    echo "RESULT: SUCCESS (Identitas Desa loaded)\n";
    if (preg_match('/<input[^>]*name="nama_desa"[^>]*value="([^"]*)"/', $html, $matches)) {
        echo "Village Name: " . $matches[1] . "\n";
    }
} else {
    echo "RESULT: UNKNOWN / REDIRECTED TO LOGIN\n";
    if (preg_match('/<title>(.*)<\/title>/', $html, $matches)) {
        echo "Page Title: " . $matches[1] . "\n";
    }
    echo "HTML PREVIEW:\n" . substr(strip_tags($html), 0, 800) . "\n";
}

@unlink($cookie_file);
