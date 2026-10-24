<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/siteman/auth");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: master.localhost"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Set up cookies to simulate session
$cookie_file = __DIR__ . "/cookie.txt";
@unlink($cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

// First let's POST login to generate session
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'password'
]));
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "LOGIN HTTP: $code\n";

// Now GET identitas_desa
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/identitas_desa");
curl_setopt($ch, CURLOPT_POST, false);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
echo "GET IDENTITAS_DESA HTTP: $code\n";
echo "Error: $err\n";
if (strpos($html, 'Call to a member function makeVisible() on null') !== false) {
    echo "ERROR: makeVisible() error is still present!\n";
} elseif (strpos($html, 'An uncaught Exception was encountered') !== false) {
    echo "ERROR: Other uncaught exception present!\n";
    if (preg_match('/<p>Message: (.*)<\/p>/', $html, $matches)) {
        echo "Exception Message: " . $matches[1] . "\n";
    }
} else {
    echo "SUCCESS: No error detected! HTML length: " . strlen($html) . "\n";
    if (preg_match('/<title>(.*)<\/title>/', $html, $matches)) {
        echo "Page Title: " . $matches[1] . "\n";
    }
}
@unlink($cookie_file);
