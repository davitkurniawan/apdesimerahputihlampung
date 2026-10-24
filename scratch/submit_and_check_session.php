<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$session_id = '8dfd986939bc4c62711b7855f9c687c3';
$form_url = "http://localhost/index.php/gawai_layanan/form";
$insert_url = "http://localhost/index.php/gawai_layanan/insert";

$cookie_file = tempnam(sys_get_temp_dir(), 'cookie');

// Step 1: GET request to form to get CSRF token and initialize cookies in cookie jar
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $form_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIE, "ci_session=" . $session_id);
$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    die("Failed to fetch form");
}

// Extract CSRF token
$dom = new DOMDocument();
@$dom->loadHTML($html);
$inputs = $dom->getElementsByTagName('input');
$csrf_token = '';
foreach ($inputs as $input) {
    if ($input->getAttribute('name') === 'sidcsrf') {
        $csrf_token = $input->getAttribute('value');
        break;
    }
}

echo "Extracted CSRF Token: {$csrf_token}\n";
if (empty($csrf_token)) {
    die("Failed to find CSRF token");
}

// Step 2: POST request to insert
$post_data = [
    'sidcsrf' => $csrf_token,
    'ip_address' => '172.19.0.5', // Test IP
    'mac_address' => '00:1B:44:11:3A:B9',
    'id_pengunjung' => 'testfingerprintvisitorid12345',
    'printer_ip' => '',
    'printer_port' => '',
    'keyboard' => '0',
    'status' => '1', // Active
    'keterangan' => 'Simulated Gawai Registration Test'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $insert_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER, true);
$post_response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "POST HTTP STATUS: " . $info['http_code'] . "\n";
echo "POST REDIRECT URL: " . ($info['redirect_url'] ?? 'None') . "\n";

// Capture the updated session ID from the cookie jar
$cookies = file_get_contents($cookie_file);
echo "\n=== COOKIE JAR CONTENTS ===\n{$cookies}\n";

// Let's find the active session files in /tmp that were modified in the last minute
echo "\n=== RECENT SESSION FILES IN /tmp ===\n";
$files = glob('/tmp/sess_*');
foreach ($files as $file) {
    if (time() - filemtime($file) < 30) {
        echo "File: " . basename($file) . " | Modified: " . date('Y-m-d H:i:s', filemtime($file)) . "\n";
        echo "Contents: " . file_get_contents($file) . "\n\n";
    }
}

unlink($cookie_file);
