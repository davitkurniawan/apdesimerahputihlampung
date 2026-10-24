<?php
// Start session to write captcha and CSRF
session_start();
$_SESSION['captcha'] = password_hash('1234', PASSWORD_BCRYPT);
$_SESSION['sidcsrf'] = 'testcsrf';
$session_id = session_id();
session_write_close();

// Make POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/siteman/auth");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Host: master.localhost:8080"
]);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=$session_id; sidcsrf=testcsrf");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'sid304',
    'captcha_code' => '1234',
    'sidcsrf' => 'testcsrf'
]));
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
echo "=== RESPONSE ===\n";
echo $response;
curl_close($ch);
