<?php
$login_url = "http://localhost/index.php/siteman";
$auth_url = "http://localhost/index.php/siteman/auth";

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
    die("CSRF token not found.\n");
}

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

// Now query database session
$conn = mysqli_connect('xosid_neural_db', 'root', 'password', 'xosid');
$res = mysqli_query($conn, "SELECT session, last_login FROM user WHERE id = 1");
$row = mysqli_fetch_assoc($res);

header('Content-Type: text/plain');
echo "PHPSESSID Cookie after login: $new_sess_id\n";
echo "Database session column:      " . $row['session'] . "\n";
echo "Database last_login column:   " . $row['last_login'] . "\n";
echo "Match? " . ($new_sess_id === $row['session'] ? "YES" : "NO") . "\n";
