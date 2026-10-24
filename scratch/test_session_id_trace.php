<?php
$login_url = "http://localhost/index.php/siteman";
$auth_url = "http://localhost/index.php/siteman/auth";
$target_url = "http://localhost/index.php/pengguna";

// Step 1: Initial load of login page
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
if (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $res1, $m)) {
    $csrf_token = $m[1];
}

$cookie_header = "";
foreach ($cookies as $k => $v) {
    $cookie_header .= "$k=$v; ";
}
$cookie_header = rtrim($cookie_header, "; ");

// Step 2: POST login
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
$headers2 = substr($res2, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers2, $matches2);
$new_cookies = $cookies;
foreach ($matches2[1] as $item) {
    list($k, $v) = explode('=', $item, 2);
    $new_cookies[trim($k)] = trim($v);
}
$new_sess_id = $new_cookies['PHPSESSID'] ?? '';
echo "POST login response headers:\n$headers2\n";
echo "Session ID from login response: $new_sess_id\n\n";

// Step 3: GET target page (before DB update)
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
$res3 = curl_exec($ch);
$headers3 = substr($res3, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
echo "GET target response headers (BEFORE DB update):\n$headers3\n";

// Step 4: Update DB to match
$conn = mysqli_connect('xosid_neural_db', 'root', 'password', 'xosid');
mysqli_query($conn, "UPDATE user SET session = '$new_sess_id' WHERE id = 1");
mysqli_close($conn);
echo "Updated DB user.session to: $new_sess_id\n\n";

// Step 5: GET target page (after DB update)
$res4 = curl_exec($ch);
$headers4 = substr($res4, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
echo "GET target response headers (AFTER DB update):\n$headers4\n";

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers4, $matches4);
if (!empty($matches4[1])) {
    echo "Cookies set in final response:\n";
    print_r($matches4[1]);
}
