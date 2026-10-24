<?php
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
// We will just do a curl call with proper cookies.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://bumidaya.localhost:8080/index.php/siteman');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
$response = curl_exec($ch);
preg_match('/^Set-Cookie:\s*([^;]*)/mi', $response, $m);
$cookie = $m[1] ?? '';
preg_match('/name="sidcsrf"\s+value="(.*?)"/', $response, $m2);
$csrf = $m2[1] ?? '';

curl_setopt($ch, CURLOPT_URL, 'http://bumidaya.localhost:8080/index.php/siteman/auth');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "username=admin&password=wrong&sidcsrf=$csrf");
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
$response2 = curl_exec($ch);
echo "RESPONSE HEADERS:\n$response2\n";
