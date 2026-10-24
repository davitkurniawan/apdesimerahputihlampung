<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_url = "http://localhost/index.php/siteman";
$auth_url = "http://localhost/index.php/siteman/auth";
$target_url = "http://localhost/index.php/pengguna";

// Step 1: Get CSRF and PHPSESSID
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Host: master.localhost:8080"]);
$res1 = curl_exec($ch);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $res1, $matches);
$cookies = [];
foreach ($matches[1] as $item) {
    list($k, $v) = explode('=', $item, 2);
    $cookies[trim($k)] = trim($v);
}

$csrf_token = '';
if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $res1, $m)) {
    $csrf_token = $m[1];
} elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $res1, $m)) {
    $csrf_token = $m[1];
}

if (empty($csrf_token)) {
    echo "CSRF token not found.\n";
    exit;
}

// Step 2: POST login details
$cookie_header = "";
foreach ($cookies as $k => $v) {
    $cookie_header .= "$k=$v; ";
}
$cookie_header = rtrim($cookie_header, "; ");

$post_data = [
    'sidcsrf' => $csrf_token,
    'username' => 'admin',
    'password' => 'P@ssw0rd123!',
    'captcha_code' => '1234'
];

curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080",
    "Cookie: $cookie_header"
]);
$res2 = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers2 = substr($res2, 0, $header_size);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers2, $matches2);
$new_cookies = $cookies;
foreach ($matches2[1] as $item) {
    list($k, $v) = explode('=', $item, 2);
    $new_cookies[trim($k)] = trim($v);
}

$new_sess_id = $new_cookies['PHPSESSID'] ?? '';
echo "Logged in successfully! New session ID: $new_sess_id\n";

if (empty($new_sess_id)) {
    echo "New session ID not found in login response.\n";
    exit;
}

// Step 3: Check if target URL redirects without updating DB
$cookie_header_target = "";
foreach ($new_cookies as $k => $v) {
    $cookie_header_target .= "$k=$v; ";
}
$cookie_header_target = rtrim($cookie_header_target, "; ");

curl_setopt($ch, CURLOPT_URL, $target_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080",
    "Cookie: $cookie_header_target"
]);
$res3_before = curl_exec($ch);
$code3_before = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headers3_before = substr($res3_before, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
echo "Request before DB update: HTTP Code $code3_before. Redirects to: " . (preg_match('/Location:\s*([^\r\n]*)/i', $headers3_before, $loc) ? $loc[1] : 'NONE') . "\n";

// Step 4: Update DB session column
$conn = mysqli_connect('xosid_neural_db', 'root', 'password', 'xosid');
if (!$conn) {
    echo "DB connection failed: " . mysqli_connect_error() . "\n";
    exit;
}
$update = mysqli_query($conn, "UPDATE user SET session = '" . mysqli_real_escape_string($conn, $new_sess_id) . "' WHERE id = 1");
if ($update) {
    echo "Database updated session to $new_sess_id successfully.\n";
} else {
    echo "Database update failed: " . mysqli_error($conn) . "\n";
}
mysqli_close($conn);

// Step 5: Try hitting target URL again after DB update
$res3_after = curl_exec($ch);
$code3_after = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headers3_after = substr($res3_after, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
$body3_after = substr($res3_after, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

echo "Request after DB update: HTTP Code $code3_after. Redirects to: " . (preg_match('/Location:\s*([^\r\n]*)/i', $headers3_after, $loc) ? $loc[1] : 'NONE') . "\n";
if (preg_match('/<title>(.*?)<\/title>/i', $body3_after, $m)) {
    echo "Page Title: " . trim($m[1]) . "\n";
} else {
    echo "Body length: " . strlen($body3_after) . "\n";
}

curl_close($ch);
