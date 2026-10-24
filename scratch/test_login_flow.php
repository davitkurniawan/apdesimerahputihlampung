<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_url = "http://localhost/index.php/siteman";
$auth_url = "http://localhost/index.php/siteman/auth";
$dashboard_url = "http://localhost/index.php/beranda";

// Step 1: GET login page to obtain CSRF and PHPSESSID
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080"
]);
$res1 = curl_exec($ch);
$http_code1 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "=== STEP 1: GET siteman ===\n";
echo "HTTP Code: $http_code1\n";

// Parse headers for cookies
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $res1, $matches);
$cookies = [];
foreach ($matches[1] as $item) {
    list($k, $v) = explode('=', $item, 2);
    $cookies[trim($k)] = trim($v);
}
echo "Cookies received: " . json_encode($cookies) . "\n";

// Parse body for CSRF
$csrf_token = '';
if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $res1, $m)) {
    $csrf_token = $m[1];
} elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $res1, $m)) {
    $csrf_token = $m[1];
}
echo "CSRF Token: $csrf_token\n\n";

if (empty($csrf_token)) {
    echo "Could not find CSRF token! Exiting.\n";
    exit;
}

// Step 2: POST login details with captcha bypass
$cookie_header = "";
foreach ($cookies as $k => $v) {
    $cookie_header .= "$k=$v; ";
}
$cookie_header = rtrim($cookie_header, "; ");

$post_data = [
    'sidcsrf' => $csrf_token,
    'username' => 'admin',
    'password' => 'P@ssw0rd123!',
    'captcha_code' => '1234' // Captcha bypass code
];

curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080",
    "Cookie: $cookie_header"
]);
$res2 = curl_exec($ch);
$http_code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "=== STEP 2: POST siteman/auth ===\n";
echo "HTTP Code: $http_code2\n";

// Parse headers from res2
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers2 = substr($res2, 0, $header_size);
$body2 = substr($res2, $header_size);

echo "Headers returned:\n$headers2\n";

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers2, $matches2);
$new_cookies = $cookies;
foreach ($matches2[1] as $item) {
    list($k, $v) = explode('=', $item, 2);
    $new_cookies[trim($k)] = trim($v);
}
echo "Cookies updated: " . json_encode($new_cookies) . "\n";

// Step 3: Try to hit dashboard with the new cookies
$cookie_header_dashboard = "";
foreach ($new_cookies as $k => $v) {
    $cookie_header_dashboard .= "$k=$v; ";
}
$cookie_header_dashboard = rtrim($cookie_header_dashboard, "; ");

curl_setopt($ch, CURLOPT_URL, $dashboard_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080",
    "Cookie: $cookie_header_dashboard"
]);
$res3 = curl_exec($ch);
$http_code3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "=== STEP 3: GET beranda ===\n";
echo "HTTP Code: $http_code3\n";

$header_size3 = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers3 = substr($res3, 0, $header_size3);
$body3 = substr($res3, $header_size3);

echo "Headers returned:\n$headers3\n";
if (preg_match('/<title>(.*?)<\/title>/i', $body3, $m)) {
    echo "Page Title: " . trim($m[1]) . "\n";
} else {
    echo "No title found. Body length: " . strlen($body3) . "\n";
}

curl_close($ch);
